<?php
/**
 * General functions.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'dokani_scripts' );
	/**
	 * Enqueue scripts and styles
	 */
	function dokani_scripts() {
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$dir_uri = get_template_directory_uri();

		if ( ! function_exists( 'dokan' ) ) {
			wp_enqueue_style( 'dokani-font-awesome', $dir_uri . "/assets/vendors/font-awesome/font-awesome.min.css", false, 5.15 );
		}

		wp_enqueue_style( 'flexslider', $dir_uri . "/assets/css/flexslider.css", false, null );

		wp_enqueue_style( 'dokani-style-grid', $dir_uri . "/assets/css/unsemantic-grid{$suffix}.css", false, DOKANI_VERSION, 'all' );
		wp_enqueue_style( 'dokani-style', $dir_uri . "/style{$suffix}.css", array( 'dokani-style-grid' ), DOKANI_VERSION, 'all' );
		wp_enqueue_style( 'dokani-mobile-style', $dir_uri . "/assets/css/mobile{$suffix}.css", array( 'dokani-style' ), DOKANI_VERSION, 'all' );
		wp_enqueue_style( 'dokani-flaticon', $dir_uri . "/assets/css/flaticon.css", array(), DOKANI_VERSION, 'all' );
		wp_enqueue_style( 'dokani-master', $dir_uri . "/assets/css/master.css", array(), DOKANI_VERSION, 'all' );

		if ( is_child_theme() ) {
			wp_enqueue_style( 'dokani-child', get_stylesheet_uri(), array( 'dokani-style' ), filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
		}

		if ( ! apply_filters( 'dokani_fontawesome_essentials', false ) ) {
			wp_enqueue_style( 'font-awesome', $dir_uri . "/assets/css/font-awesome{$suffix}.css", false, '4.7', 'all' );
		}

		if ( function_exists( 'wp_script_add_data' ) ) {
			wp_enqueue_script( 'dokani-classlist', $dir_uri . "/assets/js/classList{$suffix}.js", array(), DOKANI_VERSION, true );
			wp_script_add_data( 'dokani-classlist', 'conditional', 'lte IE 11' );
		}

		wp_enqueue_script( 'dokani-menu', $dir_uri . "/assets/js/menu{$suffix}.js", array(), DOKANI_VERSION, true );
		wp_enqueue_script( 'dokani-a11y', $dir_uri . "/assets/js/a11y{$suffix}.js", array(), DOKANI_VERSION, true );

		wp_enqueue_script( 'flexslider', $dir_uri . "/assets/js/jquery.flexslider-min.js", array( 'jquery' ) );
		wp_enqueue_script( 'dokani-tooltip', $dir_uri . "/assets/js/tooltips.min.js", array( 'jquery' ) );
		wp_enqueue_script( 'dokani-script', $dir_uri . "/assets/js/script.js", array( 'jquery' ), DOKANI_VERSION, true );

		wp_enqueue_script( 'wc-cart-fragments' ); // Enqueue cart fragment script for loaded mini cart preview on `dokani` theme.

		if ( 'click' == $dokani_settings[ 'nav_dropdown_type' ] || 'click-arrow' == $dokani_settings[ 'nav_dropdown_type' ] ) {
			wp_enqueue_script( 'dokani-dropdown-click', $dir_uri . "/assets/js/dropdown-click{$suffix}.js", array( 'dokani-menu' ), DOKANI_VERSION, true );
		}

		if ( 'enable' == $dokani_settings['back_to_top'] ) {
			wp_enqueue_script( 'dokani-back-to-top', $dir_uri . "/assets/js/back-to-top{$suffix}.js", array(), DOKANI_VERSION, true );
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

if ( ! function_exists( 'dokani_widgets_init' ) ) {
	add_action( 'widgets_init', 'dokani_widgets_init' );
	/**
	 * Register widgetized area and update sidebar with default widgets
	 */
	function dokani_widgets_init() {
		$widgets = array(
			'sidebar-1'       => __( 'Right Sidebar', 'dokani' ),
			'sidebar-2'       => __( 'Left Sidebar', 'dokani' ),
			'header'          => __( 'Header', 'dokani' ),
			'footer-1'        => __( 'Footer Widget 1', 'dokani' ),
			'footer-2'        => __( 'Footer Widget 2', 'dokani' ),
			'footer-3'        => __( 'Footer Widget 3', 'dokani' ),
			'footer-4'        => __( 'Footer Widget 4', 'dokani' ),
			'footer-5'        => __( 'Footer Widget 5', 'dokani' ),
			'footer-bar-1'    => __( 'Footer Bar Section 1','dokani' ),
			'footer-bar-2'    => __( 'Footer Bar Section 2','dokani' ),
			'store-list'      => __( 'Store List','dokani' ),
			'sidebar-shop'    => __( 'Shop','dokani' ),
			'sidebar-product' => __( 'Product','dokani' ),
			'home'            => __( 'Home','dokani' ),
		);

		foreach ( $widgets as $id => $name ) {
			register_sidebar( array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => apply_filters( 'dokani_start_widget_title', '<h2 class="widget-title">' ),
				'after_title'   => apply_filters( 'dokani_end_widget_title', '</h2>' ),
			) );
		}
	}
}

if ( ! function_exists( 'dokani_smart_content_width' ) ) {
	add_action( 'wp', 'dokani_smart_content_width' );
	/**
	 * Set the $content_width depending on layout of current page
	 * Hook into "wp" so we have the correct layout setting from dokani_get_layout()
	 * Hooking into "after_setup_theme" doesn't get the correct layout setting
	 */
	function dokani_smart_content_width() {
		global $content_width;

		$container_width = dokani_get_setting( 'container_width' );
		$right_sidebar_width = apply_filters( 'dokani_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'dokani_left_sidebar_width', '25' );
		$layout = dokani_get_layout();

		if ( 'left-sidebar' == $layout ) {
			$content_width = $container_width * ( ( 100 - $left_sidebar_width ) / 100 );
		} elseif ( 'right-sidebar' == $layout ) {
			$content_width = $container_width * ( ( 100 - $right_sidebar_width ) / 100 );
		} elseif ( 'no-sidebar' == $layout ) {
			$content_width = $container_width;
		} else {
			$content_width = $container_width * ( ( 100 - ( $left_sidebar_width + $right_sidebar_width ) ) / 100 );
		}
	}
}

if ( ! function_exists( 'dokani_page_menu_args' ) ) {
	add_filter( 'wp_page_menu_args', 'dokani_page_menu_args' );
	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The existing menu args.
	 * @return array Menu args.
	 */
	function dokani_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
}

if ( ! function_exists( 'dokani_resource_hints' ) ) {
	add_filter( 'wp_resource_hints', 'dokani_resource_hints', 10, 2 );
	/**
	 * Add resource hints to our Google fonts call.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $urls           URLs to print for resource hints.
	 * @param string $relation_type  The relation type the URLs are printed.
	 * @return array $urls           URLs to print for resource hints.
	 */
	function dokani_resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'dokani-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
			if ( version_compare( $GLOBALS['wp_version'], '4.7-alpha', '>=' ) ) {
				$urls[] = array(
					'href' => 'https://fonts.gstatic.com',
					'crossorigin',
				);
			} else {
				$urls[] = 'https://fonts.gstatic.com';
			}
		}
		return $urls;
	}
}

if ( ! function_exists( 'dokani_remove_caption_padding' ) ) {
	add_filter( 'img_caption_shortcode_width', 'dokani_remove_caption_padding' );
	/**
	 * Remove WordPress's default padding on images with captions
	 *
	 * @param int $width Default WP .wp-caption width (image width + 10px)
	 * @return int Updated width to remove 10px padding
	 */
	function dokani_remove_caption_padding( $width ) {
		return $width - 10;
	}
}

if ( ! function_exists( 'dokani_enhanced_image_navigation' ) ) {
	add_filter( 'attachment_link', 'dokani_enhanced_image_navigation', 10, 2 );
	/**
	 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
	 */
	function dokani_enhanced_image_navigation( $url, $id ) {
		if ( ! is_attachment() && ! wp_attachment_is_image( $id ) ) {
			return $url;
		}

		$image = get_post( $id );
		if ( ! empty( $image->post_parent ) && $image->post_parent != $id ) {
			$url .= '#main';
		}

		return $url;
	}
}

if ( ! function_exists( 'dokani_categorized_blog' ) ) {
	/**
	 * Determine whether blog/site has more than one category.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True of there is more than one category, false otherwise.
	 */
	function dokani_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'dokani_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'dokani_categories', $all_the_cool_cats );
		}

		if ( $all_the_cool_cats > 1 ) {
			// This blog has more than 1 category so twentyfifteen_categorized_blog should return true.
			return true;
		} else {
			// This blog has only 1 category so twentyfifteen_categorized_blog should return false.
			return false;
		}
	}
}

