<?php

/**
 * Sorter Class for Handling Aggregation
 *
 * @package IFM
 */

namespace IFM;

class Controller_Sort
{
	public static function sort_posts()
	{
		global $wpdb;
		$ppp    = (isset($_POST['ppp'])) ? $_POST['ppp'] : 31;
		$page   = (isset($_REQUEST['ifm_p'])) ? $_REQUEST['ifm_p'] : 1;
		$offset = ($page - 1) * $ppp;
		if (get_query_var('ifm_tax')) {
			$filter_by = "
          AND $wpdb->terms.slug = '" . sanitize_text_field(get_query_var('ifm_tax')) . "' ";
		} elseif (isset($_POST['aggpostTax'])) {
			$filter_by = "
            AND $wpdb->terms.slug = '" . sanitize_text_field($_POST['aggpostTax']) . "' ";
		} elseif (get_query_var('user_id')) {
			$filter_by = "
            AND $wpdb->posts.post_author = '" . sanitize_text_field(get_query_var('user_id')) . "' 
            ";
		} else {
			$filter_by = '';
		}
		$query_str = "
      SELECT
        $wpdb->posts.*,
        CASE
          WHEN ROUND(POW((TIMESTAMPDIFF( MINUTE, $wpdb->posts.post_date_gmt, UTC_TIMESTAMP())/60), 1.8), 2) = 0
          THEN .01
          ELSE ROUND(POW((TIMESTAMPDIFF( MINUTE, $wpdb->posts.post_date_gmt, UTC_TIMESTAMP())/60), 1.8), 2)
          END AS karma_divisor
        FROM $wpdb->posts
        LEFT JOIN $wpdb->term_relationships ON $wpdb->term_relationships.object_id=$wpdb->posts.ID
        LEFT JOIN $wpdb->term_taxonomy ON $wpdb->term_taxonomy.term_taxonomy_id=$wpdb->term_relationships.term_taxonomy_id
        INNER JOIN $wpdb->terms ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
        WHERE $wpdb->posts.post_type= 'aggregator-posts'
        " . $filter_by . "
        AND $wpdb->posts.post_status = 'publish'
      ORDER BY  (
                (
                  SELECT count(*)
                  FROM wp_postmeta
                  WHERE post_id=$wpdb->posts.ID
                  AND meta_key='user_upvote_id'
                  )/karma_divisor
                ) DESC
LIMIT " . $offset . ', ' . $ppp . '; ';

		$pageposts = $wpdb->get_results($query_str, OBJECT);
		// $sql_posts_total = $wpdb->get_var( "SELECT count(*) FROM wp_posts WHERE post_type='aggregator-posts';");
		// $max_num_pages = ceil($sql_posts_total / $ppp);
		return [$pageposts, $page];
	}

	public function define_meta_on_publish($post_ID)
	{

		$post_author = get_post_field('post_author', $post_ID);

		add_post_meta($post_ID, 'temporal_karma', 0);
		add_post_meta($post_ID, 'user_upvote_id', $post_author, true);
	}

	public function define_post_meta_on_load()
	{
		add_action('add_meta_boxes', array($this, 'aggregator_entry_url'));
		add_action('save_post', array($this, 'aggregator_save_entry_url'), 10, 2);
		add_action('add_meta_boxes', array($this, 'aggregator_entry_karma'));
	}

	public function aggregator_entry_karma($post)
	{
		add_meta_box(
			'aggregator-entry-karma',      // Unique ID
			esc_html__('Aggregator Entry Karma', 'example'),    // Title
			array($this, 'aggregator_entry_karma_meta_box'),   // Callback function
			'aggregator-posts',         // Admin page (or post type)
			'side',         // Context
			'high'         // Priority
		);
	}

	public function aggregator_entry_url($post)
	{
		add_meta_box(
			'aggregator-entry-url',      // Unique ID
			esc_html__('Aggregator Entry Url', 'example'),    // Title
			array($this, 'aggregator_entry_url_meta_box'),   // Callback function
			'aggregator-posts',         // Admin page (or post type)
			'normal',         // Context
			'high'         // Priority
		);
	}

	public function aggregator_entry_url_meta_box($object, $box)
	{
?>

		<?php wp_nonce_field(basename(__FILE__), 'aggregator_entry_url_nonce'); ?>

		<p>
			<label for="aggregator-entry-url"><?php _e('Add the URL for your Entry', 'example'); ?></label>
			<br />
			<input class="widefat" type="text" name="aggregator-entry-url" id="aggregator-entry-url" value="<?php echo esc_attr(get_post_meta($object->ID, 'aggregator_entry_url', true)); ?>" size="30" />
		</p>
	<?php
	}

	public function aggregator_entry_karma_meta_box($object, $box)
	{
	?>
		<p>
			<label for="aggregator-entry-karma">
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
	public function aggregator_save_entry_url($post_id, $post)
	{

		/* Verify the nonce before proceeding. */
		if (!isset($_POST['aggregator_entry_url_nonce']) || !wp_verify_nonce($_POST['aggregator_entry_url_nonce'], basename(__FILE__))) {
			return $post_id;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object($post->post_type);

		/* Check if the current user has permission to edit the post. */
		if (!current_user_can($post_type->cap->edit_post, $post_id)) {
			return $post_id;
		}

		/* Get the posted data and sanitize it for use as an HTML class. */
		$new_meta_value = (isset($_POST['aggregator-entry-url']) ? esc_url_raw($_POST['aggregator-entry-url']) : '');

		/* Get the meta key. */
		$meta_key = 'aggregator_entry_url';

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
