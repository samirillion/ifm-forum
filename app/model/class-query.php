<?php

namespace IFM;

use WP_Query;

class Model_Query extends WP_Query
{
    function __construct($args = array())
    {
        // Force these args
        $args = array_merge($args, array(
            'post_type' => IFM_POST_TYPE,
            'posts_per_page' => -1,  // Turn off paging
            'no_found_rows' => true, // Optimize query for no paging
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false
        ));

        add_filter('posts_fields', array($this, 'posts_fields'));
        add_filter('posts_join', array($this, 'posts_join'));
        add_filter('posts_where', array($this, 'posts_where'));
        add_filter('posts_orderby', array($this, 'posts_orderby'));

        parent::__construct($args);

        // Make sure these filters don't affect any other queries
        remove_filter('posts_fields', array($this, 'posts_fields'));
        remove_filter('posts_join', array($this, 'posts_join'));
        remove_filter('posts_where', array($this, 'posts_where'));
        remove_filter('posts_orderby', array($this, 'posts_orderby'));
    }

    function posts_fields($sql)
    {
        global $wpdb;
        return $sql . ", $wpdb->terms.name AS 'book_category'";
    }

    function posts_join($sql)
    {
        global $wpdb;
        return $sql . "
			INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) 
			INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) 
			INNER JOIN $wpdb->terms ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id) 
		";
    }

    function posts_where($sql)
    {
        global $wpdb;
        return $sql . " AND $wpdb->term_taxonomy.taxonomy = 'book_category'";
    }

    function posts_orderby($sql)
    {
        global $wpdb;
        return "$wpdb->terms.name ASC, $wpdb->posts.post_title ASC";
    }

    public static function main()
    {
        global $wpdb;
        $ppp    = (isset($_POST['ppp'])) ? $_POST['ppp'] : 31;
        $page   = (isset($_REQUEST['ifm_p'])) ? $_REQUEST['ifm_p'] : 1;
        $offset = ($page - 1) * $ppp;
        if (get_query_var('ifm_tax')) {
            $filter_by = "
          AND $wpdb->terms.slug = '" . sanitize_text_field(get_query_var('ifm_tax')) . "' ";
        } elseif (isset($_POST['aggpostTax'])) {
            $filter_by = "
            AND $wpdb->terms.slug = '" . sanitize_text_field($_POST['aggpostTax']) . "' ";
        } elseif (get_query_var('user_id')) {
            $filter_by = "
            AND $wpdb->posts.post_author = '" . sanitize_text_field(get_query_var('user_id')) . "' 
            ";
        } else {
            $filter_by = '';
        }
        $query_str = "
      SELECT
        $wpdb->posts.*,
        CASE
          WHEN ROUND(POW((TIMESTAMPDIFF( MINUTE, $wpdb->posts.post_date_gmt, UTC_TIMESTAMP())/60), 1.8), 2) = 0
          THEN .01
          ELSE ROUND(POW((TIMESTAMPDIFF( MINUTE, $wpdb->posts.post_date_gmt, UTC_TIMESTAMP())/60), 1.8), 2)
          END AS karma_divisor
        FROM $wpdb->posts
        LEFT JOIN $wpdb->term_relationships ON $wpdb->term_relationships.object_id=$wpdb->posts.ID
        LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id
        INNER JOIN $wpdb->terms ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
        WHERE $wpdb->posts.post_type= 'ifm-post'
        " . $filter_by . "
        AND $wpdb->posts.post_status = 'publish'
      ORDER BY  (
                (
                  SELECT count(*)
                  FROM wp_postmeta
                  WHERE post_id=$wpdb->posts.ID
                  AND meta_key='user_upvote_id'
                  )/karma_divisor
                ) DESC
LIMIT " . $offset . ', ' . $ppp . '; ';

        $pageposts = $wpdb->get_results($query_str, OBJECT);
        // $sql_posts_total = $wpdb->get_var( "SELECT count(*) FROM wp_posts WHERE post_type=IFM_POST_TYPE;");
        // $max_num_pages = ceil($sql_posts_total / $ppp);
        return [$pageposts, $page];
    }
}
