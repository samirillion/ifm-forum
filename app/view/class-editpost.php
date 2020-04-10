<?php

/**
 * Post Edit Render Class
 */

namespace IFM;

class View_EditPost
{

	public static function render()
	{
		ob_start();
		require_once('layout/edit-post.php');
		$output = ob_get_clean();

		echo $output;
	}
}
