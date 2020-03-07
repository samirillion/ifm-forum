<?php

/**
 *
 * @package er
 */

namespace Ifm;
// require(IFM_APP . 'models/comment.php');
// require(IFM_APP . 'views/class-comment-container.php');



class CommentController
{

	public static function register()
	{
		$plugin = new self();

		add_shortcode('custom-comments', array($plugin, 'main'));

		add_action('admin_post_addComment', array($plugin, 'comment_on_post'));
		add_action('admin_post_nopriv_addComment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_vote_on_comment', array($plugin, 'vote_on_comment'));
		add_action('wp_ajax_nopriv_vote_on_comment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_reply_to_comment', array($plugin, 'comment_on_comment'));
		add_action('wp_ajax_nopriv_replyToComment', array($plugin, 'redirect_to_login'));
	}
	public function __construct()
	{
	}

	public function comment_on_post(\WP_REST_Request $request)
	{
		$params = QueryVars::get_params();
		$ifm_comment = new Comment;
		$ifm_comment->comment($request, $params);

		wp_redirect(esc_url(add_query_arg('ifm_post_id', $_POST['post_id'], home_url('/comments'))));
	}

	public function comment_on_comment()
	{
		$ifm_comments = new Comment;
		$ifm_comments->comment_on_comment();
	}


	public function main()
	{
		$ifm_comments = new Comment;
		$comment_query  = $ifm_comments->query_comments();
		$comment_array  = json_decode(json_encode($comment_query), true);

		$params = QueryVars::get_params();
		return CommentContainer::render($comment_array, $params);
	}

	public function vote_on_comment()
	{
		$ifm_comments = new Comment;
		$ifm_comments->update_comment_karma();
	}

	public function redirect_to_login()
	{
		$redirect_url         = home_url('member-login');
		$response['redirect'] = $redirect_url;
		$response             = json_encode($response);
		echo $response;
		die();
	}
}

CommentController::register();
