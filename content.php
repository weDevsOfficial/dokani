<?php
/**
 * The template for displaying posts within the loop.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php dokani_article_schema( 'CreativeWork' ); ?>>
    <?php
    $is_fluid_content = get_theme_mod( 'fluid_featured_image', 'on' );
    $is_show_comments = get_theme_mod( 'blog_post_show_comment', 'on' );
    $is_show_category = get_theme_mod( 'blog_post_show_category', 'on' );
    $is_show_tag = get_theme_mod( 'blog_post_show_tag', 'on' );
    $is_show_author = get_theme_mod( 'blog_post_show_author', 'on' );
    $is_show_date = get_theme_mod( 'blog_post_show_date', 'on' );
    ?>
	<div class="inside-article <?php echo ( $is_fluid_content ) ? 'is-fluid-content' : ''; ?>">
		<?php
		/**
		 * dokani_before_content hook.
		 *
		 * @since 1.0.0
		 *
		 * @hooked dokani_featured_page_header_inside_single - 10
		 * @hooked dokani_post_image - 20
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

			the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

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
