<?php

/**
 *
 * @package Ifm
 */

namespace IFM;


class Controller_Account
{

	public static function register()
	{
		$plugin = new self();

		$plugin->user = new Model_User(get_current_user_id());

		add_shortcode('custom-login-form', array($plugin, 'login_form'));
		add_shortcode('custom-register-form', array($plugin, 'render_register_form'));
		add_shortcode('custom-password-lost-form', array($plugin, 'render_password_lost_form'));
		add_shortcode('account-info', array($plugin, 'main'));

		add_action('admin_post_nopriv_registration_form', array($plugin, 'register_account'));
		add_action('login_form_login', array($plugin, 'redirect_to_custom_login'));
		add_filter('authenticate', array($plugin, 'maybe_redirect_at_authenticate'), 101, 3);
		// add_action('wp_logout', array( $plugin, 'redirect_after_logout' ));
		add_filter('login_redirect', array($plugin, 'redirect_after_login'), 10, 3);
		add_action('login_form_register', array($plugin, 'redirect_to_custom_register'));
		add_action('login_form_register', array($plugin, 'do_register_user'));
		add_action('login_form_lostpassword', array($plugin, 'redirect_to_custom_lostpassword'));
		add_action('login_form_lostpassword', array($plugin, 'do_password_lost'));
		add_filter('retrieve_password_message', array($plugin, 'replace_retrieve_password_message'), 10, 4);
		// add_action( 'wp_footer', array($plugin,'user_login_logout' ));
		add_action('admin_post_update_account_details', array($plugin, 'update_account_details'));
		add_action('admin_post_change_password', array($plugin, 'update_password'));

		// add_filter('wp_nav_menu_items', array($plugin, 'add_conditional_menu_items'), 10, 2);

		add_action('after_setup_theme', array($plugin, 'remove_admin_bar'), 10, 2);

		add_action('admin_menu', array($plugin, 'remove_dashboard'));
	}

	public function render_main()
	{
		// determine who the user is, and if user is logged in, pass that data to the account view
		if (get_query_var('user_id')) {
			$user_id = get_query_var('user_id');
			if ($user_id == get_current_user_id()) {
				$current_user = true;
			} else {
				$current_user = false;
			}
		} else {
			if (!is_user_logged_in()) {
				$this->redirect_to_login;
			}
			$user_id = get_current_user_id();
			$current_user = true;
		}
		return view('account/account-details', null, ['user' => new Model_User($user_id), 'user_id' => $user_id, 'current_user' => $current_user]);
	}

	/* Remove the "Dashboard" from the admin menu for non-admin users */
	public function remove_dashboard()
	{
		global $menu;
		$current_user = $this->user;

		if (!in_array('administrator', $current_user->roles)) {
			reset($menu);
			$page = key($menu);
			while ((__('Dashboard') != $menu[$page][0]) && next($menu)) {
				$page = key($menu);
			}
			if (__('Dashboard') == $menu[$page][0]) {
				unset($menu[$page]);
			}
			reset($menu);
			$page = key($menu);
			while (!$current_user->has_cap($menu[$page][1]) && next($menu)) {
				$page = key($menu);
			}
			if (
				preg_match('#wp-admin/?(index.php)?$#', $_SERVER['REQUEST_URI']) &&
				('index.php' != $menu[$page][2])
			) {
				wp_redirect(get_option('siteurl') . '/wp-admin/edit.php');
			}
		}
	}

	public function create($attributes = null, $content = null)
	{
		if (is_user_logged_in()) {
			return __('', IFM_NAMESPACE);
		} elseif (!get_option('users_can_register')) {
			return __('Registering new users is currently not allowed.', IFM_NAMESPACE);
		} else {
			$form = new View_Form;
			$content             = $form->render_form('account/register-form', $attributes);
			return $content;
		}
	}

	public function remove_admin_bar()
	{
		if (!current_user_can('administrator') && !is_admin()) {
			show_admin_bar(false);
		}
	}

	public function render_user_profile()
	{
		return view('profile');
	}

	public function change_password()
	{
		return view('account/change-pass');
	}

