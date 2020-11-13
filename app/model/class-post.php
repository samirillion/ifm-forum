<?php

/**
 * Helper Functions Defining Posts
 */

namespace IFM;

use WP_Post;
use WP_REST_Request;

class Model_Post
{
	/**
	 * @var WP_Post
	 */
	private $post;

	/**
	 * @param WP_Post $post
	 */
	public function __construct(WP_Post $post = null)
	{
		$this->post = $post;
	}

	public static function store()
	{
		$nonce = $_POST['_wpnonce'];
		if (!wp_verify_nonce($nonce, 'submit_ifm_post')) {
			exit("No naughty business please");
		}
		$post_array = array(
			'post_title'  => sanitize_text_field($_POST['post-title']),
			'post_type'   => IFM_POST_TYPE,
			'post_status' => 'publish',
		);

		$post_content = wp_kses_post($_POST['post-text-content']);
		if (isset($_POST['link-toggle']) && strlen(trim($_POST['post-text-content']))) {
			$post_array['post_content'] = wp_kses_post($_POST['post-text-content']);
			$is_url                     = false;
		} else {
			$is_url = true;
		}
		$post = wp_insert_post($post_array);
		wp_set_object_terms($post, $_POST['post-type'], IFM_POST_TAXONOMY_NAME, false);
		global $wpdb;
		$firstvote = $wpdb->insert(
			$wpdb->postmeta,
			array(
				'post_id' => $post,
				'meta_key'   => 'user_upvote_id',
				'meta_value' => get_current_user_id(),
			),
			array('%d', '%s', '%d')
		);

		if ($is_url) {
			add_post_meta($post, AGGREGATOR_OR_IFM_URL, $_POST['post-url'], true);
		}

		return $post;
	}

	public function update_post_get_karma()
	{
		if (!wp_verify_nonce($_REQUEST['nonce'], 'ifm_page_nonce')) {
			exit('No naughty business please');
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
		if ($upvoted >= 1) {
			$vote = $wpdb->delete(
				$wpdb->postmeta,
				array(
					'post_id'    => $post_id,
					'meta_key'   => 'user_upvote_id',
					'meta_value' => $userid,
				),
				array('%d', '%s', '%d')
			);
		} else {
			$vote = $wpdb->insert(
				$wpdb->postmeta,
				array(
					'post_id'    => $post_id,
					'meta_key'   => 'user_upvote_id',
					'meta_value' => $userid,
				),
				array('%d', '%s', '%d')
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

		if (false === $vote) {
			$result['type']        = 'error';
			$result['entry_karma'] = $entry_karma;
			$result['redirect']    = 'wat';
		} else {
			$result['upvoted']     = $upvoted;
			$result['type']        = 'success';
			$result['entry_karma'] = $entry_karma;
		}

		if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$result = json_encode($result);
			echo $result;
		} else {
			header('Location: ' . $_SERVER['HTTP_REFERER']);
		}

		die();
	}
}
