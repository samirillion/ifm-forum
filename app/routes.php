<?php

namespace IFM;

/* Define Custom Query Vars. https://codex.wordpress.org/WordPress_Router_Qvars */

include(IFM_APP . '/query-vars.php');

// Forum Routes
Route::render(IFM_ROUTE_FORUM, "Controller_Forum@render_main");
Route::render(IFM_ROUTE_FORUM . "/submit", "Controller_Forum@render_submit");

// Comment Routes
Route::render(IFM_ROUTE_COMMENTS, "Controller_Comment@render_main");

Route::json("/post-comment", "Controller_Comment@comment_on_post", "POST", "can_create");

// Messaging Routes
Route::render(IFM_ROUTE_MAILBOX, "Controller_Mailbox@main");

// Account Management Routes
Route::render(IFM_ROUTE_ACCOUNT, "Controller_Account@render_main");
Route::render(IFM_ROUTE_ACCOUNT . "/send-verify-email", "Controller_Account@send_verify_email");
Route::render(IFM_ROUTE_ACCOUNT . "/email", "Controller_Account@verify_email");
Route::render(IFM_ROUTE_ACCOUNT . "/create", "Controller_Account@create");

// Account Registration Routes
Route::render(IFM_NAMESPACE . "/login", "Controller_Account@login_form");
Route::render(IFM_NAMESPACE . "/register", "Controller_Account@render_register_form");
Route::render(IFM_NAMESPACE . "/password-reset", "Controller_Account@render_password_lost_form");
Route::render(IFM_NAMESPACE . "/change-password", "Controller_Account@change_password");


// Register Routes
Route::register();
