<?php

namespace IFM;

$page_posts = $query->get_posts();

global $wp;

include_once(IFM_VIEW . 'layout/nav.php');

if (is_array($page_posts) && [] !== $page_posts) {

    include(IFM_VIEW . 'forum/posts.php');
    pagination(false, false, $query);
} else {
?>
    <div class='ifm-item-no-content'>
        <div class='ifm-post-title'>No posts here! You should submit one!</div>
    </div>
<?php
}
?>