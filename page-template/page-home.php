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
 * @package dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>

<div id="primary" class="content-area grid-parent grid-100">
	<main id="main" <?php dokanee_main_class(); ?>>

		<?php
		/**
		 * dokanee_before_main_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'dokanee_before_main_content' );

		/**
		 * get content-front-page template
		 *
		 * @since 0.1
		 */
		if ( class_exists( 'WooCommerce' ) ) {
			get_template_part( 'template-parts/page/content', 'front-page' );
		} else {
            echo '<div class="grid-container home-template-without-wc">';
			if ( have_posts() ) :

				while ( have_posts() ) : the_post();

					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

				endwhile;

			else :

				get_template_part( 'no-results', 'index' );

			endif;
			echo '</div>';
		}

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

get_footer();


