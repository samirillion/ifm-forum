<?php

namespace IFM;

$user_id = get_current_user_id();
$user = new Model_User($user_id);
?>
<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <div class="ifm-account-details">
                <?php
                echo '<div class="ifm-row"><span><b>Username: </b>' . \esc_html($current_user->user_login) . '</span></div>';
                $karma = $user->get_karma();
                echo '<div class="ifm-row"><span><b>User Karma: </b>' . \esc_html($karma) . '</span></div>';
                echo '<div class="ifm-row"><span><b>User for: </b>' . human_time_diff(strtotime($current_user->user_registered), current_time('timestamp', 1)) . '</span></div>';
                ?>
                <form id="account-details" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <p class="ifm-row">
                        <label for="email"><?php _e('Email', 'email'); ?></label>
                        <input type="email" name="email" id="user-email" class="post-input" value="<?php echo $current_user->user_email; ?>">
                    </p>
                    <p class="ifm-row">
                        <label for="about"><?php _e('About', IFM_NAMESPACE); ?></label>
                        <?php
                        if (!get_user_meta($user_id, 'about_user')) {
                            add_user_meta($user_id, 'about_user', '', true);
                        }
                        $about_user = stripslashes(get_user_meta($user_id, 'about_user', true));
                        ?>
                        <textarea type="text" name="about" id="user-about" class="post-input" cols='40' rows='5'><?php echo $about_user; ?></textarea>
                    </p>
                    <p class="ifm-row">
                    </p>
                    <?php wp_nonce_field('submit_ifm_post'); ?>

                    <p class="signup-submit">
                        <input type="submit" name="update" value="<?php _e('Update', IFM_NAMESPACE); ?>" />
                    </p>
                    <input type="hidden" name="action" value="update_account_details">
                </form>
                <a class="ifm-link" href="<?php echo add_query_arg('user_id', $user_id, home_url(IFM_ROUTE_FORUM)); ?>">View My Posts</a>
                <a class="ifm-link" href=<?php echo home_url(IFM_NAMESPACE . '/change-password'); ?>>Change password</a>
                <a title="Logout" class="ifm-link" href="<?php echo esc_url(wp_logout_url(IFM_ROUTE_FORUM)); ?>">Logout</a>
            </div>
        </div>
    </div>
</div>