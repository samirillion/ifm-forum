<?php

use PostTypes\PostType;

$ifm_messages = new PostType('message');

$ifm_messages->icon('dashicons-feedback');

$ifm_messages->register();
