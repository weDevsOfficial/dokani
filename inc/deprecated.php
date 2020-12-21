<?php
/**
 * Where old functions retire.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Deprecated constants
define( 'GENERATE_URI', get_template_directory_uri() );
define( 'GENERATE_DIR', get_template_directory() );

if ( ! function_exists( 'dokani_paging_nav' ) ) {
	/**
	 * Build the pagination links
	 * @since 1.0.0
	 * @deprecated 1.3.45
	 */
	function dokani_paging_nav() {
		_deprecated_function( __FUNCTION__, '1.3.45', "the_posts_navigation()" );
		if ( function_exists( 'the_posts_pagination' ) ) {
			the_posts_pagination( array(
				'mid_size' => apply_filters( 'dokani_pagination_mid_size', 1 ),
				'prev_text' => __( '&larr; Previous', 'dokani' ),
				'next_text' => __( 'Next &rarr;', 'dokani' )
			) );
		}
	}
}

if ( ! function_exists( 'dokani_additional_spacing' ) ) {
	/**
	 * Add fallback CSS for our mobile search icon color
	 * @deprecated 1.3.47
	 */
	function dokani_additional_spacing() {
		// No longer needed
	}
}

if ( ! function_exists( 'dokani_mobile_search_spacing_fallback_css' ) ) {
	/**
	 * Enqueue our mobile search icon color fallback CSS
	 * @deprecated 1.3.47
	 */
	function dokani_mobile_search_spacing_fallback_css() {
		// No longer needed
	}
}

if ( ! function_exists( 'dokani_addons_available' ) ) {
	/**
	 * Check to see if there's any addons not already activated
	 * @since 1.0.0
	 * @deprecated 1.3.47
	 */
	function dokani_addons_available() {
		if ( defined( 'GP_PREMIUM_VERSION' ) ) {
			return false;
		}
	}
}

if ( ! function_exists( 'dokani_no_addons' ) ) {
	/**
	 * Check to see if no addons are activated
	 * @since 1.0.0
	 * @deprecated 1.3.47
	 */
	function dokani_no_addons() {
		if ( defined( 'GP_PREMIUM_VERSION' ) ) {
			return false;
		}
	}
}

if ( ! function_exists( 'dokani_get_min_suffix' ) ) {
	/**
	 * Figure out if we should use minified scripts or not
	 * @since 1.0.0
	 * @deprecated 2.0
	 */
	function dokani_get_min_suffix() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}
}

if ( ! function_exists( 'dokani_add_layout_meta_box' ) ) {
	function dokani_add_layout_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_show_layout_meta_box' ) ) {
	function dokani_show_layout_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_save_layout_meta' ) ) {
	function dokani_save_layout_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokani_add_footer_widget_meta_box' ) ) {
	function dokani_add_footer_widget_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_show_footer_widget_meta_box' ) ) {
	function dokani_show_footer_widget_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_save_footer_widget_meta' ) ) {
	function dokani_save_footer_widget_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokani_add_page_builder_meta_box' ) ) {
	function dokani_add_page_builder_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_show_page_builder_meta_box' ) ) {
	function dokani_show_page_builder_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_save_page_builder_meta' ) ) {
	function dokani_save_page_builder_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokani_add_de_meta_box' ) ) {
	function dokani_add_de_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_register_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_show_de_meta_box' ) ) {
	function dokani_show_de_meta_box() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_do_layout_meta_box()" );
	}
}

if ( ! function_exists( 'dokani_save_de_meta' ) ) {
	function dokani_save_de_meta() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_save_layout_meta_data()" );
	}
}

if ( ! function_exists( 'dokani_add_base_inline_css' ) ) {
	function dokani_add_base_inline_css() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokani_color_scripts' ) ) {
	function dokani_color_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokani_typography_scripts' ) ) {
	function dokani_typography_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_enqueue_dynamic_css()" );
	}
}

if ( ! function_exists( 'dokani_spacing_scripts' ) ) {
	function dokani_spacing_scripts() {
		_deprecated_function( __FUNCTION__, '2.0', "dokani_enqueue_dynamic_css()" );
	}
}
