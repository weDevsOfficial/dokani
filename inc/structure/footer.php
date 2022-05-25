<?php
/**
 * Footer elements.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_construct_footer' ) ) {
	 add_action( 'dokani_footer', 'dokani_construct_footer' );
	/**
	 * Build our footer.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_footer() {
	    $footer_bar_layout = get_theme_mod( 'footer_bar_layout', 'layout-2' );
	    if ( $footer_bar_layout !== 'disabled' ) {
		?>
		<footer class="site-info <?php echo esc_attr( $footer_bar_layout ); ?>" itemtype="https://schema.org/WPFooter" itemscope="itemscope">
			<div class="inside-site-info <?php if ( 'full-width' !== dokani_get_setting( 'footer_inner_width' ) ) : ?>grid-container grid-parent<?php endif; ?>">
                <div class="footer-bar-row">
                    <div class="footer-bar-column footer-bar-section1">
                        <?php
                        $section_1_type = get_theme_mod( 'dokani_footer_bar_section1_type', 'text' );
                        if ( $section_1_type === 'text' ) {
                            echo wp_kses_post( get_theme_mod( 'dokani_footer_bar_section1_content', sprintf( __('Copyright %s | dokani by weDevs', 'dokani' ), date('Y') ) ) );
                        } elseif ( $section_1_type === 'widget' ) {

                            $section_1_widget = dynamic_sidebar( 'footer-bar-1' );

                            if ( $section_1_widget ) {
                                $section_1_widget;
                            } else {
                                printf(
                                /* translators: 1: admin URL */
                                    wp_kses_post( __( 'Add widget content by going to <a href="%1$s"><strong>Appearance / Widgets</strong></a> and dragging widgets into this widget area.', 'dokani'  ) ),
                                    esc_url( admin_url( 'widgets.php' ) )
                                );
                            }

                        } elseif ( $section_1_type === 'footer_menu' ) {

                            if ( has_nav_menu( 'footer_menu' ) ) {
                                wp_nav_menu(
                                    array(
                                        'theme_location' => 'footer_menu',
                                        'container' => 'div',
                                        'container_class' => 'footer-menu',
                                        'container_id' => 'footer-menu',
                                        'menu_class' => '',
                                        'items_wrap' => '<ul id="%1$s" class="%2$s ' . esc_attr( join( ' ', dokani_get_menu_class() ) ) . '">%3$s</ul>'
                                    )
                                );
                            } else {
                                echo '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add Footer Menu', 'dokani' ) . '</a>';
                            }

                        }
                        ?>
                    </div>
                    <div class="footer-bar-column footer-bar-section2">
					<?php
					$section_2_type = get_theme_mod( 'dokani_footer_bar_section2_type', 'footer_menu' );
					if ( $section_2_type === 'text' ) {
						echo wp_kses_post( get_theme_mod( 'dokani_footer_bar_section2_content', 'Add Custom Content' ) );
					} elseif ( $section_2_type === 'widget' ) {

						$section_2_widget = dynamic_sidebar( 'footer-bar-2' );

						if ( $section_2_widget ) {
							$section_2_widget;
						} else {
							printf(
							/* translators: 1: admin URL */
								wp_kses_post( __( 'Add widget content by going to <a href="%1$s"><strong>Appearance / Widgets</strong></a> and dragging widgets into this widget area.', 'dokani' ) ),
								esc_url( admin_url( 'widgets.php' ) )
							);
						}

					} elseif ( $section_2_type === 'footer_menu' ) {

						if ( has_nav_menu( 'footer_menu' ) ) {
							wp_nav_menu(
								array(
									'theme_location'  => 'footer_menu',
									'container'       => 'div',
									'container_class' => 'footer-menu',
									'container_id'    => 'footer-menu',
									'menu_class'      => '',
									'items_wrap'      => '<ul id="%1$s" class="%2$s ' . esc_attr( join( ' ', dokani_get_menu_class() ) ) . '">%3$s</ul>'
								)
							);
						} else {
							echo '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add Footer Menu', 'dokani' ) . '</a>';
						}

					}
					?>
                </div>
                </div>
			</div>
		</footer><!-- .site-info -->
		<?php
	    }
	}
}

