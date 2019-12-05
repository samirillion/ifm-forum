<?php

/**
 *
 * @package Ifmer
 */
require(IFM_APP . 'models/class-comments.php');
require(IFM_APP . 'views/class-comment-container.php');



class IfmCommentController
{

	public static function register()
	{
		$plugin = new self();

		add_shortcode('custom-comments', array($plugin, 'main'));

		add_action('wp_ajax_addComment', array($plugin, 'comment_on_post'));
		add_action('wp_ajax_nopriv_addComment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_vote_on_comment', array($plugin, 'vote_on_comment'));
		add_action('wp_ajax_nopriv_vote_on_comment', array($plugin, 'redirect_to_login'));
		add_action('wp_ajax_reply_to_comment', array($plugin, 'comment_on_comment'));
		add_action('wp_ajax_nopriv_replyToComment', array($plugin, 'redirect_to_login'));
	}
	public function __construct()
	{ }

	public function comment_on_post()
	{
		$ifm_comments = new IfmComment;
		$ifm_comments->add_comment_to_post($postID);
	}


	public function main()
	{
		$ifm_comments = new IfmComment;
		$comment_query  = $ifm_comments->query_comments();
		$comment_array  = json_decode(json_encode($comment_query), true);

		$params = IfmQueryVars::get_params();
		xdebug_break();
		return IfmCommentContainer::render($comment_array, $params);
	}

	public function vote_on_comment()
	{
		$ifm_comments = new IfmComment;
		$ifm_comments->update_comment_karma();
	}

	public function comment_on_comment()
	{
		$ifm_comments = new IfmComment;
		$ifm_comments->comment_on_comment();
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

IfmCommentController::register();
