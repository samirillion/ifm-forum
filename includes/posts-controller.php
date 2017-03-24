<?php

class crowdsortPostsController
{
    public static function register()
    {
        $plugin = new self();

        add_action('init', array( $plugin, 'generate_sorter' ));
        add_shortcode('crowdsortcontainer', array( $plugin, 'create_container' ));
        add_action('wp_ajax_add_entry_karma', array( $plugin, 'my_user_vote' ));
        // add_action('wp_ajax_nopriv_add_entry_karma', array( $plugin, 'redirect_to_login'));
        add_shortcode('custom-comments', array( $plugin, 'render_comments_page'));
        add_filter('query_vars', array( $plugin, 'add_query_vars'));
        add_action('post_ranking_cron', array( $plugin, 'update_post_hotness'));
    }
    public function __construct()
    {
    }

    public function add_query_vars( $vars ){
      $vars[] .= 'agg_post_id';
      return $vars;
    }

    public function update_post_hotness(){

      
    }

    public function create_container()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $sorter = $sorterFactory->get_sorter("News-Aggregator");
        $the_query = $sorter->sort_posts();

        require_once('views/crowdsorter-container.php');
        $content = crowdsorterContainer::render($the_query);
        return $content;
    }

    public function generate_sorter()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $aggregator = $sorterFactory->get_sorter("News-Aggregator");

        $aggregator->define_post_type();
        add_action('load-post.php', array($aggregator, 'define_post_meta'));
        add_action('load-post-new.php', array($aggregator, 'define_post_meta'));
    }



    public function render_comments_page(){
      require_once('models/news-aggregator-comments.php');
      $newsAggComments = new newsAggregatorComments;
      $commentQuery = $newsAggComments->sort_comments();

      require_once('views/comment-container.php');
      $content = commentContainer::render($commentQuery);
      return $content;
    }

    public function my_user_vote()
    {
        require_once('models/post-karma-tracker.php');
        $karmaTracker = new postKarmaTracker;
        $karmaTracker->update_karma();
    }

}

crowdsortPostsController::register();
