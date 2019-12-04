<?php
/**
 * Container for the the post lists themselves.
 */
class IfmPostsContainer {

public static function render( $page_posts ) {
		get_header();
		wp_head();
		wp_enqueue_style( 'style.css', plugin_dir_url( __FILE__ ) . '/assets/style.css', null );
		wp_register_script( 'news-aggregator', plugin_dir_url( __FILE__ ) . '/assets/js/main.js', array( 'jquery' ) );
		wp_register_script( 'toggle-switch', plugin_dir_url( __FILE__ ) . '/assets/js/toggle-switch.js', array( 'jquery' ) );
		wp_localize_script(
			'news-aggregator',
			'myAjax',
			array(
				'ajaxurl'     => admin_url( 'admin-ajax.php' ),
				'noposts'     => esc_html__( 'No older posts found', 'aggregator' ),
				'aggpost_tax' => get_query_var( 'aggpost_tax' ),
			)
			);
		wp_enqueue_script( 'toggle-switch' );
		wp_enqueue_script( 'news-aggregator' );
		$page = ( isset( $_REQUEST['crowd_p'] ) ) ? $_REQUEST['crowd_p'] : 1;
		global $wp;
		$current_url = home_url( add_query_arg( array(), $wp->request ) );
		$next_page   = add_query_arg( 'crowd_p', $page + 1, $current_url );
		?>
		<div id="ifm-container" class="clearfix aggregator-main ajax_posts" role="main">
			<?php
			require_once( 'partials/class-post-template.php' );
			require_once( 'partials/forum-nav.php' );
			if ( is_array( $page_posts ) && [] !== $page_posts ) {
				$html = IfmPostTemplate::render( $page_posts );
				?>
				<div class="ifm-load-more">
					<a href="<?php echo $next_page; ?>" >
						<?php esc_html_e( 'Load More Posts', 'aggregator' ); ?>
					</a>
				</div>
				<?php
				} else {
			?>
			<div class='ifm-item-no-content'><div class='ifm-post-title'>No posts here! You should submit one!</div></div>
		<?php
				}
				echo $html;
				?>
		</div>
				<?php
				get_footer();
	}
}
