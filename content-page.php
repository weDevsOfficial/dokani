<?php
/**
 * The template used for displaying page content in page.php
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
            <?php the_title( '<h1 class="entry-title" itemprop="headline">', '</h1>' ); ?>
            <?php if ( function_exists( 'dokan_is_store_listing' ) && dokan_is_store_listing() ) { ?>
                <div class="dokan-seller-view buttons box-shadow">
                    <button class="list"><i class="fa fa-bars"></i></button>
                    <button class="grid active"><i class="fa fa-th-large"></i></button>
                </div>
            <?php } ?>
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
		 * dokani_after_content hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_content' );
		?>
	</div><!-- .inside-article -->
</article><!-- #post-## -->
