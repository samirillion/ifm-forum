<?php

namespace IFM;

wp_cache_flush();
$customterms = get_terms(
    array(
        'taxonomy'   => IFM_POST_TAXONOMY_NAME,
        'hide_empty' => false,
    )
);
?>
<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-10-sm ifm-col-offset-1-sm">
            <form id="submit-post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                <p class="form-row">
                    <label for="dropdown"><?php _e('Post Type', 'post-type'); ?></label>
                    <select name="post-type" id="post-type" class="post-input" required>
                        <option value="" selected disabled><?php _e('Select a Post Type', IFM_NAMESPACE) ?></option>
                        <?php
                        foreach ($customterms as $term) {
                            echo '<option>' . $term->{'name'} . '</option>';
                        };
                        ?>
                    </select>
                </p>
                <p class="form-row">
                    <label for="post-title"><?php _e('Post Title', IFM_NAMESPACE); ?></label>
                    <input type="text" name="post-title" id="post-title" class="post-input" required>
                </p>
                <p class="form-row">
                    <label for="link-or-oc-toggle"><?php _e('URL or Text?', IFM_NAMESPACE); ?></label>
                    <br>
                    <input type="checkbox" name="link-toggle" id="link-toggle" class="post-input lcs_check">
                </p>
                <br>
                <p class="form-row new-post-url">
                    <label for="url"><?php _e('URL', IFM_NAMESPACE); ?></label>
                    <input type="url" name="post-url" id="new-post-url" class="post-input" required>
                </p>
                <br>
                <?php
                wp_editor(
                    '',
                    'new-post-text-content',
                    array(
                        'editor_css'    => '<style scoped>#wp-new-post-text-content-wrap { display: none; }</style>',
                        'textarea_name' => 'post-text-content',
                        'editor_height' => 200,
                        'teeny'         => true,
                        'media_buttons' => true,
                    )
                );
                wp_nonce_field('submit_ifm_post');
                ?>
                <br>
                <p class="signup-submit">
                    <input type="submit" name="submit" class="register-button" value="<?php _e('Submit', IFM_NAMESPACE); ?>" />
                </p>
                <input type="hidden" name="action" value="submit_post">
            </form>
        </div>
    </div>
</div>