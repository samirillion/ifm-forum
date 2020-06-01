<?php

namespace IFM;

$page_posts = $data;

global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
$with_params = add_query_arg($params, $current_url);

include_once(IFM_VIEW . 'layout/nav.php');

if (is_array($page_posts) && [] !== $page_posts) {

    include(IFM_VIEW . 'forum/posts.php');
    include(IFM_VIEW . 'layout/pagination-links.php');
} else {
?>
    <div class='ifm-item-no-content'>
        <div class='ifm-post-title'>No posts here! You should submit one!</div>
    </div>
<?php
}
?>