if ( ! function_exists( 'dokani_category_transient_flusher' ) ) {
	add_action( 'edit_category', 'dokani_category_transient_flusher' );
	add_action( 'save_post',     'dokani_category_transient_flusher' );
	/**
	 * Flush out the transients used in {@see dokani_categorized_blog()}.
	 *
	 * @since 1.0.0
	 */
	function dokani_category_transient_flusher() {
		// Like, beat it. Dig?
		delete_transient( 'dokani_categories' );
	}
}

if ( ! function_exists( 'dokani_get_default_color_palettes' ) ) {
	/**
	 * Set up our colors for the color picker palettes and filter them so you can change them.
	 *
	 * @since 1.0.0
	 */
	function dokani_get_default_color_palettes() {
		$palettes = array(
			'#000000',
			'#FFFFFF',
			'#F1C40F',
			'#E74C3C',
			'#1ABC9C',
			'#1e72bd',
			'#8E44AD',
			'#00CC77',
		);

		return apply_filters( 'dokani_default_color_palettes', $palettes );
	}
}

add_filter( 'dokani_fontawesome_essentials', 'dokani_set_font_awesome_essentials' );
/**
 * Check to see if we should include the full Font Awesome library or not.
 *
 * @since 1.0.0
 *
 * @param bool $essentials
 * @return bool
 */
function dokani_set_font_awesome_essentials( $essentials ) {
	if ( dokani_get_setting( 'font_awesome_essentials' ) ) {
		return true;
	}

	return $essentials;
}

add_filter( 'dokani_dynamic_css_skip_cache', 'dokani_skip_dynamic_css_cache' );
/**
 * Skips caching of the dynamic CSS if set to false.
 *
 * @since 1.0.0
 *
 * @param bool $cache
 * @return bool
 */
function dokani_skip_dynamic_css_cache( $cache ) {
	if ( ! dokani_get_setting( 'dynamic_css_cache' ) ) {
		return true;
	}

	return $cache;
}
