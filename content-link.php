<?php
/**
 * The template for displaying Link post formats.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php dokani_article_schema( 'CreativeWork' ); ?>>
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

		<header class="entry-header">
			<?php
			/**
			 * dokani_before_entry_title hook.
			 *
			 * @since 1.0.0
			 */
			do_action( 'dokani_before_entry_title' );

			the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( dokani_get_link_url() ) ), '</a></h2>' );

			/**
			 * dokani_after_entry_title hook.
			 *
			 * @since 1.0.0
			 *
			 * @hooked dokani_post_meta - 10
			 */
			do_action( 'dokani_after_entry_title' );
			?>
		</header><!-- .entry-header -->

		<?php
		/**
		 * dokani_after_entry_header hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked dokani_post_image - 10
		 */
		do_action( 'dokani_after_entry_header' );

		if ( dokani_show_excerpt() ) : ?>

			<div class="entry-summary" itemprop="text">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

		<?php else : ?>

			<div class="entry-content" itemprop="text">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'dokani' ),
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->

		<?php endif;

		/**
		 * dokani_after_entry_content hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked dokani_footer_meta - 10
		 */
		do_action( 'dokani_after_entry_content' );

		/**
		 * dokani_after_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
