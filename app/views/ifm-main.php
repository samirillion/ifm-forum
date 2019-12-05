<?php

/**
 * Template Name: Ifm Main Template
 *
 * @package Ifm
 */å
get_header();

ob_start(); ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            IfmView::render($wp->query_vars['ifm']);
            ?>
        </div>
    </div>
</div>

<?php
echo ob_get_clean();
get_footer();
