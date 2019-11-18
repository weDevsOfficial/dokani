<?php
/**
 * General functions.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_scripts' ) ) {
	add_action( 'wp_enqueue_scripts', 'dokanee_scripts' );
	/**
	 * Enqueue scripts and styles
	 */
	function dokanee_scripts() {
		$dokanee_settings = wp_parse_args(
			get_option( 'dokanee_settings', array() ),
			dokanee_get_defaults()
		);

		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		$dir_uri = get_template_directory_uri();

		if ( ! function_exists( 'dokan' ) ) {
			wp_enqueue_style( 'dokanee-font-awesome', $dir_uri . "/assets/vendors/font-awesome/font-awesome.min.css", false, 4.7 );
		}

		wp_enqueue_style( 'flexslider', $dir_uri . "/assets/css/flexslider.css", false, null );

		wp_enqueue_style( 'dokanee-style-grid', $dir_uri . "/assets/css/unsemantic-grid{$suffix}.css", false, GENERATE_VERSION, 'all' );
		wp_enqueue_style( 'dokanee-style', $dir_uri . "/style{$suffix}.css", array( 'dokanee-style-grid' ), GENERATE_VERSION, 'all' );
		wp_enqueue_style( 'dokanee-mobile-style', $dir_uri . "/assets/css/mobile{$suffix}.css", array( 'dokanee-style' ), GENERATE_VERSION, 'all' );
		wp_enqueue_style( 'dokanee-flaticon', $dir_uri . "/assets/css/flaticon.css", array(), GENERATE_VERSION, 'all' );
		wp_enqueue_style( 'dokanee-master', $dir_uri . "/assets/css/master.css", array(), GENERATE_VERSION, 'all' );

		if ( is_child_theme() ) {
			wp_enqueue_style( 'dokanee-child', get_stylesheet_uri(), array( 'dokanee-style' ), filemtime( get_stylesheet_directory() . '/style.css' ), 'all' );
		}

		if ( ! apply_filters( 'dokanee_fontawesome_essentials', false ) ) {
			wp_enqueue_style( 'font-awesome', $dir_uri . "/assets/css/font-awesome{$suffix}.css", false, '4.7', 'all' );
		}

		if ( function_exists( 'wp_script_add_data' ) ) {
			wp_enqueue_script( 'dokanee-classlist', $dir_uri . "/assets/js/classList{$suffix}.js", array(), GENERATE_VERSION, true );
			wp_script_add_data( 'dokanee-classlist', 'conditional', 'lte IE 11' );
		}

		wp_enqueue_script( 'dokanee-menu', $dir_uri . "/assets/js/menu{$suffix}.js", array(), GENERATE_VERSION, true );
		wp_enqueue_script( 'dokanee-a11y', $dir_uri . "/assets/js/a11y{$suffix}.js", array(), GENERATE_VERSION, true );

		wp_enqueue_script( 'flexslider', $dir_uri . "/assets/js/jquery.flexslider-min.js", array( 'jquery' ) );
		wp_enqueue_script( 'dokanee-tooltip', $dir_uri . "/assets/js/tooltips.min.js", array( 'jquery' ) );
		wp_enqueue_script( 'dokanee-script', $dir_uri . "/assets/js/script.js", array( 'jquery' ), GENERATE_VERSION, true );

		if ( 'click' == $dokanee_settings[ 'nav_dropdown_type' ] || 'click-arrow' == $dokanee_settings[ 'nav_dropdown_type' ] ) {
			wp_enqueue_script( 'dokanee-dropdown-click', $dir_uri . "/assets/js/dropdown-click{$suffix}.js", array( 'dokanee-menu' ), GENERATE_VERSION, true );
		}

		if ( 'enable' == $dokanee_settings['back_to_top'] ) {
			wp_enqueue_script( 'dokanee-back-to-top', $dir_uri . "/assets/js/back-to-top{$suffix}.js", array(), GENERATE_VERSION, true );
		}

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}

if ( ! function_exists( 'dokanee_widgets_init' ) ) {
	add_action( 'widgets_init', 'dokanee_widgets_init' );
	/**
	 * Register widgetized area and update sidebar with default widgets
	 */
	function dokanee_widgets_init() {
		$widgets = array(
			'sidebar-1'       => __( 'Right Sidebar', 'dokanee' ),
			'sidebar-2'       => __( 'Left Sidebar', 'dokanee' ),
			'header'          => __( 'Header', 'dokanee' ),
			'footer-1'        => __( 'Footer Widget 1', 'dokanee' ),
			'footer-2'        => __( 'Footer Widget 2', 'dokanee' ),
			'footer-3'        => __( 'Footer Widget 3', 'dokanee' ),
			'footer-4'        => __( 'Footer Widget 4', 'dokanee' ),
			'footer-5'        => __( 'Footer Widget 5', 'dokanee' ),
			'footer-bar-1'    => __( 'Footer Bar Section 1','dokanee' ),
			'footer-bar-2'    => __( 'Footer Bar Section 2','dokanee' ),
			'store-list'      => __( 'Store List','dokanee' ),
			'sidebar-shop'    => __( 'Shop','dokanee' ),
			'sidebar-product' => __( 'Product','dokanee' ),
			'home'            => __( 'Home','dokanee' ),
		);

		foreach ( $widgets as $id => $name ) {
			register_sidebar( array(
				'name'          => $name,
				'id'            => $id,
				'before_widget' => '<aside id="%1$s" class="widget inner-padding %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => apply_filters( 'dokanee_start_widget_title', '<h2 class="widget-title">' ),
				'after_title'   => apply_filters( 'dokanee_end_widget_title', '</h2>' ),
			) );
		}
	}
}

