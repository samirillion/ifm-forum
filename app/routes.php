<?php

namespace IFM;

/* Define Custom Query Vars Here. https://codex.wordpress.org/WordPress_Router_Qvars */

Route::add_query_vars(
    array(
        'ifm_post_id',
        'status',
        'user_id',
        'ifm_tax',
        'ifm_p',
        'ifm_query',
        'ifm_inbox'
    )
);

// Routes for Views. Set in config.php, plan to add settings page
Route::render(IFM_ROUTE_POSTS, 'Controller_Post@forum');
Route::render(IFM_ROUTE_CREATE_POST, 'Controller_Post@submit');

// Comment Related Routes
Route::render(IFM_ROUTE_COMMENTS, 'Controller_Comment@main');

// Register all the components of the Route object
// Messaging Related Routes
Route::render(IFM_ROUTE_INBOX, 'Controller_Messaging@inbox');

// Messaging Related Routes
Route::render(IFM_ROUTE_MY_ACCOUNT, 'Controller_User@show_account_details');

// JSON Api Routes
// Route::json_api('/comment', 'Controller_Comment@comment_on_post', 'post');
// api/ifm/comment-on-post
Route::json_api('/post-comment', 'Controller_Comment@comment_on_post', 'post');


Route::register();
