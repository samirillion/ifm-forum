<?php

/**
 * A Class for Rendering User Forms
 */

namespace IFM;

class Controller_Form
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
				$errors[] = Model_Notification::hydrate($code);
			}
		}
		// Return errors for Registration Page
		if (isset($_REQUEST['register-errors'])) {
			$error_codes = explode(',', $_REQUEST['register-errors']);

			foreach ($error_codes as $error_code) {
				$errors[] = Model_Notification::hydrate($error_code);
			}
		}

		if (isset($_REQUEST['errors'])) {
			$error_codes = explode(',', $_REQUEST['errors']);
			foreach ($error_codes as $error_code) {
				$errors[] = Model_Notification::hydrate($error_code);
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
}
