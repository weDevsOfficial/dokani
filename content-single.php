<?php
/**
 * The template for displaying single posts.
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
    ?>
	<div class="inside-article <?php echo ( $is_fluid_content ) ? 'is-fluid-content' : ''; ?>">
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

            the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' );

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
		?>

		<div class="entry-content" itemprop="text">
			<?php
			the_content();

			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'dokani' ),
				'after'  => '</div>',
			) );
			?>
		</div><!-- .entry-content -->

		<?php
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
	<?php
	/**
	 * dokane_post_meta hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokane_post_meta - 10
	 */
	do_action( 'dokane_post_meta' );

	/**
	 * dokani_post_nav hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_post_nav - 10
	 */
	do_action( 'dokani_post_nav' );

	/**
	 * dokani_post_author_profile hook.
	 *
	 * @since 1.0.0
	 *
	 * @hooked dokani_post_author_profile - 10
	 */
	do_action( 'dokani_post_author_profile' );
	?>
</article><!-- #post-## -->
