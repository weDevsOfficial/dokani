<?php
/**
 * The template for displaying posts within the loop.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php dokanee_article_schema( 'CreativeWork' ); ?>>
    <?php
    $is_fluid_content = get_theme_mod( 'fluid_featured_image' );
    $is_show_comments = get_theme_mod( 'blog_post_show_comment' );
    $is_show_category = get_theme_mod( 'blog_post_show_category' );
    $is_show_tag = get_theme_mod( 'blog_post_show_tag' );
    $is_show_author = get_theme_mod( 'blog_post_show_author' );
    $is_show_date = get_theme_mod( 'blog_post_show_date' );
    ?>
	<div class="inside-article <?php echo ( $is_fluid_content ) ? 'is-fluid-content' : ''; ?>">
		<?php
		/**
		 * dokanee_before_content hook.
		 *
		 * @since 0.1
		 *
		 * @hooked dokanee_featured_page_header_inside_single - 10
		 * @hooked dokanee_post_image - 20
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

			the_title( sprintf( '<h2 class="entry-title" itemprop="headline"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );

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
		 */
		do_action( 'dokanee_after_entry_header' );

		if ( dokanee_show_excerpt() ) : ?>

			<div class="entry-summary" itemprop="text">
				<?php the_excerpt(); ?>
			</div><!-- .entry-summary -->

		<?php else : ?>

			<div class="entry-content" itemprop="text">
				<?php
				the_content();

				wp_link_pages( array(
					'before' => '<div class="page-links">' . __( 'Pages:', 'dokanee' ),
					'after'  => '</div>',
				) );
				?>
			</div><!-- .entry-content -->

		<?php endif;

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
