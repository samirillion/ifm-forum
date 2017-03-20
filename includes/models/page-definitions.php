<?php

    class crowdsorterPageDefinitions
    {
        // Information needed for creating the plugin's pages
public function define_pages()
{
    $page_definitions = array(
'main' => array(
  'title' => __('Aggregator', 'personalize-login'),
  'content' => '[crowdsortcontainer]'
),
'member-login' => array(
    'title' => __('Sign In', 'personalize-login'),
    'content' => '[custom-login-form]'
),
'member-registration' => array(
    'title' => __('Register', 'personalize-login'),
    'content' => '[custom-register-form]'
),
'member-account' => array(
    'title' => __('your account', 'personalize-login'),
    'content' => '[account-info]'
),
);

    foreach ($page_definitions as $slug => $page) {
        // Check that the page doesn't exist already
$query = new WP_Query('pagename=' . $slug);
        if (! $query->have_posts()) {
            // Add the page using the data from the array above
    wp_insert_post(
        array(
            'post_content'   => $page['content'],
            'post_name'      => $slug,
            'post_title'     => $page['title'],
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'ping_status'    => 'closed',
            'comment_status' => 'closed',
        )
    );
        }
    }
}
    }
