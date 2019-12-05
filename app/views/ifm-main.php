<?php

/**
 * Template Name: Ifm Main Template
 * This is where all the magic happens! All ::render()  routes declared in routes.php have their callbacks run from this template,
 * by this IfmView::render function, thus generating the page.
 * 
 * @package Ifm
 */
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
