<?php

namespace IFM;

$user_id      = get_query_var('user_id');
$user = get_user_by('id', $user_id);
$user_name =  $user->data->user_nicename;
$user_karma = Model_User::calculate_user_karma($user_id);
?>
<div class="ifm-container">
	<div class="ifm-row">
		<div class="ifm-col-8-sm ifm-col-offset-2-sm">
			<div class="ifm-account-details">
				<h5><?php _e('Username: ', IFM_NAMESPACE);
					$user_name ?></h5>
				<h5><?php _e('User Karma: ', IFM_NAMESPACE);
					echo $user_karma ?></h5>
				<h5><?php _e('User Since: ', IFM_NAMESPACE);
					echo human_time_diff(strtotime($user->user_registered), current_time('timestamp', 1)); ?> ago</h5>
				<br>
				<?php
				if (get_user_meta($user_id, 'about_user', true)) { ?>
					<h5><?php _e('About:', IFM_NAMESPACE) ?></h5>
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