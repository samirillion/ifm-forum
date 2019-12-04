<?php

/**
 * Render Your Templates Here
 */
require_once(IFM_INC . 'router/web/class-web.php');

// Create a Handle for your route, then 
IfmWeb::render('/post-container', 'post-container', 'post_container');

// // pass the routes to the router
IfmWeb::register();
