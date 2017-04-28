<?php

class accountDetailsContainer
{
  public static function render()
        {
          wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
          wp_register_script("news-aggregator", WP_PLUGIN_URL.'/crowd-sorter/includes/views/js/news-aggregator.js', array('jquery'));
          wp_localize_script('news-aggregator', 'myAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'loggedIn' => is_user_logged_in(),
          ));
            wp_enqueue_script('news-aggregator');
            $current_user = wp_get_current_user();
              echo 'Username: ' . $current_user->user_login . '<br />';
              echo 'User email: ' . $current_user->user_email . '<br />';
              echo 'User first name: ' . $current_user->user_firstname . '<br />';
              echo 'User last name: ' . $current_user->user_lastname . '<br />';
              echo 'User display name: ' . $current_user->display_name . '<br />';
              echo 'User ID: ' . $current_user->ID . '<br />';
      ?>
    <form id="account-details" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

              <p class="form-row">
                  <label for="username"><?php _e( 'User Name', 'user-name' ); ?></label>
                  <input type="text" name="post-title" id="post-title" class="post-input" value="<?php echo $current_user->user_login ?>">
              </p>
                <p class="form-row">
                    <label for="username"><?php _e( 'User Name', 'user-name' ); ?></label>
                    <input type="text" name="post-title" id="post-title" class="post-input">
                </p>

                <p class="form-row">
                    <label for="url"><?php _e( 'URL', 'submit-post' ); ?></label>
                    <input type="url" name="post-url" id="post-url" class="post-input">
                </p>
                <?php wp_nonce_field( 'submit_aggregator_post' ); ?>

                <p class="signup-submit">
                    <input type="submit" name="update"
                           value="<?php _e( 'Update', 'update-account_details' ); ?>"/>
                </p>
                <input type="hidden" name="action" value="update_account_details">
            </form>
      <?php
        }

}
?>
