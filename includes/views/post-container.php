            <?php

    class crowdsorterContainer
    {
     public static function render( $the_query ) {
       wp_enqueue_style( 'crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null );
       wp_register_script( "news-aggregator", WP_PLUGIN_URL.'/crowd-sorter/includes/views/js/news-aggregator.js', array('jquery') );
       wp_localize_script( 'news-aggregator', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
       wp_enqueue_script( 'jquery' );
       wp_enqueue_script( 'news-aggregator' );
       echo '<div id="aggregator-container" class="clearfix">';
       global $wpdb;
       while ( $the_query->have_posts() ) {
              $the_query->the_post();
              $post_ID = get_the_ID();
              $postmeta = get_post_meta($post_ID );
              $posturl = $postmeta["aggregator_entry_url"]["0"];
              // $votes = $postmeta["aggregator_entry_karma"]["0"];
              $nonce = wp_create_nonce("aggregator_karma_nonce");
              $commentslink = add_query_arg( 'agg_post_id', $post_ID, home_url('comments'));
              $link = admin_url('admin-ajax.php?action=add_entry_karma&post_id='.$post_ID.'&nonce='.$nonce);
              $upvotes = $wpdb->get_var( $wpdb->prepare(
                "
                  SELECT count(*)
                  FROM $wpdb->postmeta
                  WHERE post_id=%d
                  AND meta_key='user_upvote_id'
                ",
                $post_ID
              ) );
              if( is_user_logged_in() ) {

              $upvoted = $wpdb->get_var( $wpdb->prepare(
                "
                  SELECT count(*)
                  FROM $wpdb->postmeta
                  WHERE post_id=%d
                  AND meta_key='user_upvote_id'
                  AND meta_value=%d
                ",
                $post_ID,
                get_current_user_id()
              ) );
            } else {
              $upvoted = false;
            }
              ?>
              <div class=aggregator-entry>
                <div class=entry-wrapper>
                  <a class=aggregator-entry-link href="<?php echo $posturl ?>" target="new"><?php echo get_the_title() ?></a>
                  <br>
                  <div class=host-url>(<?php echo preg_replace("#^www\.#", "", parse_url($posturl)["host"]) ?>)</div>
                  <div class="original-poster">by <?php echo get_the_author() ?></div>
                  <div class="post-time"><?php echo human_time_diff( get_the_time('U'), current_time('timestamp') ) . ' ago'; ?></div>
                    <div class=aggregator-karma><?php if ($upvotes == 1) { echo $upvotes . " point"; } else { echo $upvotes . " points"; }?></div>
                  <div class="upvote_entry" data-nonce="<?php echo $nonce ?>" data-post_id="<?php echo get_the_ID() ?>" href="<?php echo $link ?>"><?php if ($upvoted) { echo 'unvote';} else { echo '++';}?></div>
                  <a class="comments-link" href="<?php echo $commentslink ?>">comments</a>
                  </div>
              </div><?php //aggregator entry div
              }
              /* Restore original Post Data */
            echo  '</div>';

              wp_reset_postdata();
            }
    }
?>
