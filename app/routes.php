<?php
require(IFM_INC . 'router/class-ifm-route.php');

IfmRoute::render('/forum', 'IfmPostsController@main');

IfmRoute::register();
