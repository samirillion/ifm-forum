<?php

/**
 * Post Edit Render Class
 */

namespace IFM;

class EditPost
{

	public static function render()
	{
		ob_start();
		require_once('partials/edit-post.php');
		$output = ob_get_clean();

		echo $output;
	}
}
