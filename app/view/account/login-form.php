<div class="ifm-container">
	<div class="ifm-row">
		<div class="ifm-col-6-sm ifm-col-offset-3">
			<?php if ($attributes['show_title']) : ?>
				<!-- Show errors if there are any -->
				<?php _e('Sign In', IFM_NAMESPACE); ?>
			<?php endif; ?>
			<?php
			if ('success' === get_query_var('status')) {
				echo '<br>Password successfully changed. Take your new password for a spin by logging back in!<br>';
			}
			?>
			<?php if (count($attributes['errors']) > 0) : ?>
				<?php foreach ($attributes['errors'] as $error) : ?>
					<p class="csort-login-error">
						<?php echo $error; ?>
					</p>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ($attributes['registered']) : ?>
				<p class="login-info">
					<?php
					printf(
						__('You have successfully registered to the <strong>%s</strong> forum.', IFM_NAMESPACE),
						get_bloginfo('name')
					);
					?>
				</p>
			<?php endif; ?>
			<!-- Show logged out message if user just logged out -->
			<?php if ($attributes['logged_out']) : ?>
				<p class="login-info">
					<?php _e('You have signed out. Would you like to sign in again?', IFM_NAMESPACE); ?>
				</p>
			<?php endif; ?>
			<?php
			wp_login_form(
				array(
					'label_username' => __('Username', IFM_NAMESPACE),
					'label_log_in'   => __('Sign In', IFM_NAMESPACE),
					'redirect'       => $attributes['redirect'],
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