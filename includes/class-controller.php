<?php

class crowdsortController
{
    public static function register()
    {
        $plugin = new self();

        add_action('init', array( $plugin, 'generate_sorter' ));
        add_shortcode('crowdsortcontainer', array( $plugin, 'create_container' ));
        add_action('wp_ajax_add_entry_karma', array( $plugin, 'my_user_vote' ));
        // add_action('wp_ajax_nopriv_add_entry_karma', array( $plugin, 'redirect_to_login'));
        add_shortcode('custom-comments', array( $plugin, 'render_comments_page'));
        add_filter('query_vars', array( $plugin, 'add_query_vars'));

        add_shortcode('custom-login-form', array( $plugin, 'custom_login_form' ));
        add_shortcode( 'custom-register-form', array( $plugin, 'render_register_form' ) );
        add_action('admin_post_nopriv_registration_form', array( $plugin, 'register_account'));
        add_action('login_form_login', array( $plugin, 'redirect_to_custom_login' ));
        add_filter('authenticate', array( $plugin, 'maybe_redirect_at_authenticate' ), 101, 3);
        add_action( 'wp_logout', array( $plugin, 'redirect_after_logout' ) );
        add_filter( 'login_redirect', array( $plugin, 'redirect_after_login' ), 10, 3 );
        add_action( 'login_form_register', array( $plugin, 'redirect_to_custom_register' ) );
        add_action( 'login_form_register', array( $plugin, 'do_register_user' ) );
        add_action( 'login_form_lostpassword', array( $plugin, 'redirect_to_custom_lostpassword' ) );
        add_shortcode( 'custom-password-lost-form', array( $plugin, 'render_password_lost_form' ) );
        add_action( 'login_form_lostpassword', array( $plugin, 'do_password_lost' ) );
    }
    public function __construct()
    {
    }

    /**
 * Initiates password reset.
 */
public function do_password_lost() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $errors = retrieve_password();
        if ( is_wp_error( $errors ) ) {
            // Errors found
            $redirect_url = home_url( 'member-password-lost' );
            $redirect_url = add_query_arg( 'errors', join( ',', $errors->get_error_codes() ), $redirect_url );
        } else {
            // Email sent
            $redirect_url = home_url( 'member-login' );
            $redirect_url = add_query_arg( 'checkemail', 'confirm', $redirect_url );
        }

        wp_redirect( $redirect_url );
        exit;
    }
}

    /**
 * A shortcode for rendering the form used to initiate the password reset.
 *
 * @param  array   $attributes  Shortcode attributes.
 * @param  string  $content     The text content for shortcode. Not used.
 *
 * @return string  The shortcode output
 */
public function render_password_lost_form( $attributes, $content = null ) {
    // Parse shortcode attributes
    $default_attributes = array( 'show_title' => false );
    $attributes = shortcode_atts( $default_attributes, $attributes );


    if ( is_user_logged_in() ) {
        return __( 'You are already signed in.', 'personalize-login' );
    } else {
      // Retrieve possible errors from request parameters
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= $this->get_error_message( $error_code );
            }
        }
      require_once('views/crowdsorter-user-forms.php');
      $crowdsorterRegister = new crowdsorterUserForm;
      $content = $crowdsorterRegister->render_form('password-lost-form', $attributes);
      return $content;
    }
}

    /**
 * Redirects the user to the custom "Forgot your password?" page instead of
 * wp-login.php?action=lostpassword.
 */
public function redirect_to_custom_lostpassword() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user();
            exit;
        }

        wp_redirect( home_url( 'member-password-lost' ) );
        exit;
    }
}

