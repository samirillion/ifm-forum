<?php

namespace IFM;

/**
 * Template Name: Ifm Main Template
 * This is where all the magic happens! All ::render()  routes declared in routes.php have their callbacks run from this template,
 * by this View::render function, thus generating the page.
 * 
 * @package Ifm
 */
get_header();

ob_start(); ?>

<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <?php
            View_Main::render($wp->query_vars['ifm']);
            ?>
        </div>
    </div>
</div>

<?php
echo ob_get_clean();
get_footer();
