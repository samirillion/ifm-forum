<?php

use IFM\Route as Route;


/* Define Custom Query Vars Here. https://codex.wordpress.org/WordPress_Query_Vars */

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

// Posts Related Routes
Route::render('/forum', 'PostsController@main');
Route::render('/submit', 'PostsController@submit');

// Comment Related Routes
Route::render('/comments', 'CommentController@main');

// JSON Api Routes
// Route::json_api('/commen', 'CommentController@comment_on_post', 'post');
// api/ifm/comment-on-post
Route::json_api('/comment-on-post', 'CommentController@comment_on_post', 'post');

// Register all the components of the Route object
// Messaging Related Routes
Route::render('/inbox', 'MessagingController@inbox');


Route::register();
