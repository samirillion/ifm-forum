<?php

/**
 * Forum Nav
 *
 * @package Ifm
 */
if (get_query_var('ifm_tax')) {
	$ifm_query_var  = get_query_var('ifm_tax');
	$agg_all_active = '';
} elseif (get_query_var('user_id')) {
	$agg_all_active = '';
	$user_id        = (int) get_query_var('user_id');
	$user_nav_item  = "<span class='ifm-user-posts active h4'>" . get_userdata($user_id)->user_nicename . "'s posts</span>";
} else {
	$ifm_query_var  = '';
	$agg_all_active = 'active';
}

if (isset($user_nav_item)) {
	echo $user_nav_item;
	echo '<br>';
} else {
	?>
	<div class="ifm-submit-post">
		<a href="/new-post">+ Submit New Post</a>
	</div>
	<nav class="ifm-nav">
		<ul class="ifm-post-types">
			<li class="aggpost-type-nav-item <?php echo $agg_all_active; ?>">
				<a href="<?php echo esc_url($current_url) ?>">
					all
				</a>
			</li>
			<?php
				$custom_terms = get_terms(
					array(
						'taxonomy'   => 'aggpost-type',
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
					echo "<li class='aggpost-type-nav-item " . $active_class . "'><a href='" . add_query_arg('ifm_tax', $term->{'slug'}, $current_url) . "'>" . $term->{'name'} . '</a></li>';
				}
				?>
			<?php
				if (is_user_logged_in()) {
					$current_user_id = get_current_user_id();
				}
				?>
		</ul>
		<form role="search" method="get" class="ifm-searchform" action="<?php echo esc_url($current_url); ?>">
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
	}
} ?>