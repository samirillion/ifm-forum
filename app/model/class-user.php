<?php

namespace IFM;

use WP_User;

class Model_User extends WP_User
{
	private $karma;
	private $has_email;
	private $email_verified;
	private $notifications;

	/**
	 * How long has the account existed
	 *
	 * @return void
	 */
	public function get_duration()
	{
		return human_time_diff(strtotime($this->data->user_registered), current_time('timestamp', 1));
	}

	/**
	 * What is the users status re mail? If they have email, it needs to be verified. If they want notifications, they need email, etc.
	 *
	 * @return void
	 */
	public function get_email_status()
	{
		if ($this->get('email_verified')) {
			return 'verified';
		}
		if ($this->get('has_email')) {
			return 'needs_verification';
		}
		if ($this->get('notifications')) {
			return 'needs_email';
		}
		return 'no_mail';
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

			return $wpdb->update(
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
		} elseif (is_email($email)) {
			return 'email_exists';
		} else {
			return 'invalid_email';
		}
	}

	public function set_notifications($notifications)
	{
		return update_user_meta($this->ID, 'notifications', $notifications);
	}

	public function set_about($about)
	{
		return update_user_meta($this->ID, 'about_user', $about);
	}
}
