<?php
/**
 * Featured image elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_post_image' ) ) {
	add_action( 'dokanee_before_content', 'dokanee_post_image', 20 );
	/**
	 * Prints the Post Image to post excerpts
	 */
	function dokanee_post_image() {
		// If there's no featured image, return.
		if ( ! has_post_thumbnail() ) {
			return;
		}

		// If we're not on any single post/page or the 404 template, we must be showing excerpts.
		if ( ! is_singular() && ! is_404() ) {
			echo apply_filters( 'dokanee_featured_image_output', sprintf( // WPCS: XSS ok.
				'<div class="post-image">
					<a href="%1$s">
						%2$s
					</a>
				</div>',
				esc_url( get_permalink() ),
				get_the_post_thumbnail(
					get_the_ID(),
					apply_filters( 'dokanee_page_header_default_size', 'full' ),
					array(
						'itemprop' => 'image',
					)
				)
			) );
		}
	}
}

if ( ! function_exists( 'dokanee_featured_page_header_area' ) ) {
	/**
	 * Build the page header.
	 *
	 * @since 1.0.0
	 *
	 * @param string The featured image container class
	 */
	function dokanee_featured_page_header_area( $class ) {
		// Don't run the function unless we're on a page it applies to.
		if ( ! is_singular() ) {
			return;
		}

		// Don't run the function unless we have a post thumbnail.
		if ( ! has_post_thumbnail() ) {
			return;
		}
		?>
		<div class="<?php echo esc_attr( $class ); ?> grid-container grid-parent">
			<?php the_post_thumbnail(
				apply_filters( 'dokanee_page_header_default_size', 'full' ),
				array(
					'itemprop' => 'image',
					'alt' => the_title_attribute( 'echo=0' )
				)
			); ?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'dokanee_featured_page_header' ) ) {
	add_action( 'dokanee_after_header', 'dokanee_featured_page_header', 10 );
	/**
	 * Add page header above content.
	 *
	 * @since 1.0.0
	 */
	function dokanee_featured_page_header() {
		if ( function_exists( 'dokanee_page_header' ) ) {
			return;
		}

		if ( is_page() ) {
			dokanee_featured_page_header_area( 'page-header-image' );
		}
	}
}

if ( ! function_exists( 'dokanee_featured_page_header_inside_single' ) ) {
	add_action( 'dokanee_before_content', 'dokanee_featured_page_header_inside_single', 10 );
	/**
	 * Add post header inside content.
	 * Only add to single post.
	 *
	 * @since 1.0.0
	 */
	function dokanee_featured_page_header_inside_single() {
		if ( function_exists( 'dokanee_page_header' ) ) {
			return;
		}

		if ( is_single() ) {
			dokanee_featured_page_header_area( 'page-header-image-single' );
		}
	}
}
