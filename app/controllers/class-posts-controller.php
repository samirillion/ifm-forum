<?php

/**
 * Undocumented class
 *
 * @package Ifm
 */
require_once(IFM_APP . 'views/class-posts-container.php');
require_once(IFM_APP . 'views/class-edit-post.php');
require_once(IFM_APP . 'views/partials/class-post-template.php');
require_once(IFM_APP . 'views/class-submit-post.php');

require_once(IFM_APP . 'models/post.php');
require_once(IFM_APP . 'models/sorter-factory.php');
require_once(IFM_APP . 'models/news-aggregator.php');


class IfmPostsController extends IfmController
{

	/**
	 * Define Posts Per Page for Pagination. Eventually set in WordPress Admin.
	 *
	 * @var integer
	 */
	private $posts_per_page = 30;

	/**
	 * Registration function.
	 */
	public static function register()
	{
		$plugin = new self();

		add_shortcode('edit-aggpost', array($plugin, 'render_edit_post_container'));

		add_action('init', array($plugin, 'generate_sorter'));
		add_action('wp_ajax_add_entry_karma', array($plugin, 'my_user_vote'));
		add_action('wp_ajax_nopriv_add_entry_karma', array($plugin, 'redirect_to_login_ajax'));
		add_action('admin_post_submit_post', array($plugin, 'submit_post'));
		add_action('admin_post_nopriv_submit_post', array($plugin, 'redirect_to_login'));
		add_action('admin_post_edit_post', array($plugin, 'edit_post'));

		// Limit media library access
		// add_action( 'wp_ajax_nopriv_more_aggregator_posts', array( $plugin, 'load_more_posts' ) );
		// add_action( 'wp_ajax_more_aggregator_posts', array( $plugin, 'load_more_posts' ) );
		// add_action( 'wp_ajax_addComment', array( $plugin, 'add_comment' ) );
		// add_action( 'wp_ajax_nopriv_addComment', array( $plugin, 'redirect_to_login_ajax' ) );
		// add_action( 'wp_ajax_vote_on_comment', array( $plugin, 'vote_on_comment' ) );
		// add_action( 'wp_ajax_nopriv_vote_on_comment', array( $plugin, 'redirect_to_login_ajax' ) );
		// add_filter( 'ajax_query_attachments_args', array( $plugin, 'ifm_limit_media_upload_to_user' ) );
	}

	/**
	 * Undocumented function
	 *
	 * @param array $search_results
	 * @return void
	 */
	public function main()
	{
		$params = IfmQueryVars::get_params();

		if (array_key_exists('ifm_query', $params)) {
			$posts = $this->agg_search_posts($params);
		} else {
			$posts = IfmPost::sort_posts()[0];
		}

		return IfmPostsContainer::render($posts, $params);
	}

	public function newpage()
	{
		return "<hr><hr><h1>hello new page world</h1>";
	}

	public function myroute()
	{
		return "<h1>cool</h1><h1>cool</h1><h1>cool</h1>";
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function submit()
	{
		$ifm_submit = new IfmSubmitPost;
		$ifm_submit->render();
	}

	/**
	 * Undocumented function
	 *
	 * @param WP_REST_Request $request
	 * @return array
	 */
	public function select(WP_REST_Request $request)
	{
		return "Hello World!";
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function edit_post()
	{

		if (get_post_field('post_author', $_POST['post-id']) !== get_current_user_id()) {
			wp_safe_redirect(esc_url(add_query_arg('ifm_post_id', $_POST['post-id'], home_url('edit'))));
		}

		$the_post = array(
			'ID'         => $_POST['post-id'],
			'post_title' => $_POST['post-title'],
		);

		if ('' !== $_POST['post-text-content']) {
			$the_post['post_content'] = $_POST['post-text-content'];
		} else {
			update_post_meta($_POST['post-id'], 'aggregator_entry_url', $_POST['post-url']);
		}
		wp_set_object_terms($_POST['post-id'], $_POST['post-type'], 'aggpost-type', false);
		wp_update_post($the_post);

		wp_safe_redirect(home_url('forum'));
	}

	/**
	 * Limit media upload options on the frontend visual editor to the user's personal media.
	 */
	function ifm_limit_media_upload_to_user($query)
	{
		$user_id = get_current_user_id();
		if ($user_id && !current_user_can('activate_plugins') && !current_user_can('edit_others_posts')) {
			$query['author'] = $user_id;
		}
		return $query;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function render_edit_post_container()
	{
		IfmEditPost::render();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function agg_search_posts($params)
	{
		$query = null;
		$query->query_vars['s']              = sanitize_text_field($params['ifm_query']);
		$query->query_vars['posts_per_page'] = $this->posts_per_page;
		$posts                               = [];
		foreach (relevanssi_do_query($query) as $post) {
			if ('aggregator-posts' === $post->post_type) {
				$posts[] = $post;
			}
		}
		return $posts;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function load_more_posts()
	{
		$query     = IfmPost::sort_posts();
		$pageposts = $query[0];

		$content = IfmPostTemplate::render($pageposts);
		return $content;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function generate_sorter()
	{
		$sorter_factory = new sorterFactory;
		$aggregator     = $sorter_factory->get_sorter('News-Aggregator');

		// add post definition details
		$aggregator->define_post_type();
		$aggregator->define_post_meta();

		// add metadata on post creation
		// eventually add functionality to allow more vars in plugin
		add_action('load-post.php', array($aggregator, 'define_post_meta_on_load'));
		add_action('load-post-new.php', array($aggregator, 'define_post_meta_on_load'));
		add_action('publish_aggregator-posts', array($aggregator, 'define_meta_on_publish'));
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function redirect_to_login()
	{
		wp_redirect(home_url('member-login'));
		die();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function redirect_to_login_ajax()
	{
		$redirect_url         = home_url('member-login');
		$response[redirect] = $redirect_url;
		$response             = json_encode($response);
		echo $response;
		die();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function my_user_vote()
	{
		$karma_tracker = new IfmPost;
		$karma_tracker->update_post_karma();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function submit_post()
	{
		$crowd_posts = new IfmPost;
		$crowd_posts->submit_post();
	}
}

IfmPostsController::register();
