<?php
/**
 * The template for displaying the homepage.
 *
 * This page template will display any functions hooked into the `homepage` action.
 * By default this includes a variety of product displays and the page content itself. To change the order or toggle these components
 * use the Homepage Control plugin.
 * https://wordpress.org/plugins/homepage-control/
 *
 * Template name: Homepage
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" class="content-area grid-parent grid-100">
	<main id="main" <?php dokani_main_class(); ?>>

		<?php
		/**
		 * dokani_before_main_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_before_main_content' );

		/**
		 * get content-front-page template
		 *
		 * @since 1.0.0
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'template-parts/page/content', 'front-page' );
		} else { ?>
            <div class="grid-container home-template-without-wc">
			    <div class="woocommerce-notices-wrapper"><div class="woocommerce-message" role="alert">
                    <?php
                    echo __( 'Looks like you are using Homepage template without WooCommerce, This template is designed for WooCommerce so for using this page-template please install WooCommerce First!', 'dokani' );
                    ?>
                </div>
            </div>

		<?php }

		/**
		 * dokani_after_main_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_main_content' );
		?>
	</main><!-- #main -->
</div><!-- #primary -->

<?php
/**
 * dokani_after_primary_content_area hook.
 *
 * @since 1.0.0
 */
do_action( 'dokani_after_primary_content_area' );

get_footer();


