<?php

class commentContainer
{

  // O(N) - Will visit every node exactly once
  public static function sort_by_parent($comment_object)
  {
  	$comments_by_parent = array();

  	// Makes an array with (comment_parent => comment)
  	foreach ($comment_object as $comment)
  	{
  		$comment_parent = $comment['comment_parent'];

  		// Add a default array to store children comments in
  		if (!array_key_exists($comment_parent, $comments_by_parent))
  		{
  			$comments_by_parent[$comment_parent] = array();
  		}

  		// Append our comment
  		$comments_by_parent[$comment_parent][] = $comment;
  	}

  	return $comments_by_parent;
  }

  // O(N) - Will visit each node exactly once (assuming no loops)
  public static function build_comment_structure($obj, $currentID = 0, $depth = 0)
  {
  	// Quit out if we don't have any children
  	if (!array_key_exists($currentID, $obj))
  	{
  		return;
  	}
  	$children = $obj[$currentID];

  	// Each node prints its own contents, then prints the contents of its children
  	echo "<ul class='indented-list'>";
  	foreach ($children as $comment)
  	{
      $nonce = wp_create_nonce("comment_nonce");
      ?>
  		<li class="comment-node" id="<?php echo $comment['comment_ID']?>" data-nonce="<?php echo $nonce?>">
      <div class="commenter">by <?php echo $comment['comment_author'] ?></div>
      <div class="comment-time"><?php echo human_time_diff(strtotime($comment['comment_date_gmt']), current_time('timestamp', 1)) . ' ago'; ?></div>
      <?php
      // $link = admin_url('admin-ajax.php?action=vote_on_comment&comment_id='.$comment['comment_ID'].'&nonce='.$nonce);
      echo $comment['comment_content'];
      ?>
      <br>
      <div class="reply-to-comment">reply</div><a class="vote_on_comment" data-upordown="up">++</a>
      <?php
  		// Print all our children
  		self::build_comment_structure($obj, $comment['comment_ID'], $depth + 1);
  	}
  	echo "</ul>";
  }

  public static function render($commentQuery)
        {
          wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
          wp_register_script("news-aggregator", WP_PLUGIN_URL.'/crowd-sorter/includes/views/js/news-aggregator.js', array('jquery'));
          wp_localize_script('news-aggregator', 'myAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'noposts' => esc_html__('No older posts found', 'aggregator'),
            'loggedIn' => is_user_logged_in(),
            'loginPage' => home_url( 'member-login' )
          ));
            wp_enqueue_script('news-aggregator');
          echo "<h3>".get_the_title(get_query_var('agg_post_id'))."</h3>";
          if (!$commentQuery) {
            echo "No comments here! start the discussion";
          }
          echo "<form id='reply-to-post'>";
          echo "<textarea type='textarea' id='comment-text-area' name='reply' cols='40' rows='5'></textarea>";
          echo "<input type='hidden' name='action' value='addComment'/>";
          echo "<input type='submit' value='comment'>";
          echo "<input type='hidden' name='post_id' value='".get_query_var('agg_post_id')."'>";
					echo wp_nonce_field('reply-to-post-nonce');
          echo "</form>";
          if($commentQuery) {
          $object = self::sort_by_parent($commentQuery);
          self::build_comment_structure($object);
        }
        }


}
?>
