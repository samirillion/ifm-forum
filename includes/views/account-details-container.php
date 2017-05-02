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
              require_once( plugin_dir_path( __DIR__ ) . 'models/news-aggregator-users.php');
              $userKarma = newsAggregatorUsers::calculate_user_karma();
              echo 'User Karma: ' . $userKarma. '<br />';
              echo 'User Since: ' . human_time_diff(strtotime($current_user->user_registered), current_time('timestamp', 1)) . ' ago';
      ?>
    <form id="account-details" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

              <p class="form-row">
                  <label for="email"><?php _e( 'Email', 'email' ); ?></label>
                  <input type="email" name="email" id="user-email" class="post-input" value="<?php echo $current_user->user_email ?>">
              </p>

                <p class="form-row">
                    <label for="about"><?php _e( 'About', 'about' ); ?></label>
                    <textarea type="text" name="about" id="user-about" class="post-input" cols='40' rows='5' /><?php
                      if ( ! get_user_meta( get_current_user_id(), 'about_user')) {
                        add_user_meta( get_current_user_id(), 'about_user', '', true);
                      }
                      echo stripslashes(get_user_meta(get_current_user_id(), 'about_user', true));
                      ?></textarea>
                </p>
                <?php wp_nonce_field( 'submit_aggregator_post' ); ?>

                <p class="signup-submit">
                    <input type="submit" name="update"
                           value="<?php _e( 'Update', 'update-account_details' ); ?>"/>
                </p>
                <input type="hidden" name="action" value="update_account_details">
            </form>
            <a href=<?php echo home_url('change-password')?>>Change password</a>
      <?php
        }

}
?>
