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

ob_start();
?>
<div class="ifm-container ifm-main <?= main_classes($wp->query_vars[IFM_NAMESPACE]) ?>">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <?php
            echo parse_render_method($wp->query_vars[IFM_NAMESPACE]);
            ?>
        </div>
    </div>
</div>

<?php
echo ob_get_clean();
get_footer();
