<?php

namespace ifm;

function forum_post_types()
{
    $custom_post_args = array(
        IFM_POST_TYPE_NAME,
        array(
            'labels'              => array(
                'name'               => __('Forum Posts', 'ifm-forum'), /* This is the Title of the Group */
                'singular_name'      => __('Forum Post', 'ifm-forum'), /* This is the individual type */
                'all_items'          => __('All Forum Posts', 'ifm-forum'), /* the all items menu item */
                'add_new'            => __('Add New', 'ifm-forum'), /* The add new menu item */
                'add_new_item'       => __('Add New Aggregator Entry', 'ifm-forum'), /* Add New Display Title */
                'edit'               => __('Edit', 'ifm-forum'), /* Edit Dialog */
                'edit_item'          => __('Edit Forum Post', 'ifm-forum'), /* Edit Display Title */
                'new_item'           => __('New Forum Post', 'ifm-forum'), /* New Display Title */
                'view_item'          => __('View Post Type', 'ifm-forum'), /* View Display Title */
                'search_items'       => __('Search Post Type', 'ifm-forum'), /* Search Custom Type Title */
                'not_found'          => __('Nothing found in the Database.', 'ifm-forum'), /* This displays if there are no entries yet */
                'not_found_in_trash' => __('Nothing found in Trash', 'ifm-forum'), /* This displays if there is nothing in the trash */
                'parent_item_colon'  => '',
            ), /* end of arrays */
            'menu_icon'           => __('dashicons-share', 'ifm-forum'),
            'description'         => __('For posting to the Forum', 'ifm-forum'), /* Custom Type Description */
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
                'slug'       => IFM_POST_TYPE_NAME,
                'with_front' => false,
            ), /* you can specify its url slug */
            'has_archive'         => IFM_POST_TYPE_NAME, /* you can rename the slug here */
            'capability_type'     => 'post',
            'hierarchical'        => false,
            /* the next one is important, it tells what's enabled in the post editor */
            'supports'            => array('title', 'thumbnail', 'revisions', 'sticky', 'comments', 'tags', 'author', 'editor'),
        ), /* end of options */
    );

    $custom_taxonomy_args = array(
        IFM_POST_TAXONOMY_NAME,
        IFM_POST_TYPE_NAME,
        array(
            // Hierarchical taxonomy (like categories)
            'hierarchical' => true,
            // This array of options controls the labels displayed in the WordPress Admin UI
            'labels'       => array(
                'name'              => _x('Forum Post Type', 'taxonomy general name'),
                'singular_name'     => _x('Forum Post Type', 'taxonomy singular name'),
                'search_items'      => __('Search Forum Post Types'),
                'all_items'         => __('All Forum Post Types'),
                'parent_item'       => __('Parent Forum Post Type'),
                'parent_item_colon' => __('Parent Forum Post Type:'),
                'edit_item'         => __('Edit Forum Post Type'),
                'update_item'       => __('Update Forum Post Type'),
                'add_new_item'      => __('Add New Forum Post Type'),
                'new_item_name'     => __('New Forum Post Type Name'),
                'menu_name'         => __('Forum Post Types'),
            ),
            // Control the slugs used for this taxonomy
            'rewrite'      => array(
                'slug'         => IFM_POST_TAXONOMY_NAME, // This controls the base slug that will display before each term
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
