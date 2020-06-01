<?php

namespace IFM;

$page_posts = $data;
global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
$with_params = add_query_arg($params, $current_url);


if (is_array($page_posts) && [] !== $page_posts) {

    include(IFM_VIEW . 'pm/messages.php');
    include(IFM_VIEW . 'layout/pagination-links.php');

} else {
?>
    <div class='ifm-item-no-content'>
        <div class='ifm-post-title'>No messages here! You should create one!</div>
    </div>
<?php
}
?>