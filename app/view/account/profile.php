<?php

namespace IFM;

$user_id      = get_query_var('user_id');
$user = new Model_User($user_id);
$user_name =  $user->data->user_nicename;
$user_karma = $user->get_karma();
?>
<div class="ifm-container">
	<div class="ifm-row">
		<div class="ifm-col-12">
			<div class="ifm-account-details">
				<div class="ifm-row">
					<span><b><?php _e('Username: ', IFM_NAMESPACE); ?></b><?php
																			echo $user_name ?></span>
				</div>
				<div class="ifm-row">
					<span><b><?php _e('User Karma: ', IFM_NAMESPACE); ?></b><?php
																			echo $user_karma ?></span>
				</div>
				<div class="ifm-row">
					<span><b><?php _e('User Since: ', IFM_NAMESPACE); ?></b><?php
																			echo human_time_diff(strtotime($user->user_registered), current_time('timestamp', 1)); ?> ago</span>
				</div>
				<br>
				<?php
				if (get_user_meta($user_id, 'about_user', true)) { ?>
					<span><?php _e('About:', IFM_NAMESPACE) ?></span>
					<div class="ifm-about-user"><?php echo stripslashes(get_user_meta($user_id, 'about_user', true)) ?>s</div>
				<?php
				}
				if (count_user_posts($user_id, IFM_POST_TYPE)) { ?>
					<a class='btn btn-default ifm-view-user-posts' href='<?php echo add_query_arg('user_id', $user_id, home_url(IFM_ROUTE_FORUM)) ?>'><?php echo $user->user_nicename ?>'s posts</a>
				<?php
				}
				if (\get_current_user_id() === (int) get_query_var('user_id')) {
				?>
					<div class='ifm-user-edit'><a href='<?php echo home_url(IFM_ROUTE_ACCOUNT) ?>'>Edit Your Profile</a></div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>