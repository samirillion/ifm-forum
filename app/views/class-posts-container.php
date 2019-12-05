<?php

/**
 * Container for the the post lists themselves.
 */
class IfmPostsContainer
{

	public static function render($page_posts)
	{
		$page = (isset($_REQUEST['ifm_p'])) ? $_REQUEST['ifm_p'] : 1;
		global $wp;
		$current_url = home_url(add_query_arg(array(), $wp->request));
		$next_page   = add_query_arg('ifm_p', $page + 1, $current_url);
		?>
		<div class="ifm-container" class="clearfix aggregator-main ajax_posts" role="main">
			<?php
					require_once('partials/class-post-template.php');
					require_once('partials/forum-nav.php');
					if (is_array($page_posts) && [] !== $page_posts) {
						$html = IfmPostTemplate::render($page_posts);
						?>
				<div class="ifm-load-more">
					<a href="<?php echo $next_page; ?>">
						<?php esc_html_e('Load More Posts', 'aggregator'); ?>
					</a>
				</div>
			<?php
					} else {
						?>
				<div class='ifm-item-no-content'>
					<div class='ifm-post-title'>No posts here! You should submit one!</div>
				</div>
			<?php
					}
					echo $html;
					?>
		</div>
<?php
	}
}
