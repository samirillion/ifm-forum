<?php

namespace IFM;

use WP_User;

class Model_User extends WP_User
{

	const notification_options = array(
		'new_post' => 'I want to receive notifications for all new forum posts.',
		'comment_on_post' => 'I want to receive notifications for comments on my posts',
		'comment_on_comment' => 'I want to receive notifications for comments on my comments'
	);
	/**
	 * How long has the account existed
	 *
	 * @return void
	 */
	public function get_duration()
	{
		return human_time_diff(strtotime($this->data->user_registered), current_time('timestamp', 1));
	}

	public function get_karma()
	{
		global $wpdb;
		$user_id = $this->ID;
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
		$this->set_about($_POST['about']);
		$this->set_email($_POST['email']);
		$this->set_notifications($_POST['notifications']);
	}

	public function set_email($email)
	{
		global $wpdb;

		if (!email_exists($email) && is_email($email)) {

			$wpdb->update(
				$wpdb->users,
				array(
					'user_email' => $email,
				),
				array(
					'ID' => $this->ID,
				),
				array('%s'),
				array('%d')
			);

			update_user_meta($this->ID, 'email_verified', false);

			return 'success';
		} elseif (is_email($email)) {
			return 'email_exists';
		} else {
			return 'invalid_email';
		}
	}

	public function get_notifications()
	{
		$notifications = array();
		foreach (self::notification_options as $name => $description) {
			if ($this->get($name)) {
				$notifications[$name] = true;
			} else {
				$notifications[$name] = false;
			}
		}
		return $notifications;
	}

	public function set_notifications($notifications)
	{
		foreach (self::notification_options as $name => $description) {
			if (in_array($name, $notifications)) {
				update_user_meta($this->ID, $name, true);
			} else {
				update_user_meta($this->ID, $name, false);
			}
		}
	}

	public function set_about($about)
	{
		return update_user_meta($this->ID, 'about_user', $about);
	}
}
