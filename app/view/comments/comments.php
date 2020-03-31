<?php
$comment_query = $data;
if (isset(get_post_meta($params['ifm_post_id'])['aggregator_entry_url']['0'])) {
    $post_title_content = '<a href="' . get_post_meta($params['ifm_post_id'])['aggregator_entry_url']['0'] . '" target="_blank">' . get_the_title($params['ifm_post_id']) . '</a>';
    $post_url           = '<a class="ifm-comment-main-url" href="' . get_post_meta($params['ifm_post_id'])['aggregator_entry_url']['0'] . '">' . get_post_meta($params['ifm_post_id'])['aggregator_entry_url']['0'] . '</a> &ndash; ';
} else {
    $post_title_content = get_the_title($params['ifm_post_id']);
    $post_url           = '';
}
echo '<div class="ifm-comment-wrapper">';
echo '<h4 class="ifm-comment-post-title">' . $post_title_content . '</h4>';
echo $post_url;
echo '<span class="ifm-post-type">' . (wp_get_object_terms($params['ifm_post_id'], 'aggpost-type'))[0]->{'name'} . '</span>';
if (get_post($params['ifm_post_id'])->post_content !== '') {
    echo '<div class="ifm-comment-main-content-wrapper">';
    echo '<div class="ifm-comment-post-content">' . get_post($params['ifm_post_id'])->post_content . '</div>';
    echo '</div>';
}
echo '<hr style="text-align:left;margin-left:0;margin-bottom:5px;width:60%;">';

include(IFM_VIEW . '/partials/comment-form.php');

if (!$comment_query) {
    echo '<span class="ifm-no-comments">No comments here! Start the discussion.</span>';
} elseif ($comment_query) {
    ob_start();
    $object = IFM\View_Comments::sort_by_parent($comment_query);
    IFM\View_Comments::build_comment_structure($object);
    echo ob_get_clean();
}
echo '</div>';
