<?php

namespace IFM;

/* Define Custom Query Vars. https://codex.wordpress.org/WordPress_Router_Qvars */

/**
 * Define Base Routes
 */
define('IFM_ROUTE_FORUM', '/' . IFM_NAMESPACE . '/forum');
define('IFM_ROUTE_COMMENTS', '/' . IFM_NAMESPACE . '/comments');
define('IFM_ROUTE_INBOX', '/' . IFM_NAMESPACE . '/inbox');
define('IFM_ROUTE_ACCOUNT', '/' . IFM_NAMESPACE . '/account');
define('IFM_ROUTE_CREATE', '/' . IFM_NAMESPACE . '/create');



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

// Forum Routes
Route::render(IFM_ROUTE_FORUM, 'Controller_Forum@main');

Route::render(IFM_ROUTE_FORUM . "/submit", 'Controller_Forum@submit');

// Comment Routes
Route::render(IFM_ROUTE_COMMENTS, 'Controller_Comment@main');
Route::json('/post-comment', 'Controller_Comment@comment_on_post', 'post');

// Messaging Routes
Route::render(IFM_ROUTE_INBOX, 'Controller_PM@main');

// Account Management Routes
// Main
Route::render(IFM_ROUTE_ACCOUNT, 'Controller_Account@main');
// Create New Account
Route::render(IFM_ROUTE_ACCOUNT . "/create", 'Controller_Account@create');
// Login Form
Route::render(IFM_NAMESPACE . "/login", 'Controller_Account@login_form');
// View Profile/Details
Route::render(IFM_ROUTE_ACCOUNT . "/details", 'Controller_Account@render_user_profile');
// View Profile/Details
Route::render(IFM_NAMESPACE . "/password-reset", 'Controller_Account@render_password_lost_form');


// Register Routes
Route::register();
