<?php

namespace IFM;

class User
{

	public static function calculate_user_karma($user_id = -1)
	{
		global $wpdb;
		if ($user_id == -1) {
			$user_id = get_current_user_id();
		}
		$post_karma_count    = $wpdb->get_results(
			$wpdb->prepare(
				"select count(*)
        from wp_posts p
        inner join wp_postmeta pm
        on p.ID = pm.post_id
        where pm.meta_key='user_upvote_id'
          and pm.meta_value <> %d
          and p.post_author=%d",
				$user_id,
				$user_id
			)
		);
		$comment_karma_count = $wpdb->get_results(
			$wpdb->prepare(
				"select count(*)
        from wp_comments c
        inner join wp_commentmeta
        cm on c.comment_ID = cm.comment_id
        where cm.meta_key='user_upvote_id'
          and cm.meta_value <> %d
          and c.comment_author=%d",
				$user_id,
				$user_id
			)
		);
		return $post_karma_count[0]->{'count(*)'} + $comment_karma_count[0]->{'count(*)'};
	}

	public function update_user_information()
	{
		$about = $_POST['about'];
		$email = $_POST['email'];
		global $wpdb;
		$wpdb->update(
			$wpdb->users,
			array(
				'user_email' => $email,
			),
			array(
				'ID' => get_current_user_id(),
			),
			array('%s'),
			array('%d')
		);
		$wpdb->update(
			$wpdb->usermeta,
			array(
				'meta_value' => $about,
			),
			array(
				'user_id'  => get_current_user_id(),
				'meta_key' => 'about_user',
			),
			array('%s'),
			array('%d', '%s')
		);
		wp_redirect(add_query_arg('user_id', get_current_user_id(), home_url('user-profile')));
	}
}
