<?php
/**
 * Navigation elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_navigation_position' ) ) {
	/**
	 * Build the navigation.
	 *
	 * @since 0.1
	 */
	function dokanee_navigation_position() {
		?>
		<nav itemtype="https://schema.org/SiteNavigationElement" itemscope="itemscope" id="site-navigation" <?php dokanee_navigation_class(); ?>>
			<div <?php dokanee_inside_navigation_class(); ?>>
				<?php
				/**
				 * dokanee_inside_navigation hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_navigation_search - 10
				 * @hooked dokanee_mobile_menu_search_icon - 10
				 */
				do_action( 'dokanee_inside_navigation' );
				?>
				<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
					<?php do_action( 'dokanee_inside_mobile_menu' ); ?>
					<span class="mobile-menu"><?php echo apply_filters( 'dokanee_mobile_menu_label', __( 'Menu', 'dokanee' ) ); // WPCS: XSS ok. ?></span>
				</button>
				<?php
                if ( has_nav_menu( 'primary' ) ) {
	                wp_nav_menu(
		                array(
			                'theme_location' => 'primary',
			                'container' => 'div',
			                'container_class' => 'main-nav',
			                'container_id' => 'primary-menu',
			                'menu_class' => '',
			                'fallback_cb' => 'dokanee_menu_fallback',
			                'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', dokanee_get_menu_class() ) . '">%3$s</ul>'
		                )
	                );
                } else { ?>

                    <a class="navbar-brand" href="<?php echo home_url(); ?>"><?php _e( 'Home', 'dokanee' ); ?></a>

                <?php } ?>
			</div><!-- .inside-navigation -->
		</nav><!-- #site-navigation -->
		<?php
	}
}

if ( ! function_exists( 'dokanee_menu_fallback' ) ) {
	/**
	 * Menu fallback.
	 *
	 * @since 1.1.4
	 *
	 * @param  array $args
	 * @return string
	 */
	function dokanee_menu_fallback( $args ) {
		$dokanee_settings = wp_parse_args(
			get_option( 'dokanee_settings', array() ),
			dokanee_get_defaults()
		);
		?>
		<div id="primary-menu" class="main-nav">
			<ul <?php dokanee_menu_class(); ?>>
				<?php
				$args = array(
					'sort_column' => 'menu_order',
					'title_li' => '',
					'walker' => new Dokanee_Page_Walker()
				);

				wp_list_pages( $args );

				if ( 'cart-nav' == $dokanee_settings['cart_position_setting'] ){
					echo dokanee_cart_position();
				}

				?>
			</ul>
		</div><!-- .main-nav -->
		<?php
	}
}

/**
 * Generate the navigation based on settings
 *
 * It would be better to have all of these inside one action, but these
 * are kept this way to maintain backward compatibility for people
 * un-hooking and moving the navigation/changing the priority.
 *
 * @since 0.1
 */

if ( ! function_exists( 'dokanee_add_navigation_after_header' ) ) {
	add_action( 'dokanee_after_header', 'dokanee_add_navigation_after_header', 5 );
	function dokanee_add_navigation_after_header() {
		if ( 'nav-below-header' == dokanee_get_navigation_location() ) {
			dokanee_navigation_position();
		}
	}
}

if ( ! function_exists( 'dokanee_add_navigation_float_right' ) ) {
	add_action( 'dokanee_after_header_right', 'dokanee_add_navigation_float_right', 5 );
	function dokanee_add_navigation_float_right() {
		if ( 'nav-float-right' == dokanee_get_navigation_location() ) {
			dokanee_navigation_position();
		}
	}
}

if ( ! class_exists( 'Dokanee_Page_Walker' ) && class_exists( 'Walker_Page' ) ) {
	/**
	 * Add current-menu-item to the current item if no theme location is set
	 * This means we don't have to duplicate CSS properties for current_page_item and current-menu-item
	 *
	 * @since 1.3.21
	 */
	class Dokanee_Page_Walker extends Walker_Page {
		function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
			$css_class = array( 'page_item', 'page-item-' . $page->ID );
			$button = '';

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'menu-item-has-children';
				$button = '<span role="button" class="dropdown-menu-toggle" aria-expanded="false"></span>';
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors ) ) {
					$css_class[] = 'current-menu-ancestor';
				}
				if ( $page->ID == $current_page ) {
					$css_class[] = 'current-menu-item';
				} elseif ( $_current_page && $page->ID == $_current_page->post_parent ) {
					$css_class[] = 'current-menu-parent';
				}
			} elseif ( $page->ID == get_option('page_for_posts') ) {
				$css_class[] = 'current-menu-parent';
			}

			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after'] = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$output .= sprintf(
				'<li class="%s"><a href="%s">%s%s%s%s</a>',
				$css_classes,
				get_permalink( $page->ID ),
				$args['link_before'],
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after'],
				$button
			);
		}
	}
}

