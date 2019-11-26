<?php
/**
 * Definitions for pages with accompanying shortcodes
 *
 * @package IfmSort
 */
class IfmPageDefinitions {
	/**
	 *  Information needed for creating the plugin's pages
	 */
	public function define_pages() {
		$page_definitions = array(
			'main'                  => array(
				'title'   => __( 'Aggregator', 'personalize-login' ),
				'content' => '[crowdsortcontainer]',
			),
			'member-login'          => array(
				'title'   => __( 'Sign In', 'personalize-login' ),
				'content' => '[custom-login-form]',
			),
			'member-registration'   => array(
				'title'   => __( 'Register', 'personalize-login' ),
				'content' => '[custom-register-form]',
			),
			'member-account'        => array(
				'title'   => __( 'your account', 'personalize-login' ),
				'content' => '[account-info]',
			),
			'member-password-lost'  => array(
				'title'   => __( 'Forgot Your Password?', 'personalize-login' ),
				'content' => '[custom-password-lost-form]',
			),
			'member-password-reset' => array(
				'title'   => __( 'Pick a New Password', 'personalize-login' ),
				'content' => '[custom-password-reset-form]',
			),
			'add-a-post'            => array(
				'title'   => __( 'Create a New Post', 'create-post' ),
				'content' => '[ifm-post]',
			),
			'account-details'       => array(
				'title'   => __( 'My Account Details', 'account-details' ),
				'content' => '[ifm-account-details]',
			),
			'change-password'       => array(
				'title'   => __( 'Change My Password', 'change-password' ),
				'content' => '[change-password]',
			),
			'edit-post'             => array(
				'title'   => __( 'Edit My Post', 'edit-aggpost' ),
				'content' => '[edit-aggpost]',
			),
			'view-user-profile'     => array(
				'title'   => __( 'View User Profile', 'user-profile' ),
				'content' => '[user-profile]',
			),
		);

		foreach ( $page_definitions as $slug => $page ) {
			// Check that the page doesn't exist already
			$query = new WP_Query( 'pagename=' . $slug );
			if ( ! $query->have_posts() ) {
				// Add the page using the data from the array above
			wp_insert_post(
				array(
					'post_content'   => $page['content'],
					'post_name'      => $slug,
					'post_title'     => $page['title'],
					'post_status'    => 'publish',
					'post_type'      => 'page',
					'ping_status'    => 'closed',
					'comment_status' => 'closed',
				)
			);
			}
		}
	}
}
