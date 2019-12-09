<?php
$steps = array(
    array(
        'id' => 'user_id',
        'method' => 'get_user_by_email',
        'map' => array(
            'email' => '$Email',
        ),
    ),
    array(
        'method' => 'create_post',
        'id' => 'profile_id',
        'map' => array(
            'post_title' => '$Display Name',
            'post_content' => '$html_data',
            'post_type' => 'profile',
            'post_status' => 'publish'
        ),
    ),
    array(
        'method' => 'update_user_meta',
        'user_id' => '@user_id',
        'map' => array(
            'linked_profile_id' => '@profile_id',
        )
    ),
    array(
        'method' => 'add_post_terms',
        'term_type' => 'category',
        'post_id' => '@profile_id',
        'map' => array(
            'terms' => '$categories',
        )
    ),
    array(
        'method' => 'add_post_terms',
        'term_type' => 'post_tag',
        'post_id' => '@profile_id',
        'map' => array(
            'terms' => '$tags',
        )
    ),
    array(
        'method' => 'add_acf_meta',
        'post_id' => '@profile_id',
        'map' => array(
            'field_5d49e7d290b38' => '@user_id',
            'field_5d409e4c07116' => '$facebook_url',
            'field_5d409eaf0711b' => '$youtube_url',
            'field_5d61adb93f9e0' => '$pay_range',
            'field_5d49e7d290b38' => '@user_id',
            'field_5d409df707115' => '$profile_image',
        ),
    )
);
