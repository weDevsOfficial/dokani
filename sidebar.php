<?php
/**
 * The Sidebar containing the main widget areas.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// If the navigation is set in the sidebar, set variable to true.
$navigation_active = ( 'nav-right-sidebar' == dokani_get_navigation_location() ) ? true : false;

// If the secondary navigation is set in the sidebar, set variable to true.
if ( function_exists( 'dokani_secondary_nav_get_defaults' ) ) {
	$secondary_nav = wp_parse_args(
		get_option( 'dokani_secondary_nav_settings', array() ),
		dokani_secondary_nav_get_defaults()
	);

	if ( 'secondary-nav-right-sidebar' == $secondary_nav['secondary_nav_position_setting'] ) {
		$navigation_active = true;
	}
}
?>
<div id="right-sidebar" itemtype="https://schema.org/WPSideBar" itemscope="itemscope" <?php dokani_right_sidebar_class(); ?>>
	<?php
	if ( function_exists( 'dokan_is_store_listing' ) && dokan_is_store_listing() ) {
		woocommerce_breadcrumb();
	}
	?>
	<div class="inside-right-sidebar">
		<?php
		/**
		 * dokani_before_right_sidebar_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_before_right_sidebar_content' );

		if ( function_exists( 'dokan_is_store_listing' ) && dokan_is_store_listing() ) :

			if ( ! dynamic_sidebar( 'store-list' ) ) :

				if ( false == $navigation_active ) : ?>

					<aside id="search" class="widget widget_search">
						<?php get_search_form(); ?>
					</aside>

					<?php
						$dokani_product_category_widget_instance = array(
							'title'	=> esc_html__( 'Browse Category', 'dokani' ),
						);
						$dokani_product_category_widget_args     = array(
							'before_widget' => '<aside class="widget dokan-category-menu">',
							'after_widget'  => '</aside>',
							'before_title'  => '<h2 class="widget-title">',
							'after_title'   => '</h2>',
						);
						the_widget( 'WeDevs\Dokan\Widgets\ProductCategoryMenu', $dokani_product_category_widget_instance, $dokani_product_category_widget_args );
						?>

				<?php endif;

			endif;

        elseif ( is_archive( 'product' ) ) :

			if ( ! dynamic_sidebar( 'shop' ) ) :

				if ( false == $navigation_active ) : ?>

                    <aside id="archives" class="widget">
                        <h2 class="widget-title"><?php esc_html_e( 'Archives', 'dokani' ); ?></h2>
                        <ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </aside>

				<?php endif;

			endif;

        elseif ( class_exists( 'WooCommerce' ) && is_product() ) :

			if ( ! dynamic_sidebar( 'product' ) ) :

				if ( false == $navigation_active ) : ?>

                    <aside id="archives" class="widget">
                        <h2 class="widget-title"><?php esc_html_e( 'Archives', 'dokani' ); ?></h2>
                        <ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </aside>

				<?php endif;

			endif;

		elseif ( function_exists( 'dokan_is_store_page' ) && dokan_is_store_page() ) :

			if ( dokan_get_option( 'enable_theme_store_sidebar', 'dokan_general', 'off' ) == 'off' ) :

                if ( ! dynamic_sidebar( 'sidebar-store' ) ) :
                    $args = array(
                        'before_widget' => '<aside class="widget inner-padding">',
                        'after_widget'  => '</aside>',
                        'before_title'  => '<h3 class="widget-title">',
                        'after_title'   => '</h3>',
                    );

                    if ( class_exists( 'store_location' ) ) :

                        the_widget( 'store_category_menu', array( 'title' => __( 'Store Category', 'dokani' ) ), $args );

                        if ( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on' && ! empty( $map_location ) ) {
                            the_widget( 'store_location', array( 'title' => __( 'Store Location', 'dokani' ) ), $args );
                        }

                        if ( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                            the_widget( 'store_contact_form', array( 'title' => __( 'Contact Vendor', 'dokani' ) ), $args );
                        }
                    endif;

                endif;

			else :

                if ( ! dynamic_sidebar( 'sidebar-store' ) ) :

                $args = array(
                    'before_widget' => '<aside class="widget inner-padding">',
                    'after_widget'  => '</aside>',
                    'before_title'  => '<h3 class="widget-title">',
                    'after_title'   => '</h3>',
                );

                if ( false == $navigation_active ) : ?>

                    <?php

                    if ( class_exists( 'Dokan_Store_Location' ) ) {
                        the_widget( 'Dokan_Store_Category_Menu', array( 'title' => __( 'Store Category', 'dokani' ) ), $args );

                        if ( dokan_get_option( 'store_map', 'dokan_general', 'on' ) == 'on' && ! empty( $map_location ) ) {
                            the_widget( 'Dokan_Store_Location', array( 'title' => __( 'Store Location', 'dokani' ) ), $args );
                        }

                        if ( dokan_get_option( 'contact_seller', 'dokan_general', 'on' ) == 'on' ) {
                            the_widget( 'Dokan_Store_Contact_Form', array( 'title' => __( 'Contact Vendor', 'dokani' ) ), $args );
                        }
                    }

                    ?>

                <?php endif;

            endif;

			endif;

		else :
			if ( ! dynamic_sidebar( 'sidebar-1' ) ) :

				if ( false == $navigation_active ) : ?>

                    <aside id="search" class="widget widget_search">
						<?php get_search_form(); ?>
                    </aside>

                    <aside id="archives" class="widget">
                        <h2 class="widget-title"><?php esc_html_e( 'Archives', 'dokani' ); ?></h2>
                        <ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </aside>

				<?php endif;

			endif;
		endif;


		/**
		 * dokani_after_right_sidebar_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_right_sidebar_content' );
		?>
	</div><!-- .inside-right-sidebar -->
</div><!-- #secondary -->
