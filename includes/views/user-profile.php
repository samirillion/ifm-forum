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

            $user_id = get_query_var('user_id');
            $current_user = get_user_by('id', $user_id);
            // var_dump($current_user);
              echo '<div class="user-account-details">';
              echo '<h5>Username: ' . $current_user->user_nicename . '</h5>';
              require_once( plugin_dir_path( __DIR__ ) . 'models/news-aggregator-users.php');
              $userKarma = newsAggregatorUsers::calculate_user_karma($user_id);
              echo '<h5>User Karma: ' . $userKarma. '</h5>';
              echo '<h5>User Since: ' . human_time_diff(strtotime($current_user->user_registered), current_time('timestamp', 1)) . ' ago</h5>';
              if ( get_user_meta($user_id, 'about_user', true)) { 
                echo '<h5>About:</h5><div class="agg-about-user">' . stripslashes(get_user_meta($user_id, 'about_user', true)) . '</div>'; 
              }
              if (count_user_posts( $userid , 'aggregator-posts' )) { 
                echo "<a class='btn btn-default view-user-posts' href='".  add_query_arg( 'user_id', $user_id , home_url('fin-forum') )  ."'>" . $current_user->user_nicename . "'s posts</a>";
              }
              if (get_current_user_id() === (int)get_query_var('user_id')) {
                echo "<div class='agg-user-edit'><a href='". home_url('my-account') ."'>Edit Your Profile</a></div>";
              }
              echo "</div>";
        }

}
?>
