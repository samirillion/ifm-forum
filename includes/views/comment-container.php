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
    global $wpdb;
    $user_id = get_current_user_id();
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
      $upvoted = $wpdb->get_results(
        $wpdb->prepare(
          "SELECT COUNT(*) FROM $wpdb->commentmeta WHERE meta_key='user_upvote_id' AND meta_value=%d AND comment_id=%d",
          $user_id,
          $comment['comment_ID']
          )
        );
      ?>
  		<li class="comment-node" id="<?php echo $comment['comment_ID']?>" data-nonce="<?php echo $nonce?>">
      <a class="vote_on_comment" data-upordown="up">
          <?php if ($upvoted[0]->{'COUNT(*)'} == 1 ){
                        echo '<div class="agg-vote upvoted"></div>';
                    } else {
                        echo '<div class="agg-vote"></div>';
                    }?>
      </a>
      <div class="commenter">by <?php echo $comment['comment_author'] ?></div>
      <div class="comment-time"><?php echo human_time_diff(strtotime($comment['comment_date_gmt']), current_time('timestamp', 1)) . ' ago'; ?></div>
      <?php
      // $link = admin_url('admin-ajax.php?action=vote_on_comment&comment_id='.$comment['comment_ID'].'&nonce='.$nonce);
      echo '<div class="comment-content">' . $comment['comment_content'] . '</div>';
          ?>
        <div class="reply-to-comment">reply</div>
        <div class="comment-reply-container" style="display:none;">
          <textarea name="comment-reply-content" id="comment-reply-content" required></textarea>
          <a class="submit-reply">submit</a>
        </div>
      <?php
  		// Print all our children
  		self::build_comment_structure($obj, $comment['comment_ID'], $depth + 1);
  	}
  	echo "</ul>";
  }

  public static function render($commentQuery)
        {
          wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
          wp_register_script("news-aggregator", plugin_dir_url(__FILE__).'/js/news-aggregator.js', array('jquery'));
          wp_localize_script('news-aggregator', 'myAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'noposts' => esc_html__('No older posts found', 'aggregator'),
            'loggedIn' => is_user_logged_in(),
            'loginPage' => home_url( 'member-login' )
          ));
            wp_enqueue_script('news-aggregator');
          echo "<h4 class='comment-post-title'><a href='".get_post_meta(get_query_var('agg_post_id'))["aggregator_entry_url"]["0"]."' target='_blank'>".get_the_title(get_query_var('agg_post_id'))."</a></h4>";
          echo '<div class="post-type">(' . (wp_get_object_terms( get_query_var('agg_post_id'), 'aggpost-type'))[0]->{'name'} . ')</div>';
          if ( get_post(get_query_var('agg_post_id'))->post_content != "") {
              echo '<div class="comment-post-content-wrapper">';
              echo '<div class="comment-post-content">' . get_post(get_query_var('agg_post_id'))->post_content . '</div>';
              echo '</div>';
          }
          if (!$commentQuery) {
            echo "No comments here! start the discussion";
          }
          echo "<form id='reply-to-post'>";
          echo "<textarea id='comment-text-area' name='reply' cols='40' rows='5' required></textarea>";
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