	public function update_password()
	{
		if (wp_check_password($_POST['old-password'], $this->user->user_pass)) {
			wp_set_password($_POST['new-password'], get_current_user_id());
			$redirect_url = home_url(IFM_NAMESPACE . '/login');
			$redirect_url = add_query_arg('status', 'success', $redirect_url);
		} else {
			$redirect_url = home_url(IFM_NAMESPACE . '/change-password');
			$redirect_url = add_query_arg('status', 'failed', $redirect_url);
		}
		wp_redirect($redirect_url);
	}

	public function user_login_logout()
	{
		echo "<div id='loginlogout' style='position:fixed;top:1em;right:1em;'>";
		if (is_user_logged_in()) {
			$userKarma = $this->user->get_karma(); ?><a href="<?php echo home_url(IFM_ROUTE_ACCOUNT); ?>"><?php echo $this->user->user_login; ?></a> (<?php echo $userKarma; ?>) | <a href="<?php echo wp_logout_url(); ?>">logout</a>
		<?php
		} else {
		?>
			<a href="<?php echo home_url(IFM_NAMESPACE . '/login'); ?>">login</a>
<?php
		}
		echo '</div>';
	}

	public function update_account_details()
	{
		if (!is_user_logged_in()) {
			wp_redirect(home_url());
			exit();
		}
		$user = new Model_User(get_current_user_id());

		if (array_key_exists('resend_verification_link', $_POST)) {
			$this->send_verification_email($user);
		}

		$user->update_user_information();

		wp_redirect(home_url(IFM_ROUTE_ACCOUNT));
	}

	public function send_verification_email(\WP_User $user)
	{
		$email_verification_key = wp_generate_password(20, false);
		update_user_meta($user->ID, 'email_verification_key', $email_verification_key);

		$message = sprintf(__('Username: %s'), $user->user_login) . "\r\n\r\n";
		$message .= __('To set your password, visit the following address:') . "\r\n\r\n";
		$message .= network_site_url(IFM_ROUTE_ACCOUNT . "/email/&mail_key=$email_verification_key&login=" . rawurlencode($user->user_login), 'login') . "\r\n\r\n";
		wp_mail($user->user_email, sprintf(__('[%s] Your username and password info'), IFM_NAMESPACE), $message);
	}

	public function verify_email()
	{
		xdebug_break();
		if (get_user_meta(get_current_user_id(), 'email_verification_key') == $_GET['mail_key']) {

			update_user_meta(get_current_user_id(), 'email_verified', true);
			wp_redirect(IFM_ROUTE_ACCOUNT);
		}
	}

