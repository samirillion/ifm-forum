<?php

if( !class_exists( crowdsorterModel ) ):

    class crowdsorterModel
    {
        protected $posts;
        public function __construct($posts)
        {
          $this->posts = $posts;
        }

        public function get_pond_posts()
        {
          $args = array(
            'post_type' => 'ponds_posts'
          );
          $query = new WP_Query( $args );
          return $query;
        }
    }
endif;
?>
