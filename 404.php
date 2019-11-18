<?php
/**
 * The template for displaying 404 pages (Not Found).
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
			?>

			<div class="inside-article">

				<?php
				/**
				 * dokani_before_content hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked dokani_featured_page_header_inside_single - 10
				 */
				do_action( 'dokani_before_content' );
				?>

				<?php
				/**
				 * dokani_after_entry_header hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked dokani_post_image - 10
				 */
				do_action( 'dokani_after_entry_header' );
				?>

				<div class="entry-content" itemprop="text">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/404_illustration@2x.png" alt="<?php _e( 'Not Found', 'dokani' ); ?>" class="not-found-thumb">
                    <h2><?php _e( 'Page Not Found', 'dokani' ); ?></h2>
					<?php
					echo '<p>' . apply_filters( 'dokani_404_text', __( 'For Some Reason The Page You Requested Could Not Be Found On Our Server', 'dokani' ) ) . '</p>'; // WPCS: XSS OK.

					get_search_form();
					?>
				</div><!-- .entry-content -->

				<?php
				/**
				 * dokani_after_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_after_content' );
				?>

			</div><!-- .inside-article -->

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

get_footer();
