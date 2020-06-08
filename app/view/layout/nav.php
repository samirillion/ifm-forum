<?php

/**
 * Forum Nav
 *
 * @package Ifm
 */
if (get_query_var('ifm_tax')) {
	$ifm_query_var  = get_query_var('ifm_tax');
	$agg_all_active = '';
} else {
	$ifm_query_var  = '';
	$agg_all_active = 'active';
}

$forum_url = IFM_ROUTE_FORUM;
?>
<div class="ifm-submit-post">
	<a href="<?php echo home_url('/' . IFM_ROUTE_FORUM . "/submit"); ?>">+ Submit New Post</a>
</div>
<nav class="ifm-nav">
	<ul class="ifm-post-types">
		<li class="ifm-post-nav-item">Categories:</li>
		<li class="ifm-post-nav-item <?php echo $agg_all_active; ?>">
			<a href="<?php echo esc_url($forum_url) ?>">
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
		// var_dump($custom_terms);
		foreach ($custom_terms as $term) {
			if ($term->{'slug'} === $ifm_query_var) {
				$active_class     = 'active';
				$term_description = '<div class="ifm-term-description">' . $term->description . '</div>';
			} else {
				$active_class = '';
			}
			echo "<li class='ifm-post-nav-item " . $active_class . "'><a href='" . add_query_arg('ifm_tax', $term->{'slug'}, $forum_url) . "'>" . $term->{'name'} . '</a></li>';
		}
		?>
		<li class="ifm-post-nav-item ifm-nav-private">
			<?php
			if (is_user_logged_in()) {
			?>
				<a href="<?php echo home_url(IFM_ROUTE_ACCOUNT) ?>" class="ifm-button"><?php _e('My Account', IFM_NAMESPACE) ?></a>
			<?php
			} else {
			?>
				<a href="<?php echo home_url(IFM_NAMESPACE . "/login") ?>" class="ifm-button"><?php _e('Login/Register', IFM_NAMESPACE) ?></a>
			<?php } ?>
		</li>
	</ul>
	<form role="search" method="get" class="ifm-searchform" action="<?php echo esc_url($forum_url); ?>">
		<div class="ifm-search-wrapper">
			<label class="screen-reader-text" for="s">Search for:</label>
			<input type="text" placeholder="search forum" name="ifm_query" class="ifm-query-input" />
			<input type="hidden" name="action" value="agg_search_posts">
			<input type="submit" class="ifm-search-submit" value="Search" />
		</div>
	</form>
</nav>
<?php
if (isset($term_description)) {
	echo $term_description;
} ?>