<?php
$comment_query = $data;
$ifm_post_id = $params['ifm_post_id'];

if (isset(get_post_meta($ifm_post_id)['ifm_entry_url']['0'])) {
    $post_title_content = '<a href="' . get_post_meta($ifm_post_id)['ifm_entry_url']['0'] . '" target="_blank">' . get_the_title($ifm_post_id) . '</a>';
    $post_url           = '<a class="ifm-comment-main-url" href="' . get_post_meta($ifm_post_id)['ifm_entry_url']['0'] . '">' . get_post_meta($ifm_post_id)['ifm_entry_url']['0'] . '</a> &ndash; ';
} else {
    $post_title_content = get_the_title($ifm_post_id);
    $post_url           = '';
}
?>
<div class="ifm-comment-wrapper">
    <h4 class="ifm-comment-post-title"><?php echo $post_title_content ?></h4>
    <?php
    echo $post_url
    ?>
    <span class="ifm-post-type"><?php echo (wp_get_object_terms($ifm_post_id, IFM_POST_TAXONOMY_NAME))[0]->{'name'}; ?></span>
    <?php
    if (get_post($ifm_post_id)->post_content !== '') {
    ?>
        <div class="ifm-comment-main-content-wrapper">
            <div class="ifm-comment-post-content"><?php echo get_post($ifm_post_id)->post_content ?></div>
        </div>
    <?php
    }
    ?>
    <hr style="text-align:left;margin-left:0;margin-bottom:5px;width:60%;">
    <?php
    include(IFM_VIEW . '/comments/comment-form.php');

    if (!$comment_query) {
        echo '<span class="ifm-no-comments">No comments here! Start the discussion.</span>';
    } elseif ($comment_query) {
        ob_start();
        $object = IFM\View_Comments::sort_by_parent($comment_query);
        IFM\View_Comments::build_comment_structure($object);
        echo ob_get_clean();
    }
    ?>
</div>