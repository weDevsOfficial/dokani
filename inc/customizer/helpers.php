<?php
/**
 * Helper functions for the Customizer.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_is_posts_page' ) ) {
	/**
	 * Check to see if we're on a posts page
	 *
	 * @since 1.3.39
	 */
	function dokanee_is_posts_page() {
		return ( is_home() || is_archive() || is_tax() ) ? true : false;
	}
}

if ( ! function_exists( 'dokanee_is_footer_bar_active' ) ) {
	/**
	 * Check to see if we're using our footer bar widget
	 *
	 * @since 1.3.42
	 */
	function dokanee_is_footer_bar_active() {
		return ( is_active_sidebar( 'footer-bar' ) ) ? true : false;
	}
}

if ( ! function_exists( 'dokanee_is_top_bar_active' ) ) {
	/**
	 * Check to see if the top bar is active
	 *
	 * @since 1.3.45
	 */
	function dokanee_is_top_bar_active() {
		if ( get_theme_mod( 'show_topbar', 'enabled' ) == 'enabled' ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'dokanee_hidden_navigation' ) && function_exists( 'is_customize_preview' ) ) {
	add_action( 'wp_footer', 'dokanee_hidden_navigation' );
	/**
	 * Adds a hidden navigation if no navigation is set
	 * This allows us to use postMessage to position the navigation when it doesn't exist
	 *
	 * @since 1.3.40
	 */
	function dokanee_hidden_navigation() {
		if ( is_customize_preview() && function_exists( 'dokanee_navigation_position' ) ) {
			?>
            <div style="display:none;">
				<?php dokanee_navigation_position(); ?>
            </div>
			<?php
		}
	}
}

if ( ! function_exists( 'dokanee_customize_partial_blogname' ) ) {
	/**
	 * Render the site title for the selective refresh partial.
	 *
	 * @since 1.3.41
	 */
	function dokanee_customize_partial_blogname() {
		bloginfo( 'name' );
	}
}

if ( ! function_exists( 'dokanee_customize_partial_blogdescription' ) ) {
	/**
	 * Render the site tagline for the selective refresh partial.
	 *
	 * @since 1.3.41
	 */
	function dokanee_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
}

if ( ! function_exists( 'dokanee_enqueue_color_palettes' ) ) {
	add_action( 'customize_controls_enqueue_scripts', 'dokanee_enqueue_color_palettes' );
	/**
	 * Add our custom color palettes to the color pickers in the Customizer.
	 *
	 * @since 1.3.42
	 */
	function dokanee_enqueue_color_palettes() {
		// Old versions of WP don't get nice things
		if ( ! function_exists( 'wp_add_inline_script' ) ) {
			return;
		}

		// Grab our palette array and turn it into JS
		$palettes = json_encode( dokanee_get_default_color_palettes() );

		// Add our custom palettes
		// json_encode takes care of escaping
		wp_add_inline_script( 'wp-color-picker',
			'jQuery.wp.wpColorPicker.prototype.options.palettes = ' . $palettes . ';' );
	}
}

if ( ! function_exists( 'dokanee_sanitize_integer' ) ) {
	/**
	 * Sanitize integers.
	 *
	 * @since 1.0.8
	 */
	function dokanee_sanitize_integer( $input ) {
		return absint( $input );
	}
}

if ( ! function_exists( 'dokanee_sanitize_decimal_integer' ) ) {
	/**
	 * Sanitize integers that can use decimals.
	 *
	 * @since 1.3.41
	 */
	function dokanee_sanitize_decimal_integer( $input ) {
		return abs( floatval( $input ) );
	}
}

if ( ! function_exists( 'dokanee_sanitize_checkbox' ) ) {
	/**
	 * Sanitize checkbox values.
	 *
	 * @since 1.0.8
	 */
	function dokanee_sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}

if ( ! function_exists( 'dokanee_sanitize_blog_excerpt' ) ) {
	/**
	 * Sanitize blog excerpt.
	 * Needed because GP Premium calls the control ID which is different from the settings ID.
	 *
	 * @since 1.0.8
	 */
	function dokanee_sanitize_blog_excerpt( $input ) {
		$valid = array(
			'full',
			'excerpt',
		);

		if ( in_array( $input, $valid ) ) {
			return $input;
		} else {
			return 'full';
		}
	}
}

if ( ! function_exists( 'dokanee_sanitize_hex_color' ) ) {
	/**
	 * Sanitize colors.
	 * Allow blank value.
	 *
	 * @since 1.2.9.6
	 */
	function dokanee_sanitize_hex_color( $color ) {
		if ( '' === $color ) {
			return '';
		}

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $color ) ) {
			return $color;
		}

		return '';
	}
}

if ( ! function_exists( 'dokanee_sanitize_choices' ) ) {
	/**
	 * Sanitize choices.
	 *
	 * @since 1.0.0
	 */
	function dokanee_sanitize_choices( $input, $setting ) {
		// Ensure input is a slug
		$input = sanitize_key( $input );

		// Get list of choices from the control
		// associated with the setting
		$choices = $setting->manager->get_control( $setting->id )->choices;

		// If the input is a valid key, return it;
		// otherwise, return the default
		return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
	}
}

if ( ! function_exists( 'dokanee_sanitize_file' ) ) {
	/**
	 * Sanitize files.
	 *
	 * @since 1.0.0
	 */
	function dokanee_sanitize_file( $file, $setting ) {

		//allowed file types
		$mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'svg'          => 'image/svg',
		);

		//check file type from file name
		$file_ext = wp_check_filetype( $file, $mimes );

		//if file has a valid mime type return it, otherwise return default
		return ( $file_ext['ext'] ? $file : $setting->default );
	}
}

