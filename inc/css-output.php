<?php
/**
 * Output all of our dynamic CSS.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_base_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	function dokani_base_css() {
		// Get our settings
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		$og_defaults = dokani_get_defaults();

		// Initiate our class
		$css = new dokani_CSS;

		// Body
		$css->set_selector( 'body' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'background_color' ] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'text_color' ] ) );

		// Links
		$css->set_selector( 'a, a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'link_color' ] ) );
		$css->add_property( 'text-decoration', 'none' ); // Temporary until people can get their browser caches cleared

		// Visited links
		$css->set_selector( 'a:visited' )->add_property( 'color', esc_attr( $dokani_settings[ 'link_color_visited' ] ) );

		// Hover/focused links
		$css->set_selector( 'a:hover, a:focus, a:active' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'link_color_hover' ] ) );
		$css->add_property( 'text-decoration', 'none' ); // Temporary until people can get their browser caches cleared

		$css->set_selector( '.entry-meta a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'link_color_hover' ] ) );

		$css->set_selector( '.comments-area .logged-in-as a:hover, .comments-area .logged-in-as a:focus, .comments-area .logged-in-as a:active' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'link_color_hover' ] ) );

		$css->set_selector( '.widget-area .widget ul > li > a:hover, .dokan-widget-area .widget ul > li > a:hover, .widget-area .widget ul > li > a:active, .dokan-widget-area .widget ul > li > a:active, .widget-area .widget ul > li > a:focus, .dokan-widget-area .widget ul > li > a:focus' );
		$css->add_property( 'color', esc_attr( $dokani_settings['link_color_hover'] ) );

		// Entry meta links
		$css->set_selector( '.entry-meta a' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'link_color' ] ) );

		// widget list styles
		$css->set_selector( '.widget-area .widget ul > li, .dokan-widget-area .widget ul > li, .widget-area .widget .widget-title, .dokan-widget-area .widget .widget-title' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['sidebar_list_border_color'] ) );

		$css->add_property( 'border-bottom-width', absint( $dokani_settings['sidebar_list_border_width'] ), false, 'px' );
		$css->add_property( 'border-style', 'solid' );
		$css->add_property( 'padding-top', absint( $dokani_settings['sidebar_list_spacing'] ), false, 'px' );
		$css->add_property( 'padding-bottom', absint( $dokani_settings['sidebar_list_spacing'] ), false, 'px' );

		// Container width
		$css->set_selector( 'body .grid-container' )->add_property( 'max-width', absint( $dokani_settings['container_width'] ), absint( $dokani_settings['container_width'] ), 'px' );

		// Allow us to hook CSS into our output
		do_action( 'dokani_base_css', $css );

		return apply_filters( 'dokani_base_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'dokani_advanced_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	function dokani_advanced_css() {
		// Get our settings
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_color_defaults()
		);

		$og_defaults = dokani_get_defaults();

		// Initiate our CSS class
		$css = new dokani_CSS;

		// Top bar
		$css->set_selector( '.top-bar' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'top_bar_background_color' ] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'top_bar_text_color' ] ) );

		// Top bar link
		$css->set_selector( '.top-bar a,.top-bar a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'top_bar_text_color' ] ) );
		//		$css->add_property( 'color', esc_attr( $dokani_settings[ 'top_bar_link_color' ] ) );

		// Top bar link hover
		$css->set_selector( '.top-bar a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'top_bar_link_color_hover' ] ) );

		// Header
		$css->set_selector( '.site-header' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'header_background_color' ] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_text_color' ] ) );

		// Header link
		$css->set_selector( '.site-header a,.site-header a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_link_color' ] ) );

		// Header link hover
		$css->set_selector( '.site-header a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_link_hover_color' ] ) );

		// Site title
		$css->set_selector( '.main-title a,.main-title a:hover,.main-title a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'site_title_color' ] ) );

		// Site description
		$css->set_selector( '.site-description' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'site_tagline_color' ] ) );

		// Navigation background
		$css->set_selector( '.site-header' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'header_background_color' ] ) );// Navigation background

		// Header text
		$css->set_selector( '.site-branding .main-title' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_text_color' ] ) );


		// Mobile button text
		$css->set_selector( 'button.menu-toggle:hover,button.menu-toggle:focus,.main-navigation .mobile-bar-items a,.main-navigation .mobile-bar-items a:hover,.main-navigation .mobile-bar-items a:focus' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'navigation_text_color' ] ) );

		// header nav-link colors
		$css->set_selector( '.site-header .main-navigation .main-nav a' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_link_color' ] ) );

		// header nav-link hover colors
		$css->set_selector( '.site-header .main-navigation .main-nav a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'header_link_hover_color' ] ) );

		// main navigation style below header
		$css->set_selector( '.site-header + .main-navigation' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'navigation_background_color' ] ) );
		$css->add_property( 'border-bottom-width', '1px' );
		$css->add_property( 'border-bottom-style', 'solid' );
		$css->add_property( 'border-bottom-color', esc_attr( $dokani_settings[ 'navigation_border_color' ] ) );

		$css->set_selector( '.site-header + .main-navigation .main-nav a' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'navigation_link_color' ] ) );

		$css->set_selector( '.site-header + .main-navigation .main-nav a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'navigation_link_hover_color' ] ) );

		// Content
		$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .one-container .container, .separate-containers .paging-navigation, .inside-page-header' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'content_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'content_background_color' ] ) );

		// Content links
		$css->set_selector( '.inside-article a,.inside-article a:visited,.paging-navigation a,.paging-navigation a:visited,.comments-area a,.comments-area a:visited,.page-header a,.page-header a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'content_link_color' ] ) );

		// Content links on hover
		$css->set_selector( '.inside-article a:hover,.paging-navigation a:hover,.comments-area a:hover,.page-header a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'content_link_hover_color' ] ) );

		// Entry header
		$css->set_selector( '.entry-header h1,.page-header h1' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'content_title_color' ] ) );

		// Section title and heading
		$css->set_selector( 'h1, h2, h3, h4, h5, h6' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'heading_color' ] ) );

		// Blog post title
		$css->set_selector( '.entry-title a,.entry-title a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'blog_post_title_color' ] ) );

		// Blog post title on hover
		$css->set_selector( '.entry-title a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'blog_post_title_hover_color' ] ) );

		// Entry meta text
		$css->set_selector( '.entry-meta' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'entry_meta_text_color' ] ) );



		$css->set_selector( '.paging-navigation .nav-links .page-numbers.current, .paging-navigation .nav-links .page-numbers:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'theme_color' ] ) );

		$css->set_selector( '.product-cat-section .product-cat-wrapper .product-cat-box.more h3' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'theme_color' ] ) );


		// Sidebar widget
		$css->set_selector( '.sidebar .widget' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'sidebar_widget_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'sidebar_widget_background_color' ] ) );

		// Sidebar widget links
		$css->set_selector( '.sidebar .widget a,.sidebar .widget a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'sidebar_widget_link_color' ] ) );

		// Sidebar widget links on hover
		$css->set_selector( '.sidebar .widget a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'sidebar_widget_link_hover_color' ] ) );

		// Sidebar widget title
		$css->set_selector( '.sidebar .widget .widget-title' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'sidebar_widget_title_color' ] ) );

		// Footer widget
		$css->set_selector( '#footer-widgets' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_widget_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'footer_widget_bg_color' ] ) );

		// Footer widget links
		$css->set_selector( '.footer-widgets a,.footer-widgets a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_widget_link_color' ] ) );

		// Footer widget links on hover
		$css->set_selector( '.footer-widgets a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_widget_link_hover_color' ] ) );

		// Footer widget title
		$css->set_selector( '.footer-widgets .widget-title' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_widget_title_color' ] ) );

		// Footer
		$css->set_selector( '.site-info' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_bottom_bar_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'footer_bottom_bar_bg_color' ] ) );

		// Footer links
		$css->set_selector( '.site-info a,.site-info a:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_bottom_bar_link_color' ] ) );

		// Footer links on hover
		$css->set_selector( '.site-info a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_bottom_bar_hover_color' ] ) );

		// Footer bar widget menu
		$css->set_selector( '.footer-bar .widget_nav_menu .current-menu-item a' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_link_hover_color' ] ) );

		// Form input
		$css->set_selector( 'input[type="text"],input[type="email"],input[type="url"],input[type="password"],input[type="search"],input[type="tel"],input[type="number"],textarea,select' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'form_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'form_background_color' ] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings[ 'form_border_color' ] ) );

		// Form input on focus
		$css->set_selector( 'input[type="text"]:focus,input[type="email"]:focus,input[type="url"]:focus,input[type="password"]:focus,input[type="search"]:focus,input[type="tel"]:focus,input[type="number"]:focus,textarea:focus,select:focus' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'form_text_color_focus' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'form_background_color_focus' ] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings[ 'form_border_color_focus' ] ) );

		// Form button
		$css->set_selector( 'button,html input[type="button"],input[type="reset"],input[type="submit"],a.button,a.button:visited' );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'form_button_text_color' ] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'form_button_background_color' ] ) );

		// Form button on hover
		$css->set_selector( 'button:hover,html input[type="button"]:hover,input[type="reset"]:hover,input[type="submit"]:hover,a.button:hover,button:focus,html input[type="button"]:focus,input[type="reset"]:focus,input[type="submit"]:focus,a.button:focus' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'theme_color' ] ) );

		// Back to top button
		$css->set_selector( '.dokani-back-to-top,.dokani-back-to-top:visited' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['back_to_top_background_color'] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings['back_to_top_text_color'] ) );

		$css->set_selector( '.dokani-back-to-top:hover,.dokani-back-to-top:focus' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['back_to_top_background_color_hover'] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings['back_to_top_text_color_hover'] ) );

		// dokani theme color
		$css->set_selector( '.dokani-category-menu .title:before, .products-section .product-sliders .flex-direction-nav a:hover:before, #dokan-seller-listing-wrap ul.dokan-seller-list li .store-footer .store-data h2 a:hover, .woocommerce ul.products li.product .item-content a.woocommerce-LoopProduct-link:hover, .woocommerce-page ul.products li.product .item-content a.woocommerce-LoopProduct-link:hover, .woocommerce ul.products li.product .price, .breadcrumb > li a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce nav.woocommerce-pagination ul li a.current, .woocommerce nav.woocommerce-pagination ul li .current.current, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li .current:hover, .woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li .current:focus, .woocommerce nav.woocommerce-pagination ul li a:active, .woocommerce nav.woocommerce-pagination ul li .current:active' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.dokani-products-view button:hover, .dokani-products-view button:active, .dokani-products-view button:focus, .dokani-products-view button.active, .dokan-seller-view button:hover, .dokan-seller-view button:focus, .dokan-seller-view button:active, .dokan-seller-view button.active' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.entry-header .entry-title a:hover', '.entry-header .entry-meta a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( 'input[type="submit"].dokan-btn-theme, a.dokan-btn-theme, .dokan-btn-theme, .entry-content .read-more, .entry-summary .read-more' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce ul.products li.product .item-bar .item-button > a:hover, .woocommerce-page ul.products li.product .item-bar .item-button > a:hover, .woocommerce ul.products li.product .item-bar .item-button > a:active, .woocommerce-page ul.products li.product .item-bar .item-button > a:active, .woocommerce ul.products li.product .item-bar .item-button > a:focus, .woocommerce-page ul.products li.product .item-bar .item-button > a:focus, .added_to_cart, .woocommerce div.product div.images .woocommerce-product-gallery__trigger:after, .woocommerce div.product .single_add_to_cart_button' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce ul.products.list li .item-bar .item-button > a:hover, .woocommerce-page ul.products.list li .item-bar .item-button > a:hover, .woocommerce ul.products.list li .item-bar .item-button > a:active, .woocommerce-page ul.products.list li .item-bar .item-button > a:active, .woocommerce ul.products.list li .item-bar .item-button > a:focus, .woocommerce-page ul.products.list li .item-bar .item-button > a:focus, .woocommerce ul.products.list li .item-bar .item-button .added_to_cart, .woocommerce-page ul.products.list li .item-bar .item-button .added_to_cart' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'color', '#fff' );

		$css->set_selector( '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce div.product .product_meta > div a:hover, #dokane-menu-cart-wrapper .widget_shopping_cart_content .woocommerce-mini-cart.cart_list li a, .woocommerce ul.cart_list li .woocommerce-Price-amount, .woocommerce ul.product_list_widget li .woocommerce-Price-amount' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '#dokane-menu-cart-wrapper .widget_shopping_cart_content p.buttons a.button:nth-child(2)' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '#dokane-menu-cart-wrapper .widget_shopping_cart_content p.buttons a.button:nth-child(2):hover' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'color', '#fff' );

		$css->set_selector( '.woocommerce div.product .woocommerce-tabs ul.tabs li:active a, .woocommerce div.product .woocommerce-tabs ul.tabs li.active a' );
		$css->add_property( 'border-bottom-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'border-bottom-width', '2px' );
		$css->add_property( 'border-bottom-style', 'solid' );

		$css->set_selector( '.woocommerce a.button, .woocommerce-page a.button, .woocommerce button.button, .woocommerce-page button.button, .woocommerce input.button, .woocommerce-page input.button, .woocommerce #respond input#submit, .woocommerce-page #respond input#submit, .woocommerce #content input.button, .woocommerce-page #content input.button, .woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce a.button.alt:disabled[disabled], .woocommerce button.button.alt.disabled, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .woocommerce input.button.alt.disabled, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled[disabled]' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.woocommerce div.product .woocommerce-tabs ul.tabs li:hover a, .woocommerce div.product .woocommerce-tabs ul.tabs li:focus a, .woocommerce div.product div.images .woocommerce-product-gallery__trigger:before' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.product-cat-section .product-cat-wrapper .product-cat-box .btn:hover, .product-cat-section .product-cat-wrapper .product-cat-box .btn:focus, .product-cat-section .product-cat-wrapper .product-cat-box .btn:active' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );

		// woocommerce my account styles
		$css->set_selector( '.woocommerce-MyAccount-navigation li a:hover, .woocommerce-MyAccount-navigation li a:focus, .woocommerce-MyAccount-navigation li a:focus, .woocommerce-MyAccount-navigation li.is-active a' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );

		// footer widget styles
		$css->set_selector( '#footer-widgets.footer-widgets .widget-title' );
		$css->add_property( 'color', esc_attr( $dokani_settings['footer_widget_title_color'] ) );

		$css->set_selector( '#footer-widgets.footer-widgets' );
		$css->add_property( 'color', esc_attr( $dokani_settings['footer_widget_text_color'] ) );

		$css->set_selector( '#footer-widgets.footer-widgets .widget a' );
		$css->add_property( 'color', esc_attr( $dokani_settings['footer_widget_link_color'] ) );

		$css->set_selector( '#footer-widgets.footer-widgets .widget a:hover' );
		$css->add_property( 'color', esc_attr( $dokani_settings['footer_widget_link_hover_color'] ) );

		// trusted factor section style
		$css->set_selector( '.trust-factors-section' );
		$css->add_property( 'background-image', 'linear-gradient(85deg, ' . esc_attr( $dokani_settings['trusted_factor_bg_color1'] ) . ', ' . esc_attr( $dokani_settings['trusted_factor_bg_color2'] ) . ' )' );

		$css->set_selector( '.trust-factors-section .grid-container .factor-wrapper' );
		$css->add_property( 'color', esc_attr( $dokani_settings['trusted_factor_text_color'] ) );

		$css->set_selector( '.trust-factors-section .grid-container .factor-wrapper .factor-box .factor-icon' );
		$css->add_property( 'color', esc_attr( $dokani_settings['trusted_factor_icon_color'] ) );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['trusted_factor_icon_bg_color'] ) );

		// single store styles
		$css->set_selector( '.dokan-store-tab-wrapper .dokan-store-tabs .dokan-list-inline li a:hover, .dokan-store-tab-wrapper .dokan-store-tabs .dokan-list-inline li a:active, .dokan-store-tab-wrapper .dokan-store-tabs .dokan-list-inline li a:focus' );
		$css->add_property( 'color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );

		$css->set_selector( '.dokan-theme-dokani input[type="submit"].dokan-btn-theme, .dokan-theme-dokani a.dokan-btn-theme, .dokan-theme-dokani .dokan-btn-theme' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) . '!important' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) . '!important' );

		$css->set_selector( '.dokan-theme-dokani input[type="submit"].dokan-btn-theme:hover, .dokan-theme-dokani .dokan-btn-theme:hover, .dokan-theme-dokani .dokan-btn-theme:focus' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) . '!important' );
		$css->add_property( 'opacity', '.9' );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) . '!important' );


		// Bottom bar
		$css->set_selector( 'footer.site-info' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings[ 'footer_bottom_bar_bg_color' ] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings[ 'footer_bottom_bar_border_color' ] ) );
		$css->add_property( 'color', esc_attr( $dokani_settings[ 'footer_bottom_bar_text_color' ] ) );

		// store buttons
		$css->set_selector( 'div#dokan-seller-listing-wrap ul.dokan-seller-list li .store-footer .dokan-btn:hover' );
		$css->add_property( 'background-color', esc_attr( $dokani_settings['theme_color'] ) );
		$css->add_property( 'border-color', esc_attr( $dokani_settings['theme_color'] ) );

		// store header template colors
		if ( function_exists( 'dokan' ) ) {
			$css->set_selector( 'div.profile-frame .store-banner .profile-info-box .profile-info-summery .profile-info h1' );
			$css->add_property( 'color', esc_attr( $dokani_settings[ 'store_header_title_color' ] ) );

			$css->set_selector( '.profile-info-summery' );
			$css->add_property( 'color', esc_attr( $dokani_settings[ 'store_header_text_color' ] ) );

			$css->set_selector( 'div.profile-frame .store-banner .profile-info-box .store-info-column .store-meta-info a' );
			$css->add_property( 'color', esc_attr( $dokani_settings['store_header_link_color'] ) );

			$css->set_selector( 'div.profile-frame .store-banner .profile-info-box .store-info-column .store-meta-info a:hover' );
			$css->add_property( 'color', esc_attr( $dokani_settings['store_header_link_hover_color'] ) );
		}

		// Allow us to hook CSS into our output
		do_action( 'dokani_colors_css', $css );

		// Return our dynamic CSS
		return apply_filters( 'dokani_colors_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'dokani_font_css' ) ) {
	/**
	 * Generate the CSS in the <head> section using the Theme Customizer.
	 *
	 * @since 1.0.0
	 */
	function dokani_font_css() {

		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_default_fonts()
		);

		$og_defaults = dokani_get_default_fonts( false );

		$css = new dokani_CSS;

		// Get our sub-navigation font size
		$subnav_font_size = $dokani_settings['navigation_font_size'] >= 17 ? $dokani_settings['navigation_font_size'] - 3 : $dokani_settings['navigation_font_size'] - 1;

		// Create all of our font family entries
		$body_family = dokani_get_font_family_css( 'font_body', 'dokani_settings', dokani_get_default_fonts() );
		$top_bar_family = dokani_get_font_family_css( 'font_top_bar', 'dokani_settings', dokani_get_default_fonts() );
		$site_title_family = dokani_get_font_family_css( 'font_site_title', 'dokani_settings', dokani_get_default_fonts() );
		$site_tagline_family = dokani_get_font_family_css( 'font_site_tagline', 'dokani_settings', dokani_get_default_fonts() );
		$navigation_family = dokani_get_font_family_css( 'font_navigation', 'dokani_settings', dokani_get_default_fonts() );
		$widget_family = dokani_get_font_family_css( 'font_widget_title', 'dokani_settings', dokani_get_default_fonts() );
		$heading_family = dokani_get_font_family_css( 'font_heading', 'dokani_settings', dokani_get_default_fonts() );
		$footer_family = dokani_get_font_family_css( 'font_footer', 'dokani_settings', dokani_get_default_fonts() );
		$buttons_family = dokani_get_font_family_css( 'font_buttons', 'dokani_settings', dokani_get_default_fonts() );

		// Body
		$css->set_selector( 'body, button, input, select, textarea' );
		$css->add_property( 'font-family', $body_family );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'body_font_weight' ] ), $og_defaults[ 'body_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'body_font_transform' ] ), $og_defaults[ 'body_font_transform' ] );

		$css->set_selector( 'html, body' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'body_font_size' ] ), $og_defaults[ 'body_font_size' ], 'px' );

		// Line hieght
		$css->set_selector( 'body' );
		$css->add_property( 'line-height', floatval( $dokani_settings['body_line_height'] ), $og_defaults['body_line_height'] );

		// Paragraph margin
		$css->set_selector( 'p' );
		$css->add_property( 'margin-bottom', floatval( $dokani_settings['paragraph_margin'] ), $og_defaults['paragraph_margin'], 'em' );

		// Top bar
		$css->set_selector( '.top-bar' );
		$css->add_property( 'font-family', $og_defaults[ 'font_top_bar' ] !== $dokani_settings[ 'font_top_bar' ] ? $top_bar_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'top_bar_font_weight' ] ), $og_defaults[ 'top_bar_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'top_bar_font_transform' ] ), $og_defaults[ 'top_bar_font_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'top_bar_font_size' ] ), absint( $og_defaults[ 'top_bar_font_size' ] ), 'px' );

		// Site title
		$css->set_selector( '.main-title' );
		$css->add_property( 'font-family', $og_defaults[ 'font_site_title' ] !== $dokani_settings[ 'font_site_title' ] ? $site_title_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'site_title_font_weight' ] ), $og_defaults[ 'site_title_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'site_title_font_transform' ] ), $og_defaults[ 'site_title_font_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'site_title_font_size' ] ), $og_defaults[ 'site_title_font_size' ], 'px' );

		// Site description
		$css->set_selector( '.site-description' );
		$css->add_property( 'font-family', $og_defaults[ 'font_site_tagline' ] !== $dokani_settings[ 'font_site_tagline' ] ? $site_tagline_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'site_tagline_font_weight' ] ), $og_defaults[ 'site_tagline_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'site_tagline_font_transform' ] ), $og_defaults[ 'site_tagline_font_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'site_tagline_font_size' ] ), $og_defaults[ 'site_tagline_font_size' ], 'px' );

		// Navigation
		$css->set_selector( '.main-navigation a, .menu-toggle' );
		$css->add_property( 'font-family', $og_defaults[ 'font_navigation' ] !== $dokani_settings[ 'font_navigation' ] ? $navigation_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'navigation_font_weight' ] ), $og_defaults[ 'navigation_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'navigation_font_transform' ] ), $og_defaults[ 'navigation_font_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'navigation_font_size' ] ), $og_defaults[ 'navigation_font_size' ], 'px' );

		// Sub-navigation font size
		$css->set_selector( '.main-navigation .main-nav ul ul li a' );
		$css->add_property( 'font-size', absint( $subnav_font_size ), false, 'px' );

		// Widget title
		$css->set_selector( '.widget-title' );
		$css->add_property( 'font-family', $og_defaults[ 'font_widget_title' ] !== $dokani_settings[ 'font_widget_title' ] ? $widget_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'widget_title_font_weight' ] ), $og_defaults[ 'widget_title_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'widget_title_font_transform' ] ), $og_defaults[ 'widget_title_font_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'widget_title_font_size' ] ), $og_defaults[ 'widget_title_font_size' ], 'px' );
		$css->add_property( 'margin-bottom', absint( $dokani_settings['widget_title_separator'] ), absint( $og_defaults['widget_title_separator'] ), 'px' );

		// Widget font size
		$css->set_selector( '.sidebar .widget, .footer-widgets .widget' );
		$css->add_property( 'font-size', absint( $dokani_settings['widget_content_font_size'] ), $og_defaults['widget_content_font_size'], 'px' );

		// Form button
		$css->set_selector( 'button:not(.menu-toggle),html input[type="button"],input[type="reset"],input[type="submit"],.button,.button:visited' );
		$css->add_property( 'font-family', $og_defaults[ 'font_buttons' ] !== $dokani_settings[ 'font_buttons' ] ? $buttons_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'buttons_font_weight' ] ), $og_defaults[ 'buttons_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'buttons_font_transform' ] ), $og_defaults[ 'buttons_font_transform' ] );

		if ( '' !== $dokani_settings[ 'buttons_font_size' ] ) {
			$css->add_property( 'font-size', absint( $dokani_settings[ 'buttons_font_size' ] ), $og_defaults[ 'buttons_font_size' ], 'px' );
		}

		// h1, h2, h3, h4, h5, h6
		$css->set_selector( 'h1, h2, h3, h4, h5, h6' );
		$css->add_property( 'font-family', $og_defaults[ 'font_heading' ] !== $dokani_settings[ 'font_heading' ] ? $heading_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'heading_font_weight' ] ), $og_defaults[ 'heading_font_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'heading_font_transform' ] ), $og_defaults[ 'heading_font_transform' ] );

		// h1 font size
		$css->set_selector( 'h1' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_1_font_size' ] ), absint( $og_defaults[ 'heading_1_font_size' ] ), 'px' );

		// h2 font size
		$css->set_selector( 'h2' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_2_font_size' ] ), absint( $og_defaults[ 'heading_2_font_size' ] ), 'px' );

		// h3 font size
		$css->set_selector( 'h3' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_3_font_size' ] ), absint( $og_defaults[ 'heading_3_font_size' ] ), 'px' );

		// h4 font size
		$css->set_selector( 'h4' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_4_font_size' ] ), absint( $og_defaults[ 'heading_4_font_size' ] ), 'px' );

		// h5 font size
		$css->set_selector( 'h5' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_5_font_size' ] ), absint( $og_defaults[ 'heading_5_font_size' ] ), 'px' );

		// h6 font size
		$css->set_selector( 'h6' );
		$css->add_property( 'font-size', absint( $dokani_settings[ 'heading_6_font_size' ] ), absint( $og_defaults[ 'heading_6_font_size' ] ), 'px' );


		// Footer
		$css->set_selector( '.site-info' );
		$css->add_property( 'font-family', $og_defaults[ 'font_footer' ] !== $dokani_settings[ 'font_footer' ] ? $footer_family : null );
		$css->add_property( 'font-weight', esc_attr( $dokani_settings[ 'footer_weight' ] ), $og_defaults[ 'footer_weight' ] );
		$css->add_property( 'text-transform', esc_attr( $dokani_settings[ 'footer_transform' ] ), $og_defaults[ 'footer_transform' ] );
		$css->add_property( 'font-size', absint( $dokani_settings['footer_font_size'] ), $og_defaults['footer_font_size'], 'px' );

		// Mobile
		$css->start_media_query( apply_filters( 'dokani_mobile_media_query', '(max-width:768px)' ) );
		// Site title
		$mobile_site_title = ( isset( $dokani_settings[ 'mobile_site_title_font_size' ] ) ) ? $dokani_settings[ 'mobile_site_title_font_size' ] : '30';
		$css->set_selector( '.main-title' );
		$css->add_property( 'font-size', absint( $mobile_site_title ), false, 'px' );

		// H1
		$mobile_h1 = ( isset( $dokani_settings[ 'mobile_heading_1_font_size' ] ) ) ? $dokani_settings[ 'mobile_heading_1_font_size' ] : '30';
		$css->set_selector( 'h1' );
		$css->add_property( 'font-size', absint( $mobile_h1 ), false, 'px' );

		// H2
		$mobile_h2 = ( isset( $dokani_settings[ 'mobile_heading_2_font_size' ] ) ) ? $dokani_settings[ 'mobile_heading_2_font_size' ] : '25';
		$css->set_selector( 'h2' );
		$css->add_property( 'font-size', absint( $mobile_h2 ), false, 'px' );
		$css->stop_media_query();

		// Allow us to hook CSS into our output
		do_action( 'dokani_typography_css', $css );

		return apply_filters( 'dokani_typography_css_output', $css->css_output() );
	}
}

if ( ! function_exists( 'dokani_spacing_css' ) ) {
	/**
	 * Write our dynamic CSS.
	 *
	 * @since 1.0.0
	 */
	function dokani_spacing_css() {
		$spacing_settings = wp_parse_args(
			get_option( 'dokani_spacing_settings', array() ),
			dokani_spacing_get_defaults()
		);

		$og_defaults = dokani_spacing_get_defaults( false );
		$sidebar_layout = dokani_get_layout();

		$css = new dokani_CSS;

		// Top bar padding
		$css->set_selector( '.inside-top-bar' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'top_bar_top' ], $spacing_settings[ 'top_bar_right' ], $spacing_settings[ 'top_bar_bottom' ], $spacing_settings[ 'top_bar_left' ] ), dokani_padding_css( $og_defaults[ 'top_bar_top' ], $og_defaults[ 'top_bar_right' ], $og_defaults[ 'top_bar_bottom' ], $og_defaults[ 'top_bar_left' ] ) );

		// Header padding
		$css->set_selector( '.inside-header' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'header_top' ], $spacing_settings[ 'header_right' ], $spacing_settings[ 'header_bottom' ], $spacing_settings[ 'header_left' ] ), dokani_padding_css( $og_defaults[ 'header_top' ], $og_defaults[ 'header_right' ], $og_defaults[ 'header_bottom' ], $og_defaults[ 'header_left' ] ) );

		// Content padding
		$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .separate-containers .paging-navigation, .one-container .site-content, .inside-page-header' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'content_top' ], $spacing_settings[ 'content_right' ], $spacing_settings[ 'content_bottom' ], $spacing_settings[ 'content_left' ] ), dokani_padding_css( $og_defaults[ 'content_top' ], $og_defaults[ 'content_right' ], $og_defaults[ 'content_bottom' ], $og_defaults[ 'content_left' ] ) );

		// Mobile Content padding
		$css->start_media_query( apply_filters( 'dokani_mobile_media_query', '(max-width:768px)' ) );
		$css->set_selector( '.separate-containers .inside-article, .separate-containers .comments-area, .separate-containers .page-header, .separate-containers .paging-navigation, .one-container .site-content, .inside-page-header' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'mobile_content_top' ], $spacing_settings[ 'mobile_content_right' ], $spacing_settings[ 'mobile_content_bottom' ], $spacing_settings[ 'mobile_content_left' ] ) );
		$css->stop_media_query();

		// One container
		$css->set_selector( '.one-container.right-sidebar .site-main,.one-container.both-right .site-main' );
		$css->add_property( 'margin-right', absint( $spacing_settings['content_right'] ), absint( $og_defaults['content_right'] ), 'px' );

		$css->set_selector( '.one-container.left-sidebar .site-main,.one-container.both-left .site-main' );
		$css->add_property( 'margin-left', absint( $spacing_settings['content_left'] ), absint( $og_defaults['content_left'] ), 'px' );

		$css->set_selector( '.one-container.both-sidebars .site-main' );
		$css->add_property( 'margin', dokani_padding_css( '0', $spacing_settings[ 'content_right' ], '0', $spacing_settings[ 'content_left' ] ), dokani_padding_css( '0', $og_defaults[ 'content_right' ], '0', $og_defaults[ 'content_left' ] ) );

		// Separate containers
		// Container bottom margins
		$css->set_selector( '.separate-containers .widget, .separate-containers .site-main > *, .separate-containers .page-header, .widget-area .main-navigation' );
		$css->add_property( 'margin-bottom', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );

		// Right sidebar
		$css->set_selector( '.right-sidebar.separate-containers .site-main' );
		$css->add_property( 'margin', dokani_padding_css( $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ], '0' ), dokani_padding_css( $og_defaults[ 'separator' ], $og_defaults[ 'separator' ], $og_defaults[ 'separator' ], '0' ) );

		// Left sidebar
		$css->set_selector( '.left-sidebar.separate-containers .site-main' );
		$css->add_property( 'margin', dokani_padding_css( $spacing_settings[ 'separator' ], '0', $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ] ), dokani_padding_css( $og_defaults[ 'separator' ], '0', $og_defaults[ 'separator' ], $og_defaults[ 'separator' ] ) );

		// Both sidebars
		$css->set_selector( '.both-sidebars.separate-containers .site-main' );
		$css->add_property( 'margin', absint( $spacing_settings['separator'] ), absint( $og_defaults['separator'] ), 'px' );

		// Both right sidebar content separating space
		$css->set_selector( '.both-right.separate-containers .site-main' );
		$css->add_property( 'margin', dokani_padding_css( $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ], '0' ), dokani_padding_css( $og_defaults[ 'separator' ], $og_defaults[ 'separator' ], $og_defaults[ 'separator' ], '0' ) );

		// Both right sidebar - left sidebar separating space
		$css->set_selector( '.both-right.separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-right', absint( $spacing_settings[ 'separator' ] / 2 ), absint( $og_defaults[ 'separator' ] / 2 ), 'px' );

		// Both right sidebar - right sidebar separating space
		$css->set_selector( '.both-right.separate-containers .inside-right-sidebar' );
		$css->add_property( 'margin-left', absint( $spacing_settings[ 'separator' ] / 2 ), absint( $og_defaults[ 'separator' ] / 2 ), 'px' );

		// Both left sidebar content separating space
		$css->set_selector( '.both-left.separate-containers .site-main' );
		$css->add_property( 'margin', dokani_padding_css( $spacing_settings[ 'separator' ], '0', $spacing_settings[ 'separator' ], $spacing_settings[ 'separator' ] ), dokani_padding_css( $og_defaults[ 'separator' ], '0', $og_defaults[ 'separator' ], $og_defaults[ 'separator' ] ) );

		// Both left sidebar - left sidebar separating space
		$css->set_selector( '.both-left.separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-right', absint( $spacing_settings[ 'separator' ] / 2 ), absint( $og_defaults[ 'separator' ] / 2 ), 'px' );

		// Both left sidebar - right sidebar separating space
		$css->set_selector( '.both-left.separate-containers .inside-right-sidebar' );
		$css->add_property( 'margin-left', absint( $spacing_settings[ 'separator' ] / 2 ), absint( $og_defaults[ 'separator' ] / 2 ), 'px' );

		// Site main separators
		$css->set_selector( '.separate-containers .site-main' );
		$css->add_property( 'margin-top', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );
		$css->add_property( 'margin-bottom', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );

		// Page header top margin
		$css->set_selector( '.separate-containers .page-header-image, .separate-containers .page-header-contained, .separate-containers .page-header-image-single, .separate-containers .page-header-content-single' );
		$css->add_property( 'margin-top', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );

		// Sidebar separator
		$css->set_selector( '.separate-containers .inside-right-sidebar, .separate-containers .inside-left-sidebar' );
		$css->add_property( 'margin-top', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );
		$css->add_property( 'margin-bottom', absint( $spacing_settings[ 'separator' ] ), absint( $og_defaults[ 'separator' ] ), 'px' );

		// Navigation spacing
		// Menu item size
		$css->set_selector( '.main-navigation .main-nav ul li a,.menu-toggle,.main-navigation .mobile-bar-items a' );
		$css->add_property( 'padding-left', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );
		$css->add_property( 'padding-right', absint( $spacing_settings['menu_item'] ), absint( $og_defaults['menu_item'] ), 'px' );
		$css->add_property( 'line-height', absint( $spacing_settings['menu_item_height'] ), absint( $og_defaults['menu_item_height'] ), 'px' );

		// Sub-menu item size
		$css->set_selector( '.main-navigation .main-nav ul ul li a' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'sub_menu_item_height' ], $spacing_settings[ 'menu_item' ], $spacing_settings[ 'sub_menu_item_height' ], $spacing_settings[ 'menu_item' ] ), dokani_padding_css( $og_defaults[ 'sub_menu_item_height' ], $og_defaults[ 'menu_item' ], $og_defaults[ 'sub_menu_item_height' ], $og_defaults[ 'menu_item' ] ) );

		// Sub-menu positioning
		$css->set_selector( '.main-navigation ul ul' );
		$css->add_property( 'top', 'auto' ); // Added for compatibility purposes on 22/12/2016

		// Dropdown arrow spacing
		$css->set_selector( '.rtl .menu-item-has-children .dropdown-menu-toggle' );
		$css->add_property( 'padding-left', absint( $spacing_settings[ 'menu_item' ] ), false, 'px' );

		$css->set_selector( '.menu-item-has-children .dropdown-menu-toggle' );
		$css->add_property( 'padding-right', absint( $spacing_settings[ 'menu_item' ] ), absint( $og_defaults[ 'menu_item' ] ), 'px' );

		// Sub-menu dropdown arrow spacing
		$css->set_selector( '.menu-item-has-children ul .dropdown-menu-toggle' );
		$css->add_property( 'padding-top', absint( $spacing_settings[ 'sub_menu_item_height' ] ), absint( $og_defaults[ 'sub_menu_item_height' ] ), 'px' );
		$css->add_property( 'padding-bottom', absint( $spacing_settings[ 'sub_menu_item_height' ] ), absint( $og_defaults[ 'sub_menu_item_height' ] ), 'px' );
		$css->add_property( 'margin-top', '-' . absint( $spacing_settings[ 'sub_menu_item_height' ] ), '-' . absint( $og_defaults[ 'sub_menu_item_height' ] ), 'px' );

		// RTL menu item padding
		$css->set_selector( '.rtl .main-navigation .main-nav ul li.menu-item-has-children > a' );
		$css->add_property( 'padding-right', absint( $spacing_settings[ 'menu_item' ] ), false, 'px' );

		// Sidebar widget padding
		$css->set_selector( '.widget-area .widget' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'widget_top' ], $spacing_settings[ 'widget_right' ], $spacing_settings[ 'widget_bottom' ], $spacing_settings[ 'widget_left' ] ), dokani_padding_css( $og_defaults[ 'widget_top' ], $og_defaults[ 'widget_right' ], $og_defaults[ 'widget_bottom' ], $og_defaults[ 'widget_left' ] ) );

		// Footer widget padding
		$css->set_selector( '.footer-widgets' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'footer_widget_container_top' ], $spacing_settings[ 'footer_widget_container_right' ], $spacing_settings[ 'footer_widget_container_bottom' ], $spacing_settings[ 'footer_widget_container_left' ] ), dokani_padding_css( $og_defaults[ 'footer_widget_container_top' ], $og_defaults[ 'footer_widget_container_right' ], $og_defaults[ 'footer_widget_container_bottom' ], $og_defaults[ 'footer_widget_container_left' ] ) );

		// Footer widget separator
		$css->set_selector( '.site-footer .footer-widgets-container .inner-padding' );
		$css->add_property( 'padding', dokani_padding_css( '0', '0', '0', $spacing_settings[ 'footer_widget_separator' ] ), dokani_padding_css( '0', '0', '0', $og_defaults[ 'footer_widget_separator' ] ) );

		$css->set_selector( '.site-footer .footer-widgets-container .inside-footer-widgets' );
		$css->add_property( 'margin-left', '-' . absint( $spacing_settings[ 'footer_widget_separator' ] ), '-' . absint( $og_defaults[ 'footer_widget_separator' ] ), 'px' );

		// Footer padding
		$css->set_selector( '.site-info' );
		$css->add_property( 'padding', dokani_padding_css( $spacing_settings[ 'footer_top' ], $spacing_settings[ 'footer_right' ], $spacing_settings[ 'footer_bottom' ], $spacing_settings[ 'footer_left' ] ), dokani_padding_css( $og_defaults[ 'footer_top' ], $og_defaults[ 'footer_right' ], $og_defaults[ 'footer_bottom' ], $og_defaults[ 'footer_left' ] ) );

		// Add spacing back where dropdown arrow should be
		// Old versions of WP don't get nice things
		if ( version_compare( $GLOBALS['wp_version'], '4.4', '<' ) ) {
			$css->set_selector( '.main-navigation .main-nav ul li.menu-item-has-children>a, .secondary-navigation .main-nav ul li.menu-item-has-children>a' );
			$css->add_property( 'padding-right', absint( $spacing_settings[ 'menu_item' ] ), absint( $og_defaults[ 'menu_item' ] ), 'px' );
		}

		$output = '';
		// Get color settings
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_color_defaults()
		);

		// Find out if the content background color and sidebar widget background color is the same
		$sidebar = strtoupper( $dokani_settings['sidebar_widget_background_color'] );
		$content = strtoupper( $dokani_settings['content_background_color'] );
		$colors_match = ( ( $sidebar == $content ) || '' == $sidebar ) ? true : false;

		// If they're all 40 (default), remove the padding when one container is set
		// This way, the user can still adjust the padding and it will work (unless they want 40px padding)
		// We'll also remove the padding if there's no color difference between the widgets and content background color
		if ( ( '40' == $spacing_settings[ 'widget_top' ] && '40' == $spacing_settings[ 'widget_right' ] && '40' == $spacing_settings[ 'widget_bottom' ] && '40' == $spacing_settings[ 'widget_left' ] ) && $colors_match ) {
			$output .= '.one-container .sidebar .widget{padding:0px;}';
		}

		// Allow us to hook CSS into our output
		do_action( 'dokani_spacing_css', $css );

		return apply_filters( 'dokani_spacing_css_output', $css->css_output() . $output );
	}
}

