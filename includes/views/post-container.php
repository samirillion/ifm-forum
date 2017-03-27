<?php

    class crowdsorterContainer
    {
        public static function render($pageposts)
        {
            //  var_dump( $the_query );
      wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
            wp_register_script("news-aggregator", WP_PLUGIN_URL.'/crowd-sorter/includes/views/js/news-aggregator.js', array('jquery'));
            wp_localize_script('news-aggregator', 'myAjax', array(
              'ajaxurl' => admin_url('admin-ajax.php'),
              'noposts' => esc_html__('No older posts found', 'aggregator')
            ));
            wp_enqueue_script('jquery');
            wp_enqueue_script('news-aggregator');
            ?>
            <div id="aggregator-container" class="clearfix aggregator-main ajax_posts" role="main">

            <?php

              require('templates/post-template.php');
              $html = postTemplate::render($pageposts);

              echo $html;
              ?>
            </div>
            <div id="more_aggregator_posts"><?php esc_html_e('Load More Posts', 'aggregator') ?></div>
        <?php
      }
    }
