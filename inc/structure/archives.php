<?php
/**
 * Archive elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_archive_title' ) ) {
	add_action( 'dokanee_archive_title', 'dokanee_archive_title' );
	/**
	 * Build the archive title
	 *
	 * @since 1.3.24
	 */
	function dokanee_archive_title() {
		if ( ! function_exists( 'the_archive_title' ) ) {
			return;
		}
		?>
			<?php
			/**
			 * dokanee_before_archive_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_before_archive_title' );
			?>

            <?php the_archive_title(); ?>

			<?php
			/**
			 * dokanee_after_archive_title hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_after_archive_title' );

			// Show an optional term description.
			$term_description = term_description();
			if ( ! empty( $term_description ) ) {
				printf( '<div class="taxonomy-description">%s</div>', $term_description ); // WPCS: XSS ok, sanitization ok.
			}

			if ( get_the_author_meta( 'description' ) && is_author() ) {
				echo '<div class="author-info">' . get_the_author_meta( 'description' ) . '</div>'; // WPCS: XSS ok, sanitization ok.
			}

			/**
			 * dokanee_after_archive_description hook.
			 *
			 * @since 0.1
			 */
			do_action( 'dokanee_after_archive_description' ); ?>
		<?php
	}
}

if ( ! function_exists( 'dokanee_filter_the_archive_title' ) ) {
	add_filter( 'get_the_archive_title', 'dokanee_filter_the_archive_title' );
	/**
	 * Alter the_archive_title() function to match our original archive title function
	 *
	 * @since 1.3.45
	 *
	 * @param string $title The archive title
	 * @return string The altered archive title
	 */
	function dokanee_filter_the_archive_title( $title ) {
		if ( is_category() ) {
			$title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
			$title = single_tag_title( '', false );
		} elseif ( is_author() ) {
			/*
			 * Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			 */
			the_post();
			$title = sprintf( '%1$s<span class="vcard">%2$s</span>',
				get_avatar( get_the_author_meta( 'ID' ), 75 ),
				get_the_author()
			);
			/*
			 * Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();
		} elseif ( is_post_type_archive( 'product' ) ) {
			$title = woocommerce_page_title() ;
		}

		return $title;

	}
}