//plugin activation hook registered in bootstrap.php
    public static function plugin_activated()
    {
        require_once('models/page-definitions.php');
        $pageDefinitions = new crowdsorterPageDefinitions;
        $pageDefinitions->define_pages();
    }

    public function add_query_vars( $vars ){
      $vars[] .= 'agg_post_id';
      return $vars;
    }

    public function create_container()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $sorter = $sorterFactory->get_sorter("News-Aggregator");
        $the_query = $sorter->sort_posts();

        require_once('views/crowdsorter-container.php');
        $content = crowdsorterContainer::render($the_query);
        return $content;
    }

    public function generate_sorter()
    {
        require_once('models/sorter-factory.php');
        $sorterFactory = new sorterFactory;
        $aggregator = $sorterFactory->get_sorter("News-Aggregator");

        $aggregator->define_post_type();
        add_action('load-post.php', array($aggregator, 'define_post_meta'));
        add_action('load-post-new.php', array($aggregator, 'define_post_meta'));
    }



    public function render_comments_page(){
      require_once('models/news-aggregator-comments.php');
      $newsAggComments = new newsAggregatorComments;
      $commentQuery = $newsAggComments->sort_comments();

      require_once('views/comment-container.php');
      $content = commentContainer::render($commentQuery);
      return $content;
    }

    public function my_user_vote()
    {
        require_once('models/post-karma-tracker.php');
        $karmaTracker = new postKarmaTracker;
        $karmaTracker->update_karma();
    }

    public function custom_login_form($attributes, $content = null)
    {
        // Parse shortcode attributes
    $default_attributes = array( 'show_title' => false );
        $attributes = shortcode_atts($default_attributes, $attributes);
        $show_title = $attributes['show_title'];

        if (is_user_logged_in()) {
            return __('You are already signed in.', 'personalize-login');
        }

        $attributes['redirect'] = '';
        if (isset($_REQUEST['redirect_to'])) {
            $attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
        }

        $attributes['registered'] = isset( $_REQUEST['registered'] );

        $attributes['logged_out'] = isset( $_REQUEST['logged_out'] ) && $_REQUEST['logged_out'] == true;

        require_once('views/crowdsorter-user-forms.php');
        $crowdsorterLogin = new crowdsorterUserForm;
        $content = $crowdsorterLogin->render_form('login-form', $attributes);
        return $content;
    }

      /**
   * Redirect the user to the custom login page instead of wp-login.php.
   */
   public function redirect_to_custom_login()
   {
       if ($_SERVER['REQUEST_METHOD'] == 'GET') {
           $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : null;

           if (is_user_logged_in()) {
               $this->redirect_logged_in_user($redirect_to);
               exit;
           }

          // The rest are redirected to the login page
          $login_url = home_url('member-login');
           if (! empty($redirect_to)) {
               $login_url = add_query_arg('redirect_to', $redirect_to, $login_url);
           }

           wp_redirect($login_url);
           exit;
       }
   }

    private function redirect_logged_in_user($redirect_to = null)
    {
        $user = wp_get_current_user();
        if (user_can($user, 'manage_options')) {
            if ($redirect_to) {
                wp_safe_redirect($redirect_to);
            } else {
                wp_redirect(admin_url());
            }
        } else {
            wp_redirect(home_url('member-account'));
        }
    }

    public function maybe_redirect_at_authenticate($user, $username, $password)
    {
        // Check if the earlier authenticate filter (most likely,
    // the default WordPress authentication) functions have found errors
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (is_wp_error($user)) {
            $error_codes = join(',', $user->get_error_codes());

            $login_url = home_url('member-login');
            $login_url = add_query_arg('login', $error_codes, $login_url);

            wp_redirect($login_url);
            exit;
        }
    }

        return $user;
    }

    public function redirect_to_login() {
      wp_safe_redirect(home_url('member-login'));
      exit;
    }

    public function redirect_after_logout() {
    $redirect_url = home_url( 'member-login?logged_out=true' );
    wp_safe_redirect( $redirect_url );
    exit;
}

public function redirect_after_login() {
  $redirect_url = home_url();

   if ( ! isset( $user->ID ) ) {
       return $redirect_url;
   }

   if ( user_can( $user, 'manage_options' ) ) {
       // Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
       if ( $requested_redirect_to == '' ) {
           $redirect_url = admin_url();
       } else {
           $redirect_url = $requested_redirect_to;
       }
   } else {
       // Non-admin users always go to their account page after login
       $redirect_url = home_url( 'member-account' );
   }

   return wp_validate_redirect( $redirect_url, home_url() );
}

