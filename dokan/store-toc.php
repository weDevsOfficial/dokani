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
?>

<?php dokan_get_template_part( 'store-header' ); ?>

<div class="grid-container">
	<div id="primary" <?php dokanee_content_class(); ?>>
		<main id="dokan-content" <?php dokanee_main_class(); ?>>
			<?php

			/**
			 * dokanee_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_before_main_content' );

			?>


			<div id="store-toc-wrapper">
				<div id="store-toc">
					<?php if ( ! empty( $vendor->get_store_tnc() ) ): ?>
						<h2 class="headline"><?php esc_html_e( 'Terms And Conditions', 'dokanee' ); ?></h2>
						<div>
							<?php echo wp_kses_post( nl2br( $vendor->get_store_tnc() ) ); ?>
						</div>
					<?php endif; ?>
				</div><!-- #store-toc -->
			</div><!-- #store-toc-wrap -->

			<?php

			/**
			 * dokanee_after_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_after_main_content' );

			?>

		</main><!-- #content .site-content -->

	</div><!-- #primary .content-area -->
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

<div class="dokan-clearfix"></div>

<?php do_action( 'woocommerce_after_main_content' ); ?>

<?php get_footer(); ?>