	public function replace_retrieve_password_message($message, $key, $user_login, $user_data)
	{
		// Create new message
		$msg  = __('Hello!', IFM_NAMESPACE) . "\r\n\r\n";
		$msg .= sprintf(__('You asked us to reset your password for your account using the email address %s.', IFM_NAMESPACE), $user_login) . "\r\n\r\n";
		$msg .= __("If this was a mistake, or you didn't ask for a password reset, just ignore this email and nothing will happen.", IFM_NAMESPACE) . "\r\n\r\n";
		$msg .= __('To reset your password, visit the following address:', IFM_NAMESPACE) . "\r\n\r\n";
		$msg .= site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\r\n\r\n";
		$msg .= __('Thanks!', IFM_NAMESPACE) . "\r\n";

		return $msg;
	}
	/**
	 * Initiates password reset.
	 */
	public function do_password_lost()
	{
		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			$errors = retrieve_password();
			if (is_wp_error($errors)) {
				// Errors found
				$redirect_url = home_url(IFM_NAMESPACE . "/password-reset");
				$redirect_url = add_query_arg('errors', join(',', $errors->get_error_codes()), $redirect_url);
			} else {
				// Email sent
				$redirect_url = home_url(IFM_NAMESPACE . '/login');
				$redirect_url = add_query_arg('checkemail', 'confirm', $redirect_url);
			}

			wp_redirect($redirect_url);
			exit;
		}
	}

	/**
	 * A shortcode for rendering the form used to initiate the password reset.
	 *
	 * @param  array  $attributes  Shortcode attributes.
	 * @param  string $content     The text content for shortcode. Not used.
	 *
	 * @return string  The shortcode output
	 */
	public function render_password_lost_form($attributes = null, $content = null)
	{
		// Parse shortcode attributes
		$default_attributes = array('show_title' => false);
		$attributes         = shortcode_atts($default_attributes, $attributes);

		if (is_user_logged_in()) {
			return __('You are already signed in.', IFM_NAMESPACE);
		} else {
			// Retrieve possible errors from request parameters
			$form  = new View_Form;
			$attributes['errors'] = array();
			if (isset($_REQUEST['errors'])) {
				$error_codes = explode(',', $_REQUEST['errors']);

				foreach ($error_codes as $error_code) {
					$attributes['errors'][] = $form->get_error_message($error_code);
				}
			}
			$content = $form->render_form('account/password-lost-form', $attributes);
			return $content;
		}
	}

	/**
	 * Redirects the user to the custom "Forgot your password?" page instead of
	 * wp-login.php?action=lostpassword.
	 */
	public function redirect_to_custom_lostpassword()
	{
		if ('GET' == $_SERVER['REQUEST_METHOD']) {
			if (is_user_logged_in()) {
				$this->redirect_logged_in_user();
				exit;
			}

			wp_redirect(home_url(IFM_NAMESPACE . "/password-reset"));
			exit;
		}
	}

	public function login_form($attributes = null, $content = null)
	{
		// Parse shortcode attributes
		$default_attributes = array('show_title' => false);
		$attributes         = shortcode_atts($default_attributes, $attributes);
		$show_title         = $attributes['show_title'];

		if (is_user_logged_in()) {
			return __('You are already signed in.', IFM_NAMESPACE);
		}

		$attributes['redirect'] = '';
		if (isset($_REQUEST['redirect_to'])) {
			$attributes['redirect'] = wp_validate_redirect($_REQUEST['redirect_to'], $attributes['redirect']);
		}

		$attributes['registered'] = isset($_REQUEST['registered']);

		$attributes['logged_out'] = isset($_REQUEST['logged_out']) && $_REQUEST['logged_out'] == true;

		$form        = new View_Form;
		$content     = $form->render_form('account/login-form', $attributes);
		return $content;
	}

	/**
	 * Redirect the user to the custom login page instead of wp-login.php.
	 */
	public function redirect_to_custom_login()
	{
		if ('GET' == $_SERVER['REQUEST_METHOD']) {
			$redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : null;

			if (is_user_logged_in()) {
				$this->redirect_logged_in_user($redirect_to);
				exit;
			}

			// The rest are redirected to the login page
			$login_url = home_url(IFM_NAMESPACE . '/login');
			if (!empty($redirect_to)) {
				$login_url = add_query_arg('redirect_to', $redirect_to, $login_url);
			}

			wp_redirect($login_url);
			exit;
		}
	}

	private function redirect_logged_in_user($redirect_to = null)
	{
		$user = $this->user;
		if (user_can($user, 'manage_options')) {
			if ($redirect_to) {
				wp_safe_redirect($redirect_to);
			} else {
				wp_redirect(admin_url());
			}
		} else {
			wp_redirect(home_url(IFM_ROUTE_ACCOUNT));
		}
	}

	public function maybe_redirect_at_authenticate($user, $username, $password)
	{
		// Check if the earlier authenticate filter (most likely,
		// the default WordPress authentication) functions have found errors
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if (is_wp_error($user)) {
				$error_codes = join(',', $user->get_error_codes());

				$login_url = home_url(IFM_NAMESPACE . '/login');
				$login_url = add_query_arg('login', $error_codes, $login_url);

				wp_redirect($login_url);
				exit;
			}
		}

		return $user;
	}

	public function redirect_to_login()
	{
		wp_safe_redirect(home_url(IFM_NAMESPACE . '/login'));
		exit;
	}

	public function redirect_after_logout()
	{
		$redirect_url = home_url(IFM_NAMESPACE . '/login?logged_out=true');
		wp_safe_redirect($redirect_url);
		exit;
	}

	public function redirect_after_login()
	{
		$redirect_url = home_url() . IFM_ROUTE_FORUM;
		$user = $this->user;

		if (!isset($user->ID)) {
			return $redirect_url;
		}

		if (user_can($user, 'manage_options')) {
			// Use the redirect_to parameter if one is set, otherwise redirect to admin dashboard.
			// if ($requested_redirect_to == '') {
			$redirect_url = admin_url();
			// } else {
			// $redirect_url = $requested_redirect_to;
			// }
		} else {
			// Non-admin users always go to their account page after login
			$redirect_url = home_url(IFM_ROUTE_ACCOUNT);
		}

		return wp_validate_redirect($redirect_url, home_url());
	}

	/**
	 * Redirects the user to the custom registration page instead
	 * of wp-login.php?action=register.
	 */
	public function redirect_to_custom_register()
	{
		if ('GET' == $_SERVER['REQUEST_METHOD']) {
			if (is_user_logged_in()) {
				$this->redirect_logged_in_user();
			} else {
				wp_redirect(home_url(IFM_NAMESPACE . '/login'));
			}
			exit;
		}
	}

	private function register_user($email, $username, $password)
	{
		$errors              = new \WP_Error();
		$form = new View_Form;
		// Email address is used as both username and email. It is also the only
		// parameter we need to validate
		if (!is_email($email) && $email != 0) {
			$errors->add('email', $form->get_error_message('email'));
			return $errors;
		}

		if (username_exists($username)) {
			$errors->add('username_exists', $form->get_error_message('username_exists'));
			return $errors;
		}

		$user_data = array(
			'user_login' => $username,
			'nickname'   => $username,
		);

		if ($email != 0) {
			$user_data['user_email'] = $email;
		}

		$user_id = wp_insert_user($user_data);
		wp_set_password($password, $user_id);

		add_user_meta($user_id, 'about_user', '', true);

		return $user_id;
	}

	/**
	 * Handles the registration of a new user.
	 *
	 * Used through the action hook "login_form_register" activated on wp-login.php
	 * when accessed through the registration action.
	 */
	public function do_register_user()
	{
		if ('POST' == $_SERVER['REQUEST_METHOD']) {
			$redirect_url = home_url(IFM_NAMESPACE . '/login');

			if (!get_option('users_can_register')) {
				// Registration closed, display error
				$redirect_url = add_query_arg('register-errors', 'closed', $redirect_url);
			} else {
				if ($_POST['email']) {
					$email = $_POST['email'];
				} else {
					$email = 0;
				}
				$username = sanitize_text_field($_POST['username']);
				$password = $_POST['password'];

				$result = $this->register_user($email, $username, $password);

				if (is_wp_error($result)) {
					// Parse errors into a string and append as parameter to redirect
					$errors       = join(',', $result->get_error_codes());
					$redirect_url = home_url('registration');
					$redirect_url = add_query_arg('register-errors', $errors, $redirect_url);
				} else {
					// Success, redirect to login page.
					$redirect_url = home_url(IFM_NAMESPACE . '/login');
					$redirect_url = add_query_arg('registered', $email, $redirect_url);
				}
			}

			wp_redirect($redirect_url);
			exit;
		}
	}

	public function add_conditional_menu_items($items, $args)
	{
		if ($args->theme_location == 'primary' && is_admin()) {
			$items .= '<li><a title="Admin" href="' . esc_url(admin_url()) . '">' . __('Admin') . '</a></li>';
		}
		if ($args->theme_location == 'primary' && is_user_logged_in()) {
			$userKarma = $this->user->get_karma();
			$items    .= '<li><a href="' . home_url() . IFM_ROUTE_ACCOUNT;
			'" class="logged-in-user">' . get_user_meta(get_current_user_id(), 'nickname', true) . ' (' . $userKarma . ')</a></li>';
		} elseif ($args->theme_location == 'primary' && !is_user_logged_in()) {
			$items .= '<li><a title="Login" href="' . esc_url(wp_login_url(IFM_ROUTE_FORUM)) . '">' . __('Login') . '</a></li>';
		}
		return $items;
	}
}

Controller_Account::register();
