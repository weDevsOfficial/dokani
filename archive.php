<?php
/**
 * The template for displaying Archive pages.
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

			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;

				dokanee_content_nav( 'nav-below' );

			else :

				get_template_part( 'no-results', 'archive' );

			endif;

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
