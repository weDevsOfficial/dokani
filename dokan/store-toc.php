<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokan
 * @package dokan - 2014 1.0
 */

$vendor      = dokan()->vendor->get( get_query_var( 'author' ) );
$vendor_info = $vendor->get_shop_info();

get_header();

dokan_get_template_part( 'store-header' ); ?>

<div class="grid-container">
	<div id="primary" <?php dokani_content_class( 'dokan-single-store' ); ?>>
		<main id="dokan-content" <?php dokani_main_class(); ?>>
			<?php

			/**
			 * dokani_before_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokani_before_main_content' );

			?>


			<div id="store-toc-wrapper">
				<div id="store-toc">
					<?php if ( ! empty( $vendor->get_store_tnc() ) ) : ?>
						<h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'dokani' ); ?></h2>
						<div>
							<?php echo wp_kses_post( nl2br( $vendor->get_store_tnc() ) ); ?>
						</div>
					<?php endif; ?>
				</div><!-- #store-toc -->
			</div><!-- #store-toc-wrap -->

			<?php

			/**
			 * dokani_after_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokani_after_main_content' );

			?>

		</main><!-- #content .site-content -->

	</div><!-- #primary .content-area -->
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

<div class="dokan-clearfix"></div>

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer(); ?>
