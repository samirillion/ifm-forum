<?php

class IfmPost {
	public static function sort_posts( $tax_term = '' ) {
		global $wpdb;
		$ppp    = ( isset( $_POST['ppp'] ) ) ? $_POST['ppp'] : 31;
		$page   = ( isset( $_REQUEST['crowd_p'] ) ) ? $_REQUEST['crowd_p'] : 1;
		$offset = ( $page - 1 ) * $ppp;
		if ( get_query_var( 'aggpost_tax' ) ) {
		  $filter_by = "
          AND $wpdb->terms.slug = '" . sanitize_text_field( get_query_var( 'aggpost_tax' ) ) . "' ";
		} elseif ( isset( $_POST['aggpostTax'] ) ) {
		  $filter_by = "
            AND $wpdb->terms.slug = '" . sanitize_text_field( $_POST['aggpostTax'] ) . "' ";
		} elseif ( get_query_var( 'user_id' ) ) {
			$filter_by = "
            AND $wpdb->posts.post_author = '" . sanitize_text_field( get_query_var( 'user_id' ) ) . "' 
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
        WHERE $wpdb->posts.post_type= 'aggregator-posts'
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

		$pageposts = $wpdb->get_results( $query_str, OBJECT );
	// $sql_posts_total = $wpdb->get_var( "SELECT count(*) FROM wp_posts WHERE post_type='aggregator-posts';");
	// $max_num_pages = ceil($sql_posts_total / $ppp);
	return [ $pageposts, $page ];
	}

	public static function update_temporal_karma() {
		// might institute this later
	// global $wpdb;
	//
	// $entry_karma = $wpdb->get_var($wpdb->prepare(
	// "
	// SELECT count(*)
	// FROM $wpdb->postmeta
	// WHERE post_id=%d
	// AND meta_key='user_upvote_id'
	// ",
	// $post_id
	// ));
	}
	public function update_post_karma() {
		if ( ! wp_verify_nonce( $_REQUEST['nonce'], 'aggregator_page_nonce' ) ) {
			exit( 'No naughty business please' );
		}
		global $wpdb;
		$userid  = get_current_user_id();
		$post_id = $_REQUEST['post_id'];
		$upvoted = $wpdb->get_var(
			$wpdb->prepare(
		  "
            SELECT count(1)
            FROM $wpdb->postmeta
            WHERE post_id=%d
            AND meta_key='user_upvote_id'
            AND meta_value=%d
          ",
		  $post_id,
		  $userid
		)
			);
		if ( $upvoted >= 1 ) {
			$vote = $wpdb->delete(
				 $wpdb->postmeta,
				array(
					'post_id'    => $post_id,
					'meta_key'   => 'user_upvote_id',
					'meta_value' => $userid,
				),
				array( '%d', '%s', '%d' )
				);
		} else {
			$vote = $wpdb->insert(
				 $wpdb->postmeta,
				array(
					'post_id'    => $post_id,
					'meta_key'   => 'user_upvote_id',
					'meta_value' => $userid,
				),
				array( '%d', '%s', '%d' )
				);
		}

		$entry_karma = $wpdb->get_var(
			$wpdb->prepare(
		  "
            SELECT count(*)
            FROM $wpdb->postmeta
            WHERE post_id=%d
            AND meta_key='user_upvote_id'
          ",
		  $post_id
		)
			);

		if ( false === $vote ) {
			$result['type']        = 'error';
			$result['entry_karma'] = $entry_karma;
			$result['redirect']    = 'wat';
		} else {
			$result['upvoted']     = $upvoted;
			$result['type']        = 'success';
			$result['entry_karma'] = $entry_karma;
		}

		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			$result = json_encode( $result );
			echo $result;
		} else {
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
		}

		die();
	}

	public function submit_post() {
	  // $nonce = $_POST['nonce'];
	  // if (! wp_verify_nonce( $nonce, 'submit_aggregator_post' )) {
	  // exit("No naughty business please");
	  // }
	xdebug_break();
	$post_array = array(
		'post_title'  => sanitize_text_field( $_POST['post-title'] ),
		'post_type'   => 'aggregator-posts',
		'post_status' => 'publish',
	);

	$post_content = wp_kses_post( $_POST['post-text-content'] );
	if ( $_POST['link-toggle'] && strlen( trim( $_POST['post-text-content'] ) ) ) {
			$post_array['post_content'] = wp_kses_post( $_POST['post-text-content'] );
			$is_url                     = false;
	} else {
			$is_url = true;
	}
	$post = wp_insert_post( $post_array );
	wp_set_object_terms( $post, $_POST['post-type'], 'aggpost-type', false );
	global $wpdb;
	$firstvote = $wpdb->insert(
		$wpdb->postmeta,
		array(
			'comment_id' => $post,
			'meta_key'   => 'user_upvote_id',
			'meta_value' => get_current_user_id(),
		),
		array( '%d', '%s', '%d' )
	);

	if ( $is_url ) {
			add_post_meta( $post, 'aggregator_entry_url', $_POST['post-url'], true );
	}

	wp_redirect( home_url() . '/fin-forum' );
	exit();
}

}
