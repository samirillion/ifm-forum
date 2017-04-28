<?php

class crowdsortUsersController
{
    public static function register()
    {
        $plugin = new self();

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
        add_filter( 'retrieve_password_message', array( $plugin, 'replace_retrieve_password_message' ), 10, 4 );
        add_shortcode('crowdsorter-account-details', array( $plugin, 'show_account_details' ));
    }
    public function __construct()
    {
    }

    /**
     * Returns the message body for the password reset mail.
     * Called through the retrieve_password_message filter.
     *
     * @param string  $message    Default mail message.
     * @param string  $key        The activation key.
     * @param string  $user_login The username for the user.
     * @param WP_User $user_data  WP_User object.
     *
     * @return string   The mail message to send.
     */
    public function replace_retrieve_password_message( $message, $key, $user_login, $user_data ) {
        // Create new message
        $msg  = __( 'Hello!', 'personalize-login' ) . "\r\n\r\n";
        $msg .= sprintf( __( 'You asked us to reset your password for your account using the email address %s.', 'personalize-login' ), $user_login ) . "\r\n\r\n";
        $msg .= __( "If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", 'personalize-login' ) . "\r\n\r\n";
        $msg .= __( 'To reset your password, visit the following address:', 'personalize-login' ) . "\r\n\r\n";
        $msg .= site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ) . "\r\n\r\n";
        $msg .= __( 'Thanks!', 'personalize-login' ) . "\r\n";

        return $msg;
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
      require_once('views/user-forms.php');
      $crowdsorterRegister = new crowdsorterUserForm;
        $attributes['errors'] = array();
        if ( isset( $_REQUEST['errors'] ) ) {
            $error_codes = explode( ',', $_REQUEST['errors'] );

            foreach ( $error_codes as $error_code ) {
                $attributes['errors'] []= $crowdsorterRegister->get_error_message( $error_code );
            }
        }
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

        require_once('views/user-forms.php');
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
      require_once('views/user-forms.php');
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
    require_once('views/user-forms.php');
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

public function show_account_details() {
  require_once('views/account-details-container.php');
  $userAccount = new accountDetailsContainer;
  $userAccount::render();
}

}

crowdsortUsersController::register();
