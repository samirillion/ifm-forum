<?php

    class crowdsorterUserForm
    {
        public function render_form($template_name, $attributes = null)
        {
            // Parse shortcode attributes
     if (! $attributes) {
         $attributes = array();
     }
     // Check if the user just requested a new password
$attributes['lost_password_sent'] = isset( $_REQUEST['checkemail'] ) && $_REQUEST['checkemail'] == 'confirm';
     // Error messages
        $errors = array();
            if (isset($_REQUEST['login'])) {
                $error_codes = explode(',', $_REQUEST['login']);

                foreach ($error_codes as $code) {
                    $errors []= $this->get_error_message($code);
                }
            }
            $attributes['errors'] = $errors;

            ob_start();

            do_action('personalize_login_before_' . $template_name);

            require('templates/' . $template_name . '.php');

            do_action('personalize_login_after_' . $template_name);

            $html = ob_get_contents();
            ob_end_clean();

            return $html;
        }

        /**
     * Finds and returns a matching error message for the given error code.
     *
     * @param string $error_code    The error code to look up.
     *
     * @return string               An error message.
     */
    public function get_error_message($error_code)
    {
        switch ($error_code) {

                      // Lost password

            case 'empty_username':
              return __( 'You need to enter your email address to continue.', 'personalize-login' );

            case 'invalid_email':
            case 'invalidcombo':
              return __( 'There are no users registered with this email address.', 'personalize-login' );

            case 'empty_username':
                return __('You do have an email address, right?', 'personalize-login');

            case 'empty_password':
                return __('You need to enter a password to login.', 'personalize-login');

            case 'invalid_email':
                return __(
                    "We don't have any users with that email address. Maybe you used a different one when signing up?",
                    'personalize-login'
                );
            case 'email':
            return __('The email address you entered is not valid.', 'personalize-login');

            case 'email_exists':
                return __('An account exists with this email address.', 'personalize-login');

            case 'closed':
                return __('Registering new users is currently not allowed.', 'personalize-login');

            case 'incorrect_password':
                $err = __(
                    "The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
                    'personalize-login'
                );
                return sprintf($err, wp_lostpassword_url());

            default:
                break;
        }

        return __('An unknown error occurred. Please try again later.', 'personalize-login');
    }
    }
