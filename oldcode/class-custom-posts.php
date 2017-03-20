<?php
add_action( 'init', 'ponds_custom_post_type' );

function ponds_custom_post_type() {
 // creating (registering) the custom type
 register_post_type( 'ponds_posts', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
   // let's now add all the options for this post type
   array( 'labels' => array(
     'name' => __( 'Pond Posts', 'pondsaggregator' ), /* This is the Title of the Group */
     'singular_name' => __( 'Pond Post', 'pondsaggregator' ), /* This is the individual type */
     'all_items' => __( 'All Pond Posts', 'pondsaggregator' ), /* the all items menu item */
     'add_new' => __( 'Add New', 'pondsaggregator' ), /* The add new menu item */
     'add_new_item' => __( 'Add New Pond Post', 'pondsaggregator' ), /* Add New Display Title */
     'edit' => __( 'Edit', 'pondsaggregator' ), /* Edit Dialog */
     'edit_item' => __( 'Edit Pond Post', 'pondsaggregator' ), /* Edit Display Title */
     'new_item' => __( 'New Pond Post', 'pondsaggregator' ), /* New Display Title */
     'view_item' => __( 'View Post Type', 'pondsaggregator' ), /* View Display Title */
     'search_items' => __( 'Search Post Type', 'pondsaggregator' ), /* Search Custom Type Title */
     'not_found' =>  __( 'Nothing found in the Database.', 'pondsaggregator' ), /* This displays if there are no entries yet */
     'not_found_in_trash' => __( 'Nothing found in Trash', 'pondsaggregator' ), /* This displays if there is nothing in the trash */
     'parent_item_colon' => ''
     ), /* end of arrays */
     'description' => __( 'This is a pond post', 'pondsaggregator' ), /* Custom Type Description */
     'public' => true,
     'publicly_queryable' => true,
     'exclude_from_search' => false,
     'show_ui' => true,
     'query_var' => true,
     'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
     'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
     'rewrite'	=> array( 'slug' => 'custom_type', 'with_front' => false ), /* you can specify its url slug */
     'has_archive' => 'custom_type', /* you can rename the slug here */
     'capability_type' => 'post',
     'hierarchical' => false,
     /* the next one is important, it tells what's enabled in the post editor */
     'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'sticky')
   ) /* end of options */
 ); /* end of register post type */

 /* this adds your post categories to your custom post type */
 register_taxonomy_for_object_type( 'category', 'ponds_post_type' );
 /* this adds your post tags to your custom post type */
 register_taxonomy_for_object_type( 'post_tag', 'ponds_post_type' );

}
?>
