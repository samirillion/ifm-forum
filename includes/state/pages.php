<?php

namespace IFM;

function create_forum_pages()
{
    $page_definitions = array(
        'main'                  => array(
            'post_title'   => __('Aggregator', 'personalize-login'),
            'post_content' => '[ifm-container]',
        ),
        'member-login'          => array(
            'post_title'   => __('Sign In', 'personalize-login'),
            'post_content' => '[custom-login-form]',
        ),
        'member-registration'   => array(
            'post_title'   => __('Register', 'personalize-login'),
            'post_content' => '[custom-register-form]',
        ),
        'member-password-lost'  => array(
            'post_title'   => __('Forgot Your Password?', 'personalize-login'),
            'post_content' => '[custom-password-lost-form]',
        ),
        'member-password-reset' => array(
            'post_title'   => __('Pick a New Password', 'personalize-login'),
            'post_content' => '[custom-password-reset-form]',
        ),
        'add-a-post'            => array(
            'post_title'   => __('Create a New Post', 'create-post'),
            'post_content' => '[ifm-post]',
        ),
        'account-details'       => array(
            'post_title'   => __('My Account Details', 'account-details'),
            'post_content' => '[account-info]',
        ),
        'change-password'       => array(
            'post_title'   => __('Change My Password', 'change-password'),
            'post_content' => '[change-password]',
        ),
        'edit-post'             => array(
            'post_title'   => __('Edit My Post', 'edit-aggpost'),
            'post_content' => '[edit-aggpost]',
        ),
        'view-user-profile'     => array(
            'post_title'   => __('View User Profile', 'user-profile'),
            'post_content' => '[user-profile]',
        ),
    );
    foreach ($page_definitions as $slug => $page) {
        // Check that the page doesn't exist already
        $query = new \WP_Query('pagename=' . $slug);
        if (!$query->have_posts()) {
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
create_forum_pages();
