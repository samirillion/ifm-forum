<div class="ifm-container">
	<div class="ifm-row">
		<div class="ifm-col-8-sm ifm-col-offset-2-sm">
			<?php if ($params['show_title']) : ?>
				<!-- Show errors if there are any -->
				<?php _e('Sign In', IFM_NAMESPACE); ?>
			<?php endif; ?>
			<?php
			if ('success' === get_query_var('status')) {
				echo '<br>Password successfully changed. Take your new password for a spin by logging back in!<br>';
			}
			?>
			<?php if (count($params['errors']) > 0) : ?>
				<?php foreach ($params['errors'] as $error) : ?>
					<p class="ifm-error">
						<?php echo $error; ?>
					</p>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($params['registered']) : ?>
				<p class="ifm-login">
					<?php
					printf(
						__('You have successfully registered to the <strong>%s</strong> forum.<br> Time to Sign in!', IFM_NAMESPACE),
						get_bloginfo('name')
					);
					?>
				</p>
			<?php endif; ?>
			<!-- Show logged out message if user just logged out -->
			<?php if ($params['logged_out']) : ?>
				<p class="ifm-login">
					<?php _e('You have signed out. Would you like to sign in again?', IFM_NAMESPACE); ?>
				</p>
			<?php endif; ?>
			<?php
			wp_login_form(
				array(
					'label_username' => __('Username', IFM_NAMESPACE),
					'label_log_in'   => __('Sign In', IFM_NAMESPACE),
					'redirect'       => $params['redirect'],
				)
			);
			?>

			<a class="forgot-password" href="<?php echo home_url(IFM_NAMESPACE . "/password-reset"); ?>">
				<?php _e('Forgot your password?', IFM_NAMESPACE); ?>
			</a>
			<a class="registration-redirect" href="<?php echo home_url(IFM_ROUTE_ACCOUNT . "/create"); ?>">
				<?php _e('Or register a new account', IFM_NAMESPACE); ?>
			</a>
		</div>
	</div>
</div>