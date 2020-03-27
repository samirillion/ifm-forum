<?php

namespace IFM;

/**
 * Template Name: Ifm Main Template
 * This is where all the magic happens! Right now, all ::render()  routes declared in routes.php have their callbacks run from this template,
 * by this View::render function, thus generating the page.
 * 
 * @package Ifm
 */
get_header();

ob_start(); ?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="ifm-container">
                <?php
                Mvc_View::render($wp->query_vars['ifm']);
                ?>
            </div>
        </div>
    </div>
</div>

<?php
echo ob_get_clean();
get_footer();
