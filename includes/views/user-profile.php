<?php

class crowdsorterUserProfile
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
            $user_id = get_query_var('user_id');
            $current_user = get_user_by('id', $user_id);
              echo 'Username: ' . $current_user->user_login . '<br />';
              require_once( plugin_dir_path( __DIR__ ) . 'models/news-aggregator-users.php');
              $userKarma = newsAggregatorUsers::calculate_user_karma($user_id);
              echo 'User Karma: ' . $userKarma. '<br />';
              echo 'User Since: ' . human_time_diff(strtotime($current_user->user_registered), current_time('timestamp', 1)) . ' ago<br/>';
              echo 'About: ' . stripslashes(get_user_meta($user_id, 'about_user', true));
        }

}
?>
