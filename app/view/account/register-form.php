<div class="ifm-container">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <div id="register-form" class="widecolumn">
                <?php if ($params['show_title']) : ?>
                    <h3><?php _e('Register', 'personalize-login'); ?></h3>
                <?php endif; ?>
                <?php if (count($params['errors']) > 0) : ?>
                    <?php foreach ($params['errors'] as $error) : ?>
                        <p>
                            <?php echo $error; ?>
                        </p>
                    <?php endforeach; ?>
                <?php endif; ?>
                <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
                    <p class="form-row">
                        <label for="username"><?php _e('Username', 'personalize-login'); ?></label>
                        <input type="text" name="username" id="username">
                    </p>
                    <p class="form-row">
                        <label for="password"><?php _e('Password', 'personalize-login'); ?></label>
                        <input type="password" name="password" id="password">
                    </p>

                    <p class="form-row">
                        <label for="email"><?php _e('Email', 'personalize-login'); ?> (optional)</label>
                        <input type="text" name="email" id="email">
                    </p>

                    <p class="signup-submit">
                        <input type="submit" name="submit" class="register-button" value="<?php _e('Register', 'personalize-login'); ?>" />
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>