<?php

class crowdsortActivationController
{
    public static function register()
    {
        $plugin = new self();
        show_admin_bar( false );
        // add_filter('cron_schedules', array($plugin, 'my_cron_schedules'));
    }
    public function __construct()
    {
        // add_filter('wp_nav_menu_items', array('crowdsortActivationController', 'adjust_menu_items'), 10, 2);
    }

    public function my_cron_schedules($schedules)
    {
        if (!isset($schedules["5min"])) {
            $schedules["5min"] = array(
              'interval' => 5*60,
              'display' => __('Once every 5 minutes'));
        }
        return $schedules;
    }

    //plugin activation hook registered in bootstrap.php
    public static function plugin_activated()
    {
        if (! wp_next_scheduled('post_ranking_cron')) {
            wp_schedule_event(time(), '5min', 'post_ranking_cron');
        }

        require_once('models/page-definitions.php');
        $pageDefinitions = new crowdsorterPageDefinitions;
        $pageDefinitions->define_pages();
    }

    // Defunct Code, but who knows!
    // public static function adjust_menu_items($items, $args)
    // {
    //     var_dump(wp_get_nav_menu_items());
    //     $doc = new DOMDocument();
    //     $doc->loadHTML($items);
    //     $xpath = new DOMXpath($doc);
          
    //     if (is_user_logged_in()) {
    //         $loginbutton = $xpath->query("//li/a[contains(@href, 'member-login')]");
    //         foreach( $loginbutton as $quality) {
    //           var_dump($quality);
    //         }
    //         $loginbutton->nodeValue = 'logout';
    //         // $loginurl = home_url('member-login');
    //         // $items = str_replace($loginurl, wp_logout_url(), $items);
    //     } else {

    //     }
    //     return $items;
    // }
}

  crowdsortActivationController::register();
