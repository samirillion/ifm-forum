<?php

namespace IFM;

$current_user = wp_get_current_user();
?>
<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-6-sm ifm-col-offset-3-sm">
            <div class="ifm-account-details">
                <?php
                echo '<h5>Username: ' . \esc_html($current_user->user_login) . '</h5><br />';
                $user_karma = Model_User::calculate_user_karma();
                echo '<h5>User Karma: ' . \esc_html($user_karma) . '</h5><br />';
                echo '<h5>User for: ' . human_time_diff(strtotime($current_user->user_registered), current_time('timestamp', 1)) . '</h5>';
                ?>
                <form id="account-details" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <p class="form-row">
                        <label for="email"><?php _e('Email', 'email'); ?></label>
                        <input type="email" name="email" id="user-email" class="post-input" value="<?php echo $current_user->user_email; ?>">
                    </p>
                    <p class="form-row">
                        <label for="about"><?php _e('About', IFM_NAMESPACE); ?></label>
                        <?php
                        if (!get_user_meta(get_current_user_id(), 'about_user')) {
                            add_user_meta(get_current_user_id(), 'about_user', '', true);
                        }
                        $about_user = stripslashes(get_user_meta(get_current_user_id(), 'about_user', true));
                        ?>
                        <textarea type="text" name="about" id="user-about" class="post-input" cols='40' rows='5'><?php echo $about_user; ?></textarea>
                    </p>
                    <p class="form-row">
                    </p>
                    <?php wp_nonce_field('submit_ifm_post'); ?>

                    <p class="signup-submit">
                        <input type="submit" name="update" value="<?php _e('Update', 'update-account_details'); ?>" />
                    </p>
                    <input type="hidden" name="action" value="update_account_details">
                </form>
                <a class="ifm-link" href="<?php echo add_query_arg('user_id', get_current_user_id(), home_url(IFM_ROUTE_FORUM)); ?>">View My Posts</a>
                <a class="ifm-link" href=<?php echo home_url('change-password'); ?>>Change password</a>
                <a title="Logout" class="ifm-link" href="<?php echo esc_url(wp_logout_url(IFM_ROUTE_FORUM)); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>