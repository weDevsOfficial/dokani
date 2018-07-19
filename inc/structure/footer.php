<?php
/**
 * Footer elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_construct_footer' ) ) {
	add_action( 'dokanee_footer', 'dokanee_construct_footer' );
	/**
	 * Build our footer.
	 *
	 * @since 1.3.42
	 */
	function dokanee_construct_footer() {
		?>
		<footer class="site-info" itemtype="https://schema.org/WPFooter" itemscope="itemscope">
			<div class="inside-site-info <?php if ( 'full-width' !== dokanee_get_setting( 'footer_inner_width' ) ) : ?>grid-container grid-parent<?php endif; ?>">
				<?php
				/**
				 * dokanee_before_copyright hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_footer_bar - 15
				 */
				do_action( 'dokanee_before_copyright' );
				?>
				<div class="copyright-bar">

                    <?php

                    $dokanee_footer_content = get_theme_mod('dokanee_footer_content');

                    if ( $dokanee_footer_content == '' ) {

	                    /**
	                     * dokanee_credits hook.
	                     *
	                     * @since 0.1
	                     *
	                     * @hooked dokanee_add_footer_info - 10
	                     */
	                    do_action( 'dokanee_credits' );

                    } else {?>

                        <span class="copyright"> <?php _e( $dokanee_footer_content, 'dokanee' ); ?> </span>

                    <?php }

					?>
				</div>

                <?php

                $payment_options = get_theme_mod( 'payment_options' );

                if ( $payment_options !='' ) { ?>

                    <div class="payment-options">
                        <img src="<?php echo esc_url( $payment_options ); ?>" alt="<?php esc_attr_e('Payment Options', 'dokanee'); ?>">
                    </div>

                 <?php } ?>
			</div>
		</footer><!-- .site-info -->
		<?php
	}
}

if ( ! function_exists( 'dokanee_footer_bar' ) ) {
	add_action( 'dokanee_before_copyright', 'dokanee_footer_bar', 15 );
	/**
	 * Build our footer bar
	 *
	 * @since 1.3.42
	 */
	function dokanee_footer_bar() {
		if ( ! is_active_sidebar( 'footer-bar' ) ) {
			return;
		}
		?>
		<div class="footer-bar">
			<?php dynamic_sidebar( 'footer-bar' ); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'dokanee_add_footer_info' ) ) {
	add_action( 'dokanee_credits', 'dokanee_add_footer_info' );
	/**
	 * Add the copyright to the footer
	 *
	 * @since 0.1
	 */
	function dokanee_add_footer_info() {
		$copyright = sprintf( '<span class="copyright">&copy; %1$s</span> &bull; <a href="%2$s" target="_blank" itemprop="url">%3$s</a>',
			date( 'Y' ),
			esc_url( 'https://generatepress.com' ),
			__( 'Dokanee', 'dokanee' )
		);

		echo apply_filters( 'dokanee_copyright', $copyright ); // WPCS: XSS ok.
	}
}

/**
 * Build our individual footer widgets.
 * Displays a sample widget if no widget is found in the area.
 *
 * @since 2.0
 *
 * @param int $widget_width The width class of our widget.
 * @param int $widget The ID of our widget.
 */
