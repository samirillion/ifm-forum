<?php

    class crowdsorterPostTemplate
    {
        public static function render()
        {
          wp_cache_flush();
          wp_enqueue_style('crowdsorter.css', plugin_dir_url(__FILE__) . '/css/crowdsorter.css', null);
          wp_register_script("toggle-switch", plugin_dir_url(__FILE__).'/js/toggle-switch.js', array('jquery'));
          wp_register_script("news-aggregator", plugin_dir_url(__FILE__).'/js/news-aggregator.js', array('jquery', 'toggle-switch'), false, true);
          wp_localize_script('news-aggregator', 'myAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'noposts' => esc_html__('No older posts found', 'aggregator'),
            'loggedIn' => is_user_logged_in(),
            'loginPage' => home_url( 'member-login' )
          ));
            wp_enqueue_script("jquery");
            wp_enqueue_script("toggle-switch");
            wp_enqueue_script("news-aggregator"); 
          ?>
            <form id="submit-post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="post">
                <p class="form-row">
                    <label for="dropdown"><?php _e( 'Post Type', 'post-type' ); ?></label>
                    <select name="post-type" id="post-type" class="post-input" required>
                      <?php $customterms =  get_terms( array(
                              'taxonomy' => 'aggpost-type',
                              'hide_empty' => false,
                                )
                              );
                              // var_dump($customterms);
                        foreach ( $customterms as $term) {
                           echo "<option>" .$term->{'name'} . "</option>";
                        };
                        ?>
                    </select>
                </p>
                <p class="form-row">
                    <label for="post-title"><?php _e( 'Post Title', 'submit-post' ); ?></label>
                    <br>
                    <input type="text" name="post-title" id="post-title" class="post-input" required>
                </p>

                <p class="form-row">
                    <label for="link-or-oc-toggle"><?php _e( 'Link or Text?', 'submit-post' ); ?></label>
                    <br>
                    <input type="checkbox" name="link-toggle" id="link-toggle" class="post-input lcs_check">
                </p>

                <p class="form-row new-post-url">
                    <label for="url"><?php _e( 'URL', 'submit-post' ); ?></label>
                    <br>
                    <input type="url" name="post-url" id="new-post-url" class="post-input" required>
                </p>
                <p class="form-row new-post-textarea" style="display:none;">
                    <label for="content"><?php _e( 'Content', 'submit-post' ); ?></label>
                    <br>
                    <textarea name="post-text-content" id="new-post-text-content" class="post-input" rows="10"></textarea>
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
