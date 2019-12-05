<?php
require(IFM_INC . 'router/class-ifm-route.php');

IfmRoute::add_query_vars(
    array(
        'ifm_post_id',
        'status',
        'user_id',
        'ifm_tax',
        'ifm_p',
        'ifm_query'
    )
);

IfmRoute::render('/forum', 'IfmPostsController@main');

IfmRoute::register();
