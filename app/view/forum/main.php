<?php

namespace IFM;

$page_posts = $data;

global $wp;
$page = array_key_exists('ifm_p', $params) ? $params['ifm_p'] : 1;
$current_url = home_url(add_query_arg(array(), $wp->request));
$with_params = add_query_arg($params, $current_url);
$next_page   = add_query_arg('ifm_p', $page + 1, $with_params);

include_once(IFM_VIEW . 'layout/nav.php');

if (is_array($page_posts) && [] !== $page_posts) {
    include(IFM_VIEW . 'forum/posts.php');
?>
    <div class="pagination">
        <?php
        echo paginate_links(array(
            // 'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            // 'total'        => $query->max_num_pages,
            'current'      => max(1, get_query_var('paged')),
            'format'       => '?paged=%#%',
            'show_all'     => false,
            'type'         => 'plain',
            'end_size'     => 2,
            'mid_size'     => 1,
            'prev_next'    => true,
            'prev_text'    => sprintf('<i></i> %1$s', __('Newer Posts', 'text-domain')),
            'next_text'    => sprintf('%1$s <i></i>', __('Older Posts', 'text-domain')),
            'add_args'     => false,
            'add_fragment' => '',
        ));
        ?>
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