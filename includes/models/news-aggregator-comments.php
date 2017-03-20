<?php

    class newsAggregatorComments
    {
        // Information needed for creating the plugin's pages
public function sort_comments()
{
  $args = array(
    'post_id' => get_query_var('agg_post_id'),
    'hierarchical' => true
);
  // The Query
  $comments_query = new WP_Comment_Query;
  $comments = $comments_query->query( $args );
  return $comments;
}
    }
