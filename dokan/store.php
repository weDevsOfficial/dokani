<?php
/**
 * The Template for displaying single vendor.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$store_user   = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info   = $store_user->get_shop_info();
$map_location = $store_user->get_location();

get_header(); ?>

    <?php dokan_get_template_part( 'store-header' ); ?>

    <div class="grid-container">

        <div id="primary" <?php dokanee_content_class();?>>

            <main id="main" <?php dokanee_main_class(); ?>>

                <?php

                /**
                 * dokanee_before_main_content hook.
                 *
                 * @since 0.1
                 */
                do_action( 'dokanee_before_main_content' );

                ?>

                <?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

                <?php if ( have_posts() ) { ?>

                    <div class="seller-items">

                        <?php woocommerce_product_loop_start(); ?>

                        <?php while ( have_posts() ) : the_post(); ?>

                            <?php wc_get_template_part( 'content', 'product' ); ?>

                        <?php endwhile; // end of the loop. ?>

                        <?php woocommerce_product_loop_end(); ?>

                    </div>

                    <?php dokan_content_nav( 'nav-below' ); ?>

                <?php } else { ?>

                    <p class="dokan-info"><?php _e( 'No products were found of this vendor!', 'dokan-lite' ); ?></p>

                <?php }

                /**
                 * dokanee_after_main_content hook.
                 *
                 * @since 0.1
                 */
                do_action( 'dokanee_after_main_content' );

                ?>

            </main><!-- #main -->

        </div><!-- #primary -->

        <?php

        /**
         * dokanee_after_primary_content_area hook.
         *
         * @since 2.0
         */
        do_action( 'dokanee_after_primary_content_area' );

        dokanee_construct_sidebars();

        ?>

    </div>

<?php get_footer();