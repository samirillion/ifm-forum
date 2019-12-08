<?php
require(IFM_INC . 'router/class-ifm-route.php');

/* Define Custom Query Vars Here. https://codex.wordpress.org/WordPress_Query_Vars */
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
IfmRoute::render('/my-new-page', 'IfmPostsController@newpage');

// Comment Related Routes
IfmRoute::render('/comments', 'IfmCommentController@main');

// JSON Api Routes
// IfmRoute::json_api('/commen', 'IfmCommentController@comment_on_post', 'post');
// api/ifm/comment-on-post
IfmRoute::json_api('/comment-on-post', 'IfmCommentController@comment_on_post', 'post');

// Register all the components of the IfmRoute object
IfmRoute::register();
