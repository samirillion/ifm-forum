<?php

class ifmChangePassword
{
  public static function render()
        {
          if ( !is_user_logged_in() ) {
            echo "you need to be logged in to change your password<br>";
          } else if ( get_query_var('status') == 'failed') {
            echo "You have not entered the correct original password.";
          } else {
          $current_user = wp_get_current_user();
              echo 'Username: ' . $current_user->user_login . '<br />';
            }
      ?>
    <form id="change-password" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

              <p class="form-row">
                  <label for="email"><?php _e( 'Old Password', 'old-password' ); ?></label>
                  <input type="password" name="old-password" id="old-password" class="post-input">
              </p>

              <p class="form-row">
                  <label for="email"><?php _e( 'New Password', 'new-password' ); ?></label>
                  <input type="password" name="new-password" id="new-password" class="post-input">
              </p>
                <?php wp_nonce_field( 'submit_password' ); ?>

                <p class="change-password-submit">
                    <input type="submit" name="change-password"
                           value="<?php _e( 'Change Password', 'change_password' ); ?>"/>
                </p>
                <input type="hidden" name="action" value="change_password">
            </form>
      <?php
        }

}
?>
