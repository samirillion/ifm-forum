<?php
class crowdsortCommentsController
{
    public static function register()
    {
        $plugin = new self();
        
        add_shortcode('custom-comments', array( $plugin, 'render_comments_page'));
        
        add_action('wp_ajax_addComment', array( $plugin, 'comment_on_post'));
        add_action('wp_ajax_nopriv_addComment', array( $plugin, 'redirect_to_login'));
        add_action('wp_ajax_vote_on_comment', array( $plugin, 'vote_on_comment'));
        add_action('wp_ajax_nopriv_vote_on_comment', array( $plugin, 'redirect_to_login'));
        add_action('wp_ajax_reply_to_comment', array( $plugin, 'comment_on_comment'));
        add_action('wp_ajax_nopriv_replyToComment', array( $plugin, 'redirect_to_login'));

    }
    public function __construct()
    {
    }

    public function render_comments_page()
    {
        require_once('models/news-aggregator-comments.php');
        $newsAggComments = new newsAggregatorComments;
        $commentQuery = $newsAggComments->query_comments();
        $commentArray = json_decode(json_encode($commentQuery), true);

        require_once('views/comment-container.php');
        return commentContainer::render($commentArray);
    }

    public function vote_on_comment(){

      require_once('models/news-aggregator-comments.php');
      $newsAggregatorComments = new newsAggregatorComments;
      $newsAggregatorComments->update_comment_karma();

   }

   public function comment_on_post() {
     require_once('models/news-aggregator-comments.php');
     $newsAggComments = new newsAggregatorComments;
     $newsAggComments->add_comment_to_post($postID);
   }

   public function comment_on_comment(){
     require_once('models/news-aggregator-comments.php');
     $newsAggComments = new newsAggregatorComments;
     $newsAggComments->comment_on_comment();
   }

   public function redirect_to_login()
   {
     $redirect_url = home_url( 'member-login' );
     $response[redirect] = $redirect_url;
     $response = json_encode($response);
     echo $response;
     die();
   }
  }
crowdsortCommentsController::register();
