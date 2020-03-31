<?php
global $wp;
$page = array_key_exists('ifm_p', $params) ? $params['ifm_p'] : 1;
$current_url = home_url(add_query_arg(array(), $wp->request));
$with_params = add_query_arg($params, $current_url);
$next_page   = add_query_arg('ifm_p', $page + 1, $with_params);
$page_posts = $data;
?>
<?php

require_once(IFM_VIEW . 'partials/forum-nav.php');

if (is_array($page_posts) && [] !== $page_posts) {
    ob_start();
    require_once(IFM_VIEW . 'posts/posts.php');
    echo ob_get_clean();
?>
    <div class="ifm-load-more">
        <a href="<?php echo $next_page; ?>">
            <?php esc_html_e('Load More Posts', 'aggregator'); ?>
        </a>
    </div>
<?php
} else {
?>
    <div class='ifm-item-no-content'>
        <div class='ifm-post-title'>No posts here! You should submit one!</div>
    </div>
<?php
}
?>