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
            $posturl = $postmeta["aggregator_entry_url"]["0"];
            $nonce = wp_create_nonce("aggregator_page_nonce");
            $commentslink = add_query_arg('agg_post_id', $post_ID, home_url('comments'));
            $link = admin_url('admin-ajax.php?action=add_entry_karma&post_id='.$post_ID.'&nonce='.$nonce);
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
    <div class=aggregator-entry-wrapper>
      <a class=aggregator-entry-link href="<?php echo $posturl ?>" target="new"><?php echo $post->post_title ?></a><br>
      <div class="host-url aggregator-item">(<?php echo preg_replace("#^www\.#", "", parse_url($posturl)["host"]) ?>)</div>
      <div class="original-poster aggregator-item">by <?php echo get_user_meta($post->post_author, 'nickname', true) ?></div>
      <div class="post-time aggregator-item"><?php echo human_time_diff($post_Date_GMT, current_time('timestamp', 1)) . ' ago'; ?></div>
        <div class="aggregator-karma aggregator-item"><?php if ($upvotes == 1) {
                echo $upvotes . " point";
            } else {
                echo $upvotes . " points";
            } ?></div>
      <div class="upvote_entry aggregator-item" data-nonce="<?php echo $nonce ?>" data-post_id="<?php echo $post_ID ?>" href="<?php echo $link ?>"><?php if ($upvoted) {
                echo 'unvote';
            } else {
                echo '++';
            } ?></div>
      <a class="comments-link aggregator-item" href="<?php echo $commentslink ?>">comments</a>
    </div>
  <?php

        }
    }
}
