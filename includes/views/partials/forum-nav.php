<?php
/**
 * Forum Nav
 *
 * @package Ifm
 */
if ( get_query_var( 'aggpost_tax' ) ) {
	$agg_query_var  = get_query_var( 'aggpost_tax' );
	$agg_all_active = '';
} elseif ( get_query_var( 'user_id' ) ) {
	$agg_all_active = '';
	$user_id        = (int) get_query_var( 'user_id' );
	$user_nav_item  = "<span class='agg-user-posts active h4'>" . get_userdata( $user_id )->user_nicename . "'s posts</span>";
} else {
	$agg_query_var  = '';
	$agg_all_active = 'active';
}

if ( isset( $user_nav_item ) ) {
	echo $user_nav_item;
	echo '<br>';
} else {
?>
<div class="agg-submit-post">
	<a href="/new-post">+ Submit New Post</a>
</div>
<nav class="agg-nav">
	<ul class="agg-post-types">
		<li class="aggpost-type-nav-item <?php echo $agg_all_active; ?>">
			<a href="/fin-forum">
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
		foreach ( $custom_terms as $term ) {
			if ( $term->{'slug'} === $agg_query_var ) {
			$active_class     = 'active';
			$term_description = '<div class="agg-term-description">' . $term->description . '</div>';
					} else {
				$active_class = '';
					}
			echo "<li class='aggpost-type-nav-item " . $active_class . "'><a href='" . add_query_arg( 'aggpost_tax', $term->{'slug'}, home_url( 'fin-forum' ) ) . "'>" . $term->{'name'} . '</a></li>';
		}
		?>
		<?php
		if ( is_user_logged_in() ) {
			$current_user_id = get_current_user_id();
		}
		?>
	</ul>
	<form role="search" method="get" class="agg-searchform" action="<?php echo esc_url( home_url( '/fin-forum' ) ); ?>">
	<div class="agg-search-wrapper">
		<label class="screen-reader-text" for="s">Search for:</label>
		<input type="text" placeholder="search forum" name="agg_query" class="agg-query-input" />
		<input type="hidden" name="action" value="agg_search_posts">
		<input type="submit" class="agg-search-submit" value="Search" />
	</div>
	</form>
</nav>
<?php
if ( isset( $term_description ) ) {
		echo $term_description; }
		} ?>
