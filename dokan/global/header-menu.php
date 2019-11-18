<?php
/**
 * Dokanee Header Menu Template
 *
 * @since 1.0.0
 *
 * @package Dokanee
 */
?>

<ul class="nav navbar-nav navbar-right">
    <?php
    $cart_topbar = dokanee_get_setting( 'cart_position_setting' );

    if ( 'cart-topbar' == $cart_topbar){
        echo dokanee_cart_position();
    }
    ?>

    <?php if ( is_user_logged_in() ) { ?>

        <?php

        if ( dokan_is_user_seller( $user_id ) ) {
            ?>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle dokanee-menu-vendor-dashboard" data-toggle="dropdown"><?php _e( 'Vendor Dashboard', 'dokanee' ); ?> <i class="fa fa-angle-down"></i></a>

                <ul class="dropdown-menu">
                    <li><a href="<?php echo dokan_get_store_url( $user_id ); ?>" target="_blank"><?php _e( 'Visit your store', 'dokanee' ); ?> <i class="fa fa-external-link"></i></a></li>
                    <li class="divider"></li>
                    <?php
                    foreach ( $nav_urls as $key => $item ) {
                        printf( '<li><a href="%s">%s &nbsp;%s</a></li>', $item['url'], $item['icon'], $item['title'] );
                    }
                    ?>
                </ul>
            </li>
        <?php } ?>

        <li class="dropdown">
            <a href="#" class="dropdown-toggle dokanee-menu-user" data-toggle="dropdown"><?php echo esc_html( $current_user->display_name ); ?> <i class="fa fa-angle-down"></i></a>
            <ul class="dropdown-menu">
                <li><a href="<?php echo dokan_get_page_url( 'my_orders' ); ?>"><?php _e( 'My Orders', 'dokanee' ); ?></a></li>
                <li><a href="<?php echo dokan_get_page_url( 'myaccount', 'woocommerce' ); ?>"><?php _e( 'My Account', 'dokanee' ); ?></a></li>
                <li><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e( 'Edit Account', 'dokanee' ); ?></a></li>
                <li class="divider"></li>
                <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Billing Address', 'dokanee' ); ?></a></li>
                <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Shipping Address', 'dokanee' ); ?></a></li>
                <li><?php wp_loginout( home_url() ); ?></li>
            </ul>
        </li>

    <?php } else { ?>
        <li><a href="<?php echo dokan_get_page_url( 'myaccount', 'woocommerce' ); ?>" class="dokanee-menu-login"><?php _e( 'Login / Register', 'dokanee' ); ?></a></li>
    <?php } ?>
</ul>
