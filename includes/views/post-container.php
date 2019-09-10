<?php

    class crowdsorterContainer
    {
        public static function render($pageposts)
        {
            wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/assets/css/crowdsorter.css', null);
            wp_register_script("news-aggregator", plugin_dir_url(__FILE__).'/assets/js/news-aggregator.js', array('jquery'));
            wp_register_script("toggle-switch", plugin_dir_url(__FILE__).'/assets/js/toggle-switch.js', array('jquery'));
            wp_localize_script('news-aggregator', 'myAjax', array(
              'ajaxurl' => admin_url('admin-ajax.php'),
              'noposts' => esc_html__('No older posts found', 'aggregator'),
              'aggpost_tax' => get_query_var('aggpost_tax')
            ));
            wp_enqueue_script("jquery");
            wp_enqueue_script('toggle-switch');
            wp_enqueue_script("news-aggregator"); ?>
            <div id="agg-container" class="clearfix aggregator-main ajax_posts" role="main">

            <?php
              require('templates/post-template.php');
              require('templates/forum-nav.php');
              if (is_array($pageposts) && $pageposts !== []) {
              $html = postTemplate::render($pageposts);
             } else { ?>
              <div class='agg-item-no-content'><div class='agg-post-title'><span clas='title'>No posts here! You should submit one!</span></div></div>
            <?php  }
            echo $html; ?>
            </div>
            <?php if (is_array($pageposts) && $pageposts !== []) { ?>
            <div id="more_aggregator_posts"><?php esc_html_e('Load More Posts', 'aggregator') ?></div>
        <?php
            } 
        }
    }
