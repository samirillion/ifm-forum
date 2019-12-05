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

// Pages To Render From Controller Methods
IfmRoute::render('/forum', 'IfmPostsController@main');

// Register all the components of the IfmRoute object
IfmRoute::register();
