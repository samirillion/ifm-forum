<?php

class crowdsortActivationController
{
    public static function register()
    {
        $plugin = new self();
        show_admin_bar(false);
        // add_filter('cron_schedules', array($plugin, 'my_cron_schedules'));
    }
    public function __construct()
    { }

    public function my_cron_schedules($schedules)
    {
        if (!isset($schedules["5min"])) {
            $schedules["5min"] = array(
                'interval' => 5 * 60,
                'display' => __('Once every 5 minutes')
            );
        }
        return $schedules;
    }

    //plugin activation hook registered in bootstrap.php
    public static function plugin_activated()
    {
        if (!wp_next_scheduled('post_ranking_cron')) {
            wp_schedule_event(time(), '5min', 'post_ranking_cron');
        }

        require_once('models/page-definitions.php');
        $pageDefinitions = new crowdsorterPageDefinitions;
        $pageDefinitions->define_pages();
    }
}

crowdsortActivationController::register();
