<?php
/**
 * Pages to Create on Activation. Mostly For Dropping in WordPress Shortcodes
 */
$ifm_page_definitions = array(
	'main'                  => array(
		'title'   => __( 'Aggregator', 'personalize-login' ),
		'content' => '[ifm-container]',
	),
	'member-login'          => array(
		'title'   => __( 'Sign In', 'personalize-login' ),
		'content' => '[custom-login-form]',
	),
	'member-registration'   => array(
		'title'   => __( 'Register', 'personalize-login' ),
		'content' => '[custom-register-form]',
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
