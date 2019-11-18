<?php
/**
 * Builds our Customizer controls.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( class_exists( 'WP_Customize_Panel' ) ) {
	class dokani_WP_Customize_Panel extends WP_Customize_Panel {
		public $panel;
		public $type = 'dokani_panel';
		public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'type', 'panel', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			return $array;
		}
	}
}

if ( class_exists( 'WP_Customize_Section' ) ) {
	class dokani_WP_Customize_Section extends WP_Customize_Section {
		public $section;
		public $type = 'dokani_section';
		public function json() {
			$array = wp_array_slice_assoc( (array) $this, array( 'id', 'description', 'priority', 'panel', 'type', 'description_hidden', 'section', ) );
			$array['title'] = html_entity_decode( $this->title, ENT_QUOTES, get_bloginfo( 'charset' ) );
			$array['content'] = $this->get_content();
			$array['active'] = $this->active();
			$array['instanceNumber'] = $this->instance_number;
			if ( $this->panel ) {
				$array['customizeAction'] = sprintf( 'Customizing &#9656; %s', esc_html( $this->manager->get_panel( $this->panel )->title ) );
			} else {
				$array['customizeAction'] = 'Customizing';
			}
			return $array;
		}
	}
}

function dokani_customize_controls_scripts() {
	wp_enqueue_script( 'dokani-customize-controls', get_theme_file_uri( '/assets/js/extend-customizer.js' ), array(), '1.0', true );
}

add_action( 'customize_controls_enqueue_scripts', 'dokani_customize_controls_scripts' );

function dokani_customize_controls_styles() {
	wp_enqueue_style( 'dokani-customize-controls-styles', get_theme_file_uri( '/assets/css/admin/customize-controls.css' ), array(), '1.0' );
}

add_action( 'customize_controls_print_styles', 'dokani_customize_controls_styles' );



if ( ! function_exists( 'dokani_customize_register' ) ) {
	add_action( 'customize_register', 'dokani_customize_register' );
	/**
	 * Add our base options to the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
	 */
	function dokani_customize_register( $wp_customize ) {
		// Get our default values
		$defaults       = dokani_get_defaults();
		$defaults_color = dokani_get_color_defaults();

		// Load helpers
		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/customizer-helpers.php';
		require_once trailingslashit( get_template_directory() ) . 'inc/customizer/controls/class-radio-control.php';

		$wp_customize->register_panel_type( 'dokani_WP_Customize_Panel' );
		$wp_customize->register_section_type( 'dokani_WP_Customize_Section' );
		$wp_customize->register_control_type( 'dokani_Customize_Control_Radio_Image' );

		/**
		 * Move default title_tagline section inside header panel
		 */
		if ( $wp_customize->get_section('title_tagline' ) ) {
			$wp_customize->get_section('title_tagline' )->panel = 'dokani_header_panel';
			$wp_customize->get_section('title_tagline' )->priority = 1;
		}

		if ( $wp_customize->get_section( 'static_front_page' ) ) {
			$wp_customize->get_section( 'static_front_page' )->priority = 14;
		}


		/**
		 * Add Global Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_global_panel' ) ) {
				$dokani_global_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_global_panel', array(
					'priority' => 10,
					'title'    => __( 'Global', 'dokani' ),
				));
				$wp_customize->add_panel( $dokani_global_panel );
			}
		}


		/**
		 * Add Header Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_header_panel' ) ) {
				$dokani_header_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_header_panel', array(
					'priority' => 11,
					'title'    => __( 'Header', 'dokani' ),
				));
				$wp_customize->add_panel( $dokani_header_panel );
			}
		}

		/**
		 * Add Typograpy Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_typography_panel' ) ) {
				$dokani_typography_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_typography_panel', array(
					'priority' => 11,
					'title'    => __( 'Typograpy', 'dokani' ),
					'panel'    => 'dokani_global_panel'
				));
				$wp_customize->add_panel( $dokani_typography_panel );
			}
		}

		/**
		 * Add Sidebar Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_sidebar_panel' ) ) {
				$dokani_sidebar_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_sidebar_panel', array(
					'priority' => 11,
					'title'    => __( 'Sidebars', 'dokani' ),
				));
				$wp_customize->add_panel( $dokani_sidebar_panel );
			}
		}

		/**
		 * Add Sidebars settings Section
		 */
		$wp_customize->add_section(
			'dokani_layout_sidebars',
			array(
				'title'    => __( 'Layout Settings', 'dokani' ),
				'priority' => 12,
				'panel'    => 'dokani_sidebar_panel'
			)
		);

		/**
		 * Add Sidebars advance settings Section
		 */
		$wp_customize->add_section(
			'dokani_sidebar_list_style',
			array(
				'title'    => __( 'Sidebar List Style', 'dokani' ),
				'priority' => 12,
				'panel'    => 'dokani_sidebar_panel'
			)
		);

		/**
		 * Add Footer Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_footer_panel' ) ) {
				$wp_customize->add_panel( 'dokani_footer_panel', array(
					'priority' => 13,
					'title'    => __( 'Footer', 'dokani' ),
				) );
			}
		}

		/**
		 * Add Blog Panel
		 */
		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_blog_panel' ) ) {
				$wp_customize->add_panel( 'dokani_blog_panel', array(
					'priority' => 20,
					'title'    => __( 'Blog', 'dokani' ),
				) );
			}
		}


		// Add Layout section
		$wp_customize->add_section(
			'dokani_blog_section',
			array(
				'title'    => __( 'Blog / Archive', 'dokani' ),
				'priority' => 20,
				'panel'    => 'dokani_blog_panel',
			)
		);

		// Add Layout section
		$wp_customize->add_section(
			'dokani_blog_single_section',
			array(
				'title'    => __( 'Single', 'dokani' ),
				'priority' => 20,
				'panel'    => 'dokani_blog_panel',
			)
		);


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
			$wp_customize->register_section_type( 'dokani_Upsell_Section' );
		}

		// Add selective refresh to site title and description
		if ( isset( $wp_customize->selective_refresh ) ) {
			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector'        => '.main-title a',
				'render_callback' => 'dokani_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'dokani_customize_partial_blogdescription',
			) );
		}

		// Remove title
		$wp_customize->add_setting(
			'dokani_settings[hide_title]',
			array(
				'default'           => $defaults['hide_title'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokani_settings[hide_title]',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Hide site title', 'dokani' ),
				'section'  => 'title_tagline',
				'priority' => 2
			)
		);

		// Remove tagline
		$wp_customize->add_setting(
			'dokani_settings[hide_tagline]',
			array(
				'default'           => $defaults['hide_tagline'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokani_settings[hide_tagline]',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Hide site tagline', 'dokani' ),
				'section'  => 'title_tagline',
				'priority' => 4
			)
		);

		// Only show this option if we're not using WordPress 4.5
		if ( ! function_exists( 'the_custom_logo' ) ) {
			$wp_customize->add_setting(
				'dokani_settings[logo]',
				array(
					'default'           => $defaults['logo'],
					'type'              => 'option',
					'sanitize_callback' => 'esc_url_raw'
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Image_Control(
					$wp_customize,
					'dokani_settings[logo]',
					array(
						'label'       => __( 'Site Logo', 'dokani' ),
						'description' => __( 'Upload your logo to replace the default Logo (dimension : 180 X 50)', 'dokani' ),
						'section'     => 'title_tagline',
						'settings'    => 'dokani_settings[logo]'
					)
				)
			);
		}

		$wp_customize->add_setting(
			'dokani_settings[retina_logo]',
			array(
				'default'           => $defaults['retina_logo'],
				'type'              => 'option',
				'sanitize_callback' => 'esc_url_raw'
			)
		);

		$wp_customize->add_control(
			new WP_Customize_Image_Control(
				$wp_customize,
				'dokani_settings[retina_logo]',
				array(
					'label'           => __( 'Retina Logo', 'dokani' ),
					'section'         => 'title_tagline',
					'settings'        => 'dokani_settings[retina_logo]',
					'active_callback' => 'dokani_has_custom_logo_callback'
				)
			)
		);

		/**
		 * Add the Colors Section
		 */

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_base_color_panel' ) ) {
				$dokani_colors_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_base_color_panel', array(
					'priority' => 11,
					'title'    => __( 'Colors', 'dokani' ),
					'panel'    => $wp_customize->get_panel( 'dokani_global_panel' ) ? 'dokani_global_panel' : false,
				));
				$wp_customize->add_panel( $dokani_colors_panel );
			}
		}

		$wp_customize->add_section(
			'dokani_base_color_section',
			array(
				'title'    => __( 'Base Colors', 'dokani' ),
				'panel'    => 'dokani_base_color_panel',
			)
		);

		if( function_exists( 'dokan' ) ) {
			$wp_customize->add_section(
				'dokani_store_color_section',
				array(
					'title'    => __( 'Store Header Template Colors', 'dokani' ),
					'panel'    => 'dokani_base_color_panel',
				)
			);

			// store header title color setting
			$wp_customize->add_setting(
				'dokani_settings[store_header_title_color]', array(
					'default'           => $defaults_color['store_header_title_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[store_header_title_color]',
					array(
						'label'    => __( 'Title Color', 'dokani' ),
						'section'  => 'dokani_store_color_section',
						'settings' => 'dokani_settings[store_header_title_color]'
					)
				)
			);

			// store header text color setting
			$wp_customize->add_setting(
				'dokani_settings[store_header_text_color]', array(
					'default'           => $defaults_color['store_header_text_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[store_header_text_color]',
					array(
						'label'    => __( 'Text Color', 'dokani' ),
						'section'  => 'dokani_store_color_section',
						'settings' => 'dokani_settings[store_header_text_color]'
					)
				)
			);

			// store header link color setting
			$wp_customize->add_setting(
				'dokani_settings[store_header_link_color]', array(
					'default'           => $defaults_color['store_header_link_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[store_header_link_color]',
					array(
						'label'    => __( 'Link Color', 'dokani' ),
						'section'  => 'dokani_store_color_section',
						'settings' => 'dokani_settings[store_header_link_color]'
					)
				)
			);

			// store header link_hover color setting
			$wp_customize->add_setting(
				'dokani_settings[store_header_link_hover_color]', array(
					'default'           => $defaults_color['store_header_link_hover_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[store_header_link_hover_color]',
					array(
						'label'    => __( 'Link Hover Color', 'dokani' ),
						'section'  => 'dokani_store_color_section',
						'settings' => 'dokani_settings[store_header_link_hover_color]'
					)
				)
			);
		}

		// add theme color setting
		$wp_customize->add_setting(
			'dokani_settings[theme_color]', array(
				'default'           => $defaults_color['theme_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);

		// add theme color control
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[theme_color]',
				array(
					'label'    => __( 'Theme Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[theme_color]'
				)
			)
		);

		// add background_color
		$wp_customize->add_setting(
			'dokani_settings[background_color]', array(
				'default'           => $defaults['background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[background_color]',
				array(
					'label'    => __( 'Background Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[background_color]'
				)
			)
		);

		// add text_color
		$wp_customize->add_setting(
			'dokani_settings[text_color]', array(
				'default'           => $defaults['text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[text_color]',
				array(
					'label'    => __( 'Text Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[text_color]'
				)
			)
		);

		// add heading color
		$wp_customize->add_setting(
			'dokani_settings[heading_color]', array(
				'default'           => $defaults_color['heading_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[heading_color]',
				array(
					'label'    => __( 'Heading Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[heading_color]'
				)
			)
		);

		// add link_color
		$wp_customize->add_setting(
			'dokani_settings[link_color]', array(
				'default'           => $defaults['link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[link_color]',
				array(
					'label'    => __( 'Link Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[link_color]'
				)
			)
		);

		// add link_color_hover
		$wp_customize->add_setting(
			'dokani_settings[link_color_hover]', array(
				'default'           => $defaults['link_color_hover'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[link_color_hover]',
				array(
					'label'    => __( 'Link Hover Color', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[link_color_hover]'
				)
			)
		);

		// add link_color_visited
		$wp_customize->add_setting(
			'dokani_settings[link_color_visited]', array(
				'default'           => $defaults['link_color_visited'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[link_color_visited]',
				array(
					'label'    => __( 'Link Color Visited', 'dokani' ),
					'section'  => 'dokani_base_color_section',
					'settings' => 'dokani_settings[link_color_visited]'
				)
			)
		);




		/**
		 * Add Front Page Settings
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			$wp_customize->add_section(
				'dokani_frontpage_section',
				array(
					'title'    => __( 'Front Page', 'dokani' ),
					'priority' => 15
				)
			);
		}

		// show slider
		$wp_customize->add_setting( 'show_slider', array(
				'sanitize_callback'=> 'dokani_sanitize_checkbox',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_slider',
				array(
					'label'   => __( 'Show Slider on home page', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// Select Plugin slider
		$wp_customize->add_setting( 'plugin_slider_shortcode', array(
			'sanitize_callback' => 'wp_kses_post',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'plugin_slider_shortcode',
				array(
					'label'   => __( 'Shortcode of the slider', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'textarea',
					'input_attrs' => array(
						'placeholder' => __( 'Paste shortcode here.', 'dokani' ),
					),
					'active_callback' => 'is_show_slider',
				)
			)
		);

		// show products category section
		$wp_customize->add_setting( 'show_products_cat', array(
			'default'           => 'on',
			'sanitize_callback' => 'dokani_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_products_cat',
				array(
					'label'   => __( 'Show products category section', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show products category section
		$wp_customize->add_setting( 'products_cat_counter', array(
			'default'           => '5',
			'sanitize_callback' => 'dokani_sanitize_integer',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'products_cat_counter',
				array(
					'label'   => __( 'How many category will show?', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'number',
					'active_callback' => 'is_show_products_cat_on'
				)
			)
		);

		// show featured
		$wp_customize->add_setting( 'show_featured', array(
			'default'           => 'on',
			'sanitize_callback' => 'dokani_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_featured',
				array(
					'label'   => __( 'Show Featured Products on Homepage', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show latest
		$wp_customize->add_setting( 'show_latest_pro', array(
			'default'           => 'on',
			'sanitize_callback' => 'dokani_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_latest_pro',
				array(
					'label'   => __( 'Show Latest Products on Homepage', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show best selling
		$wp_customize->add_setting( 'show_best_selling', array(
			'default'           => 'on',
			'sanitize_callback' => 'dokani_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_best_selling',
				array(
					'label'   => __( 'Show Best Selling Products on Homepage', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show store list section
		$wp_customize->add_setting( 'show_store_list', array(
			'default'           => 'on',
			'sanitize_callback' => 'dokani_sanitize_checkbox',
		) );
		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'show_store_list',
				array(
					'label'   => __( 'Show Store List Section', 'dokani' ),
					'section' => 'dokani_frontpage_section',
					'type'    => 'checkbox',
				)
			)
		);

		// show trusted factors
		if ( class_exists( 'WooCommerce' ) ) {
			// footer trusted_factors section
			$wp_customize->add_section(
				'dokani_footer_trusted_factors',
				array(
					'title'    => 'Trusted Factors',
					'priority' => 15,
					'panel'    => 'dokani_footer_panel',
				)
			);

			$wp_customize->add_setting('show_trusted_factors_section', array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox',
			) );

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'show_trusted_factors_section',
					array(
						'label'   => __( 'Show Trusted Factors Section', 'dokani' ),
						'section' => 'dokani_footer_trusted_factors',
						'type'    => 'checkbox',
					)
				)
			);

			// trusted fact 1
			$wp_customize->add_setting(
				'dokani_trusted_fact_1_icon',
				array(
					'default'           => 'flaticon flaticon-transport',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_1_icon',
					array(
						'label'           => __( 'Fact 1 Icon ( Class Name )', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_1_icon',
						'type'            => 'text',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			$wp_customize->add_setting(
				'dokani_trusted_fact_1',
				array(
					'default'           => __( 'Fast & Free Delivery', 'dokani' ),
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_1',
					array(
						'label'           => __( 'Fact 1 Text', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_1',
						'type'            => 'textarea',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			// trusted fact 2
			$wp_customize->add_setting(
				'dokani_trusted_fact_2_icon',
				array(
					'default'           => 'flaticon flaticon-business-2',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_2_icon',
					array(
						'label'           => __( 'Fact 2 Icon ( Class Name )', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_2_icon',
						'type'            => 'text',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			$wp_customize->add_setting(
				'dokani_trusted_fact_2',
				array(
					'default'           => __( 'Safe & Secure Payment', 'dokani' ),
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_2',
					array(
						'label'           => __( 'Fact 2 Text', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_2',
						'type'            => 'textarea',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			// trusted fact 3
			$wp_customize->add_setting(
				'dokani_trusted_fact_3_icon',
				array(
					'default'           => 'flaticon flaticon-technology',
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_3_icon',
					array(
						'label'           => __( 'Fact 3 Icon ( Class Name )', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_3_icon',
						'type'            => 'text',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			$wp_customize->add_setting(
				'dokani_trusted_fact_3',
				array(
					'default'           => __( '100% Money Back Guarantee', 'dokani' ),
					'sanitize_callback' => 'wp_kses_post',
				)
			);

			$wp_customize->add_control(
				new WP_Customize_Control(
					$wp_customize,
					'dokani_trusted_fact_3',
					array(
						'label'           => __( 'Fact 3 Text', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'settings'        => 'dokani_trusted_fact_3',
						'type'            => 'textarea',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			// add trusted factor section background color 1
			$wp_customize->add_setting(
				'dokani_settings[trusted_factor_bg_color1]', array(
					'default'           => $defaults_color['trusted_factor_bg_color1'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[trusted_factor_bg_color1]',
					array(
						'label'           => __( 'Background Color 1', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			// add trusted factor section background color 1
			$wp_customize->add_setting(
				'dokani_settings[trusted_factor_bg_color2]', array(
					'default'           => $defaults_color['trusted_factor_bg_color2'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[trusted_factor_bg_color2]',
					array(
						'label'           => __( 'Background Color 2', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			// add trusted factor section text color
			$wp_customize->add_setting(
				'dokani_settings[trusted_factor_text_color]', array(
					'default'           => $defaults_color['trusted_factor_text_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[trusted_factor_text_color]',
					array(
						'label'           => __( 'Content Color', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			$wp_customize->add_setting(
				'dokani_settings[trusted_factor_icon_color]', array(
					'default'           => $defaults_color['trusted_factor_icon_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[trusted_factor_icon_color]',
					array(
						'label'           => __( 'Icon Color', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);

			$wp_customize->add_setting(
				'dokani_settings[trusted_factor_icon_bg_color]', array(
					'default'           => $defaults_color['trusted_factor_icon_bg_color'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_hex_color',
					'transport'         => 'refresh',
				)
			);
			$wp_customize->add_control(
				new WP_Customize_Color_Control(
					$wp_customize,
					'dokani_settings[trusted_factor_icon_bg_color]',
					array(
						'label'           => __( 'Icon Background Color', 'dokani' ),
						'section'         => 'dokani_footer_trusted_factors',
						'active_callback' => 'is_show_trusted_factors',
					)
				)
			);



		}

		// footer bottom bar section
		$wp_customize->add_section(
			'dokani_footer_bottom_bar',
			array(
				'title'    => 'Bottom Bar',
				'priority' => 20,
				'panel'    => 'dokani_footer_panel',
			)
		);


		// Add footer bar layout setting.
		$wp_customize->add_setting(
			'footer_bar_layout',
			array(
				'default'           => 'layout-2',
				'sanitize_callback' => 'dokani_sanitize_radio',
			)
		);

		// Add the layout control.
		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'footer_bar_layout',
				array(
					'label'    => esc_html__( 'Layout', 'dokani' ),
					'section'  => 'dokani_footer_bottom_bar',
					'choices'  => array(
						'disabled' => array(
							'label' => esc_html__( 'Disabled', 'dokani' ),
							'url'   => '%s/assets/images/customizer/disabled.svg',
						),
						'layout-1' => array(
							'label' => esc_html__( 'Footer Bar Layout 1', 'dokani' ),
							'url'   => '%s/assets/images/customizer/footer-bottom-bar-layout1.svg',
						),
						'layout-2' => array(
							'label' => esc_html__( 'Footer Bar Layout 2', 'dokani' ),
							'url'   => '%s/assets/images/customizer/footer-bottom-bar-layout2.svg',
						)
					)
				)
			)
		);

		$wp_customize->add_setting( 'dokani_footer_bar_section1_type', array(
			'default'           => 'text',
			'sanitize_callback' => 'dokani_sanitize_choices',
		) );

		$wp_customize->add_control(
			'dokani_footer_bar_section1_type',
			array(
				'type'            => 'select',
				'label'           => __( 'Section 1 Content Type', 'dokani' ),
				'section'         => 'dokani_footer_bottom_bar',
				'choices'         => array(
					'none'        => __( 'None', 'dokani' ),
					'text'        => __( 'Text', 'dokani' ),
					'widget'      => __( 'Widget', 'dokani' ),
					'footer_menu' => __( 'Footer Menu', 'dokani' ),
				),
				'active_callback' => 'dokani_is_footer_bar_layout_not_disabled',
			)
		);

		$wp_customize->add_setting( 'dokani_footer_bar_section1_content', array(
			'default'           => __( 'Copyright 2019 | dokani by weDevs', 'dokani' ),
			'sanitize_callback' => 'wp_kses_post',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokani_footer_bar_section1_content',
				array(
					'label'           => __( 'Section 1 Custom Text', 'dokani' ),
					'section'         => 'dokani_footer_bottom_bar',
					'type'            => 'textarea',
					'active_callback' => 'is_section1_type_text'
				)
			)
		);


		$wp_customize->add_setting( 'dokani_footer_bar_section2_type', array(
			'default'           => 'footer_menu',
			'sanitize_callback' => 'dokani_sanitize_choices',
		) );

		$wp_customize->add_control(
			'dokani_footer_bar_section2_type',
			array(
				'type'            => 'select',
				'label'           => __( 'Section 2 Content Type', 'dokani' ),
				'section'         => 'dokani_footer_bottom_bar',
				'choices'         => array(
					'none'        => __( 'None', 'dokani' ),
					'text'        => __( 'Text', 'dokani' ),
					'widget'      => __( 'Widget', 'dokani' ),
					'footer_menu' => __( 'Footer Menu', 'dokani' ),
				),
				'active_callback' => 'dokani_is_footer_bar_layout_not_disabled',
			)
		);


		$wp_customize->add_setting( 'dokani_footer_bar_section2_content', array(
			'default'           => __( 'Copyright 2019 | dokani by weDevs', 'dokani' ),
			'sanitize_callback' => 'wp_kses_post',
		) );

		$wp_customize->add_control(
			new WP_Customize_Control(
				$wp_customize,
				'dokani_footer_bar_section2_content',
				array(
					'label'           => __( 'Section 2 Custom Text', 'dokani' ),
					'section'         => 'dokani_footer_bottom_bar',
					'type'            => 'textarea',
					'active_callback' => 'is_section2_type_text'
				)
			)
		);

		// add footer bottom_bar_bg_color
		$wp_customize->add_setting(
			'dokani_settings[footer_bottom_bar_border_color]', array(
				'default'           => $defaults_color['footer_bottom_bar_border_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_bottom_bar_border_color]',
				array(
					'label'           => __( 'Top Border Color', 'dokani' ),
					'section'         => 'dokani_footer_bottom_bar',
					'active_callback' => 'dokani_is_footer_bar_layout_not_disabled',
					'settings'		  => 'dokani_settings[footer_bottom_bar_border_color]',
				)
			)
		);

		// add footer bottom_bar_bg_color
		$wp_customize->add_setting(
			'dokani_settings[footer_bottom_bar_bg_color]', array(
				'default'           => $defaults_color['footer_bottom_bar_bg_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_bottom_bar_bg_color]',
				array(
					'label'           => __( 'Background Color', 'dokani' ),
					'section'         => 'dokani_footer_bottom_bar',
					'active_callback' => 'dokani_is_footer_bar_layout_not_disabled',
					'settings'		  => 'dokani_settings[footer_bottom_bar_bg_color]',
				)
			)
		);

		// add footer bottom_bar_content_color
		$wp_customize->add_setting(
			'dokani_settings[footer_bottom_bar_text_color]', array(
				'default'           => $defaults_color['footer_bottom_bar_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_bottom_bar_text_color]',
				array(
					'label'           => __( 'Text Color', 'dokani' ),
					'section'         => 'dokani_footer_bottom_bar',
					'active_callback' => 'dokani_is_footer_bar_layout_not_disabled',
					'settings'		  => 'dokani_settings[footer_bottom_bar_text_color]',
				)
			)
		);

		// add footer bottom_bar_content_color
		$wp_customize->add_setting(
			'dokani_settings[footer_bottom_bar_link_color]', array(
				'default'           => $defaults_color['footer_bottom_bar_link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_bottom_bar_link_color]',
				array(
					'label'             => __( 'Link Color', 'dokani' ),
					'section'           => 'dokani_footer_bottom_bar',
					'active_callback'   => 'dokani_is_footer_bar_layout_not_disabled',
				)
			)
		);

		// add footer bottom_bar_content_color
		$wp_customize->add_setting(
			'dokani_settings[footer_bottom_bar_hover_color]', array(
				'default'           => $defaults_color['footer_bottom_bar_hover_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_bottom_bar_hover_color]',
				array(
					'label'             => __( 'Link Hover Color', 'dokani' ),
					'section'           => 'dokani_footer_bottom_bar',
					'active_callback'   => 'dokani_is_footer_bar_layout_not_disabled',
				)
			)
		);


		/**
		 * Add the Layout Panel
		 */

		if ( class_exists( 'WP_Customize_Panel' ) ) {
			if ( ! $wp_customize->get_panel( 'dokani_layout_panel' ) ) {
				$dokani_layout_panel = new dokani_WP_Customize_Panel( $wp_customize, 'dokani_layout_panel', array(
					'priority' => 25,
					'title'    => __( 'Layout', 'dokani' ),
					'panel' => 'dokani_global_panel',
				));
				$wp_customize->add_panel( $dokani_layout_panel );
			}
		}

		// Add Container section
		$wp_customize->add_section(
			'dokani_layout_panel',
			array(
				'title'    => __( 'Layout', 'dokani' ),
				'priority' => 10,
				'panel'    => 'dokani_global_panel'
			)
		);

		// Add Container section
		$wp_customize->add_section(
			'dokani_layout_container',
			array(
				'title'    => __( 'Container', 'dokani' ),
				'priority' => 10,
				'panel'    => 'dokani_global_panel'
			)
		);

		// Container width
		$wp_customize->add_setting(
			'dokani_settings[container_width]',
			array(
				'default'           => $defaults['container_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_integer',
				'transport'         => 'postMessage'
			)
		);

		// Container control
		$wp_customize->add_control(
			new Generate_Range_Slider_Control(
				$wp_customize,
				'dokani_settings[container_width]',
				array(
					'type'     => 'dokani-range-slider',
					'label'    => __( 'Container Width', 'dokani' ),
					'section'  => 'dokani_layout_container',
					'settings' => array(
						'desktop' => 'dokani_settings[container_width]',
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
			'dokani_top_bar',
			array(
				'title'    => __( 'Top Bar', 'dokani' ),
				'priority' => 15,
				'panel'    => 'dokani_header_panel',
			)
		);

		// Add Top Bar width
		$wp_customize->add_setting(
			'show_topbar',
			array(
				'default'           => 'enabled',
				'sanitize_callback' => 'dokani_sanitize_radio',
			)
		);

		// Add the layout control.
		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'show_topbar',
				array(
					'label'    => esc_html__( 'Layout', 'dokani' ),
					'section'  => 'dokani_top_bar',
					'choices'  => array(
						'disabled' => array(
							'label' => esc_html__( 'Disabled', 'dokani' ),
							'url'   => '%s/assets/images/customizer/disabled.svg',
						),
						'enabled' => array(
							'label' => esc_html__( 'Footer Widgets', 'dokani' ),
							'url'   => '%s/assets/images/customizer/header-top-bar.svg',
						)
					)
				)
			)
		);


		// Add Top Bar width
		$wp_customize->add_setting(
			'dokani_settings[top_bar_width]',
			array(
				'default'           => $defaults['top_bar_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'dokani_settings[top_bar_width]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Width', 'dokani' ),
				'section'         => 'dokani_top_bar',
				'choices'         => array(
					'full'      => __( 'Full', 'dokani' ),
					'contained' => __( 'Contained', 'dokani' )
				),
				'settings'        => 'dokani_settings[top_bar_width]',
				'active_callback' => 'dokani_is_top_bar_active',
			)
		);

		// Add Top Bar inner width
		$wp_customize->add_setting(
			'dokani_settings[top_bar_inner_width]',
			array(
				'default'           => $defaults['top_bar_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Top Bar width control
		$wp_customize->add_control(
			'dokani_settings[top_bar_inner_width]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Inner Width', 'dokani' ),
				'section'         => 'dokani_top_bar',
				'choices'         => array(
					'full'      => __( 'Full', 'dokani' ),
					'contained' => __( 'Contained', 'dokani' )
				),
				'settings'        => 'dokani_settings[top_bar_inner_width]',
				'priority'        => 10,
				'active_callback' => 'dokani_is_top_bar_active',
			)
		);

		// Add top_bar_alignment
		$wp_customize->add_setting(
			'dokani_settings[top_bar_alignment]',
			array(
				'default'           => $defaults['top_bar_alignment'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		$wp_customize->add_control(
			'dokani_settings[top_bar_alignment]',
			array(
				'type'            => 'select',
				'label'           => __( 'Top Bar Alignment', 'dokani' ),
				'section'         => 'dokani_top_bar',
				'choices'         => array(
					'left'   => __( 'Left', 'dokani' ),
					'center' => __( 'Center', 'dokani' ),
					'right'  => __( 'Right', 'dokani' )
				),
				'settings'        => 'dokani_settings[top_bar_alignment]',
				'active_callback' => 'dokani_is_top_bar_active',
			)
		);

		// Add cart_position_setting
		$wp_customize->add_setting(
			'dokani_settings[cart_position_setting]',
			array(
				'default'           => $defaults['cart_position_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh'
			)
		);

		$wp_customize->add_control(
			'dokani_settings[cart_position_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Cart Position', 'dokani' ),
				'section'  => 'dokani_top_bar',
				'choices'  => array(
					'cart-topbar' => __( 'Show Cart in Topbar', 'dokani' ),
					'cart-nav'    => __( 'Show Cart in Navigation', 'dokani' ),
					'cart-search' => __( 'Show Cart after Search', 'dokani' )
				),
				'settings' => 'dokani_settings[cart_position_setting]',
			)
		);

		// add top_bar_background_color
		$wp_customize->add_setting(
			'dokani_settings[top_bar_background_color]', array(
				'default'           => $defaults_color['top_bar_background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[top_bar_background_color]',
				array(
					'label'    => __( 'Topbar Background Color', 'dokani' ),
					'section'  => 'dokani_top_bar',
					'settings' => 'dokani_settings[top_bar_background_color]',
					'active_callback' => 'dokani_is_top_bar_active',
				)
			)
		);

		// add top_bar_text_color
		$wp_customize->add_setting(
			'dokani_settings[top_bar_text_color]', array(
				'default'           => $defaults_color['top_bar_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[top_bar_text_color]',
				array(
					'label'    => __( 'Topbar Link Color', 'dokani' ),
					'section'  => 'dokani_top_bar',
					'settings' => 'dokani_settings[top_bar_text_color]',
					'active_callback' => 'dokani_is_top_bar_active',
				)
			)
		);

		// add top_bar_link_color_hover
		$wp_customize->add_setting(
			'dokani_settings[top_bar_link_color_hover]', array(
				'default'           => $defaults_color['top_bar_link_color_hover'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[top_bar_link_color_hover]',
				array(
					'label'    => __( 'Topbar Link Hover Color', 'dokani' ),
					'section'  => 'dokani_top_bar',
					'settings' => 'dokani_settings[top_bar_link_color_hover]',
					'active_callback' => 'dokani_is_top_bar_active',
				)
			)
		);

		// Add Header section
		$wp_customize->add_section(
			'dokani_layout_header',
			array(
				'title'    => __( 'Header Layout', 'dokani' ),
				'priority' => 20,
				'panel'    => 'dokani_header_panel'
			)
		);

		if ( function_exists( 'dokan' ) ) {
			// Show product category menu
			$wp_customize->add_setting(
				'show_product_cateogry_menu',
				array(
					'default'           => 'on',
					'sanitize_callback' => 'dokani_sanitize_checkbox',
					'transport'         => 'refresh'
				)
			);

			// Add Header Layout control
			$wp_customize->add_control(
				'show_product_cateogry_menu',
				array(
					'type'     => 'checkbox',
					'label'    => __( 'Show Product Category menu', 'dokani' ),
					'section'  => 'dokani_layout_header',
				)
			);
		}

		// Add Header Layout setting
		$wp_customize->add_setting(
			'dokani_settings[header_layout_setting]',
			array(
				'default'           => $defaults['header_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'dokani_settings[header_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Header Width', 'dokani' ),
				'section'  => 'dokani_layout_header',
				'choices'  => array(
					'fluid-header'     => __( 'Full', 'dokani' ),
					'contained-header' => __( 'Contained', 'dokani' )
				),
				'settings' => 'dokani_settings[header_layout_setting]',
			)
		);

		// Add Inside Header Layout setting
		$wp_customize->add_setting(
			'dokani_settings[header_inner_width]',
			array(
				'default'           => $defaults['header_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add Header Layout control
		$wp_customize->add_control(
			'dokani_settings[header_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Header Width', 'dokani' ),
				'section'  => 'dokani_layout_header',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokani' ),
					'full-width' => __( 'Full', 'dokani' )
				),
				'settings' => 'dokani_settings[header_inner_width]',
			)
		);

		// add header_background_color
		$wp_customize->add_setting(
			'dokani_settings[header_background_color]', array(
				'default'           => $defaults_color['header_background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[header_background_color]',
				array(
					'label'    => __( 'Header Background Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[header_background_color]'
				)
			)
		);

		// add header_text_color
		$wp_customize->add_setting(
			'dokani_settings[header_text_color]', array(
				'default'           => $defaults_color['header_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[header_text_color]',
				array(
					'label'    => __( 'Header Text Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[header_text_color]',
				)
			)
		);

		// add header_link_color
		$wp_customize->add_setting(
			'dokani_settings[header_link_color]', array(
				'default'           => $defaults_color['header_link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[header_link_color]',
				array(
					'label'    => __( 'Header Link Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[header_link_color]',
				)
			)
		);

		// add header_link_color
		$wp_customize->add_setting(
			'dokani_settings[header_link_hover_color]', array(
				'default'           => $defaults_color['header_link_hover_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[header_link_hover_color]',
				array(
					'label'    => __( 'Header Link Hover Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[header_link_hover_color]',
				)
			)
		);

		// add site_title_color
		$wp_customize->add_setting(
			'dokani_settings[site_title_color]', array(
				'default'           => $defaults_color['site_title_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[site_title_color]',
				array(
					'label'    => __( 'Site Title Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[site_title_color]',
				)
			)
		);

		// add site_tagline_color
		$wp_customize->add_setting(
			'dokani_settings[site_tagline_color]', array(
				'default'           => $defaults_color['site_tagline_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[site_tagline_color]',
				array(
					'label'    => __( 'Site Tagline Color', 'dokani' ),
					'section'  => 'dokani_layout_header',
					'settings' => 'dokani_settings[site_tagline_color]',
				)
			)
		);



		$wp_customize->add_section(
			'dokani_layout_navigation',
			array(
				'title'    => __( 'Primary Navigation', 'dokani' ),
				'priority' => 30,
				'panel'    => 'dokani_header_panel'
			)
		);

		// Add nav_position_setting
		$wp_customize->add_setting(
			'dokani_settings[nav_position_setting]',
			array(
				'default'           => $defaults['nav_position_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'dokani_settings[nav_position_setting]',
				array(
					'label'    => esc_html__( 'Navigation Location', 'dokani' ),
					'section'  => 'dokani_layout_navigation',
					'choices'  => array(
						'nav-float-right' => array(
							'label' => esc_html__( 'Navigation Inside Header', 'dokani' ),
							'url'   => '%s/assets/images/customizer/header-only.svg',
						),
						'nav-below-header' => array(
							'label' => esc_html__( 'Navigation Below Header', 'dokani' ),
							'url'   => '%s/assets/images/customizer/header-below-nav.svg',
						)
					),
					'settings' => 'dokani_settings[nav_position_setting]',

				)
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokani_settings[nav_layout_setting]',
			array(
				'default'           => $defaults['nav_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokani_settings[nav_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Width', 'dokani' ),
				'section'  => 'dokani_layout_navigation',
				'choices'  => array(
					'fluid-nav'     => __( 'Full', 'dokani' ),
					'contained-nav' => __( 'Contained', 'dokani' )
				),
				'settings' => 'dokani_settings[nav_layout_setting]',
				'active_callback' => 'is_nav_position_bellow_header',
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokani_settings[nav_inner_width]',
			array(
				'default'           => $defaults['nav_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokani_settings[nav_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Navigation Width', 'dokani' ),
				'section'  => 'dokani_layout_navigation',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokani' ),
					'full-width' => __( 'Full', 'dokani' )
				),
				'settings' => 'dokani_settings[nav_inner_width]',
				'active_callback' => 'is_nav_position_bellow_header',
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokani_settings[nav_alignment_setting]',
			array(
				'default'           => $defaults['nav_alignment_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokani_settings[nav_alignment_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Alignment', 'dokani' ),
				'section'  => 'dokani_layout_navigation',
				'choices'  => array(
					'left'   => __( 'Left', 'dokani' ),
					'center' => __( 'Center', 'dokani' ),
					'right'  => __( 'Right', 'dokani' )
				),
				'settings' => 'dokani_settings[nav_alignment_setting]',
				'active_callback' => 'is_nav_position_bellow_header',
			)
		);

		// Add navigation setting
		$wp_customize->add_setting(
			'dokani_settings[nav_dropdown_type]',
			array(
				'default'           => $defaults['nav_dropdown_type'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices'
			)
		);

		// Add navigation control
		$wp_customize->add_control(
			'dokani_settings[nav_dropdown_type]',
			array(
				'type'     => 'select',
				'label'    => __( 'Navigation Dropdown', 'dokani' ),
				'section'  => 'dokani_layout_navigation',
				'choices'  => array(
					'hover'       => __( 'Hover', 'dokani' ),
					'click'       => __( 'Click - Menu Item', 'dokani' ),
					'click-arrow' => __( 'Click - Arrow', 'dokani' )
				),
				'settings' => 'dokani_settings[nav_dropdown_type]',
				'active_callback' => 'is_nav_position_bellow_header',
			)
		);

		// add navigation background color
		$wp_customize->add_setting(
			'dokani_settings[navigation_background_color]', array(
				'default'           => $defaults_color['navigation_background_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[navigation_background_color]',
				array(
					'label'           => __( 'Background Color', 'dokani' ),
					'section'         => 'dokani_layout_navigation',
					'active_callback' => 'is_nav_position_bellow_header',
				)
			)
		);

		// add navigation background color
		$wp_customize->add_setting(
			'dokani_settings[navigation_border_color]', array(
				'default'           => $defaults_color['navigation_border_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[navigation_border_color]',
				array(
					'label'           => __( 'Border Color', 'dokani' ),
					'section'         => 'dokani_layout_navigation',
					'active_callback' => 'is_nav_position_bellow_header',
				)
			)
		);

		// add navigation link color
		$wp_customize->add_setting(
			'dokani_settings[navigation_link_color]', array(
				'default'           => $defaults_color['navigation_link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[navigation_link_color]',
				array(
					'label'           => __( 'Link Color', 'dokani' ),
					'section'         => 'dokani_layout_navigation',
					'active_callback' => 'is_nav_position_bellow_header',
				)
			)
		);

		// add footer widget text color
		$wp_customize->add_setting(
			'dokani_settings[navigation_link_hover_color]', array(
				'default'           => $defaults_color['navigation_link_hover_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[navigation_link_hover_color]',
				array(
					'label'           => __( 'Hover Color', 'dokani' ),
					'section'         => 'dokani_layout_navigation',
					'active_callback' => 'is_nav_position_bellow_header',
				)
			)
		);


		// Add content setting
		$wp_customize->add_setting(
			'dokani_settings[content_layout_setting]',
			array(
				'default'           => $defaults['content_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'postMessage'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokani_settings[content_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Content Layout', 'dokani' ),
				'section'  => 'dokani_layout_container',
				'choices'  => array(
					'separate-containers' => __( 'Separate Containers', 'dokani' ),
					'one-container'       => __( 'One Container', 'dokani' )
				),
				'settings' => 'dokani_settings[content_layout_setting]',
				'priority' => 25
			)
		);

		// Add Layout setting
		$wp_customize->add_setting(
			'dokani_settings[layout_setting]',
			array(
				'default'           => $defaults['layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'dokani_settings[layout_setting]',
				array(
					'label'    => esc_html__( 'Sidebar Layout', 'dokani' ),
					'section'  => 'dokani_layout_sidebars',
					'choices'  => array(
						'left-sidebar' => array(
							'label' => esc_html__( 'Left Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/left-sidebar.svg',
						),
						'no-sidebar' => array(
							'label' => esc_html__( 'No Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/no-sidebar.svg',
						),
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/right-sidebar.svg',
						),
					),
					'settings' => 'dokani_settings[layout_setting]',

				)
			)
		);

		// Add Blog Layout setting
		$wp_customize->add_setting(
			'dokani_settings[blog_layout_setting]',
			array(
				'default'           => $defaults['blog_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'dokani_settings[blog_layout_setting]',
				array(
					'label'    => esc_html__( 'Blog Sidebar Layout', 'dokani' ),
					'section'  => 'dokani_layout_sidebars',
					'choices'  => array(
						'left-sidebar' => array(
							'label' => esc_html__( 'Left Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/left-sidebar.svg',
						),
						'no-sidebar' => array(
							'label' => esc_html__( 'No Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/no-sidebar.svg',
						),
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/right-sidebar.svg',
						),
					),
					'settings' => 'dokani_settings[blog_layout_setting]',
					'priority' => 35,
				)
			)
		);

		// Setting - Single Post Layout
		$wp_customize->add_setting(
			'dokani_settings[single_layout_setting]',
			array(
				'default'           => $defaults['single_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh',
			)
		);

		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'dokani_settings[single_layout_setting]',
				array(
					'label'    => esc_html__( 'Single Post Sidebar Layout', 'dokani' ),
					'section'  => 'dokani_layout_sidebars',
					'choices'  => array(
						'left-sidebar' => array(
							'label' => esc_html__( 'Left Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/left-sidebar.svg',
						),
						'no-sidebar' => array(
							'label' => esc_html__( 'No Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/no-sidebar.svg',
						),
						'right-sidebar' => array(
							'label' => esc_html__( 'Right Sidebar', 'dokani' ),
							'url'   => '%s/assets/images/customizer/right-sidebar.svg',
						),
					),
					'settings' => 'dokani_settings[single_layout_setting]',
					'priority' => 36,
				)
			)
		);

		if ( class_exists( 'WooCommerce' ) ) {
			// Setting - Shop Layout
			$wp_customize->add_setting(
				'dokani_settings[shop_layout_setting]',
				array(
					'default'           => $defaults['shop_layout_setting'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_choices',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new dokani_Customize_Control_Radio_Image(
					$wp_customize,
					'dokani_settings[shop_layout_setting]',
					array(
						'label'    => esc_html__( 'Shop Sidebar Layout', 'dokani' ),
						'section'  => 'dokani_layout_sidebars',
						'choices'  => array(
							'left-sidebar' => array(
								'label' => esc_html__( 'Left Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/left-sidebar.svg',
							),
							'no-sidebar' => array(
								'label' => esc_html__( 'No Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/no-sidebar.svg',
							),
							'right-sidebar' => array(
								'label' => esc_html__( 'Right Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/right-sidebar.svg',
							),
						),
						'settings' => 'dokani_settings[shop_layout_setting]',
						'priority' => 37,
					)
				)
			);



			// Setting - Single Product Layout
			$wp_customize->add_setting(
				'dokani_settings[single_product_layout_setting]',
				array(
					'default'           => $defaults['single_product_layout_setting'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_choices',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new dokani_Customize_Control_Radio_Image(
					$wp_customize,
					'dokani_settings[single_product_layout_setting]',
					array(
						'label'    => esc_html__( 'Single Product Sidebar Layout', 'dokani' ),
						'section'  => 'dokani_layout_sidebars',
						'choices'  => array(
							'left-sidebar' => array(
								'label' => esc_html__( 'Left Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/left-sidebar.svg',
							),
							'no-sidebar' => array(
								'label' => esc_html__( 'No Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/no-sidebar.svg',
							),
							'right-sidebar' => array(
								'label' => esc_html__( 'Right Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/right-sidebar.svg',
							),
						),
						'settings' => 'dokani_settings[single_product_layout_setting]',
						'priority' => 38,
					)
				)
			);
		}

		if ( function_exists( 'dokan' ) ) {
			// Setting - Store List Layout
			$wp_customize->add_setting(
				'dokani_settings[store_list_layout_setting]',
				array(
					'default'           => $defaults['store_list_layout_setting'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_choices',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new dokani_Customize_Control_Radio_Image(
					$wp_customize,
					'dokani_settings[store_list_layout_setting]',
					array(
						'label'    => esc_html__( 'Store List Sidebar Layout', 'dokani' ),
						'section'  => 'dokani_layout_sidebars',
						'choices'  => array(
							'left-sidebar' => array(
								'label' => esc_html__( 'Left Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/left-sidebar.svg',
							),
							'no-sidebar' => array(
								'label' => esc_html__( 'No Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/no-sidebar.svg',
							),
							'right-sidebar' => array(
								'label' => esc_html__( 'Right Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/right-sidebar.svg',
							),
						),
						'settings' => 'dokani_settings[store_list_layout_setting]',
						'priority' => 39,
					)
				)
			);

			// Setting - Store Layout
			$wp_customize->add_setting(
				'dokani_settings[store_layout_setting]',
				array(
					'default'           => $defaults['store_layout_setting'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_choices',
					'transport'         => 'refresh',
				)
			);

			$wp_customize->add_control(
				new dokani_Customize_Control_Radio_Image(
					$wp_customize,
					'dokani_settings[store_layout_setting]',
					array(
						'label'    => esc_html__( 'Store Sidebar Layout', 'dokani' ),
						'section'  => 'dokani_layout_sidebars',
						'choices'  => array(
							'left-sidebar' => array(
								'label' => esc_html__( 'Left Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/left-sidebar.svg',
							),
							'no-sidebar' => array(
								'label' => esc_html__( 'No Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/no-sidebar.svg',
							),
							'right-sidebar' => array(
								'label' => esc_html__( 'Right Sidebar', 'dokani' ),
								'url'   => '%s/assets/images/customizer/right-sidebar.svg',
							),
						),
						'settings' => 'dokani_settings[store_layout_setting]',
						'priority' => 40,
					)
				)
			);
		}


		// Add widget list border color setting
		$wp_customize->add_setting(
			'dokani_settings[sidebar_list_border_color]', array(
				'default'           => $defaults['sidebar_list_border_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[sidebar_list_border_color]',
				array(
					'label'    => __( 'List Border Color', 'dokani' ),
					'section'  => 'dokani_sidebar_list_style',
					'settings' => 'dokani_settings[sidebar_list_border_color]'
				)
			)
		);

		$wp_customize->add_setting(
			'dokani_settings[sidebar_list_border_width]',
			array(
				'default' => $defaults['sidebar_list_border_width'],
				'type' => 'option',
				'sanitize_callback' => 'dokani_sanitize_integer',
				'transport' => 'refresh'
			)
		);

		$wp_customize->add_control(
			new Generate_Range_Slider_Control(
				$wp_customize,
				'dokani_settings[sidebar_list_border_width]',
				array(
					'type' => 'dokani-range-slider',
					'description' => __( 'List Border Width', 'dokani' ),
					'section' => 'dokani_sidebar_list_style',
					'settings' => array(
						'desktop' => 'dokani_settings[sidebar_list_border_width]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 0,
							'max' => 4,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 3,
				)
			)
		);

		$wp_customize->add_setting(
			'dokani_settings[sidebar_list_spacing]',
			array(
				'default' => $defaults['sidebar_list_spacing'],
				'type' => 'option',
				'sanitize_callback' => 'dokani_sanitize_integer',
				'transport' => 'refresh'
			)
		);

		$wp_customize->add_control(
			new Generate_Range_Slider_Control(
				$wp_customize,
				'dokani_settings[sidebar_list_spacing]',
				array(
					'type' => 'dokani-range-slider',
					'description' => __( 'List Spacing', 'dokani' ),
					'section' => 'dokani_sidebar_list_style',
					'settings' => array(
						'desktop' => 'dokani_settings[sidebar_list_spacing]',
					),
					'choices' => array(
						'desktop' => array(
							'min' => 5,
							'max' => 20,
							'step' => 1,
							'edit' => true,
							'unit' => 'px',
						),
					),
					'priority' => 3,
				)
			)
		);



		$wp_customize->add_section(
			'dokani_layout_footer',
			array(
				'title'    => __( 'Footer Widgets', 'dokani' ),
				'priority' => 1,
				'panel'    => 'dokani_footer_panel'
			)
		);

		// Add footer widgetlayout setting.
		$wp_customize->add_setting(
			'footer_widget_layout',
			array(
				'default'           => 'layout-1',
				'sanitize_callback' => 'dokani_sanitize_radio',
			)
		);

		// Add the layout control.
		$wp_customize->add_control(
			new dokani_Customize_Control_Radio_Image(
				$wp_customize,
				'footer_widget_layout',
				array(
					'label'    => esc_html__( 'Layout', 'dokani' ),
					'section'  => 'dokani_layout_footer',
					'choices'  => array(
						'disabled' => array(
							'label' => esc_html__( 'Disabled', 'dokani' ),
							'url'   => '%s/assets/images/customizer/disabled.svg',
						),
						'layout-1' => array(
							'label' => esc_html__( 'Footer Widgets', 'dokani' ),
							'url'   => '%s/assets/images/customizer/footer-widgets.svg',
						)
					)
				)
			)
		);

		// Add footer setting
		$wp_customize->add_setting(
			'dokani_settings[footer_layout_setting]',
			array(
				'default'           => $defaults['footer_layout_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokani_settings[footer_layout_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Footer Width', 'dokani' ),
				'section'  => 'dokani_layout_footer',
				'choices'  => array(
					'fluid-footer'     => __( 'Full', 'dokani' ),
					'contained-footer' => __( 'Contained', 'dokani' )
				),
				'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
			)
		);


		// Add footer setting
		$wp_customize->add_setting(
			'dokani_settings[footer_inner_width]',
			array(
				'default'           => $defaults['footer_inner_width'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices',
				'transport'         => 'refresh'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokani_settings[footer_inner_width]',
			array(
				'type'     => 'select',
				'label'    => __( 'Inner Footer Width', 'dokani' ),
				'section'  => 'dokani_layout_footer',
				'choices'  => array(
					'contained'  => __( 'Contained', 'dokani' ),
					'full-width' => __( 'Full', 'dokani' )
				),
				'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
			)
		);

		// Add footer widget setting
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_setting]',
			array(
				'default'           => $defaults['footer_widget_setting'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices'
			)
		);

		// Add footer widget control
		$wp_customize->add_control(
			'dokani_settings[footer_widget_setting]',
			array(
				'type'     => 'select',
				'label'    => __( 'Footer Widgets', 'dokani' ),
				'section'  => 'dokani_layout_footer',
				'choices'  => array(
					'0' => '0',
					'1' => '1',
					'2' => '2',
					'3' => '3',
					'4' => '4',
					'5' => '5'
				),
				'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
			)
		);

		// Add back to top setting
		$wp_customize->add_setting(
			'dokani_settings[back_to_top]',
			array(
				'default'           => $defaults['back_to_top'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices'
			)
		);

		// Add content control
		$wp_customize->add_control(
			'dokani_settings[back_to_top]',
			array(
				'type'     => 'select',
				'label'    => __( 'Back to Top Button', 'dokani' ),
				'section'  => 'dokani_layout_footer',
				'choices'  => array(
					'enable' => __( 'Enable', 'dokani' ),
					''       => __( 'Disable', 'dokani' )
				),
			)
		);

		// add footer widgets background color
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_bg_color]', array(
				'default'           => $defaults_color['footer_widget_bg_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_widget_bg_color]',
				array(
					'label'           => __( 'Background Color', 'dokani' ),
					'section'         => 'dokani_layout_footer',
					'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
					'settings'		  => 'dokani_settings[footer_widget_bg_color]',
				)
			)
		);

		// add footer widgets title color
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_title_color]', array(
				'default'           => $defaults_color['footer_widget_title_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
				'transport'         => 'refresh',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_widget_title_color]',
				array(
					'label'           => __( 'Title Color', 'dokani' ),
					'section'         => 'dokani_layout_footer',
					'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
				)
			)
		);

		// add footer widget text color
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_text_color]', array(
				'default'           => $defaults_color['footer_widget_text_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_widget_text_color]',
				array(
					'label'           => __( 'Text Color', 'dokani' ),
					'section'         => 'dokani_layout_footer',
					'active_callback' => 'dokani_is_footer_widget_layout_not_disabled',
				)
			)
		);

		// add footer widget link color
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_link_color]', array(
				'default'           => $defaults_color['footer_widget_link_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_widget_link_color]',
				array(
					'label'             => __( 'Link Color', 'dokani' ),
					'section'           => 'dokani_layout_footer',
					'active_callback'   => 'dokani_is_footer_widget_layout_not_disabled',
				)
			)
		);

		// add footer widget link hover color
		$wp_customize->add_setting(
			'dokani_settings[footer_widget_link_hover_color]', array(
				'default'           => $defaults_color['footer_widget_link_hover_color'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_hex_color',
			)
		);
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'dokani_settings[footer_widget_link_hover_color]',
				array(
					'label'             => __( 'Link Hover Color', 'dokani' ),
					'section'           => 'dokani_layout_footer',
					'active_callback'   => 'dokani_is_footer_widget_layout_not_disabled',
				)
			)
		);



		// Add featured image setting
		$wp_customize->add_setting(
			'fluid_featured_image',
			array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		// Add featured image control
		$wp_customize->add_control(
			'fluid_featured_image',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Fluid Featured Image', 'dokani' ),
				'section'  => 'dokani_blog_section',
				'settings' => 'fluid_featured_image',
				'priority' => 10
			)
		);


		// Add category setting on single
		$wp_customize->add_setting(
			'blog_single_show_category',
			array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		// Add category control on single
		$wp_customize->add_control(
			'blog_single_show_category',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Show Category List', 'dokani' ),
				'section'  => 'dokani_blog_single_section',
				'settings' => 'blog_single_show_category',
				'priority' => 12
			)
		);

		// Add tags setting on single
		$wp_customize->add_setting(
			'blog_single_show_tag',
			array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		// Add tags control on single
		$wp_customize->add_control(
			'blog_single_show_tag',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Show Tag List', 'dokani' ),
				'section'  => 'dokani_blog_single_section',
				'settings' => 'blog_single_show_tag',
				'priority' => 12
			)
		);

		// Add author-profile setting on single
		$wp_customize->add_setting(
			'blog_single_show_author_profile',
			array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		// Add tags control on single
		$wp_customize->add_control(
			'blog_single_show_author_profile',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Show Author Profile', 'dokani' ),
				'section'  => 'dokani_blog_single_section',
				'settings' => 'blog_single_show_author_profile',
				'priority' => 12
			)
		);

		// Add post-nav
		$wp_customize->add_setting(
			'show_post_nav',
			array(
				'default'           => 'on',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		// Add tags control on single
		$wp_customize->add_control(
			'show_post_nav',
			array(
				'type'     => 'checkbox',
				'label'    => __( 'Show Post Nav', 'dokani' ),
				'section'  => 'dokani_blog_single_section',
				'settings' => 'show_post_nav',
				'priority' => 12
			)
		);

		// Add excerpt setting
		$wp_customize->add_setting(
			'dokani_settings[post_content]',
			array(
				'default'           => $defaults['post_content'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_choices'
			)
		);

		// Add Layout control
		$wp_customize->add_control(
			'dokani_settings[post_content]',
			array(
				'type'     => 'select',
				'label'    => __( 'Content Type', 'dokani' ),
				'section'  => 'dokani_blog_section',
				'choices'  => array(
					'full'    => __( 'Full', 'dokani' ),
					'excerpt' => __( 'Excerpt', 'dokani' )
				),
				'settings' => 'dokani_settings[post_content]',
				'priority' => 50
			)
		);

		// Add Performance section
		$wp_customize->add_section(
			'dokani_general_section',
			array(
				'title'    => __( 'General', 'dokani' ),
				'priority' => 99,
				'panel'    => 'dokani_global_panel',
			)
		);

		if ( ! apply_filters( 'dokani_fontawesome_essentials', false ) ) {
			$wp_customize->add_setting(
				'dokani_settings[font_awesome_essentials]',
				array(
					'default'           => $defaults['font_awesome_essentials'],
					'type'              => 'option',
					'sanitize_callback' => 'dokani_sanitize_checkbox'
				)
			);

			$wp_customize->add_control(
				'dokani_settings[font_awesome_essentials]',
				array(
					'type'        => 'checkbox',
					'label'       => __( 'Load essential icons only', 'dokani' ),
					'description' => __( 'Load essential Font Awesome icons instead of the full library.', 'dokani' ),
					'section'     => 'dokani_general_section',
					'settings'    => 'dokani_settings[font_awesome_essentials]',
				)
			);
		}

		$wp_customize->add_setting(
			'dokani_settings[dynamic_css_cache]',
			array(
				'default'           => $defaults['dynamic_css_cache'],
				'type'              => 'option',
				'sanitize_callback' => 'dokani_sanitize_checkbox'
			)
		);

		$wp_customize->add_control(
			'dokani_settings[dynamic_css_cache]',
			array(
				'type'        => 'checkbox',
				'label'       => __( 'Cache dynamic CSS', 'dokani' ),
				'description' => __( 'Cache CSS generated by your options to boost performance.', 'dokani' ),
				'section'     => 'dokani_general_section',
			)
		);
	}

}

if ( ! function_exists( 'dokani_customizer_live_preview' ) ) {
	add_action( 'customize_preview_init', 'dokani_customizer_live_preview', 100 );
	/**
	 * Add our live preview scripts
	 *
	 * @since 1.0.0
	 */
	function dokani_customizer_live_preview() {
		wp_enqueue_script( 'dokani-themecustomizer', trailingslashit( get_template_directory_uri() ) . 'inc/customizer/controls/js/customizer-live-preview.js', array( 'customize-preview' ), GENERATE_VERSION, true );
	}
}
