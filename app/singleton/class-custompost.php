<?php

namespace IFM;

class Singleton_CustomPost
{
    public static function register()
    {
        $plugin = new self();

        add_action('init', array($plugin, 'ifm_custom_post'));
        add_action('init', array($plugin, 'ifm_custom_taxonomy'));

        add_action('add_meta_boxes', array($plugin, 'ifm_meta_boxes'));
        add_action('save_post', array($plugin, 'ifm_save_entry_url'), 10, 2);
    }

    public function ifm_custom_post()
    {
        $custom_post_args = array(
            IFM_POST_TYPE,
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
                    'slug'       => IFM_POST_TYPE,
                    'with_front' => false,
                ), /* you can specify its url slug */
                'has_archive'         => IFM_POST_TYPE, /* you can rename the slug here */
                'capability_type'     => 'post',
                'hierarchical'        => false,
                /* the next one is important, it tells what's enabled in the post editor */
                'supports'            => array('title', 'thumbnail', 'revisions', 'sticky', 'comments', 'tags', 'author', 'editor'),
            ), /* end of options */
        );

        register_post_type(
            $custom_post_args[0],
            $custom_post_args[1]
        );
    }

    public function ifm_custom_taxonomy()
    {
        $custom_taxonomy_args = array(
            IFM_POST_TAXONOMY_NAME,
            IFM_POST_TYPE,
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
                'show_in_rest' => true,
                // Control the slugs used for this taxonomy
                'rewrite'      => array(
                    'slug'         => IFM_POST_TAXONOMY_NAME, // This controls the base slug that will display before each term
                    'with_front'   => false, // Don't display the category base before "/locations/"
                    'hierarchical' => true, // This will allow URL's like "/locations/boston/cambridge/"
                ),
            )
        );
        register_taxonomy($custom_taxonomy_args[0], $custom_taxonomy_args[1], $custom_taxonomy_args[2]);
    }

    public function ifm_meta_boxes()
    {
        add_meta_box(
            'ifm-entry-url',      // Unique ID
            esc_html__('Forum Entry Url', 'example'),    // Title
            array($this, 'ifm_entry_url_meta_box'),   // Callback function
            IFM_POST_TYPE,         // Admin page (or post type)
            'normal',         // Context
            'high'         // Priority
        );

        add_meta_box(
            'ifm-entry-karma',      // Unique ID
            esc_html__('Forum Entry Karma', 'example'),    // Title
            array($this, 'ifm_entry_karma_meta_box'),   // Callback function
            IFM_POST_TYPE,         // Admin page (or post type)
            'side',         // Context
            'high'         // Priority
        );
    }

    public function ifm_entry_url_meta_box($object, $box)
    {
?>

        <?php wp_nonce_field(basename(__FILE__), 'ifm_entry_url_nonce'); ?>

        <p>
            <label for="ifm-entry-url"><?php _e('Add the URL for your Entry', 'example'); ?></label>
            <br />
            <input class="widefat" type="text" name="ifm-entry-url" id="ifm-entry-url" value="<?php echo esc_attr(get_post_meta($object->ID, 'ifm_entry_url', true)); ?>" size="30" />
        </p>
    <?php
    }
    public function ifm_entry_karma_meta_box($object, $box)
    {
    ?>
        <p>
            <label for="ifm-entry-karma">
                <?php
                global $wpdb;
                $postID = $object->ID;
                $upvotes  = $wpdb->get_var(
                    $wpdb->prepare(
                        "
                    SELECT count(*)
                    FROM $wpdb->postmeta
                    WHERE post_id=%d
                    AND meta_key='user_upvote_id'
                  ",
                        $postID
                    )
                );
                echo $upvotes;
                ?>
            </label>
        </p>
<?php
    }
    /* Save the meta box's post metadata. */
    public function ifm_save_entry_url($post_id, $post)
    {

        /* Verify the nonce before proceeding. */
        if (!isset($_POST['ifm_entry_url_nonce']) || !wp_verify_nonce($_POST['ifm_entry_url_nonce'], basename(__FILE__))) {
            return $post_id;
        }

        /* Get the post type object. */
        $post_type = get_post_type_object($post->post_type);

        /* Check if the current user has permission to edit the post. */
        if (!current_user_can($post_type->cap->edit_post, $post_id)) {
            return $post_id;
        }

        /* Get the posted data and sanitize it for use as an HTML class. */
        $new_meta_value = (isset($_POST['ifm-entry-url']) ? esc_url_raw($_POST['ifm-entry-url']) : '');

        /* Get the meta key. */
        $meta_key = 'ifm_entry_url';

        /* Get the meta value of the custom field key. */
        $meta_value = get_post_meta($post_id, $meta_key, true);

        /* If a new meta value was added and there was no previous value, add it. */
        if ($new_meta_value && '' == $meta_value) {
            add_post_meta($post_id, $meta_key, $new_meta_value, true);
        }

        /* If the new meta value does not match the old value, update it. */ elseif ($new_meta_value && $new_meta_value != $meta_value) {
            update_post_meta($post_id, $meta_key, $new_meta_value);
        }

        /* If there is no new meta value but an old value exists, delete it. */ elseif ('' == $new_meta_value && $meta_value) {
            delete_post_meta($post_id, $meta_key, $meta_value);
        }
    }
}
