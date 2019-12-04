<?php

/**
 * Template Name: Ifm Main Template
 *
 * @package Ifm
 */
require_once(IFM_VIEW . 'class-template.php');

get_header();

ob_start(); ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            IfmTemplate::render($wp->query_vars['ifm']);
            ?>
        </div>
    </div>
</div>

<?php
echo ob_get_clean();
get_footer();
