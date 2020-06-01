<?php

namespace IFM;

$page_posts = $query->get_posts();

global $wp;
$current_url = home_url(add_query_arg(array(), $wp->request));
$with_params = add_query_arg($params, $current_url);

include_once(IFM_VIEW . 'layout/nav.php');

if (is_array($page_posts) && [] !== $page_posts) {

    include(IFM_VIEW . 'pm/messages.php');
    pagination(false, false, $query);
} else {
?>
    <div class='ifm-item-no-content'>
        <div class='ifm-post-title'><?php _e('No messages here! You should submit one!', IFM_NAMESPACE) ?></div>
    </div>
<?php
}
?>