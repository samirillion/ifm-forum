<?php
class postTemplate
{
  public static function render($pageposts)
  {
    global $wpdb;
    foreach ($pageposts as $post) {
      $post_ID = $post->ID;
      $post_Date_GMT = strtotime($post->post_date_gmt);
      $postmeta = get_post_meta($post_ID);
      $nonce = wp_create_nonce("aggregator_page_nonce");
      $commentslink = add_query_arg('agg_post_id', $post_ID, home_url('comments'));
      if (get_post($post_ID)->post_content != "") {
        $posturl = $commentslink;
        $target = "";
      } else {
        $posturl = $postmeta["aggregator_entry_url"]["0"];
        $target = "target='_blank'";
      }
      $link = admin_url('admin-ajax.php?action=add_entry_karma&post_id=' . $post_ID . '&nonce=' . $nonce);
      $editlink = add_query_arg('agg_post_id', $post_ID, home_url('edit'));
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
      }
      $user_is_op = $post->post_author == get_current_user_id() ? true : false;
      ?>
      <div class="agg-entry-wrapper clearfix">
        <div class="agg-item-voter">
          <div data-nonce="<?php echo $nonce ?>" data-post_id="<?php echo $post_ID ?>" href="<?php echo $link ?>" class="upvote_entry">
            <?php
                  if ($upvoted) {
                    echo '<div class="agg-vote upvoted"></div>';
                  } else {
                    echo '<div class="agg-vote"></div>';
                  } ?>
          </div>
          <div class="agg-karma"><?php if ($upvotes == 1) {
                                          echo $upvotes;
                                        } else {
                                          echo $upvotes;
                                        } ?>
          </div>
        </div>
        <div class="agg-item-content">
          <div class="agg-post-title">
            <span class="title">
              <span class="agg-post-type"><?php echo (wp_get_object_terms($post_ID, 'aggpost-type'))[0]->{'name'}; ?> &ndash; </span>
            </span>
            <a class="agg-entry-link" href="<?php echo $posturl ?>" <?php echo $target ?>><?php echo $post->post_title ?></a>
            <span class="host-url">(<?php echo preg_replace("#^www\.#", "", parse_url($posturl)["host"]) ?>)</span>
          </div>
          <div class="agg-post-meta">
            <span class="agg-time-since-post">
              <?php echo human_time_diff($post_Date_GMT, current_time('timestamp', 1)) . ' ago'; ?>
            </span>
            <span class="agg-op <?php if ($user_is_op) {
                                        echo 'agg-user-is-op';
                                      } ?>">by <a href="<?php echo add_query_arg('user_id', $post->post_author, home_url('user')); ?>"><?php echo get_userdata($post->post_author)->user_nicename; ?></a>
            </span>
            <a class="agg-comments-link" href="<?php echo $commentslink ?>">comments (<?php echo wp_count_comments($post_ID)->total_comments; ?>)</a>
            <?php if ($user_is_op) {
                    echo "<a href='" . $editlink . "'> - edit</a>";
                  } ?>
          </div>
        </div>
      </div>
<?php
    }
  }
}
