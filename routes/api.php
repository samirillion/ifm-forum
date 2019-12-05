<?php

/**
 * Define Your Api Endpoints here. Mimicking laravel's syntax, define a method on a controller in app/controllers to be called on. 
 * Add optional middlewares, define add to the object with includes/router/api/class-api.php, then handle in includes/router/api/class-router.php
 */
require_once(IFM_INC . 'router/api/class-api.php');

// // build up routes, composed of path and a callback
// IfmApi::get('/get_posts', 'IfmPostsController@select')::permission_callback('at_least_member');
// IfmApi::get('/get_posts', 'IfmPostsController@select')::permission_callback('at_least_member');

IfmApi::register();