if ( ! function_exists( 'dokani_footer_bar' ) ) {
	add_action( 'dokani_before_copyright', 'dokani_footer_bar', 15 );
	/**
	 * Build our footer bar
	 *
	 * @since 1.0.0
	 */
	function dokani_footer_bar() {
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

if ( ! function_exists( 'dokani_add_footer_info' ) ) {
	add_action( 'dokani_credits', 'dokani_add_footer_info' );
	/**
	 * Add the copyright to the footer
	 *
	 * @since 1.0.0
	 */
	function dokani_add_footer_info() {
		$copyright = sprintf( '<span class="copyright">&copy; %1$s</span> &bull; <a href="%2$s" target="_blank" itemprop="url">%3$s</a>',
			date( 'Y' ),
			esc_url( 'https://wedevs.com' ),
			esc_html__( 'dokani', 'dokani' )
		);

		echo wp_kses_post( apply_filters( 'dokani_copyright', 'dokani' ), $copyright );
	}
}

/**
 * Build our individual footer widgets.
 * Displays a sample widget if no widget is found in the area.
 *
 * @since 1.0.0
 *
 * @param int $widget_width The width class of our widget.
 * @param int $widget The ID of our widget.
 */
function dokani_do_footer_widget( $widget_width, $widget ) {
	$widget_width = apply_filters( "dokani_footer_widget_{$widget}_width", $widget_width );
	$tablet_widget_width = apply_filters( "dokani_footer_widget_{$widget}_tablet_width", '50' );
	?>
	<div class="footer-widget-<?php echo absint( $widget ); ?> grid-parent grid-<?php echo absint( $widget_width ); ?> tablet-grid-<?php echo absint( $tablet_widget_width ); ?> mobile-grid-100">
		<?php if ( ! dynamic_sidebar( 'footer-' . absint( $widget ) ) ) : ?>
			<aside class="widget inner-padding widget_text">
				<h4 class="widget-title"><?php esc_html_e( 'Footer Widget', 'dokani' );?></h4>
				<div class="textwidget">
					<p>
						<?php
						printf(
							/* translators: 1: admin URL */
							wp_kses_post( __( 'Replace this widget content by going to <a href="%1$s"><strong>Appearance / Widgets</strong></a> and dragging widgets into this widget area.', 'dokani' ) ),
							esc_url( admin_url( 'widgets.php' ) )
						);
						?>
					</p>
					<p>
						<?php
						printf(
							/* translators: 1: admin URL */
							wp_kses_post( __( 'To remove or choose the number of footer widgets, go to <a href="%1$s"><strong>Appearance / Customize / Layout / Footer Widgets</strong></a>.', 'dokani' ) ),
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

if ( ! function_exists( 'dokani_construct_footer_widgets' ) ) {
	add_action( 'dokani_footer', 'dokani_construct_footer_widgets', 5 );


	/**
	 * Build our footer widgets.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_footer_widgets() {
		// Get how many widgets to show.
		$widgets = dokani_get_footer_widgets();

		if ( ! empty( $widgets ) && 0 !== $widgets && get_theme_mod('footer_widget_layout') !== 'disabled' ) :

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
				<div <?php dokani_inside_footer_class(); ?>>
					<div class="inside-footer-widgets">
						<?php
						if ( $widgets >= 1 ) {
							dokani_do_footer_widget( $widget_width, 1 );
						}

						if ( $widgets >= 2 ) {
							dokani_do_footer_widget( $widget_width, 2 );
						}

						if ( $widgets >= 3 ) {
							dokani_do_footer_widget( $widget_width, 3 );
						}

						if ( $widgets >= 4 ) {
							dokani_do_footer_widget( $widget_width, 4 );
						}

						if ( $widgets >= 5 ) {
							dokani_do_footer_widget( $widget_width, 5 );
						}
						?>
					</div>
				</div>
			</div>
		<?php
		endif;

		/**
		 * dokani_after_footer_widgets hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_footer_widgets' );

	}
}

if ( ! function_exists( 'dokani_back_to_top' ) ) {
	add_action( 'dokani_after_footer', 'dokani_back_to_top' );
	/**
	 * Build the back to top button
	 *
	 * @since 1.0.0
	 */
	function dokani_back_to_top() {
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		if ( 'enable' !== $dokani_settings[ 'back_to_top' ] ) {
			return;
		}

		echo wp_kses_post( apply_filters( 'dokani_back_to_top_output', sprintf(
            '<a title="%1$s" rel="nofollow" href="#" class="dokani-back-to-top" style="opacity:0;visibility:hidden;" data-scroll-speed="%2$s" data-start-scroll="%3$s">
				<span class="screen-reader-text">%5$s</span>
			</a>',
            esc_attr__( 'Scroll back to top', 'dokani' ),
            esc_attr( absint( apply_filters( 'dokani_back_to_top_scroll_speed', 400 ) ) ),
            esc_attr( absint( apply_filters( 'dokani_back_to_top_start_scroll', 300 ) ) ),
            esc_attr( apply_filters( 'dokani_back_to_top_icon', 'fa-angle-up' ) ),
            esc_html__( 'Scroll back to top', 'dokani' )
        )  ) );
	}
}
