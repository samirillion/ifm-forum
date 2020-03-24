<?php

namespace IFM;

/* Define Custom Query Vars Here. https://codex.wordpress.org/WordPress_Router_Qvars */

Router_Route::add_query_vars(
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
Router_Route::render('/forum', 'Controller_Post@main');
Router_Route::render('/submit', 'Controller_Post@submit');

// Comment Related Routes
Router_Route::render('/comments', 'Controller_Comment@main');

// JSON Api Routes
// Router_Route::json_api('/commen', 'Controller_Comment@comment_on_post', 'post');
// api/ifm/comment-on-post
Router_Route::json_api('/comment-on-post', 'Controller_Comment@comment_on_post', 'post');

// Register all the components of the Route object
// Messaging Related Routes
Router_Route::render('/inbox', 'Controller_Messaging@inbox');


Router_Route::register();
