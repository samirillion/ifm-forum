<?php

/**
 * Render Your Templates Here
 */
require_once(IFM_INC . 'router/web/class-web.php');

// Create a Handle for your route, then 
IfmWeb::render('/post-container', 'IfmPostsController@render_container');

// // pass the routes to the router
IfmWeb::register();
