<?php
$steps = array(
    'string_replace' => array(
        'column' => 'categories',
        'strings' => array(
            'Award Winning Cover Band' => 'Cover Band',
            'Wedding Bands' => 'Wedding Band',
            'Alternative Rock Music' => 'Alternative Rock',
            'Jazz Bands' => 'Jazz Band',
        )
    ),
    'duplicate_col' => array(
        'from' => 'categories',
        'to' => 'tags',
    ),
    'drop_first' => array(
        'column' => 'tags',
    ),
    'drop_all_but_first' => array(
        'column' => 'categories',
    ),
    'prepend_string' => array(
        'column' => 'profile_image',
        'string' => 'https://www.chicagoentertainmentagency.com/'
    )
);
