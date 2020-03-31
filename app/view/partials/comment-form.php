<form action='<?php echo home_url('/api/ifm/post-comment'); ?>' method="post" id='reply-to-post'>
    <textarea class='ifm-comment' name='reply' cols='40' rows='5' required></textarea>
    <input type='submit' value='comment'>
    <input type='hidden' name='post_id' value='<?php xdebug_break();
                                                echo isset($ifm_post_id) ? $ifm_post_id : get_query_var('ifm_post_id'); ?>'>
    <input type='hidden' name='nonce' value='<?php echo wp_create_nonce('wp_rest'); ?>'>
</form>