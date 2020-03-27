<?php
namespace ifm;
function forum_post_types()
{
    $custom_post_args = array(
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
    );

    $custom_taxonomy_args = array(
        'aggpost-type',
        'aggregator-posts',
        array(
            // Hierarchical taxonomy (like categories)
            'hierarchical' => true,
            // This array of options controls the labels displayed in the WordPress Admin UI
            'labels'       => array(
                'name'              => _x('Aggpost-type', 'taxonomy general name'),
                'singular_name'     => _x('Aggpost-type', 'taxonomy singular name'),
                'search_items'      => __('Search Aggpost-type'),
                'all_items'         => __('All Aggpost-type'),
                'parent_item'       => __('Parent Aggpost-type'),
                'parent_item_colon' => __('Parent Aggpost-type:'),
                'edit_item'         => __('Edit Aggpost-type'),
                'update_item'       => __('Update Aggpost-type'),
                'add_new_item'      => __('Add New Aggpost-type'),
                'new_item_name'     => __('New Aggpost-type Name'),
                'menu_name'         => __('Aggpost-types'),
            ),
            // Control the slugs used for this taxonomy
            'rewrite'      => array(
                'slug'         => 'aggpost-types', // This controls the base slug that will display before each term
                'with_front'   => false, // Don't display the category base before "/locations/"
                'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
            ),
        )
    );
    register_post_type(
        $custom_post_args[0],
        $custom_post_args[1]
    ); /* end of register post type */
    register_taxonomy($custom_taxonomy_args[0], $custom_taxonomy_args[1], $custom_taxonomy_args[2]);
}
add_action('init', 'ifm\forum_post_types');
