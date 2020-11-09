<?php

/**
 * Container for comments under posts
 */

namespace IFM;

class View_Comments
{
	// O(N) - Will visit every node exactly once
	public static function sort_by_parent($comment_object)
	{
		$comments_by_parent = array();

		// Makes an array with (comment_parent => comment)
		foreach ($comment_object as $comment) {
			$comment_parent = $comment['comment_parent'];

			// Add a default array to store children comments in
			if (!array_key_exists($comment_parent, $comments_by_parent)) {
				$comments_by_parent[$comment_parent] = array();
			}

			// Append our comment
			$comments_by_parent[$comment_parent][] = $comment;
		}

		return $comments_by_parent;
	}

	// O(N) - Will visit each node exactly once (assuming no loops)
	public static function build_comment_structure($obj, $current_id = 0, $depth = 0)
	{
		// Quit out if we don't have any children
		global $wpdb;
		$user_id = get_current_user_id();
		if (!array_key_exists($current_id, $obj)) {
			return;
		}
		$children = $obj[$current_id];

		// Each node prints its own contents, then prints the contents of its children
		echo "<ul class='ifm-indented-list'>";
		foreach ($children as $comment) {
			$nonce = wp_create_nonce('comment_nonce', 'comment_nonce');
			$upvoted = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $wpdb->commentmeta WHERE meta_key='user_upvote_id' AND meta_value=%d AND comment_id=%d",
					$user_id,
					$comment['comment_ID']
				)
			);
			$upvotes = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT COUNT(*) FROM $wpdb->commentmeta WHERE comment_id=%d",
					$comment['comment_ID']
				)
			)[0]->{'COUNT(*)'};
?>
			<li class="comment-node" id="<?php echo $comment['comment_ID']; ?>" data-nonce="<?php echo $nonce; ?>">
				<a class="vote_on_comment" data-upordown="up">
					<?php
					if ($upvoted[0]->{'COUNT(*)'} == 1) {
						echo '<div class="ifm-vote upvoted"></div>';
					} else {
						echo '<div class="ifm-vote"></div>';
					}
					?>
				</a>
				<div class="commenter">
					<?php
					if ($user_id === (int) $comment['user_id']) {
						echo $upvotes;
						if ((int) $upvotes === 1) {
							echo ' point';
						} else {
							echo ' points';
						}
					}
					?>
					by
					<?php
					echo $comment['comment_author'];
					?>
				</div>
				<div class="comment-time"><?php echo human_time_diff(strtotime($comment['comment_date_gmt']), current_time('timestamp', 1)) . ' ago'; ?></div>
				<?php
				// $link = admin_url('admin-ajax.php?action=vote_on_comment&comment_id='.$comment['comment_ID'].'&nonce='.$nonce);
				echo '<div class="ifm-comment-content">' . $comment['comment_content'] . '</div>';
				?>
				<div class="reply-to-comment"><?php _e('reply', IFM_NAMESPACE) ?></div>
				<div class="ifm-comment-reply-container" style="display:none;">
					<textarea name="ifm-comment-reply-textarea" cols="40" rows="5" required></textarea>
					<br>
					<a class="ifm-submit-reply ifm-button ifm-button-secondary"><?php _e('submit', IFM_NAMESPACE) ?></a>
					<?php view('comments/comment-form'); ?>
				</div>
	<?php
			// Print all our children
			self::build_comment_structure($obj, $comment['comment_ID'], $depth + 1);
		}
		echo '</li></ul>';
	}
}
	?>