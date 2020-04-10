<?php

namespace IFM;

use WP_Query;

class Model_Query extends WP_Query
{
  /**
   * Define Sorting Method
   */
  private $sorting_method;

  /**
   * Define How Fast Posts Drop with Time
   */
  private $gravity;


  function __construct($args = array())
  {
    // Force these args
    $args = wp_parse_args($args, array(
      'post_type' => IFM_POST_TYPE,
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
    return $sql . ", ROUND(POW((TIMESTAMPDIFF( SECOND, $wpdb->posts.post_date_gmt, UTC_TIMESTAMP())/3600), 1.8), 2) AS karma_divisor,
    (
      SELECT count(*)
      FROM wp_postmeta
      WHERE post_id=$wpdb->posts.ID
      AND meta_key='user_upvote_id'
      ) as karma
    ";
  }

  function posts_join($sql)
  {
    global $wpdb;
    return $sql . "
        LEFT JOIN $wpdb->term_relationships ON ($wpdb->term_relationships.object_id = $wpdb->posts.ID)
        LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id)
        INNER JOIN $wpdb->terms ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
    ";
  }

  function posts_where($sql)
  {
    global $wpdb;
    $filter_by = "";
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
    }
    return $sql . "$filter_by";
  }

  function posts_orderby($sql)
  {
    global $wpdb;
    return "( karma / (karma_divisor) ) DESC
            ";
  }
}
