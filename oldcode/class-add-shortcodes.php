<?php

/**
 * The main plugin controller
 *
 * @package MVC Example
 * @subpackage Main Plugin Controller
 * @since 0.1
 */
class pondsShortcodes
{
    /**
     * the class constructor
     *
     * @package MVC Example
     * @subpackage Main Plugin Controller
     *
     * @since 0.1
     */
    public function __construct()
    {
      if( !is_admin() ):
         add_action( 'wp', array( $this, 'init' ) );
     endif;
 }

 /**
  * callback for the 'wp' action
  *
  * In this function, we determine what WordPress is doing and add plugin actions depending upon the results.
  * This helps to keep the plugin code as light as possible, and reduce processing times.
  *
  * @package MVC Example
  * @subpackage Main Plugin Controller
  *
  * @since 0.1
  */
 public function init()
 {
     //is this a post display page? If so, then filter the content\
     if( is_single() )
         add_filter( 'the_content', array(&$this, 'render_foo_single_post' ) );
 }

 public function render_foo_single_post( $content )
 {
     //require_once our model
     require_once( 'models/foo-model.php' );
     //instantiate the model
     $fooModel = new fooModel;

     //get the message
    //  $fooModel->set_message("Waaat");
     $message = $fooModel->get_message();

    // //  require_once our view
     require_once( 'views/single-post-html.php' );

     //render the view
     $content = fooSinglePostHtmlView::render( $message ) . $content;

     //return the result
     return $content;
 }

}

$pond = new pondsShortcodes();
