<?php

/**
 * Render Container To Change Password
 */

namespace IFM;
?>
<div class="ifm-container">
	<div class="ifm-row">
		<div class="ifm-col-12">
			<form id="change-password" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">

				<p class="form-row">
					<label for="email"><?php _e('Old Password', 'old-password'); ?></label>
					<input type="password" name="old-password" id="old-password" class="post-input">
				</p>

				<p class="form-row">
					<label for="email"><?php _e('New Password', 'new-password'); ?></label>
					<input type="password" name="new-password" id="new-password" class="post-input">
				</p>
				<?php wp_nonce_field('submit_password'); ?>

				<p class="change-password-submit">
					<input type="submit" name="change-password" value="<?php _e('Change Password', 'change_password'); ?>" />
				</p>
				<input type="hidden" name="action" value="change_password">
			</form>
		</div>
	</div>
</div>