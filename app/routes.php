<?php
/**
 * This is where custom routes go! WordPress, of course, has a lot of routes (to posts, pages, etc.) configured out of the box.
 * For simple stuff "routing," just dropping shortcodes into pages works. But this can be quite unpredictable.
 * This Router is a wrapper around the WP Rest API.
 * But this is for defining all the HTTP stuff without using clunky hooks like admin_post etc.
 * Just a lot more scalable for a larger application.
 */
require_once( IFM_INC . 'router/class-route.php' );

// // build up routes, composed of path and a callback
IfmRoute::get( '/connect', 'IfmPostsController@create_main' )::auth( 'member' );
// IfmRoute::get( '/fing-post', 'IfmPostsController@createview' );
// IfmRoute::get( '/frip-post', 'IfmPostsController@createview' )::auth( 'admin' );

// // pass the routes object to the router
IfmRoute::register();
