<?php
$steps = array(
    'trim' => array(
        'column' => 'Display Name',
        'offset' => '0',
        'length' => '2',
        'delimiter' => ' ',
    ),
    'duplicate_col' => array(
        'from' => 'Display Name',
        'to' => 'Username',
    ),
    'strip_special' => array(
        'column' => 'Username',
    ),
    'str_to_lower' => array(
        'column' => 'Username'
    )
);
