<?php
/**
 * The main template file for homepage.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Dokanee
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
            get_template_part( 'template-parts/page/content', 'front-page' );

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

