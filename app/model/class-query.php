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
    $this->args = $args;

    add_filter('posts_fields', array($this, 'posts_fields'));
    add_filter('posts_join', array($this, 'posts_join'));
    add_filter('posts_orderby', array($this, 'posts_orderby'));

    parent::__construct($args);

    // Make sure these filters don't affect any other queries
    remove_filter('posts_fields', array($this, 'posts_fields'));
    remove_filter('posts_join', array($this, 'posts_join'));
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
        LEFT JOIN $wpdb->term_relationships WPT ON (WPT.object_id = $wpdb->posts.ID)
        LEFT JOIN $wpdb->term_taxonomy WTT ON (WTT.term_taxonomy_id=WPT.term_taxonomy_id)
        INNER JOIN $wpdb->terms ON ($wpdb->terms.term_id = WTT.term_id)
    ";
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
