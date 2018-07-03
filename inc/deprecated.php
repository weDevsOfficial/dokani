<?php
/**
 * Where old functions retire.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Deprecated constants
define( 'GENERATE_URI', get_template_directory_uri() );
define( 'GENERATE_DIR', get_template_directory() );

if ( ! function_exists( 'dokanee_paging_nav' ) ) {
	/**
	 * Build the pagination links
	 * @since 1.3.35
	 * @deprecated 1.3.45
	 */
	function dokanee_paging_nav() {
		_deprecated_function( __FUNCTION__, '1.3.45', "the_posts_navigation()" );
		if ( function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'mid_size' => apply_filters( 'dokanee_pagination_mid_size', 1 ),
				'prev_text' => __( '&larr; Previous', 'dokanee' ),
				'next_text' => __( 'Next &rarr;', 'dokanee' )
			) );
		}
	}
}

if ( ! function_exists( 'dokanee_additional_spacing' ) ) {
	/**
	 * Add fallback CSS for our mobile search icon color
	 * @deprecated 1.3.47
	 */
	function dokanee_additional_spacing() {
		// No longer needed
	}
}

if ( ! function_exists( 'dokanee_mobile_search_spacing_fallback_css' ) ) {
	/**
	 * Enqueue our mobile search icon color fallback CSS
	 * @deprecated 1.3.47
	 */
	function dokanee_mobile_search_spacing_fallback_css() {
		// No longer needed
	}
}

if ( ! function_exists( 'dokanee_addons_available' ) ) {
	/**
	 * Check to see if there's any addons not already activated
	 * @since 1.0.9
	 * @deprecated 1.3.47
	 */
	function dokanee_addons_available() {
		if ( defined( 'GP_PREMIUM_VERSION' ) ) {
			return false;
		}
	}
}

if ( ! function_exists( 'dokanee_no_addons' ) ) {
	/**
	 * Check to see if no addons are activated
	 * @since 1.0.9
	 * @deprecated 1.3.47
	 */
	function dokanee_no_addons() {
		if ( defined( 'GP_PREMIUM_VERSION' ) ) {
			return false;
		}
	}
}

if ( ! function_exists( 'dokanee_get_min_suffix' ) ) {
	/**
	 * Figure out if we should use minified scripts or not
	 * @since 1.3.29
	 * @deprecated 2.0
	 */
	function dokanee_get_min_suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}
}

if ( ! function_exists( 'dokanee_add_layout_meta_box' ) ) {
	function dokanee_add_layout_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_show_layout_meta_box' ) ) {
	function dokanee_show_layout_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_save_layout_meta' ) ) {
	function dokanee_save_layout_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokanee_add_footer_widget_meta_box' ) ) {
	function dokanee_add_footer_widget_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_show_footer_widget_meta_box' ) ) {
	function dokanee_show_footer_widget_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_save_footer_widget_meta' ) ) {
	function dokanee_save_footer_widget_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokanee_add_page_builder_meta_box' ) ) {
	function dokanee_add_page_builder_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_show_page_builder_meta_box' ) ) {
	function dokanee_show_page_builder_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_save_page_builder_meta' ) ) {
	function dokanee_save_page_builder_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokanee_add_de_meta_box' ) ) {
	function dokanee_add_de_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_show_de_meta_box' ) ) {
	function dokanee_show_de_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokanee_save_de_meta' ) ) {
	function dokanee_save_de_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokanee_add_base_inline_css' ) ) {
	function dokanee_add_base_inline_css() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokanee_color_scripts' ) ) {
	function dokanee_color_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokanee_typography_scripts' ) ) {
	function dokanee_typography_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokanee_spacing_scripts' ) ) {
	function dokanee_spacing_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokanee_enqueue_dynamic_css()" );
	}
}
