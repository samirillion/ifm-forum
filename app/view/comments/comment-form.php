<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" id="reply-to-post">
    <textarea class="ifm-comment" name="replyContent" cols="40" rows="5" required></textarea>
    <p class="signup-submit">
        <input type="submit" name="submit" class="register-button" value="<?php _e('Submit', IFM_NAMESPACE); ?>" />
    </p>
    <input type="hidden" name="action" value="addComment">
    <input type="hidden" name="post_id" value="<?php echo isset($ifm_post_id) ? $ifm_post_id : get_query_var('ifm_post_id'); ?>">
    <?php wp_nonce_field('comment_nonce', 'comment_nonce'); ?>
</form>