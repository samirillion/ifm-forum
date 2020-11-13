<?php

namespace IFM;

$user = $params['user'];
$user_id = $params['user_id'];
$is_current_user = $params['current_user'];
$notifications = $user->get_notifications();
?>
<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <div class="ifm-account-details">
                <?php
                echo '<div class="ifm-row"><span><b>Username: </b>' . \esc_html($user->user_login) . '</span></div>';
                echo '<div class="ifm-row"><span><b>User Karma: </b>' . \esc_html($user->get_karma()) . '</span></div>';
                echo '<div class="ifm-row"><span><b>User for: </b>' . $user->get_duration() . '</span></div>';
                ?>
                <form id="account-details" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <?php // disable fields if this is not current user acct 
                    ?>
                    <fieldset <?php echo !$is_current_user ? 'disabled="disabled"' : ''; ?>>
                        <p class="ifm-row">
                            <label for="email"><?php _e('Email', 'email'); ?></label>
                            <input type="email" name="email" id="user-email" class="post-input" value="<?php echo $user->user_email; ?>">
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

                        <?php if ($is_current_user) { ?>
                            <p class="ifm-row">
                                <h5>Notification Settings<span> (you must have a verified email address for these to work)</span></h5>
                                <table cellpadding="0" cellspacing="2">
                                    <?php foreach (Model_User::notification_options as $name => $description) { ?>
                                        <tr>
                                            <td class="ifm-checkbox-td"><input type="checkbox" name="notifications[]" id="<?= $name ?>" value=<?= $name ?> <?= $notifications[$name] ? 'checked="checked"' : ''; ?> /></td>
                                            <td style="padding-left:3px"><label for="<?= $name ?>"><?= $description ?></label></td>
                                        </tr>
                                    <?php } ?>
                                </table>
                            </p>
                        <?php } ?>
                        <?php wp_nonce_field('submit_ifm_post'); ?>

                        <p class="ifm-submit">
                            <input type="submit" name="update" value="<?php _e('Update', IFM_NAMESPACE); ?>" />
                        </p>
                        <input type="hidden" name="action" value="update_account_details">
                    </fieldset>
                </form>
                <a class="ifm-link" href="<?php echo add_query_arg('user_id', $user_id, home_url(IFM_ROUTE_FORUM)); ?>">view <?= \esc_html($user->user_login) ?>'s posts</a>
                <?php if ($is_current_user) : ?>
                    <a class="ifm-link" href=<?php echo home_url(IFM_NAMESPACE . '/change-password'); ?>>change password</a>
                    <a title="Logout" class="ifm-link" href="<?php echo esc_url(wp_logout_url(IFM_ROUTE_FORUM)); ?>">logout</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>