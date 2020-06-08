<?php

/**
 * A Class for Rendering User Forms
 */

namespace IFM;

class View_Form
{

	public function render_form($template_path, $attributes = null)
	{
		// Parse shortcode attributes
		if (!$attributes) {
			$attributes = array();
		}
		// Check if the user just requested a new password
		$attributes['lost_password_sent'] = isset($_REQUEST['checkemail']) && $_REQUEST['checkemail'] == 'confirm';
		// Error messages
		$errors = array();
		// Return errors for Login Page
		if (isset($_REQUEST['login'])) {
			$error_codes = explode(',', $_REQUEST['login']);

			foreach ($error_codes as $code) {
				$errors[] = $this->get_error_message($code);
			}
		}
		// Return errors for Registration Page
		if (isset($_REQUEST['register-errors'])) {
			$error_codes = explode(',', $_REQUEST['register-errors']);

			foreach ($error_codes as $error_code) {
				$errors[] = $this->get_error_message($error_code);
			}
		}

		if (isset($_REQUEST['errors'])) {
			$error_codes = explode(',', $_REQUEST['errors']);
			foreach ($error_codes as $error_code) {
				$errors[] = $this->get_error_message($error_code);
			}
		};

		$attributes['errors'] = $errors;

		ob_start();

		do_action('personalize_login_before_' . $template_path);

		return view($template_path, null, $attributes);

		do_action('personalize_login_after_' . $template_path);

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
		xdebug_break();

		switch ($error_code) {

				// Lost password
			case 'empty_username':
				return __('You need to enter your username  to continue.', IFM_NAMESPACE);

			case 'invalidcombo':
				return __('There are no users registered with this email address.', IFM_NAMESPACE);

			case 'invalid_username':
				return __('There are no users registered with this username.', IFM_NAMESPACE);

			case 'empty_username':
				return __('You do have an email address, right?', IFM_NAMESPACE);

			case 'username_exists':
				return __('Your chosen username is not available', IFM_NAMESPACE);

			case 'empty_password':
				return __('You need to enter a password to login.', IFM_NAMESPACE);

			case 'invalid_email':
				return __(
					"We don't have any users with that email address. Maybe you used a different one when signing up?",
					IFM_NAMESPACE
				);
			case 'email':
				return __('The email address you entered is not valid.', IFM_NAMESPACE);

			case 'retrieve_password_email_failure':
				return __('There does not seem to be an email address associated with this account. Contact the admins for help.', IFM_NAMESPACE);

			case 'email_exists':
				return __('An account already exists with this email address.', IFM_NAMESPACE);

			case 'closed':
				return __('Registering new users is currently not allowed.', IFM_NAMESPACE);

			case 'incorrect_password':
				$err = __(
					"The password you entered wasn't quite right. <a href='%s'>Did you forget your password</a>?",
					IFM_NAMESPACE
				);
				return sprintf($err, wp_lostpassword_url());

			default:
				break;
		}

		return __('An unknown error occurred. Please try again later.', IFM_NAMESPACE);
	}
}
