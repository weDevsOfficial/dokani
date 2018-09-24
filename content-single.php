<?php
/**
 * The template for displaying single posts.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php dokanee_article_schema( 'CreativeWork' ); ?>>
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
			<?php
			/**
			 * dokanee_before_entry_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_before_entry_title' );

            the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );

			/**
			 * dokanee_after_entry_title hook.
			 *
			 * @since 0.1
			 *
			 * @hooked dokanee_post_meta - 10
			 */
			do_action( 'dokanee_after_entry_title' );
			?>
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
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'dokanee' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

		<?php
		/**
		 * dokanee_after_entry_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked dokanee_footer_meta - 10
		 */
		do_action( 'dokanee_after_entry_content' );

		/**
		 * dokanee_after_content hook.
		 *
		 * @since 0.1
		 */
		do_action( 'dokanee_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
