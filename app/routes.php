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

// Posts Related Routes
IfmRoute::render('/forum', 'IfmPostsController@main');
IfmRoute::render('/submit', 'IfmPostsController@submit');

// Comment Related Routes
IfmRoute::render('/comments', 'IfmCommentController@main');

// Register all the components of the IfmRoute object
IfmRoute::register();
