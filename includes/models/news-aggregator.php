<?php
    class newsAggregator extends abstractSorter
    {
        public function __construct()
        {
            $this->sorter = "News-Aggregator";
        }

        public function define_post_type()
        {
            register_post_type('aggregator-posts', /* (http://codex.wordpress.org/Function_Reference/register_post_type) */
            // let's now add all the options for this post type
            array( 'labels' => array(
              'name' => __('Aggregator Posts', 'newsaggregator'), /* This is the Title of the Group */
              'singular_name' => __('Aggregator Post', 'newsaggregator'), /* This is the individual type */
              'all_items' => __('All Aggregator Posts', 'newsaggregator'), /* the all items menu item */
              'add_new' => __('Add New', 'newsaggregator'), /* The add new menu item */
              'add_new_item' => __('Add New Aggregator Entry', 'newsaggregator'), /* Add New Display Title */
              'edit' => __('Edit', 'newsaggregator'), /* Edit Dialog */
              'edit_item' => __('Edit Aggregator Post', 'newsaggregator'), /* Edit Display Title */
              'new_item' => __('New Aggregator Post', 'newsaggregator'), /* New Display Title */
              'view_item' => __('View Post Type', 'newsaggregator'), /* View Display Title */
              'search_items' => __('Search Post Type', 'newsaggregator'), /* Search Custom Type Title */
              'not_found' =>  __('Nothing found in the Database.', 'newsaggregator'), /* This displays if there are no entries yet */
              'not_found_in_trash' => __('Nothing found in Trash', 'newsaggregator'), /* This displays if there is nothing in the trash */
              'parent_item_colon' => ''
              ), /* end of arrays */
              'description' => __('For posting to the newsaggregator', 'newsaggregator'), /* Custom Type Description */
              'public' => true,
              'publicly_queryable' => true,
              'exclude_from_search' => false,
              'show_ui' => true,
              'query_var' => true,
              'menu_position' => 8, /* this is what order you want it to appear in on the left hand side menu */
              'menu_icon' => get_stylesheet_directory_uri() . '/library/images/custom-post-icon.png', /* the icon for the custom post type menu */
              'rewrite'    => array( 'slug' => 'aggregatorentries', 'with_front' => false ), /* you can specify its url slug */
              'has_archive' => 'aggregatorentries', /* you can rename the slug here */
              'capability_type' => 'post',
              'hierarchical' => false,
              /* the next one is important, it tells what's enabled in the post editor */
              'supports' => array( 'title', 'thumbnail', 'revisions', 'sticky', 'comments', 'tags', 'author')
            ) /* end of options */
          ); /* end of register post type */

          // need to add this programmatically somewhere because of 404s...
          flush_rewrite_rules();
        }

        public function define_meta_on_publish($post_ID) {

          $post_author = get_post_field( 'post_author', $post_ID );

           add_post_meta ( $post_ID, 'temporal_karma', 0 );
           add_post_meta( $post_ID, 'user_upvote_id', $post_author, true );

        }

      public function define_post_meta_on_load()
        {
          add_action ( 'add_meta_boxes', array($this, 'aggregator_entry_url'));
          add_action( 'save_post', array($this, 'aggregator_save_entry_url'), 10, 2 );
          add_action ( 'add_meta_boxes', array($this, 'aggregator_entry_karma'));
        }

        public function aggregator_entry_karma( $post ) {
                      add_meta_box(
                'aggregator-entry-karma',      // Unique ID
                esc_html__( 'Aggregator Entry Karma', 'example' ),    // Title
                array($this, 'aggregator_entry_karma_meta_box'),   // Callback function
                'aggregator-posts',         // Admin page (or post type)
                'side',         // Context
                'high'         // Priority
              );
         }

       public function aggregator_entry_url( $post ) {
                     add_meta_box(
               'aggregator-entry-url',      // Unique ID
               esc_html__( 'Aggregator Entry Url', 'example' ),    // Title
               array($this, 'aggregator_entry_url_meta_box'),   // Callback function
               'aggregator-posts',         // Admin page (or post type)
               'normal',         // Context
               'high'         // Priority
             );
        }

        public function aggregator_entry_url_meta_box( $object, $box ) {
          ?>

             <?php wp_nonce_field( basename( __FILE__ ), 'aggregator_entry_url_nonce' ); ?>

             <p>
               <label for="aggregator-entry-url"><?php _e( "Add the URL for your Entry", 'example' ); ?></label>
               <br />
               <input class="widefat" type="text" name="aggregator-entry-url" id="aggregator-entry-url" value="<?php echo esc_attr( get_post_meta( $object->ID, 'aggregator_entry_url', true ) ); ?>" size="30" />
             </p>
            <?php
        }

        public function aggregator_entry_karma_meta_box( $object, $box ) {
          ?>
             <p>
               <label for="aggregator-entry-karma"><?php
               global $wpdb;
               $postID = $object->ID;
               $upvotes = $wpdb->get_var( $wpdb->prepare(
                 "
                   SELECT count(*)
                   FROM $wpdb->postmeta
                   WHERE post_id=%d
                   AND meta_key='user_upvote_id'
                 ",
                 $postID
               ) );
               echo $upvotes;
               ?>
               </label>
             </p>
            <?php
        }

                /* Save the meta box's post metadata. */
        public function aggregator_save_entry_url ( $post_id, $post ) {

          /* Verify the nonce before proceeding. */
          if ( !isset( $_POST['aggregator_entry_url_nonce'] ) || !wp_verify_nonce( $_POST['aggregator_entry_url_nonce'], basename( __FILE__ ) ) )
            return $post_id;

          /* Get the post type object. */
          $post_type = get_post_type_object( $post->post_type );

          /* Check if the current user has permission to edit the post. */
          if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
            return $post_id;

          /* Get the posted data and sanitize it for use as an HTML class. */
          $new_meta_value = ( isset( $_POST['aggregator-entry-url'] ) ? esc_url_raw( $_POST['aggregator-entry-url'] ) : '' );

          /* Get the meta key. */
          $meta_key = 'aggregator_entry_url';

          /* Get the meta value of the custom field key. */
          $meta_value = get_post_meta( $post_id, $meta_key, true );

          /* If a new meta value was added and there was no previous value, add it. */
          if ( $new_meta_value && '' == $meta_value )
            add_post_meta( $post_id, $meta_key, $new_meta_value, true );

          /* If the new meta value does not match the old value, update it. */
          elseif ( $new_meta_value && $new_meta_value != $meta_value )
            update_post_meta( $post_id, $meta_key, $new_meta_value );

          /* If there is no new meta value but an old value exists, delete it. */
          elseif ( '' == $new_meta_value && $meta_value )
            delete_post_meta( $post_id, $meta_key, $meta_value );
        }

        public function define_user_meta()
        {
            return "wat";
        }
    }