/**
 * A shortcode for rendering the new user registration form.
 *
 * @param  array   $attributes  Shortcode attributes.
 * @param  string  $content     The text content for shortcode. Not used.
 *
 * @return string  The shortcode output
 */
public function render_register_form( $attributes, $content = null ) {
    // Parse shortcode attributes
    $default_attributes = array( 'show_title' => false );
    $attributes = shortcode_atts( $default_attributes, $attributes );

    if ( is_user_logged_in() ) {
        return __( '', 'personalize-login' );
    } elseif ( ! get_option( 'users_can_register' ) ) {
        return __( 'Registering new users is currently not allowed.', 'personalize-login' );
    } else {
      require_once('views/crowdsorter-user-forms.php');
      $crowdsorterRegister = new crowdsorterUserForm;
      $content = $crowdsorterRegister->render_form('register-form', $attributes);
      return $content;
    }
}

/**
 * Redirects the user to the custom registration page instead
 * of wp-login.php?action=register.
 */
public function redirect_to_custom_register() {
    if ( 'GET' == $_SERVER['REQUEST_METHOD'] ) {
        if ( is_user_logged_in() ) {
            $this->redirect_logged_in_user();
        } else {
            wp_redirect( home_url( 'member-login' ) );
        }
        exit;
    }
}

/**
 * Validates and then completes the new user signup process if all went well.
 *
 * @param string $email         The new user's email address
 * @param string $first_name    The new user's first name
 * @param string $last_name     The new user's last name
 *
 * @return int|WP_Error         The id of the user that was created, or error if failed.
 */
private function register_user( $email, $first_name, $last_name ) {
    $errors = new WP_Error();
    require_once('views/crowdsorter-user-forms.php');
    $crowdsorter = new crowdsorterUserForm;
    // Email address is used as both username and email. It is also the only
    // parameter we need to validate
    if ( ! is_email( $email ) ) {
        $errors->add( 'email', $crowdsorter->get_error_message( 'email' ) );
        return $errors;
    }

    if ( username_exists( $email ) || email_exists( $email ) ) {
        $errors->add( 'email_exists', $crowdsorter->get_error_message( 'email_exists') );
        return $errors;
    }

    // Generate the password so that the subscriber will have to check email...
    $password = wp_generate_password( 12, false );

    $user_data = array(
        'user_login'    => $email,
        'user_email'    => $email,
        'user_pass'     => $password,
        'first_name'    => $first_name,
        'last_name'     => $last_name,
        'nickname'      => $first_name,
    );

    $user_id = wp_insert_user( $user_data );
    wp_new_user_notification( $user_id, $password );

    return $user_id;
}

/**
 * Handles the registration of a new user.
 *
 * Used through the action hook "login_form_register" activated on wp-login.php
 * when accessed through the registration action.
 */
public function do_register_user() {
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] ) {
        $redirect_url = home_url( 'member-login' );

        if ( ! get_option( 'users_can_register' ) ) {
            // Registration closed, display error
            $redirect_url = add_query_arg( 'register-errors', 'closed', $redirect_url );
        } else {
            $email = $_POST['email'];
            $first_name = sanitize_text_field( $_POST['first_name'] );
            $last_name = sanitize_text_field( $_POST['last_name'] );

            $result = $this->register_user( $email, $first_name, $last_name );

            if ( is_wp_error( $result ) ) {
                // Parse errors into a string and append as parameter to redirect
                $errors = join( ',', $result->get_error_codes() );
                $redirect_url = add_query_arg( 'register-errors', $errors, $redirect_url );
            } else {
                // Success, redirect to login page.
                $redirect_url = home_url( 'member-login' );
                $redirect_url = add_query_arg( 'registered', $email, $redirect_url );
            }
        }

        wp_redirect( $redirect_url );
        exit;
    }
}

}

crowdsortController::register();