if ( ! function_exists( 'dokanee_dropdown_icon_to_menu_link' ) ) {
	add_filter( 'nav_menu_item_title', 'dokanee_dropdown_icon_to_menu_link', 10, 4 );
	/**
	 * Add dropdown icon if menu item has children.
	 *
	 * @since 1.3.42
	 *
	 * @param string $title The menu item title.
	 * @param WP_Post $item All of our menu item data.
	 * @param stdClass $args All of our menu item args.
	 * @param int $dept Depth of menu item.
	 * @return string The menu item.
	 */
	function dokanee_dropdown_icon_to_menu_link( $title, $item, $args, $depth ) {

		$role = 'presentation';
		$tabindex = '';

		if ( 'click-arrow' === dokanee_get_setting( 'nav_dropdown_type' ) ) {
			$role = 'button';
			$tabindex = ' tabindex="0"';
		}

		// Loop through our menu items and add our dropdown icons.
		if ( 'main-nav' === $args->container_class ) {
			foreach ( $item->classes as $value ) {
				if ( 'menu-item-has-children' === $value  ) {
					$title = $title . '<span role="' . $role . '" class="dropdown-menu-toggle"' . $tabindex .'></span>';
				}
			}
		}

		// Return our title.
		return $title;
	}
}

if ( ! function_exists( 'dokanee_menu_cart' ) ) {
	add_filter( 'wp_nav_menu_items', 'dokanee_menu_cart', 5, 2 );
	/**
	 * Add cart to primary menu if set
	 *
	 * @since 1.2.9.7
	 *
	 * @param string $nav The HTML list content for the menu items.
	 * @param stdClass $args An object containing wp_nav_menu() arguments.
	 * @return string The cart menu item.
	 */
	function dokanee_menu_cart( $nav, $args ) {
		$dokanee_settings = wp_parse_args(
			get_option( 'dokanee_settings', array() ),
			dokanee_get_defaults()
		);

		// If the cart isn't enabled, return the regular nav.
		if ( 'cart-nav' !== $dokanee_settings['cart_position_setting'] ) {
			return $nav;
		}

		// If our primary menu is set, add the cart.
		if ( $args->theme_location == 'primary' ) {
			return $nav . dokanee_cart_position();
		}

		// Our primary menu isn't set, return the regular nav.
		// In this case, the cart is added to the dokanee_menu_fallback() function in navigation.php.
		return $nav;
	}
}

add_action( 'wp_footer', 'dokanee_clone_sidebar_navigation' );
/**
 * Clone our sidebar navigation and place it below the header.
 * This places our mobile menu in a more user-friendly location.
 *
 * We're not using wp_add_inline_script() as this needs to happens
 * before menu.js is enqueued.
 *
 * @since 2.0
 */
function dokanee_clone_sidebar_navigation() {
	if ( 'nav-left-sidebar' !== dokanee_get_navigation_location() && 'nav-right-sidebar' !== dokanee_get_navigation_location() ) {
		return;
	}
	?>
	<script>
		var target, nav, clone;
		nav = document.getElementById( 'site-navigation' );
		if ( nav ) {
			clone = nav.cloneNode( true );
			clone.className += ' sidebar-nav-mobile';
			clone.setAttribute( 'aria-label', '<?php esc_attr_e( 'Mobile Menu', 'dokanee' ); ?>' );
			target = document.getElementById( 'masthead' );
			if ( target ) {
				target.insertAdjacentHTML( 'afterend', clone.outerHTML );
			} else {
				document.body.insertAdjacentHTML( 'afterbegin', clone.outerHTML )
			}
		}
	</script>
	<?php
}
