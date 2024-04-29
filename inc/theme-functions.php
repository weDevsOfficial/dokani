<?php
/**
 * Main theme functions.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_get_setting' ) ) {
	/**
	 * A wrapper function to get our settings.
	 *
	 * @since 1.0.0
	 *
	 * @param string $option The option name to look up.
	 * @return string The option value.
	 * @todo Ability to specify different option name and defaults.
	 */
	function dokani_get_setting( $setting ) {
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		return $dokani_settings[ $setting ];
	}
}

if ( ! function_exists( 'dokani_add_store_header_template' ) ) {
	/**
	 * Add store header template for single store
	 *
	 * @since 1.0.0
	 *
	 * @param array $settins.
	 * @return array.
	 */
	if ( function_exists( 'dokan' ) ) {
		add_filter( 'dokan_settings_fields', 'dokani_add_store_header_template' );
		add_filter( 'dokan_admin_localize_script', 'dokani_admin_localize_script' );
	}

	function dokani_add_store_header_template( $settings ) {
		$settings['dokan_appearance']['store_header_template']['options']['layout3'] =  get_template_directory_uri() . '/assets/images/dokani-store-header-template.png';
		return $settings;
	}

	function dokani_admin_localize_script( $args ) {
		$args['store_banner_dimension']['width']  = 1920;
		$args['store_banner_dimension']['height'] = 470;
		return $args;
	}

}




if ( ! function_exists( 'dokani_get_layout' ) ) {
	/**
	 * Get the layout for the current page.
	 *
	 * @since 1.0.0
	 *
	 * @return string The sidebar layout location.
	 */
	function dokani_get_layout() {
		// Get current post
		global $post;

		// Get Customizer options
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			 dokani_get_defaults()
		);

		// Set up the layout variable for pages
		$layout = $dokani_settings['layout_setting'];

		// Set up BuddyPress variable
		$buddypress = false;
		if ( function_exists( 'is_buddypress' ) ) {
			$buddypress = ( is_buddypress() ) ? true : false;
		}

		// If we're on the single post page
		// And if we're not on a BuddyPress page - fixes a bug where BP thinks is_single() is true
		if ( is_single() && ! $buddypress ) {
			$layout = null;
			$layout = $dokani_settings['single_layout_setting'];
		}

		// If we're on the blog, archive, attachment etc..
		if ( is_home() || is_archive() || is_search() || is_tax() ) {
			$layout = null;
			$layout = $dokani_settings['blog_layout_setting'];
		}

		// If we're on the shop page
		if ( is_archive( 'product' ) ) {
			$layout = null;
			$layout = $dokani_settings['shop_layout_setting'];
		}

		// If we're on the single product page
		if ( function_exists( 'is_product' ) && is_product() ) {
			$layout = null;
			$layout = $dokani_settings['single_product_layout_setting'];
		}

		// If we're on the store list page.
		if ( function_exists( 'dokan_is_store_listing' ) && dokan_is_store_listing() ) {
			$layout = null;
			$layout = $dokani_settings['store_list_layout_setting'];
		}

		// If we're on the store page
		if ( function_exists('dokan_is_store_page') && dokan_is_store_page() ) {
			$layout = null;
			$layout = $dokani_settings['store_layout_setting'];
		}

		// Finally, return the layout
		return apply_filters( 'dokani_sidebar_layout', $layout );
	}
}

if ( ! function_exists( 'dokani_get_footer_widgets' ) ) {
	/**
	 * Get the footer widgets for the current page
	 *
	 * @since 1.0.0
	 *
	 * @return int The number of footer widgets.
	 */
	function dokani_get_footer_widgets() {
		// Get current post
		global $post;

		// Get Customizer options
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		// Set up the footer widget variable
		$widgets = $dokani_settings['footer_widget_setting'];

		// If we're not on a single page or post, the metabox hasn't been set
		if ( ! is_singular() ) {
			$widgets_meta = '';
		}

		// Finally, return the layout
		return apply_filters( 'dokani_footer_widgets', $widgets );
	}
}

if ( ! function_exists( 'dokani_show_excerpt' ) ) {
	/**
	 * Figure out if we should show the blog excerpts or full posts
	 * @since 1.0.0
	 */
	function dokani_show_excerpt() {
		// Get current post
		global $post;

		// Get Customizer settings
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		// Check to see if the more tag is being used
		$more_tag = apply_filters( 'dokani_more_tag', strpos( $post->post_content, '<!--more-->' ) );

		// Check the post format
		$format = ( false !== get_post_format() ) ? get_post_format() : 'standard';

		// Get the excerpt setting from the Customizer
		$show_excerpt = ( 'excerpt' == $dokani_settings['post_content'] ) ? true : false;

		// If our post format isn't standard, show the full content
		$show_excerpt = ( 'standard' !== $format ) ? false : $show_excerpt;

		// If the more tag is found, show the full content
		$show_excerpt = ( $more_tag ) ? false : $show_excerpt;

		// If we're on a search results page, show the excerpt
		$show_excerpt = ( is_search() ) ? true : $show_excerpt;

		// Return our value
		return apply_filters( 'dokani_show_excerpt', $show_excerpt );
	}
}

if ( ! function_exists( 'dokani_padding_css' ) ) {
	/**
	 * Shorten our padding/margin values into shorthand form.
	 *
	 * @since 1.0.0
	 *
	 * @param int $top Top spacing.
	 * @param int $right Right spacing.
	 * @param int $bottom Bottom spacing.
	 * @param int $left Left spacing.
	 * @return string Element spacing values.
	 */
	function dokani_padding_css( $top, $right, $bottom, $left ) {
		$padding_top = ( isset( $top ) && '' !== $top ) ? absint( $top ) . 'px ' : '0px ';
		$padding_right = ( isset( $right ) && '' !== $right ) ? absint( $right ) . 'px ' : '0px ';
		$padding_bottom = ( isset( $bottom ) && '' !== $bottom ) ? absint( $bottom ) . 'px ' : '0px ';
		$padding_left = ( isset( $left ) && '' !== $left ) ? absint( $left ) . 'px' : '0px';

		// If all of our values are the same, we can return one value only
		if ( ( absint( $padding_top ) === absint( $padding_right ) ) && ( absint( $padding_right ) === absint( $padding_bottom ) ) && ( absint( $padding_bottom ) === absint( $padding_left ) ) ) {
			return $padding_left;
		}

		return $padding_top . $padding_right . $padding_bottom . $padding_left;
	}
}

if ( ! function_exists( 'dokani_get_link_url' ) ) {
	/**
	 * Return the post URL.
	 *
	 * Falls back to the post permalink if no URL is found in the post.
	 *
	 * @since 1.0.0
	 *
	 * @see get_url_in_content()
	 * @return string The Link format URL.
	 */
	function dokani_get_link_url() {
		$has_url = get_url_in_content( get_the_content() );

		return $has_url ? $has_url : apply_filters( 'the_permalink', get_permalink() );
	}
}

if ( ! function_exists( 'dokani_get_navigation_location' ) ) {
	/**
	 * Get the location of the navigation and filter it.
	 *
	 * @since 1.0.0
	 *
	 * @return string The primary menu location.
	 */
	function dokani_get_navigation_location() {
		return apply_filters( 'dokani_navigation_location', dokani_get_setting( 'nav_position_setting' ) );
	}
}
