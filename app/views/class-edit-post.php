<?php

/**
 * Post Edit Render Class
 */
class IfmEditPost
{

	public static function render()
	{
		ob_start();
		require_once('partials/edit-post.php');
		$output = ob_get_clean();

		echo $output;
	}
}
