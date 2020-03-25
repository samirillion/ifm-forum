<?php

namespace IFM;

class Seed
{
    public static function post_type($args) {
		require_once(IFM_BASE_PATH . 'seeds/post-type-agg.php');
		register_post_type(
			$ifm_custom_post_args[0],
			$ifm_custom_post_args[1]
		); /* end of register post type */
    }

    public static function taxonomy() {

    }


}
