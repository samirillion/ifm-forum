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
        add_action('post_ranking_cron', array( $plugin, 'update_post_rank'));
    }
    public function __construct()
    {
    }

    public function add_query_vars($vars)
    {
        $vars[] .= 'agg_post_id';
        return $vars;
    }

    public function update_post_rank()
    {
        require_once('models/news-aggregator.php');
        newsAggregator::update_temporal_karma();
    }

    public function create_container()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $sorter = $sorterFactory->get_sorter("News-Aggregator");
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        $query = $sorter->sort_posts($paged);
        $pageposts = $query[0];
        $max_num_pages = $query[1];

        require_once('views/post-container.php');
        $content = crowdsorterContainer::render($pageposts, $max_num_pages, $paged);
        return $content;
    }

    public function generate_sorter()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $aggregator = $sorterFactory->get_sorter("News-Aggregator");

        //add post definition details
        $aggregator->define_post_type();

        //add metadata on post creation
        //eventually add functionality to allow more vars in plugin
        add_action('load-post.php', array($aggregator, 'define_post_meta_on_load'));
        add_action('load-post-new.php', array($aggregator, 'define_post_meta_on_load'));
        add_action('publish_aggregator-posts', array($aggregator, 'define_meta_on_publish'));

    }

    public function render_comments_page()
    {
        require_once('models/news-aggregator-comments.php');
        $newsAggComments = new newsAggregatorComments;
        $commentQuery = $newsAggComments->sort_comments();

        require_once('views/comment-container.php');
        $content = commentContainer::render($commentQuery);
        return $content;
    }

    public function my_user_vote()
    {
        require_once('models/post-rank-tracker.php');
        $karmaTracker = new postRankTracker;
        $karmaTracker->update_karma();
    }
}

crowdsortPostsController::register();
