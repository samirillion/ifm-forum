<?php

/**
 * Forum Nav
 *
 * @package Ifm
 */

use IFM\Model_Notification;

if (get_query_var('ifm_tax')) {
	$ifm_query_var  = get_query_var('ifm_tax');
} else {
	$ifm_query_var  = '';
}

if ($params['main'] && "" === $ifm_query_var) {
	$ifm_all_active = 'active';
} else {
	$ifm_all_active = '';
}
?>
<ul class="ifm-topnav">
	<li class="ifm-submit-post">
		<a href="<?php echo home_url(IFM_ROUTE_FORUM . "/submit"); ?>">+ Submit New Post</a>
	</li>
	<?php
	if (is_user_logged_in()) {
	?>
		<li class="ifm-post-nav-item ifm-nav-account">
			<a href="<?php echo home_url(IFM_ROUTE_ACCOUNT) ?>" class="ifm-button ifm-button-secondary"><?php _e('My Account', IFM_NAMESPACE) ?></a>
		</li>
		<li class="ifm-post-nav-item ifm-nav-account">
			<a href="<?php echo home_url(IFM_ROUTE_MAILBOX) ?>" class="ifm-button ifm-button-primary"><?php _e('My Mailbox', IFM_NAMESPACE) ?></a>
		</li>
	<?php
	} else {
	?>
		<li class="ifm-post-nav-item ifm-nav-account">
			<a href="<?php echo home_url(IFM_NAMESPACE . "/login") ?>" class="ifm-button ifm-button-secondary"><?php _e('Login/Register', IFM_NAMESPACE) ?></a>
		</li>
	<?php } ?>
</ul>
<nav class="ifm-nav">
	<ul class="ifm-post-types">
		<li class="ifm-post-nav-item">Categories:</li>
		<li class="ifm-post-nav-item <?php echo $ifm_all_active; ?>">
			<a href="<?php echo esc_url(IFM_ROUTE_FORUM) ?>">
				all
			</a>
		</li>
		<?php
		$custom_terms = get_terms(
			array(
				'taxonomy'   => IFM_POST_TAXONOMY_NAME,
				'hide_empty' => false,
			)
		);
		foreach ($custom_terms as $term) {
			if ($term->{'slug'} === $ifm_query_var) {
				$active_class     = 'active';
				$term_description = '<div class="ifm-term-description">' . $term->description . '</div>';
			} else {
				$active_class = '';
			}
			echo "<li class='ifm-post-nav-item " . $active_class . "'><a href='" . add_query_arg('ifm_tax', $term->{'slug'}, IFM_ROUTE_FORUM) . "'>" . $term->{'name'} . '</a></li>';
		}
		?>
	</ul>
	<form role="search" method="get" class="ifm-searchform" action="<?php echo esc_url(IFM_ROUTE_FORUM); ?>">
		<div class="ifm-search-wrapper">
			<label class="screen-reader-text" for="s"><?php _e('Search for:', IFM_NAMESPACE) ?></label>
			<input type="text" placeholder="search forum" name="ifm_query" class="ifm-query-input" />
			<input type="hidden" name="action" value="ifm_search_posts">
			<input type="submit" class="ifm-search-submit" value="Search" />
		</div>
	</form>
</nav>
<?php
$ifm_notifications = Model_Notification::get();
if (!empty($ifm_notifications)) { ?>
	<div class="ifm-body">
		<div class="ifm-container ifm-notifications">
			<div class="ifm-row">
				<div class="ifm-notification">
					<?php
					foreach ($ifm_notifications as $code) {
						echo "<div class='ifm-notice'>" . Model_Notification::hydrate($code) . "</div>";
					}
					?>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
<?php
if (isset($term_description)) {
	echo $term_description;
} ?>