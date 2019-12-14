<?php

/**
 * Define Post Types
 */

$ifm_custom_post_args = array(
	'aggregator-posts',
	array(
		'labels'              => array(
			'name'               => __('Aggregator Posts', 'newsaggregator'), /* This is the Title of the Group */
			'singular_name'      => __('Aggregator Post', 'newsaggregator'), /* This is the individual type */
			'all_items'          => __('All Aggregator Posts', 'newsaggregator'), /* the all items menu item */
			'add_new'            => __('Add New', 'newsaggregator'), /* The add new menu item */
			'add_new_item'       => __('Add New Aggregator Entry', 'newsaggregator'), /* Add New Display Title */
			'edit'               => __('Edit', 'newsaggregator'), /* Edit Dialog */
			'edit_item'          => __('Edit Aggregator Post', 'newsaggregator'), /* Edit Display Title */
			'new_item'           => __('New Aggregator Post', 'newsaggregator'), /* New Display Title */
			'view_item'          => __('View Post Type', 'newsaggregator'), /* View Display Title */
			'search_items'       => __('Search Post Type', 'newsaggregator'), /* Search Custom Type Title */
			'not_found'          => __('Nothing found in the Database.', 'newsaggregator'), /* This displays if there are no entries yet */
			'not_found_in_trash' => __('Nothing found in Trash', 'newsaggregator'), /* This displays if there is nothing in the trash */
			'parent_item_colon'  => '',
		), /* end of arrays */
		'menu_icon'           => __('dashicons-share', 'newsaggregator'),
		'description'         => __('For posting to the newsaggregator', 'newsaggregator'), /* Custom Type Description */
		'public'              => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'show_ui'             => true,
		'query_var'           => true,
		'menu_position'       => 8, /* this is what order you want it to appear in on the left hand side menu */
		'menu_icon'           => 'dashicons-share-alt', /* the icon for the custom post type menu */
		'show_in_rest'        => true,
		'rest_base'           => IFM_NAMESPACE,
		'rewrite'             => array(
			'slug'       => 'aggregatorentries',
			'with_front' => false,
		), /* you can specify its url slug */
		'has_archive'         => 'aggregatorentries', /* you can rename the slug here */
		'capability_type'     => 'post',
		'hierarchical'        => false,
		/* the next one is important, it tells what's enabled in the post editor */
		'supports'            => array('title', 'thumbnail', 'revisions', 'sticky', 'comments', 'tags', 'author', 'editor'),
	), /* end of options */
); /* end of register post type */