if ( ! function_exists( 'dokanee_smart_content_width' ) ) {
	add_action( 'wp', 'dokanee_smart_content_width' );
	/**
	 * Set the $content_width depending on layout of current page
	 * Hook into "wp" so we have the correct layout setting from dokanee_get_layout()
	 * Hooking into "after_setup_theme" doesn't get the correct layout setting
	 */
	function dokanee_smart_content_width() {
		global $content_width;

		$container_width = dokanee_get_setting( 'container_width' );
		$right_sidebar_width = apply_filters( 'dokanee_right_sidebar_width', '25' );
		$left_sidebar_width = apply_filters( 'dokanee_left_sidebar_width', '25' );
		$layout = dokanee_get_layout();

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

if ( ! function_exists( 'dokanee_page_menu_args' ) ) {
	add_filter( 'wp_page_menu_args', 'dokanee_page_menu_args' );
	/**
	 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args The existing menu args.
	 * @return array Menu args.
	 */
	function dokanee_page_menu_args( $args ) {
		$args['show_home'] = true;
		return $args;
	}
}

if ( ! function_exists( 'dokanee_resource_hints' ) ) {
	add_filter( 'wp_resource_hints', 'dokanee_resource_hints', 10, 2 );
	/**
	 * Add resource hints to our Google fonts call.
	 *
	 * @since 1.0.0
	 *
	 * @param array  $urls           URLs to print for resource hints.
	 * @param string $relation_type  The relation type the URLs are printed.
	 * @return array $urls           URLs to print for resource hints.
	 */
	function dokanee_resource_hints( $urls, $relation_type ) {
		if ( wp_style_is( 'dokanee-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
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

if ( ! function_exists( 'dokanee_remove_caption_padding' ) ) {
	add_filter( 'img_caption_shortcode_width', 'dokanee_remove_caption_padding' );
	/**
	 * Remove WordPress's default padding on images with captions
	 *
	 * @param int $width Default WP .wp-caption width (image width + 10px)
	 * @return int Updated width to remove 10px padding
	 */
	function dokanee_remove_caption_padding( $width ) {
		return $width - 10;
	}
}

if ( ! function_exists( 'dokanee_enhanced_image_navigation' ) ) {
	add_filter( 'attachment_link', 'dokanee_enhanced_image_navigation', 10, 2 );
	/**
	 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
	 */
	function dokanee_enhanced_image_navigation( $url, $id ) {
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

if ( ! function_exists( 'dokanee_categorized_blog' ) ) {
	/**
	 * Determine whether blog/site has more than one category.
	 *
	 * @since 1.0.0
	 *
	 * @return bool True of there is more than one category, false otherwise.
	 */
	function dokanee_categorized_blog() {
		if ( false === ( $all_the_cool_cats = get_transient( 'dokanee_categories' ) ) ) {
			// Create an array of all the categories that are attached to posts.
			$all_the_cool_cats = get_categories( array(
				'fields'     => 'ids',
				'hide_empty' => 1,

				// We only need to know if there is more than one category.
				'number'     => 2,
			) );

			// Count the number of categories that are attached to the posts.
			$all_the_cool_cats = count( $all_the_cool_cats );

			set_transient( 'dokanee_categories', $all_the_cool_cats );
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

if ( ! function_exists( 'dokanee_category_transient_flusher' ) ) {
	add_action( 'edit_category', 'dokanee_category_transient_flusher' );
	add_action( 'save_post',     'dokanee_category_transient_flusher' );
	/**
	 * Flush out the transients used in {@see dokanee_categorized_blog()}.
	 *
	 * @since 1.0.0
	 */
	function dokanee_category_transient_flusher() {
		// Like, beat it. Dig?
		delete_transient( 'dokanee_categories' );
	}
}

if ( ! function_exists( 'dokanee_get_default_color_palettes' ) ) {
	/**
	 * Set up our colors for the color picker palettes and filter them so you can change them.
	 *
	 * @since 1.0.0
	 */
	function dokanee_get_default_color_palettes() {
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

		return apply_filters( 'dokanee_default_color_palettes', $palettes );
	}
}

add_filter( 'dokanee_fontawesome_essentials', 'dokanee_set_font_awesome_essentials' );
/**
 * Check to see if we should include the full Font Awesome library or not.
 *
 * @since 1.0.0
 *
 * @param bool $essentials
 * @return bool
 */
function dokanee_set_font_awesome_essentials( $essentials ) {
	if ( dokanee_get_setting( 'font_awesome_essentials' ) ) {
		return true;
	}

	return $essentials;
}

add_filter( 'dokanee_dynamic_css_skip_cache', 'dokanee_skip_dynamic_css_cache' );
/**
 * Skips caching of the dynamic CSS if set to false.
 *
 * @since 1.0.0
 *
 * @param bool $cache
 * @return bool
 */
function dokanee_skip_dynamic_css_cache( $cache ) {
	if ( ! dokanee_get_setting( 'dynamic_css_cache' ) ) {
		return true;
	}

	return $cache;
}
