<?php

    class crowdsorterContainer
    {
        public static function render($pageposts, $max_num_pages, $paged)
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
            echo '<div id="aggregator-container" class="clearfix aggregator-main ajax_posts" role="main">';
            global $wpdb;
            foreach ($pageposts as $post) {
                $post_ID = $post->ID;
                $post_Date_GMT = strtotime($post->post_date_gmt);
                $postmeta = get_post_meta($post_ID);
                $posturl = $postmeta["aggregator_entry_url"]["0"];
                $nonce = wp_create_nonce("aggregator_page_nonce");
                $commentslink = add_query_arg('agg_post_id', $post_ID, home_url('comments'));
                $link = admin_url('admin-ajax.php?action=add_entry_karma&post_id='.$post_ID.'&nonce='.$nonce);
                $addposts = admin_url('admin-ajax.php?action=add_posts&post_id='.$post_ID.'&nonce='.$nonce);
                $upvotes = $wpdb->get_var($wpdb->prepare(
                "
                  SELECT count(*)
                  FROM $wpdb->postmeta
                  WHERE post_id=%d
                  AND meta_key='user_upvote_id'
                ",
                $post_ID
              ));
                if (is_user_logged_in()) {
                    $upvoted = $wpdb->get_var($wpdb->prepare(
                "
                  SELECT count(*)
                  FROM $wpdb->postmeta
                  WHERE post_id=%d
                  AND meta_key='user_upvote_id'
                  AND meta_value=%d
                ",
                $post_ID,
                get_current_user_id()
              ));
                } else {
                    $upvoted = false;
                } ?>
              <div class=aggregator-entry>
                <div class=entry-wrapper>
                  <?php echo $post->karma_divisor ?>
                  <a class=aggregator-entry-link href="<?php echo $posturl ?>" target="new"><?php echo $post->post_title ?></a>
                  <br>
                  <div class=host-url>(<?php echo preg_replace("#^www\.#", "", parse_url($posturl)["host"]) ?>)</div>
                  <div class="original-poster">by <?php echo get_user_meta($post->post_author, 'nickname', true) ?></div>
                  <div class="post-time"><?php echo human_time_diff($post_Date_GMT, current_time('timestamp', 1)) . ' ago'; ?></div>
                    <div class=aggregator-karma><?php if ($upvotes == 1) {
                    echo $upvotes . " point";
                } else {
                    echo $upvotes . " points";
                } ?></div>
                  <div class="upvote_entry" data-nonce="<?php echo $nonce ?>" data-post_id="<?php echo $post_ID ?>" href="<?php echo $link ?>"><?php if ($upvoted) {
                    echo 'unvote';
                } else {
                    echo '++';
                } ?></div>
                  <a class="comments-link" href="<?php echo $commentslink ?>">comments</a>
                  </div>
              </div><?php
            }?>
            </div>
            <div id="more_posts" data-nonce="<?php echo $nonce ?>" data-category="<?php echo esc_attr($cat_id); ?>"><?php esc_html_e('Load More', 'aggregator') ?></div>
        <?php
      }
    }
