<?php
/**
 * Adds HTML markup.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_body_schema' ) ) {
	/**
	 * Figure out which schema tags to apply to the <body> element.
	 *
	 * @since 1.0.0
	 */
	function dokani_body_schema() {
		// Set up blog variable
		$blog = ( is_home() || is_archive() || is_attachment() || is_tax() || is_single() ) ? true : false;

		// Set up default itemtype
		$itemtype = 'WebPage';

		// Get itemtype for the blog
		$itemtype = ( $blog ) ? 'Blog' : $itemtype;

		// Get itemtype for search results
		$itemtype = ( is_search() ) ? 'SearchResultsPage' : $itemtype;

		// Get the result
		$result = esc_html( apply_filters( 'dokani_body_itemtype', $itemtype ) );

		// Return our HTML
		echo "itemtype='https://schema.org/$result' itemscope='itemscope'"; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_article_schema' ) ) {
	/**
	 * Figure out which schema tags to apply to the <article> element
	 * The function determines the itemtype: dokani_article_schema( 'BlogPosting' )
	 * @since 1.0.0
	 */
	function dokani_article_schema( $type = 'CreativeWork' ) {
		// Get the itemtype
		$itemtype = esc_html( apply_filters( 'dokani_article_itemtype', $type ) );

		// Print the results
		echo "itemtype='https://schema.org/$itemtype' itemscope='itemscope'"; // WPCS: XSS ok, sanitization ok.
	}
}