function dokanee_do_footer_widget( $widget_width, $widget ) {
	$widget_width = apply_filters( "dokanee_footer_widget_{$widget}_width", $widget_width );
	$tablet_widget_width = apply_filters( "dokanee_footer_widget_{$widget}_tablet_width", '50' );
	?>
	<div class="footer-widget-<?php echo absint( $widget ); ?> grid-parent grid-<?php echo absint( $widget_width ); ?> tablet-grid-<?php echo absint( $tablet_widget_width ); ?> mobile-grid-100">
		<?php if ( ! dynamic_sidebar( 'footer-' . absint( $widget ) ) ) : ?>
			<aside class="widget inner-padding widget_text">
				<h4 class="widget-title"><?php esc_html_e( 'Footer Widget', 'dokanee' );?></h4>
				<div class="textwidget">
					<p>
						<?php
						printf( // WPCS: XSS ok.
							/* translators: 1: admin URL */
							__( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets</strong></a> and dragging widgets into this widget area.', 'dokanee' ),
							esc_url( admin_url( 'widgets.php' ) )
						);
						?>
					</p>
					<p>
						<?php
						printf( // WPCS: XSS ok.
							/* translators: 1: admin URL */
							__( 'To remove or choose the number of footer widgets, go to <a href="%1$s"><strong>Appearance / Customize / Layout / Footer Widgets</strong></a>.', 'dokanee' ),
							esc_url( admin_url( 'customize.php' ) )
						);
						?>
					</p>
				</div>
			</aside>
		<?php endif; ?>
	</div>
	<?php
}

if ( ! function_exists( 'dokanee_construct_footer_widgets' ) ) {
	add_action( 'dokanee_footer', 'dokanee_construct_footer_widgets', 5 );
	/**
	 * Build our footer widgets.
	 *
	 * @since 1.3.42
	 */
	function dokanee_construct_footer_widgets() {
		// Get how many widgets to show.
		$widgets = dokanee_get_footer_widgets();

		if ( ! empty( $widgets ) && 0 !== $widgets ) :

			// Set up the widget width.
			$widget_width = '';
			if ( $widgets == 1 ) {
				$widget_width = '100';
			}

			if ( $widgets == 2 ) {
				$widget_width = '50';
			}

			if ( $widgets == 3 ) {
				$widget_width = '33';
			}

			if ( $widgets == 4 ) {
				$widget_width = '25';
			}

			if ( $widgets == 5 ) {
				$widget_width = '20';
			}
			?>
			<div id="footer-widgets" class="site footer-widgets">
				<div <?php dokanee_inside_footer_class(); ?>>
					<div class="inside-footer-widgets">
						<?php
						if ( $widgets >= 1 ) {
							dokanee_do_footer_widget( $widget_width, 1 );
						}

						if ( $widgets >= 2 ) {
							dokanee_do_footer_widget( $widget_width, 2 );
						}

						if ( $widgets >= 3 ) {
							dokanee_do_footer_widget( $widget_width, 3 );
						}

						if ( $widgets >= 4 ) {
							dokanee_do_footer_widget( $widget_width, 4 );
						}

						if ( $widgets >= 5 ) {
							dokanee_do_footer_widget( $widget_width, 5 );
						}
						?>
					</div>
				</div>
			</div>
		<?php
		endif;

		/**
		 * dokanee_after_footer_widgets hook.
		 *
		 * @since 0.1
		 */
		do_action( 'dokanee_after_footer_widgets' );
	}
}

if ( ! function_exists( 'dokanee_back_to_top' ) ) {
	add_action( 'dokanee_after_footer', 'dokanee_back_to_top' );
	/**
	 * Build the back to top button
	 *
	 * @since 1.3.24
	 */
	function dokanee_back_to_top() {
		$dokanee_settings = wp_parse_args(
			get_option( 'dokanee_settings', array() ),
			dokanee_get_defaults()
		);

		if ( 'enable' !== $dokanee_settings[ 'back_to_top' ] ) {
			return;
		}

		echo apply_filters( 'dokanee_back_to_top_output', sprintf( // WPCS: XSS ok.
			'<a title="%1$s" rel="nofollow" href="#" class="dokanee-back-to-top" style="opacity:0;visibility:hidden;" data-scroll-speed="%2$s" data-start-scroll="%3$s">
				<span class="screen-reader-text">%5$s</span>
			</a>',
			esc_attr__( 'Scroll back to top', 'dokanee' ),
			absint( apply_filters( 'dokanee_back_to_top_scroll_speed', 400 ) ),
			absint( apply_filters( 'dokanee_back_to_top_start_scroll', 300 ) ),
			esc_attr( apply_filters( 'dokanee_back_to_top_icon', 'fa-angle-up' ) ),
			esc_html__( 'Scroll back to top', 'dokanee' )
		) );
	}
}
