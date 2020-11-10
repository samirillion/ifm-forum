<div class="ifm-container ifm-register_form">
    <div class="ifm-row">
        <div class="ifm-col-12">
            <div id="register-form" class="widecolumn">
                <?php if ($params['show_title']) : ?>
                    <h3><?php _e('Register', IFM_NAMESPACE); ?></h3>
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
                        <label for="username"><?php _e('Username', IFM_NAMESPACE); ?></label>
                        <input type="text" name="username" id="username">
                    </p>
                    <p class="form-row">
                        <label for="password"><?php _e('Password', IFM_NAMESPACE); ?></label>
                        <input type="password" name="password" id="password">
                    </p>

                    <p class="form-row">
                        <label for="email"><?php _e('Email', IFM_NAMESPACE); ?> (optional)</label>
                        <input type="text" name="email" id="email">
                    </p>

                    <p class="signup-submit">
                        <input type="submit" name="submit" class="register-button" value="<?php _e('Register', IFM_NAMESPACE); ?>" />
                    </p>
                </form>
            </div>
        </div>
    </div>
</div>