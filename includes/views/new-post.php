<?php

    class crowdsorterPostTemplate
    {
        public static function render()
        {
          wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
          wp_register_script("news-aggregator", WP_PLUGIN_URL.'/crowd-sorter/includes/views/js/news-aggregator.js', array('jquery'));
          wp_localize_script('news-aggregator', 'myAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'noposts' => esc_html__('No older posts found', 'aggregator'),
            'loggedIn' => is_user_logged_in(),
            'loginPage' => home_url( 'member-login' )
          ));
            wp_enqueue_script('news-aggregator');
          ?>
            <form id="submit-post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">

                <p class="form-row">
                    <label for="post-title"><?php _e( 'Post Title', 'submit-post' ); ?></label>
                    <input type="text" name="post-title" id="post-title" class="post-input">
                </p>

                <p class="form-row">
                    <label for="url"><?php _e( 'URL', 'submit-post' ); ?></label>
                    <input type="url" name="post-url" id="post-url" class="post-input">
                </p>
                <?php wp_nonce_field( 'submit_aggregator_post' ); ?>

                <p class="signup-submit">
                    <input type="submit" name="submit" class="register-button"
                           value="<?php _e( 'Submit', 'submit-post' ); ?>"/>
                </p>
                <input type="hidden" name="action" value="submit_post">
            </form>
      <?php }
    }
