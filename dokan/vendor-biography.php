<?php
/**
 * The Template for displaying vendor biography.
 *
 * @package dokan
 */

$store_user   = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->ID );
$map_location = $store_user->get_location();

get_header();

dokan_get_template_part( 'store-header' ); ?>

<div class="grid-container">

    <div id="primary" <?php dokani_content_class( 'dokan-single-store' ); ?>>

        <main id="main" <?php dokani_main_class(); ?>>

            <?php

            /**
             * dokani_before_main_content hook.
             *
             * @since 1.0.0
             */
            do_action( 'dokani_before_main_content' );

            ?>

            <div class="store-review-wrap woocommerce">

                <div id="vendor-biography">
                    <div id="comments">

                    <?php do_action( 'dokan_vendor_biography_tab_before', $store_user, $store_info ); ?>

                    <h2 class="headline"><?php echo apply_filters( 'dokan_vendor_biography_title', __( 'Vendor Biography', 'dokan' ) ); ?></h2>

                    <?php
                        if ( ! empty( $store_info['vendor_biography'] ) ) {
                            printf( '%s', apply_filters( 'the_content', $store_info['vendor_biography'] ) );
                        }
                        do_action( 'dokan_vendor_biography_tab_after', $store_user, $store_info ); ?>

                    </div>
                </div>


            </div><!-- #content .site-content -->

            <?php

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

    dokani_construct_sidebars();

    ?>

</div>

<?php
	get_footer();
