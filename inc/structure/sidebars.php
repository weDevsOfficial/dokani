<?php
/**
 * Build the sidebars.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_construct_sidebars' ) ) {
	/**
	 * Construct the sidebars.
	 *
	 * @since 0.1
	 */
	function dokanee_construct_sidebars() {
		$layout = dokanee_get_layout();

		// When to show the right sidebar.
		$rs = array( 'right-sidebar', 'both-sidebars', 'both-right', 'both-left' );

		// When to show the left sidebar.
		$ls = array( 'left-sidebar', 'both-sidebars', 'both-right', 'both-left' );

		// If left sidebar, show it.
		if ( in_array( $layout, $ls ) ) {
			get_sidebar( 'left' );
		}

		// If right sidebar, show it.
		if ( in_array( $layout, $rs ) ) {
			get_sidebar();
		}
	}

	/**
	 * The below hook was removed in 2.0, but we'll keep the call here so child themes
	 * don't lose their sidebar when they update the theme.
	 */
	 add_action( 'dokanee_sidebars', 'dokanee_construct_sidebars' );
}
