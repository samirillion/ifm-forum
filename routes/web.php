<?php

/**
 * Render Your Templates Here
 */
require_once(IFM_INC . 'router/web/class-web.php');

// Create a Handle for your route, then 
IfmWeb::render('/forum', 'IfmPostsController@main');

IfmWeb::render('/my-messages', 'IfmMessagingController@main');

// // pass the routes to the router
IfmWeb::register();
