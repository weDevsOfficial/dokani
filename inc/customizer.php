<?php
/**
 * Builds our Customizer controls.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'customize_register', 'dokanee_set_customizer_helpers', 1 );
/**
 * Set up helpers early so they're always available.
 * Other modules might need access to them at some point.
 *
 * @since 2.0
 */
function dokanee_set_customizer_helpers( $wp_customize ) {
	// Load helpers
	require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
}

if ( ! function_exists( 'dokanee_customize_register' ) ) {
	add_action( 'customize_register', 'dokanee_customize_register' );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function dokanee_customize_register( $wp_customize ) {
		// Get our default values
		$defaults       = dokanee_get_defaults();
		$defaults_color = dokanee_get_color_defaults();

		// Load helpers
		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';

		if ( $wp_customize->get_control( 'blogdescription' ) ) {
			$wp_customize->get_control( 'blogdescription' )->priority  = 3;
			$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'blogname' ) ) {
			$wp_customize->get_control( 'blogname' )->priority  = 1;
			$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
		}

		if ( $wp_customize->get_control( 'custom_logo' ) ) {
			$wp_customize->get_setting( 'custom_logo' )->transport = 'refresh';
		}

		// Add control types so controls can be built using JS
		if ( method_exists( $wp_customize, 'register_control_type' ) ) {
			$wp_customize->register_control_type( 'Generate_Customize_Misc_Control' );
			$wp_customize->register_control_type( 'Generate_Range_Slider_Control' );
		}

		// Add upsell section type
		if ( method_exists( $wp_customize, 'register_section_type' ) ) {
			$wp_customize->register_section_type( 'Dokanee_Upsell_Section' );
		}

		// Add selective refresh to site title and description
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector'        => '.main-title a',
				'render_callback' => 'dokanee_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'dokanee_customize_partial_blogdescription',
			) );
		}

		// Remove title
		$wp_customize->add_setting(
			'dokanee_settings[hide_title]',
			array(
				'default'           => $defaults['hide_title'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[hide_title]',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Hide site title', 'dokanee' ),
				'section'  => 'title_tagline',
				'priority' => 2
			)
		);

		// Remove tagline
		$wp_customize->add_setting(
			'dokanee_settings[hide_tagline]',
			array(
				'default'           => $defaults['hide_tagline'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[hide_tagline]',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Hide site tagline', 'dokanee' ),
				'section'  => 'title_tagline',
				'priority' => 4
			)
		);

		// Only show this option if we're not using WordPress 4.5
		if ( ! function_exists( 'the_custom_logo' ) ) {
			$wp_customize->add_setting(
				'dokanee_settings[logo]',
				array(
					'default'           => $defaults['logo'],
					'type'              => 'option',
					'sanitize_callback' => 'esc_url_raw'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'dokanee_settings[logo]',
					array(
						'label'       => __( 'Site Logo', 'dokanee' ),
						'description' => __( 'Upload your logo to replace the default Logo (dimension : 180 X 50)', 'dokanee' ),
						'section'     => 'title_tagline',
						'settings'    => 'dokanee_settings[logo]'
					)
				)
			);
		}

		$wp_customize->add_setting(
			'dokanee_settings[retina_logo]',
			array(
				'default'           => $defaults['retina_logo'],
				'type'              => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'dokanee_settings[retina_logo]',
				array(
					'label'           => __( 'Retina Logo', 'dokanee' ),
					'section'         => 'title_tagline',
					'settings'        => 'dokanee_settings[retina_logo]',
					'active_callback' => 'dokanee_has_custom_logo_callback'
				)
			)
		);

		/**
		 * Add the Colors Panel
		 */

		$wp_customize->add_section(
			'body_section',
			array(
				'title'      => $wp_customize->get_panel( 'dokanee_colors_panel' ) ? __( 'Body', 'dokanee' ) : __( 'Colors', 'dokanee' ),
				'capability' => 'edit_theme_options',
				'priority'   => 30,
				'panel'      => $wp_customize->get_panel( 'dokanee_colors_panel' ) ? 'dokanee_colors_panel' : false,
			)
		);

		// add background_color
		$wp_customize->add_setting(
			'dokanee_settings[background_color]', array(
				'default'           => $defaults['background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[background_color]',
				array(
					'label'    => __( 'Background Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[background_color]'
				)
			)
		);

		// add text_color
		$wp_customize->add_setting(
			'dokanee_settings[text_color]', array(
				'default'           => $defaults['text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[text_color]',
				array(
					'label'    => __( 'Text Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[text_color]'
				)
			)
		);

		// add link_color
		$wp_customize->add_setting(
			'dokanee_settings[link_color]', array(
				'default'           => $defaults['link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[link_color]',
				array(
					'label'    => __( 'Link Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[link_color]'
				)
			)
		);

		// add link_color_hover
		$wp_customize->add_setting(
			'dokanee_settings[link_color_hover]', array(
				'default'           => $defaults['link_color_hover'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[link_color_hover]',
				array(
					'label'    => __( 'Link Hover Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[link_color_hover]'
				)
			)
		);

		// add link_color_visited
		$wp_customize->add_setting(
			'dokanee_settings[link_color_visited]', array(
				'default'           => $defaults['link_color_visited'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[link_color_visited]',
				array(
					'label'    => __( 'Link Color Visited', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[link_color_visited]'
				)
			)
		);

		// add top_bar_background_color
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_background_color]', array(
				'default'           => $defaults_color['top_bar_background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[top_bar_background_color]',
				array(
					'label'    => __( 'Topbar Background Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[top_bar_background_color]'
				)
			)
		);

		// add top_bar_text_color
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_text_color]', array(
				'default'           => $defaults_color['top_bar_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[top_bar_text_color]',
				array(
					'label'    => __( 'Topbar Text Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[top_bar_text_color]'
				)
			)
		);

		// add top_bar_link_color_hover
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_link_color_hover]', array(
				'default'           => $defaults_color['top_bar_link_color_hover'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[top_bar_link_color_hover]',
				array(
					'label'    => __( 'Topbar Text Hover Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[top_bar_link_color_hover]'
				)
			)
		);

		// add navigation_background_color
		$wp_customize->add_setting(
			'dokanee_settings[navigation_background_color]', array(
				'default'           => $defaults_color['navigation_background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[navigation_background_color]',
				array(
					'label'    => __( 'Navigation Background Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[navigation_background_color]'
				)
			)
		);

		// add navigation_text_color
		$wp_customize->add_setting(
			'dokanee_settings[navigation_text_color]', array(
				'default'           => $defaults_color['navigation_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[navigation_text_color]',
				array(
					'label'    => __( 'Navigation Text Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[navigation_text_color]'
				)
			)
		);

		// add sidebar_widget_title_color
		$wp_customize->add_setting(
			'dokanee_settings[sidebar_widget_title_color]', array(
				'default'           => $defaults_color['sidebar_widget_title_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[sidebar_widget_title_color]',
				array(
					'label'    => __( 'Sidebar Widget Title Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[sidebar_widget_title_color]'
				)
			)
		);

		// add footer_widgets_bg_color
		$wp_customize->add_setting(
			'dokanee_settings[footer_widgets_bg_color]', array(
				'default'           => '#ffffff',
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[footer_widgets_bg_color]',
				array(
					'label'    => __( 'Footer Widget Background Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[footer_widgets_bg_color]',
				)
			)
		);

		// add bottom_bar_bg_color
		$wp_customize->add_setting(
			'dokanee_settings[bottom_bar_bg_color]', array(
				'default'           => '#ffffff',
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_hex_color',
				'transport'         => 'postMessage',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokanee_settings[bottom_bar_bg_color]',
				array(
					'label'    => __( 'Bottom Bar Background Color', 'dokanee' ),
					'section'  => 'body_section',
					'settings' => 'dokanee_settings[bottom_bar_bg_color]',
				)
			)
		);


		/**
		 * Add Front Page Settings
		 */
		$wp_customize->add_section(
			'dokanee_frontpage_section',
			array(
				'title'    => __( 'Front Page', 'dokanee' ),
				'priority' => 10
			)
		);

		// show slider
		$wp_customize->add_setting( 'show_slider', array( 'default' => 'on' ) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_slider',
				array(
					'label'   => __( 'Show Slider on home page', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show products category section
		$wp_customize->add_setting( 'show_products_cat', array( 'default' => 'on' ) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_products_cat',
				array(
					'label'   => __( 'Show products category section', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show featured
		$wp_customize->add_setting( 'show_featured', array(
			'default' => 'on'
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_featured',
				array(
					'label'   => __( 'Show Featured Products on Homepage', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show latest
		$wp_customize->add_setting( 'show_latest_pro', array( 'default' => 'on' ) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_latest_pro',
				array(
					'label'   => __( 'Show Latest Products on Homepage', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show best selling
		$wp_customize->add_setting( 'show_best_selling', array( 'default' => 'on' ) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_best_selling',
				array(
					'label'   => __( 'Show Best Selling Products on Homepage', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show store list section
		$wp_customize->add_setting( 'show_store_list', array( 'default' => 'on' ) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_store_list',
				array(
					'label'   => __( 'Show Store List Section', 'dokanee' ),
					'section' => 'dokanee_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);


		/**
		 * Add the Footer Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokanee_footer_panel' ) ) {
				$wp_customize->add_panel( 'dokanee_footer_panel', array(
					'priority' => 22,
					'title'    => __( 'Footer', 'dokanee' ),
				) );
			}
		}

		// footer trusted_factors section
		$wp_customize->add_section(
			'dokanee_footer_trusted_factors',
			array(
				'title'    => 'Trusted Factors',
				'priority' => 15,
				'panel'    => 'dokanee_footer_panel',
			)
		);

		// show trusted factors
		$wp_customize->add_setting(
			'show_trusted_factors_section',
			array(
				'default' => 'on'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_trusted_factors_section',
				array(
					'label'   => __( 'Show Trusted Factors Section on WooCommerce pages', 'dokanee' ),
					'section' => 'dokanee_footer_trusted_factors',
					'type'    => 'checkbox',
				)
			)
		);

		// trusted fact 1
		$wp_customize->add_setting(
			'dokanee_trusted_fact_1_icon',
			array(
				'default'           => 'flaticon flaticon-transport',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_1_icon',
				array(
					'label'    => __( 'Fact 1 Icon ( Class Name )', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_1_icon',
					'type'     => 'text',
				)
			)
		);

		$wp_customize->add_setting(
			'dokanee_trusted_fact_1',
			array(
				'default'           => __( 'Fast & Free Delivery', 'dokanee' ),
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_1',
				array(
					'label'    => __( 'Fact 1 Text', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_1',
					'type'     => 'textarea',
				)
			)
		);

		// trusted fact 2
		$wp_customize->add_setting(
			'dokanee_trusted_fact_2_icon',
			array(
				'default'           => 'flaticon flaticon-business-2',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_2_icon',
				array(
					'label'    => __( 'Fact 2 Icon ( Class Name )', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_2_icon',
					'type'     => 'text',
				)
			)
		);

		$wp_customize->add_setting(
			'dokanee_trusted_fact_2',
			array(
				'default'           => __( 'Safe & Secure Payment', 'dokanee' ),
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_2',
				array(
					'label'    => __( 'Fact 2 Text', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_2',
					'type'     => 'textarea',
				)
			)
		);

		// trusted fact 3
		$wp_customize->add_setting(
			'dokanee_trusted_fact_3_icon',
			array(
				'default'           => 'flaticon flaticon-technology',
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_3_icon',
				array(
					'label'    => __( 'Fact 3 Icon ( Class Name )', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_3_icon',
					'type'     => 'text',
				)
			)
		);

		$wp_customize->add_setting(
			'dokanee_trusted_fact_3',
			array(
				'default'           => __( '100% Money Back Guarantee', 'dokanee' ),
				'sanitize_callback' => 'wp_kses_post',
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_trusted_fact_3',
				array(
					'label'    => __( 'Fact 3 Text', 'dokanee' ),
					'section'  => 'dokanee_footer_trusted_factors',
					'settings' => 'dokanee_trusted_fact_3',
					'type'     => 'textarea',
				)
			)
		);

		// footer bottom bar section
		$wp_customize->add_section(
			'dokanee_footer_bottom_bar',
			array(
				'title'    => 'Bottom Bar',
				'priority' => 20,
				'panel'    => 'dokanee_footer_panel',
			)
		);

		$wp_customize->add_setting( 'dokanee_footer_content', array(
			'default'           => __( 'Copyright 2018 | dokanee by weDevs', 'dokanee' ),
			'sanitize_callback' => 'wp_kses_post',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokanee_footer_content',
				array(
					'label'    => __( 'Footer Content', 'dokanee' ),
					'section'  => 'dokanee_footer_bottom_bar',
					'settings' => 'dokanee_footer_content',
					'type'     => 'textarea',
				)
			)
		);

		// payment option settings
		$wp_customize->add_setting(
			'payment_options',
			array(
				'default'    => '',
				'type'       => 'theme_mod',
				'capability' => 'edit_theme_options',
			)
		);

		// payment option control
		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'payment_options',
				array(
					'label'    => __( 'Payment Options img', 'dokanee' ),
					'section'  => 'dokanee_footer_bottom_bar',
					'settings' => 'payment_options'
				)
			)
		);


		/**
		 * Add the Layout Panel
		 */

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokanee_layout_panel' ) ) {
				$wp_customize->add_panel( 'dokanee_layout_panel', array(
					'priority' => 25,
					'title'    => __( 'Layout', 'dokanee' ),
				) );
			}
		}

		// Add Container section
		$wp_customize->add_section(
			'dokanee_layout_container',
			array(
				'title'    => __( 'Container', 'dokanee' ),
				'priority' => 10,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Container width
		$wp_customize->add_setting(
			'dokanee_settings[container_width]',
			array(
				'default'           => $defaults['container_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_integer',
				'transport'         => 'postMessage'
			)
		);

		// Container control
		$wp_customize->add_control(
			new Generate_Range_Slider_Control(
				$wp_customize,
				'dokanee_settings[container_width]',
				array(
					'type'     => 'dokanee-range-slider',
					'label'    => __( 'Container Width', 'dokanee' ),
					'section'  => 'dokanee_layout_container',
					'settings' => array(
						'desktop' => 'dokanee_settings[container_width]',
					),
					'choices'  => array(
						'desktop' => array(
							'min'  => 700,
							'max'  => 2000,
							'step' => 5,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 0,
				)
			)
		);

		// Add Top Bar section
		$wp_customize->add_section(
			'dokanee_top_bar',
			array(
				'title'    => __( 'Top Bar', 'dokanee' ),
				'priority' => 15,
				'panel'    => 'dokanee_layout_panel',
			)
		);

		// Add Top Bar width
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_width]',
			array(
				'default'           => $defaults['top_bar_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'dokanee_settings[top_bar_width]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Width', 'dokanee' ),
				'section'         => 'dokanee_top_bar',
				'choices'         => array(
					'full'      => __( 'Full', 'dokanee' ),
					'contained' => __( 'Contained', 'dokanee' )
				),
				'settings'        => 'dokanee_settings[top_bar_width]',
				'priority'        => 5,
				'active_callback' => 'dokanee_is_top_bar_active',
			)
		);

		// Add Top Bar inner width
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_inner_width]',
			array(
				'default'           => $defaults['top_bar_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'dokanee_settings[top_bar_inner_width]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Inner Width', 'dokanee' ),
				'section'         => 'dokanee_top_bar',
				'choices'         => array(
					'full'      => __( 'Full', 'dokanee' ),
					'contained' => __( 'Contained', 'dokanee' )
				),
				'settings'        => 'dokanee_settings[top_bar_inner_width]',
				'priority'        => 10,
				'active_callback' => 'dokanee_is_top_bar_active',
			)
		);

		// Add top_bar_alignment
		$wp_customize->add_setting(
			'dokanee_settings[top_bar_alignment]',
			array(
				'default'           => $defaults['top_bar_alignment'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[top_bar_alignment]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Alignment', 'dokanee' ),
				'section'         => 'dokanee_top_bar',
				'choices'         => array(
					'left'   => __( 'Left', 'dokanee' ),
					'center' => __( 'Center', 'dokanee' ),
					'right'  => __( 'Right', 'dokanee' )
				),
				'settings'        => 'dokanee_settings[top_bar_alignment]',
				'priority'        => 15,
				'active_callback' => 'dokanee_is_top_bar_active',
			)
		);

		// Add cart_position_setting
		$wp_customize->add_setting(
			'dokanee_settings[cart_position_setting]',
			array(
				'default'           => $defaults['cart_position_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[cart_position_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Cart Position', 'dokanee' ),
				'section'  => 'dokanee_top_bar',
				'choices'  => array(
					'cart-topbar' => __( 'Show Cart in Topbar', 'dokanee' ),
					'cart-nav'    => __( 'Show Cart in Navigation', 'dokanee' ),
					'cart-search' => __( 'Show Cart after Search', 'dokanee' )
				),
				'settings' => 'dokanee_settings[cart_position_setting]',
				'priority' => 20,
			)
		);

		// Add Header section
		$wp_customize->add_section(
			'dokanee_layout_header',
			array(
				'title'    => __( 'Header', 'dokanee' ),
				'priority' => 20,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Add Header Layout setting
		$wp_customize->add_setting(
			'dokanee_settings[header_layout_setting]',
			array(
				'default'           => $defaults['header_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'dokanee_settings[header_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Header Width', 'dokanee' ),
				'section'  => 'dokanee_layout_header',
				'choices'  => array(
					'fluid-header'     => __( 'Full', 'dokanee' ),
					'contained-header' => __( 'Contained', 'dokanee' )
				),
				'settings' => 'dokanee_settings[header_layout_setting]',
				'priority' => 5
			)
		);

		// Add Inside Header Layout setting
		$wp_customize->add_setting(
			'dokanee_settings[header_inner_width]',
			array(
				'default'           => $defaults['header_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'dokanee_settings[header_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Header Width', 'dokanee' ),
				'section'  => 'dokanee_layout_header',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokanee' ),
					'full-width' => __( 'Full', 'dokanee' )
				),
				'settings' => 'dokanee_settings[header_inner_width]',
				'priority' => 6
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokanee_settings[header_alignment_setting]',
			array(
				'default'           => $defaults['header_alignment_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokanee_settings[header_alignment_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Header Alignment', 'dokanee' ),
				'section'  => 'dokanee_layout_header',
				'choices'  => array(
					'left'   => __( 'Left', 'dokanee' ),
					'center' => __( 'Center', 'dokanee' ),
					'right'  => __( 'Right', 'dokanee' )
				),
				'settings' => 'dokanee_settings[header_alignment_setting]',
				'priority' => 10
			)
		);

		$wp_customize->add_section(
			'dokanee_layout_navigation',
			array(
				'title'    => __( 'Primary Navigation', 'dokanee' ),
				'priority' => 30,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Add nav_position_setting
		$wp_customize->add_setting(
			'dokanee_settings[nav_position_setting]',
			array(
				'default'           => $defaults['nav_position_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => ( '' !== dokanee_get_setting( 'nav_position_setting' ) ) ? 'postMessage' : 'refresh'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[nav_position_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Location', 'dokanee' ),
				'section'  => 'dokanee_layout_navigation',
				'choices'  => array(
					'nav-float-right'   => __( 'Header', 'dokanee' ),
					'nav-below-header'  => __( 'Below Header', 'dokanee' )
				),
				'settings' => 'dokanee_settings[nav_position_setting]',
				'priority' => 15
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokanee_settings[nav_layout_setting]',
			array(
				'default'           => $defaults['nav_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokanee_settings[nav_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Width', 'dokanee' ),
				'section'  => 'dokanee_layout_navigation',
				'choices'  => array(
					'fluid-nav'     => __( 'Full', 'dokanee' ),
					'contained-nav' => __( 'Contained', 'dokanee' )
				),
				'settings' => 'dokanee_settings[nav_layout_setting]',
				'priority' => 16
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokanee_settings[nav_inner_width]',
			array(
				'default'           => $defaults['nav_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokanee_settings[nav_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Navigation Width', 'dokanee' ),
				'section'  => 'dokanee_layout_navigation',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokanee' ),
					'full-width' => __( 'Full', 'dokanee' )
				),
				'settings' => 'dokanee_settings[nav_inner_width]',
				'priority' => 17
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokanee_settings[nav_alignment_setting]',
			array(
				'default'           => $defaults['nav_alignment_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokanee_settings[nav_alignment_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Alignment', 'dokanee' ),
				'section'  => 'dokanee_layout_navigation',
				'choices'  => array(
					'left'   => __( 'Left', 'dokanee' ),
					'center' => __( 'Center', 'dokanee' ),
					'right'  => __( 'Right', 'dokanee' )
				),
				'settings' => 'dokanee_settings[nav_alignment_setting]',
				'priority' => 20
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokanee_settings[nav_dropdown_type]',
			array(
				'default'           => $defaults['nav_dropdown_type'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokanee_settings[nav_dropdown_type]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Dropdown', 'dokanee' ),
				'section'  => 'dokanee_layout_navigation',
				'choices'  => array(
					'hover'       => __( 'Hover', 'dokanee' ),
					'click'       => __( 'Click - Menu Item', 'dokanee' ),
					'click-arrow' => __( 'Click - Arrow', 'dokanee' )
				),
				'settings' => 'dokanee_settings[nav_dropdown_type]',
				'priority' => 22
			)
		);

		// Add content setting
		$wp_customize->add_setting(
			'dokanee_settings[content_layout_setting]',
			array(
				'default'           => $defaults['content_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokanee_settings[content_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Content Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_container',
				'choices'  => array(
					'separate-containers' => __( 'Separate Containers', 'dokanee' ),
					'one-container'       => __( 'One Container', 'dokanee' )
				),
				'settings' => 'dokanee_settings[content_layout_setting]',
				'priority' => 25
			)
		);

		$wp_customize->add_section(
			'dokanee_layout_sidebars',
			array(
				'title'    => __( 'Sidebars', 'dokanee' ),
				'priority' => 40,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'dokanee_settings[layout_setting]',
			array(
				'default'           => $defaults['layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'dokanee_settings[layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'dokanee' ),
					'both-left'     => __( 'Sidebar / Sidebar / Content', 'dokanee' ),
					'both-right'    => __( 'Content / Sidebar / Sidebar', 'dokanee' )
				),
				'settings' => 'dokanee_settings[layout_setting]',
				'priority' => 30
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'dokanee_settings[blog_layout_setting]',
			array(
				'default'           => $defaults['blog_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'dokanee_settings[blog_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Blog Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'dokanee' ),
					'both-left'     => __( 'Sidebar / Sidebar / Content', 'dokanee' ),
					'both-right'    => __( 'Content / Sidebar / Sidebar', 'dokanee' )
				),
				'settings' => 'dokanee_settings[blog_layout_setting]',
				'priority' => 35
			)
		);

		// Setting - Single Post Layout
		$wp_customize->add_setting(
			'dokanee_settings[single_layout_setting]',
			array(
				'default'           => $defaults['single_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);
		$wp_customize->add_control(
			'dokanee_settings[single_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Single Post Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' ),
					'both-sidebars' => __( 'Sidebar / Content / Sidebar', 'dokanee' ),
					'both-left'     => __( 'Sidebar / Sidebar / Content', 'dokanee' ),
					'both-right'    => __( 'Content / Sidebar / Sidebar', 'dokanee' )
				),
				'settings' => 'dokanee_settings[single_layout_setting]',
				'priority' => 36
			)
		);

		// Setting - Shop Layout
		$wp_customize->add_setting(
			'dokanee_settings[shop_layout_setting]',
			array(
				'default'           => $defaults['shop_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);
		$wp_customize->add_control(
			'dokanee_settings[shop_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Shop Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' )
				),
				'settings' => 'dokanee_settings[shop_layout_setting]',
				'priority' => 37
			)
		);

		// Setting - Single Product Layout
		$wp_customize->add_setting(
			'dokanee_settings[single_product_layout_setting]',
			array(
				'default'           => $defaults['single_product_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);
		$wp_customize->add_control(
			'dokanee_settings[single_product_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Single Product Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' )
				),
				'settings' => 'dokanee_settings[single_product_layout_setting]',
				'priority' => 38
			)
		);

		// Setting - Store List Layout
		$wp_customize->add_setting(
			'dokanee_settings[store_list_layout_setting]',
			array(
				'default'           => $defaults['store_list_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);
		$wp_customize->add_control(
			'dokanee_settings[store_list_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Store List Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' )
				),
				'settings' => 'dokanee_settings[store_list_layout_setting]',
				'priority' => 39
			)
		);

		// Setting - Store Layout
		$wp_customize->add_setting(
			'dokanee_settings[store_layout_setting]',
			array(
				'default'           => $defaults['store_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);
		$wp_customize->add_control(
			'dokanee_settings[store_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Store Sidebar Layout', 'dokanee' ),
				'section'  => 'dokanee_layout_sidebars',
				'choices'  => array(
					'left-sidebar'  => __( 'Sidebar / Content', 'dokanee' ),
					'right-sidebar' => __( 'Content / Sidebar', 'dokanee' ),
					'no-sidebar'    => __( 'Content (no sidebars)', 'dokanee' )
				),
				'settings' => 'dokanee_settings[store_layout_setting]',
				'priority' => 40
			)
		);

		$wp_customize->add_section(
			'dokanee_layout_footer',
			array(
				'title'    => __( 'Footer', 'dokanee' ),
				'priority' => 50,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'dokanee_settings[footer_layout_setting]',
			array(
				'default'           => $defaults['footer_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokanee_settings[footer_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Footer Width', 'dokanee' ),
				'section'  => 'dokanee_layout_footer',
				'choices'  => array(
					'fluid-footer'     => __( 'Full', 'dokanee' ),
					'contained-footer' => __( 'Contained', 'dokanee' )
				),
				'settings' => 'dokanee_settings[footer_layout_setting]',
				'priority' => 40
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'dokanee_settings[footer_inner_width]',
			array(
				'default'           => $defaults['footer_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokanee_settings[footer_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Footer Width', 'dokanee' ),
				'section'  => 'dokanee_layout_footer',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokanee' ),
					'full-width' => __( 'Full', 'dokanee' )
				),
				'settings' => 'dokanee_settings[footer_inner_width]',
				'priority' => 41
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'dokanee_settings[footer_widget_setting]',
			array(
				'default'           => $defaults['footer_widget_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'dokanee_settings[footer_widget_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Footer Widgets', 'dokanee' ),
				'section'  => 'dokanee_layout_footer',
				'choices'  => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5'
				),
				'settings' => 'dokanee_settings[footer_widget_setting]',
				'priority' => 45
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'dokanee_settings[footer_bar_alignment]',
			array(
				'default'           => $defaults['footer_bar_alignment'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'dokanee_settings[footer_bar_alignment]',
			array(
				'type'            => 'select',
				'label'           => __( 'Footer Bar Alignment', 'dokanee' ),
				'section'         => 'dokanee_layout_footer',
				'choices'         => array(
					'left'   => __( 'Left', 'dokanee' ),
					'center' => __( 'Center', 'dokanee' ),
					'right'  => __( 'Right', 'dokanee' )
				),
				'settings'        => 'dokanee_settings[footer_bar_alignment]',
				'priority'        => 47,
				'active_callback' => 'dokanee_is_footer_bar_active'
			)
		);

		// Add back to top setting
		$wp_customize->add_setting(
			'dokanee_settings[back_to_top]',
			array(
				'default'           => $defaults['back_to_top'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_choices'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokanee_settings[back_to_top]',
			array(
				'type'     => 'select',
				'label'    => __( 'Back to Top Button', 'dokanee' ),
				'section'  => 'dokanee_layout_footer',
				'choices'  => array(
					'enable' => __( 'Enable', 'dokanee' ),
					''       => __( 'Disable', 'dokanee' )
				),
				'settings' => 'dokanee_settings[back_to_top]',
				'priority' => 50
			)
		);

		// Add Layout section
		$wp_customize->add_section(
			'dokanee_blog_section',
			array(
				'title'    => __( 'Blog', 'dokanee' ),
				'priority' => 55,
				'panel'    => 'dokanee_layout_panel'
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'dokanee_settings[post_content]',
			array(
				'default'           => $defaults['post_content'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_blog_excerpt'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'blog_content_control',
			array(
				'type'     => 'select',
				'label'    => __( 'Content Type', 'dokanee' ),
				'section'  => 'dokanee_blog_section',
				'choices'  => array(
					'full'    => __( 'Full', 'dokanee' ),
					'excerpt' => __( 'Excerpt', 'dokanee' )
				),
				'settings' => 'dokanee_settings[post_content]',
				'priority' => 10
			)
		);

		// Add Performance section
		$wp_customize->add_section(
			'dokanee_general_section',
			array(
				'title'    => __( 'General', 'dokanee' ),
				'priority' => 99
			)
		);

		if ( ! apply_filters( 'dokanee_fontawesome_essentials', false ) ) {
			$wp_customize->add_setting(
				'dokanee_settings[font_awesome_essentials]',
				array(
					'default'           => $defaults['font_awesome_essentials'],
					'type'              => 'option',
					'sanitize_callback' => 'dokanee_sanitize_checkbox'
				)
			);

			$wp_customize->add_control(
				'dokanee_settings[font_awesome_essentials]',
				array(
					'type'        => 'checkbox',
					'label'       => __( 'Load essential icons only', 'dokanee' ),
					'description' => __( 'Load essential Font Awesome icons instead of the full library.', 'dokanee' ),
					'section'     => 'dokanee_general_section',
					'settings'    => 'dokanee_settings[font_awesome_essentials]',
				)
			);
		}

		$wp_customize->add_setting(
			'dokanee_settings[dynamic_css_cache]',
			array(
				'default'           => $defaults['dynamic_css_cache'],
				'type'              => 'option',
				'sanitize_callback' => 'dokanee_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokanee_settings[dynamic_css_cache]',
			array(
				'type'        => 'checkbox',
				'label'       => __( 'Cache dynamic CSS', 'dokanee' ),
				'description' => __( 'Cache CSS generated by your options to boost performance.', 'dokanee' ),
				'section'     => 'dokanee_general_section',
			)
		);
	}
}

if ( ! function_exists( 'dokanee_customizer_live_preview' ) ) {
	add_action( 'customize_preview_init', 'dokanee_customizer_live_preview', 100 );
	/**
	 * Add our live preview scripts
	 *
	 * @since 0.1
	 */
	function dokanee_customizer_live_preview() {
		wp_enqueue_script( 'dokanee-themecustomizer', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/customizer-live-preview.js', array( 'customize-preview' ), GENERATE_VERSION, true );
	}
}
