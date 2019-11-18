<?php
/**
 * Full Width Page Template.
 *
 * Template Name: Full Width Page
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php dokani_content_class();?>>
		<main id="main" <?php dokani_main_class(); ?>>
			<?php
			/**
			 * dokani_before_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokani_before_main_content' );

			while ( have_posts() ) : the_post();

                /**
                 * dokani_before_content hook.
                 *
                 * @since 1.0.0
                 *
                 * @hooked dokani_featured_page_header_inside_single - 10
                 */
                do_action( 'dokani_before_content' );

				    the_content();

				/**
				 * dokani_after_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_after_content' );

			endwhile;

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
