<?php
/**
 * Cart elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_cart_position' ) ) {
	/**
	 * Build the cart.
	 *
	 * @since 0.1
	 */
	function dokanee_cart_position() {
	    $cart = '';
        $cart .= '<li id="dokane-menu-cart-wrapper">';
            $cart .= '<a href="#" class="dropdown-toggle dokanee-menu-cart" data-toggle="dropdown">' . sprintf( __( 'Cart %s', 'dokanee' ), '<span class="dokan-cart-amount-top">(' . WC()->cart->get_cart_total() . ')</span>' ) .'<b class="caret"></b></a>';

            $cart .= '<ul class="dropdown-menu">';
                $cart .= '<li>';
		            $cart .= '<div class="widget_shopping_cart_content"></div>';
                $cart .= '</li>';
            $cart .= '</ul>';
        $cart .= '</li>';
        if ( ! function_exists( 'dokan' ) ) {
	        $cart .= '<li>';
	        $cart .= '<a class="dokanee-menu-user" href="' . wc_get_page_permalink( 'myaccount' ) . '">' . __( 'My Account', 'dokanee' ) . '</a>';
	        $cart .= '</li>';
        }

        return $cart;
	}
}


if ( ! function_exists( 'dokanee_add_cart_menu_after_search' ) ) {
	add_action( 'dokanee_after_header_right', 'dokanee_add_cart_menu_after_search', 10 );
	function dokanee_add_cart_menu_after_search() {
		$cart_topbar = dokanee_get_setting( 'cart_position_setting' );

		if( 'cart-search' == $cart_topbar ) {
			echo '<ul class="header-cart-menu no-list-style m-0">';
			echo dokanee_cart_position();
			echo '</ul>';
		}
	}
}