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
            } ?>
    <div class="aggregator-entry-wrapper clearfix">
      <?php switch (true) {
    case strlen($post->post_title) < 25:
        $classLength = 'short';
        break;
    case strlen($post->post_title) < 50:
        $classLength = 'medium';
        break;
    case strlen($post->post_title) < 75:
        $classLength = 'long';
        break;
    case strlen($post->post_title) < 100:
        $classLength = 'longer';
        break;
    case strlen($post->post_title) > 100:
        $classLength = 'longest';
        break;
} ?>
      <a class="aggregator-entry-link <?php echo $classLength ?>" href="<?php echo $posturl ?>" target="new"><?php echo $post->post_title ?></a><br>
      <div class="aggregator-item post-type"><?php echo (wp_get_object_terms( $post_ID, 'aggpost-type'))[0]->{'name'}; ?></div>
      <div class="host-url aggregator-item">(<?php echo preg_replace("#^www\.#", "", parse_url($posturl)["host"]) ?>)</div>
      <?php if($post->post_author != get_current_user_id()) {?>
        <div class="original-poster aggregator-item">by <a href="<?php echo add_query_arg('user_id', $post->post_author, home_url('user'));?>" target="_blank"><?php echo get_user_meta($post->post_author, 'nickname', true) ?></a></div>
      <?php } ?>
      <div class="post-time aggregator-item"><?php echo human_time_diff($post_Date_GMT, current_time('timestamp', 1)) . ' ago'; ?></div>
        <div class="aggregator-karma aggregator-item"><?php if ($upvotes == 1) {
                echo $upvotes . " point";
            } else {
                echo $upvotes . " points";
            } ?></div>
      <div data-nonce="<?php echo $nonce ?>" data-post_id="<?php echo $post_ID ?>" href="<?php echo $link ?>" class="upvote_entry aggregator-item
      <?php if ($upvoted) {
        //slightly ghetto solution where I finish the div with the php
                echo 'bottom-left" > unvote';
            } else {
                echo 'bottom-right" > ++';
            } ?></div>
      <a class="comments-link aggregator-item" href="<?php echo $commentslink ?>">comments (<?php echo wp_count_comments($post_ID)->total_comments; ?>)</a>
      <?php if($post->post_author == get_current_user_id()) {
        echo "<a href='".$editlink."' target='_blank' class='aggregator-item'>edit</a>";
      } ?>
    </div>
  <?php

        }
    }
}
