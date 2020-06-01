<?php

namespace IFM;

use WP_Query;

/**
 * https://developer.wordpress.org/reference/classes/wp_query/
 */
class Model_Query extends WP_Query
{
  /**
   * Define Sorting Method
   */
  private $sorting_method;

  /**
   * Define How Fast Posts Drop with Time
   */
  private $args;


  function __construct($args = array())
  {
    // Force these args
    $args = wp_parse_args($args, array(
      'private' => false,
      'count_karma' => true,
      'orderby_karma' => true,
      'post_type' => IFM_POST_TYPE,
      'gravity' => '1.8',
      'update_post_term_cache' => false,
      'update_post_meta_cache' => false
    ));

    // add others for query
    if (get_query_var('ifm_p')) {
      $args['paged'] = get_query_var('ifm_p');
    }
    if (get_query_var('ifm_tax')) {
      $args['tax_query'] = array(
        array(
          'taxonomy' => IFM_POST_TAXONOMY_NAME,
          'terms' => get_query_var('ifm_tax'),
          'field' => 'slug',
          'include_children' => true,
          'operator' => 'IN'
        )
      );
    }

    $this->args = $args;

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

    if ($this->args['count_karma']) {
      $sql .= ", 
    POW(
      (
        GREATEST(
          1,
          TIMESTAMPDIFF( 
          SECOND, 
          $wpdb->posts.post_date_gmt, UTC_TIMESTAMP()
          ) / 3600
        )
      ), 
        " . $this->args['gravity'] . "
        ) AS karma_divisor,
      (SELECT count(*)
      FROM wp_postmeta
      WHERE post_id=$wpdb->posts.ID
      AND meta_key='user_upvote_id'
      ) as karma
    ";
    }
    return $sql;
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

    if ($this->args['orderby_karma']) {
      return "( karma / (karma_divisor) ) DESC
            ";
    }
  }
}
