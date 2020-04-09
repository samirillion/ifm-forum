<?php

/**
 * Sorter Class for Handling Aggregation
 *
 * @package IFM
 */

namespace IFM;

class Controller_Sort
{
	public static function sort_posts()
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

	public function define_meta_on_publish($post_ID)
	{

		$post_author = get_post_field('post_author', $post_ID);

		add_post_meta($post_ID, 'temporal_karma', 0);
		add_post_meta($post_ID, 'user_upvote_id', $post_author, true);
	}

}