/**
 * Generates any CSS that can't be cached (can change from page to page).
 *
 * @since 1.0.0
 */
function dokani_no_cache_dynamic_css() {
	// Initiate our class.
	$css = new dokani_CSS;

	return $css->css_output();
}

add_action( 'wp_enqueue_scripts', 'dokani_enqueue_dynamic_css', 50 );
/**
 * Enqueue our dynamic CSS.
 *
 * @since 1.0.0
 */
function dokani_enqueue_dynamic_css() {
	if ( ! get_option( 'dokani_dynamic_css_output', false ) || is_customize_preview() || apply_filters( 'dokani_dynamic_css_skip_cache', false ) ) {
		$css = dokani_base_css() . dokani_font_css() . dokani_advanced_css() . dokani_spacing_css();
	} else {
		$css = get_option( 'dokani_dynamic_css_output' ) . '/* End cached CSS */';
	}

	$css = $css . dokani_no_cache_dynamic_css();

	wp_add_inline_style( 'dokani-style', $css );
}

add_action( 'init', 'dokani_set_dynamic_css_cache' );
/**
 * Sets our dynamic CSS cache if it doesn't exist.
 *
 * If the theme version changed, bust the cache.
 *
 * @since 1.0.0
 */
function dokani_set_dynamic_css_cache() {
	if ( apply_filters( 'dokani_dynamic_css_skip_cache', false ) ) {
		return;
	}

	$cached_css = get_option( 'dokani_dynamic_css_output', false );
	$cached_version = get_option( 'dokani_dynamic_css_cached_version', '' );

	if ( ! $cached_css || $cached_version !== GENERATE_VERSION ) {
		$css = dokani_base_css() . dokani_font_css() . dokani_advanced_css() . dokani_spacing_css();

		update_option( 'dokani_dynamic_css_output', $css );
		update_option( 'dokani_dynamic_css_cached_version', GENERATE_VERSION );
	}
}

add_action( 'customize_save_after', 'dokani_update_dynamic_css_cache' );
/**
 * Update our CSS cache when done saving Customizer options.
 *
 * @since 1.0.0
 */
function dokani_update_dynamic_css_cache() {
	if ( apply_filters( 'dokani_dynamic_css_skip_cache', false ) ) {
		return;
	}

	$css = dokani_base_css() . dokani_font_css() . dokani_advanced_css() . dokani_spacing_css();
	update_option( 'dokani_dynamic_css_output', $css );
}