if ( ! function_exists( 'dokani_body_classes' ) ) {
	add_filter( 'body_class', 'dokani_body_classes' );
	/**
	 * Adds custom classes to the array of body classes.
	 * @since 1.0.0
	 */
	function dokani_body_classes( $classes ) {

		global $wp;

		// Get Customizer settings
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		// Get the layout
		$layout = dokani_get_layout();

		if ( is_page_template('page-template/page-sidebar-no.php') || is_page_template('page-template/page-full-width.php') ){
			$layout = 'no-sidebar';
		} elseif ( is_page_template('page-template/page-sidebar-left.php') ){
			$layout = 'left-sidebar';
		} elseif ( is_page_template('page-template/page-sidebar-right.php') ){
			$layout = 'right-sidebar';
		} else {
			$layout;
		}

		// Get the navigation location
		$navigation_location = dokani_get_navigation_location();

		// Get the footer widgets
		$widgets = dokani_get_footer_widgets();

		// Let us know if a featured image is being used
		if ( has_post_thumbnail() ) {
			$classes[] = 'featured-image-active';
		}

		// Layout classes
		$classes[] = ( $layout ) ? $layout : 'right-sidebar';
		$classes[] = ( $navigation_location ) ? $navigation_location : 'nav-below-header';
		$classes[] = ( $dokani_settings['header_layout_setting'] ) ? $dokani_settings['header_layout_setting'] : 'fluid-header';
		$classes[] = ( $dokani_settings['content_layout_setting'] ) ? $dokani_settings['content_layout_setting'] : 'separate-containers';
		$classes[] = ( '' !== $widgets ) ? 'active-footer-widgets-' . $widgets : 'active-footer-widgets-3';

		// Navigation alignment class
		if ( $dokani_settings['nav_alignment_setting'] == 'left' ) {
			$classes[] = 'nav-aligned-left';
		} elseif ( $dokani_settings['nav_alignment_setting'] == 'center' ) {
			$classes[] = 'nav-aligned-center';
		} elseif ( $dokani_settings['nav_alignment_setting'] == 'right' ) {
			$classes[] = 'nav-aligned-right';
		} else {
			$classes[] = 'nav-aligned-left';
		}

		// Header alignment class
		if ( $dokani_settings['header_alignment_setting'] == 'left' ) {
			$classes[] = 'header-aligned-left';
		} elseif ( $dokani_settings['header_alignment_setting'] == 'center' ) {
			$classes[] = 'header-aligned-center';
		} elseif ( $dokani_settings['header_alignment_setting'] == 'right' ) {
			$classes[] = 'header-aligned-right';
		} else {
			$classes[] = 'header-aligned-left';
		}

		// Navigation dropdown type
		if ( 'click' == $dokani_settings[ 'nav_dropdown_type' ] ) {
			$classes[] = 'dropdown-click';
			$classes[] = 'dropdown-click-menu-item';
		} elseif ( 'click-arrow' == $dokani_settings[ 'nav_dropdown_type' ] ) {
			$classes[] = 'dropdown-click-arrow';
			$classes[] = 'dropdown-click';
		} else {
			$classes[] = 'dropdown-hover';
		}

		if ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) {
			$classes[] = 'dokani-store-template';
        }

		if ( function_exists( 'is_product' ) && is_product() ) {
			$classes[] = 'dokani-product-single-template';
        }

        if ( is_front_page() && is_page_template( 'page-template/page-home.php' ) ) {
			$classes[] = 'dokani-template-front-page';
        }

		if ( is_front_page() && is_home() ) {
			$classes[] = 'dokani-template-posts';
		}

		if ( isset( $wp->query_vars['name'] ) && $wp->query_vars['name'] == 'product-category' ) {
			$page_404 = array_search( 'error404', $classes );
			unset( $classes[$page_404] );
			$classes[] = 'dokani-product-category';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_top_bar_classes' ) ) {
	add_filter( 'dokani_top_bar_class', 'dokani_top_bar_classes' );
	/**
	 * Adds custom classes to the header.
	 *
	 * @since 1.0.0
	 */
	function dokani_top_bar_classes( $classes ) {
		$classes[] = 'top-bar';

		if ( 'contained' == dokani_get_setting( 'top_bar_width' ) ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		$classes[] = 'top-bar-align-' . dokani_get_setting( 'top_bar_alignment' );

		return $classes;
	}
}

if ( ! function_exists( 'dokani_right_sidebar_classes' ) ) {
	add_filter( 'dokani_right_sidebar_class', 'dokani_right_sidebar_classes' );
	/**
	 * Adds custom classes to the right sidebar.
	 *
	 * @since 1.0.0
	 */
	function dokani_right_sidebar_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'dokani_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'dokani_left_sidebar_width', '25' );

		$right_sidebar_tablet_width = apply_filters( 'dokani_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'dokani_left_sidebar_tablet_width', $left_sidebar_width );

		$classes[] = 'widget-area';
		$classes[] = 'grid-' . $right_sidebar_width;
		$classes[] = 'tablet-grid-' . $right_sidebar_tablet_width;
		$classes[] = 'grid-parent';
		$classes[] = 'sidebar';

		// Get the layout
		$layout = dokani_get_layout();

		if ( '' !== $layout ) {
			switch ( $layout ) {
				case 'both-left' :
					$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;
					$classes[] = 'pull-' . ( 100 - $total_sidebar_width );

					$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;
					$classes[] = 'tablet-pull-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_left_sidebar_classes' ) ) {
	add_filter( 'dokani_left_sidebar_class', 'dokani_left_sidebar_classes' );
	/**
	 * Adds custom classes to the left sidebar.
	 *
	 * @since 1.0.0
	 */
	function dokani_left_sidebar_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'dokani_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'dokani_left_sidebar_width', '25' );
		$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;

		$right_sidebar_tablet_width = apply_filters( 'dokani_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'dokani_left_sidebar_tablet_width', $left_sidebar_width );
		$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;

		$classes[] = 'widget-area';
		$classes[] = 'grid-' . $left_sidebar_width;
		$classes[] = 'tablet-grid-' . $left_sidebar_tablet_width;
		$classes[] = 'mobile-grid-100';
		$classes[] = 'grid-parent';
		$classes[] = 'sidebar';

		// Get the layout
		$layout = dokani_get_layout();

		if ( '' !== $layout ) {
			switch ( $layout ) {
				case 'left-sidebar' :
					$classes[] = 'pull-' . ( 100 - $left_sidebar_width );
					$classes[] = 'tablet-pull-' . ( 100 - $left_sidebar_tablet_width );
				break;

				case 'both-sidebars' :
				case 'both-left' :
					$classes[] = 'pull-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-pull-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_content_classes' ) ) {
	add_filter( 'dokani_content_class', 'dokani_content_classes' );
	/**
	 * Adds custom classes to the content container.
	 *
	 * @since 1.0.0
	 */
	function dokani_content_classes( $classes ) {
		$right_sidebar_width = apply_filters( 'dokani_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'dokani_left_sidebar_width', '25' );
		$total_sidebar_width = $left_sidebar_width + $right_sidebar_width;

		$right_sidebar_tablet_width = apply_filters( 'dokani_right_sidebar_tablet_width', $right_sidebar_width );
		$left_sidebar_tablet_width = apply_filters( 'dokani_left_sidebar_tablet_width', $left_sidebar_width );
		$total_sidebar_tablet_width = $left_sidebar_tablet_width + $right_sidebar_tablet_width;

		$classes[] = 'content-area';
		$classes[] = 'grid-parent';
		$classes[] = 'mobile-grid-100';

		// Get the layout
		$layout = dokani_get_layout();

		if ( is_page_template('page-template/page-sidebar-no.php') || is_page_template('page-template/page-full-width.php') ){
			$layout = 'no-sidebar';
		} elseif ( is_page_template('page-template/page-sidebar-left.php') ){
			$layout = 'left-sidebar';
		} elseif ( is_page_template('page-template/page-sidebar-right.php') ){
			$layout = 'right-sidebar';
		} else {
			$layout;
		}

		if ( '' !== $layout ) {
			switch ( $layout ) {

				case 'right-sidebar' :
					$classes[] = 'grid-' . ( 100 - $right_sidebar_width );
					$classes[] = 'tablet-grid-' . ( 100 - $right_sidebar_tablet_width );
				break;

				case 'left-sidebar' :
					$classes[] = 'push-' . $left_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $left_sidebar_width );
					$classes[] = 'tablet-push-' . $left_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $left_sidebar_tablet_width );
				break;

				case 'no-sidebar' :
					$classes[] = 'grid-100';
					$classes[] = 'tablet-grid-100';
				break;

				case 'both-sidebars' :
					$classes[] = 'push-' . $left_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-push-' . $left_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;

				case 'both-right' :
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;

				case 'both-left' :
					$classes[] = 'push-' . $total_sidebar_width;
					$classes[] = 'grid-' . ( 100 - $total_sidebar_width );
					$classes[] = 'tablet-push-' . $total_sidebar_tablet_width;
					$classes[] = 'tablet-grid-' . ( 100 - $total_sidebar_tablet_width );
				break;
			}
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_header_classes' ) ) {
	add_filter( 'dokani_header_class', 'dokani_header_classes' );
	/**
	 * Adds custom classes to the header.
	 *
	 * @since 1.0.0
	 */
	function dokani_header_classes( $classes ) {
		$classes[] = 'site-header';

		// Get theme options
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);
		$header_layout = $dokani_settings['header_layout_setting'];

		if ( $header_layout == 'contained-header' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_inside_header_classes' ) ) {
	add_filter( 'dokani_inside_header_class', 'dokani_inside_header_classes' );
	/**
	 * Adds custom classes to inside the header.
	 *
	 * @since 1.0.0
	 */
	function dokani_inside_header_classes( $classes ) {
		$classes[] = 'inside-header';
		$inner_header_width = dokani_get_setting( 'header_inner_width' );

		if ( $inner_header_width !== 'full-width' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_navigation_classes' ) ) {
	add_filter( 'dokani_navigation_class', 'dokani_navigation_classes' );
	/**
	 * Adds custom classes to the navigation.
	 *
	 * @since 1.0.0
	 */
	function dokani_navigation_classes( $classes ) {
		$classes[] = 'main-navigation';

		// Get theme options
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);
		$nav_layout = $dokani_settings['nav_layout_setting'];

		if ( $nav_layout == 'contained-nav' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_inside_navigation_classes' ) ) {
	add_filter( 'dokani_inside_navigation_class', 'dokani_inside_navigation_classes' );
	/**
	 * Adds custom classes to the inner navigation.
	 *
	 * @since 1.0.0
	 */
	function dokani_inside_navigation_classes( $classes ) {
		$classes[] = 'inside-navigation';
		$inner_nav_width = dokani_get_setting( 'nav_inner_width' );

		if ( $inner_nav_width !== 'full-width' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_menu_classes' ) ) {
	add_filter( 'dokani_menu_class', 'dokani_menu_classes' );
	/**
	 * Adds custom classes to the menu.
	 *
	 * @since 1.0.0
	 */
	function dokani_menu_classes( $classes ) {
		$classes[] = 'menu';
		$classes[] = 'sf-menu';
		return $classes;
	}
}

if ( ! function_exists( 'dokani_footer_classes' ) ) {
	add_filter( 'dokani_footer_class', 'dokani_footer_classes' );
	/**
	 * Adds custom classes to the footer.
	 *
	 * @since 1.0.0
	 */
	function dokani_footer_classes( $classes ) {
		$classes[] = 'site-footer';

		// Get theme options
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);
		$footer_layout = $dokani_settings['footer_layout_setting'];

		if ( $footer_layout == 'contained-footer' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		// Footer bar
		$classes[] = ( is_active_sidebar( 'footer-bar' ) ) ? 'footer-bar-active' : '';
		$classes[] = ( is_active_sidebar( 'footer-bar' ) ) ? 'footer-bar-align-' . $dokani_settings[ 'footer_bar_alignment' ] : '';

		return $classes;
	}
}

if ( ! function_exists( 'dokani_inside_footer_classes' ) ) {
	add_filter( 'dokani_inside_footer_class', 'dokani_inside_footer_classes' );
	/**
	 * Adds custom classes to the footer.
	 *
	 * @since 1.0.0
	 */
	function dokani_inside_footer_classes( $classes ) {
		$classes[] = 'footer-widgets-container';
		$inside_footer_width = dokani_get_setting( 'footer_inner_width' );

		if ( $inside_footer_width !== 'full-width' ) {
			$classes[] = 'grid-container';
			$classes[] = 'grid-parent';
		}

		return $classes;
	}
}

if ( ! function_exists( 'dokani_main_classes' ) ) {
	add_filter( 'dokani_main_class', 'dokani_main_classes' );
	/**
	 * Adds custom classes to the <main> element
	 * @since 1.0.0
	 */
	function dokani_main_classes( $classes ) {
		$classes[] = 'site-main';
		return $classes;
	}
}

if ( ! function_exists( 'dokani_post_classes' ) ) {
	add_filter( 'post_class', 'dokani_post_classes' );
	/**
	 * Adds custom classes to the <article> element.
	 * Remove .hentry class from pages to comply with structural data guidelines.
	 *
	 * @since 1.0.0
	 */
	function dokani_post_classes( $classes ) {
		if ( 'page' == get_post_type() ) {
			$classes = array_diff( $classes, array( 'hentry' ) );
		}

		return $classes;
	}
}
