<?php
/**
 * Full Width Page Template.
 *
 * Template Name: Full Width Page
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

	<div id="primary" <?php dokanee_content_class();?>>
		<main id="main" <?php dokanee_main_class(); ?>>
			<?php
			/**
			 * dokanee_before_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokanee_before_main_content' );

			while ( have_posts() ) : the_post();

                /**
                 * dokanee_before_content hook.
                 *
                 * @since 1.0.0
                 *
                 * @hooked dokanee_featured_page_header_inside_single - 10
                 */
                do_action( 'dokanee_before_content' );

				    the_content();

				/**
				 * dokanee_after_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokanee_after_content' );

			endwhile;

			/**
			 * dokanee_after_main_content hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokanee_after_main_content' );
			?>
		</main><!-- #main -->
	</div><!-- #primary -->

	<?php
	/**
	 * dokanee_after_primary_content_area hook.
	 *
	 * @since 1.0.0
	 */
	 do_action( 'dokanee_after_primary_content_area' );

get_footer();
