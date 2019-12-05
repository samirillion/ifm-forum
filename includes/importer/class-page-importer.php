<?php

/**
 * Import Pages now, everything else later.
 */
class IfmPageImporter
{
	/**
	 * Simple Import Function, creates pages
	 */
	public static function create_pages($pages)
	{
		foreach ($pages as $slug => $page) {
			// Check that the page doesn't exist already
			$query = new WP_Query('pagename=' . $slug);
			if (!$query->have_posts()) {
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