if ( ! function_exists( 'is_show_slider' ) ) {
	/**
	 * Check Is slider enable for showing home page
	 *
	 * @since 1.0.0
	 */
	function is_show_slider() {
		if ( get_theme_mod( 'show_slider' ) == 'on' ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'is_show_products_cat_on' ) ) {
	/**
	 * Check Is products category on
	 *
	 * @since 1.0.0
	 */
    function is_show_products_cat_on() {
	    if ( get_theme_mod( 'show_products_cat' ) == 'on' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'is_show_trusted_factors' ) ) {
	/**
	 * Check Is products category on
	 *
	 * @since 1.0.0
	 */
    function is_show_trusted_factors() {
	    if ( get_theme_mod( 'show_trusted_factors_section' ) == 'on' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'is_copyright_with_image' ) ) {
	/**
	 * Check Is copyright text with image
	 *
	 * @since 1.0.0
	 */
    function is_copyright_with_image() {
        if ( get_theme_mod( 'dokanee_footer_structure' ) == 'copyright_with_image' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'dokanee_is_footer_bar_layout_1' ) ) {
	/**
	 * Check is footer bar layout 1
	 *
	 * @since 1.0.0
	 */
    function dokanee_is_footer_bar_layout_1() {
        if ( get_theme_mod( 'footer_bar_layout' ) == 'layout-1' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'dokanee_is_footer_bar_layout_2' ) ) {
	/**
	 * Check is footer bar layout 2
	 *
	 * @since 1.0.0
	 */
    function dokanee_is_footer_bar_layout_2() {
        if ( get_theme_mod( 'footer_bar_layout' ) == 'layout-2' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'dokanee_is_footer_bar_layout_disabled' ) ) {
	/**
	 * Check is footer bar layout disabled
	 *
	 * @since 1.0.0
	 */
    function dokanee_is_footer_bar_layout_disabled() {
        if ( get_theme_mod( 'footer_bar_layout' ) == 'disabled' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'dokanee_is_footer_bar_layout_not_disabled' ) ) {
	/**
	 * Check is footer bar layout not disabled
	 *
	 * @since 1.0.0
	 */
    function dokanee_is_footer_bar_layout_not_disabled() {
        if ( get_theme_mod( 'footer_bar_layout' ) == 'layout-1' || get_theme_mod( 'footer_bar_layout' ) == 'layout-2' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'dokanee_is_footer_widget_layout_not_disabled' ) ) {
	/**
	 * Check is footer bar layout not disabled
	 *
	 * @since 1.0.0
	 */
    function dokanee_is_footer_widget_layout_not_disabled() {
        if ( get_theme_mod( 'footer_widget_layout' ) == 'layout-1' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'is_section1_type_text' ) ) {
	/**
	 * Check is section 1 content type text
	 *
	 * @since 1.0.0
	 */
    function is_section1_type_text() {
	    if ( dokanee_is_footer_bar_layout_disabled() ) {
		    return false;
	    }

        if ( get_theme_mod( 'dokanee_footer_bar_section1_type' ) == 'text' ) {
		    return true;
	    }

	    return false;
    }
}

if ( ! function_exists( 'is_section2_type_text' ) ) {
	/**
	 * Check is section 2 content type text
	 *
	 * @since 1.0.0
	 */
	function is_section2_type_text() {
	    if ( dokanee_is_footer_bar_layout_disabled() ) {
	        return false;
        }
		if ( get_theme_mod( 'dokanee_footer_bar_section2_type' ) == 'text' ) {
			return true;
		}

		return false;
	}
}

if ( ! function_exists( 'is_nav_position_bellow_header' ) ) {
	/**
	 * Check is section 2 content type text
	 *
	 * @since 1.0.0
	 */
	function is_nav_position_bellow_header() {
	    if ( dokanee_get_setting( 'nav_position_setting' ) === 'nav-below-header' ) {
			return true;
		}

		return false;
	}
}


/**
 * Sanitize our Google Font variants
 *
 * @since 2.0
 */
function dokanee_sanitize_variants( $input ) {
	if ( is_array( $input ) ) {
		$input = implode( ',', $input );
	}

	return sanitize_text_field( $input );
}

/**
 * Sanitize radio buttons
 *
 * @since 1.0.0
 */
function dokanee_sanitize_radio( $input, $setting ){

	//input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only
	$input = sanitize_key($input);

	//get the list of possible radio box options
	$choices = $setting->manager->get_control( $setting->id )->choices;

	//return input if valid or return default option
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

}
add_action( 'customize_controls_enqueue_scripts', 'dokanee_do_control_inline_scripts', 100 );

/**
 * Add misc inline scripts to our controls.
 *
 * We don't want to add these to the controls themselves, as they will be repeated
 * each time the control is initialized.
 *
 * @since 2.0
 */
function dokanee_do_control_inline_scripts() {
	wp_localize_script( 'dokanee-typography-customizer', 'gp_customize',
		array( 'nonce' => wp_create_nonce( 'gp_customize_nonce' ) ) );
	wp_localize_script( 'dokanee-typography-customizer', 'typography_defaults', dokanee_typography_default_fonts() );
}

/**
 * Check to see if we have a logo or not.
 *
 * Used as an active callback. Calling has_custom_logo creates a PHP notice for
 * multisite users.
 *
 * @since 2.0.1
 */
function dokanee_has_custom_logo_callback() {
	if ( get_theme_mod( 'custom_logo' ) ) {
		return true;
	}

	return false;
}
