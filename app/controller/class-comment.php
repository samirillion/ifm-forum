<?php

/**
 *
 * @package IFM
 */

namespace IFM;

class Controller_Comment
{

	public static function register()
	{
		$plugin = new self();

		add_shortcode('custom-comments', array($plugin, 'render_main'));

		add_action('admin_post_addComment', array($plugin, 'comment_on_post'));
		add_action('admin_post_nopriv_addComment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_vote_on_comment', array($plugin, 'vote_on_comment'));
		add_action('wp_ajax_nopriv_vote_on_comment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_reply_to_comment', array($plugin, 'comment_on_comment'));
		add_action('wp_ajax_nopriv_replyToComment', array($plugin, 'redirect_to_login'));
	}

	public function render_main()
	{
		$ifm_comments = new Model_Comment;
		$comment_query  = $ifm_comments->query_comments();
		$comment_array  = json_decode(json_encode($comment_query), true);

		$params = Router_Qvars::get_params();
		return view('comments/main', $comment_array, $params);
	}

	public function comment_on_post()
	{
		$ifm_comment = new Model_Comment;
		$ifm_comment->create();

		wp_redirect(esc_url(add_query_arg('ifm_post_id', $_REQUEST['post_id'], home_url(IFM_ROUTE_COMMENTS))));
	}

	public function comment_on_comment()
	{
		$ifm_comments = new Model_Comment;
		$ifm_comments->create();

		wp_redirect(esc_url(add_query_arg('ifm_post_id', $_REQUEST['post_id'], home_url(IFM_ROUTE_COMMENTS))));
	}

	public function vote_on_comment()
	{
		$ifm_comments = new Model_Comment;
		$ifm_comments->update_comment_get_karma();
	}

	public function redirect_to_login()
	{
		$redirect_url         = home_url(IFM_NAMESPACE . '/login');
		$response['redirect'] = $redirect_url;
		$response             = json_encode($response);
		echo $response;
		die();
	}
}
