<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php dokanee_content_class(); ?>>
		<main id="main" <?php dokanee_main_class(); ?>>
			<?php
			/**
			 * dokanee_before_main_content hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_before_main_content' );
			?>

			<div class="inside-article">

				<?php
				/**
				 * dokanee_before_content hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_featured_page_header_inside_single - 10
				 */
				do_action( 'dokanee_before_content' );
				?>

				<header class="entry-header">
					<h1 class="entry-title" itemprop="headline"><?php echo apply_filters( 'dokanee_404_title', __( 'Oops! That page can&rsquo;t be found.', 'dokanee' ) ); // WPCS: XSS OK. ?></h1>
				</header><!-- .entry-header -->

				<?php
				/**
				 * dokanee_after_entry_header hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_post_image - 10
				 */
				do_action( 'dokanee_after_entry_header' );
				?>

				<div class="entry-content" itemprop="text">
					<?php
					echo '<p>' . apply_filters( 'dokanee_404_text', __( 'It looks like nothing was found at this location. Maybe try searching?', 'dokanee' ) ) . '</p>'; // WPCS: XSS OK.

					get_search_form();
					?>
				</div><!-- .entry-content -->

				<?php
				/**
				 * dokanee_after_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'dokanee_after_content' );
				?>

			</div><!-- .inside-article -->

			<?php
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

get_footer();
