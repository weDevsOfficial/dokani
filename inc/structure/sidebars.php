<?php
/**
 * Build the sidebars.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_construct_sidebars' ) ) {
	/**
	 * Construct the sidebars.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_sidebars( $sidebar_template = '' ) {

	    if ( $sidebar_template === 'left-sidebar' ) {
            get_sidebar( 'left' );
            return;
        }

        if ( $sidebar_template === 'right-sidebar' ) {
            get_sidebar();
            return;
        }

		$layout = dokani_get_layout();

		// When to show the right sidebar.
		$rs = array( 'right-sidebar', 'both-sidebars', 'both-right', 'both-left' );

		// When to show the left sidebar.
		$ls = array( 'left-sidebar', 'both-sidebars', 'both-right', 'both-left' );

		// If left sidebar, show it.
		if ( in_array( $layout, $ls ) ) {
			get_sidebar( 'left' );
		}

		// If right sidebar, show it.
		if ( in_array( $layout, $rs ) || $sidebar_template === 'right-sidebar' ) {
			get_sidebar();
		}
	}

	/**
	 * The below hook was removed in 2.0, but we'll keep the call here so child themes
	 * don't lose their sidebar when they update the theme.
	 */
	 add_action( 'dokani_sidebars', 'dokani_construct_sidebars' );
}
