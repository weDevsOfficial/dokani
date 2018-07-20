<?php
/**
 * Post meta elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable.
	 *
	 * @since 0.1
	 *
	 * @param string $nav_id The id of our navigation.
	 */
	function dokanee_content_nav( $nav_id ) {
		if ( ! apply_filters( 'dokanee_show_post_navigation', true ) ) {
			return;
		}

		global $wp_query, $post;

		// Don't print empty markup on single pages if there's nowhere to navigate.
		if ( is_single() ) {
			$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
			$next = get_adjacent_post( false, '', false );

			if ( ! $next && ! $previous ) {
				return;
			}
		}

		// Don't print empty markup in archives if there's only one page.
		if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
			return;
		}

		$nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';
		$category_specific = apply_filters( 'dokanee_category_post_navigation', false );
		?>
		<nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'dokanee' ); ?></span>

			<?php if ( is_single() ) : // navigation links for single posts.

				previous_post_link( '<div class="nav-previous"><span class="prev" title="' . esc_attr__( 'Previous', 'dokanee' ) . '">%link</span></div>', '%title', $category_specific );
				next_post_link( '<div class="nav-next"><span class="next" title="' . esc_attr__( 'Next', 'dokanee' ) . '">%link</span></div>', '%title', $category_specific );

			elseif ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages.

				if ( get_next_posts_link() ) : ?>
					<div class="nav-previous"><span class="prev" title="<?php esc_attr_e( 'Previous', 'dokanee' );?>"><?php next_posts_link( __( 'Older posts', 'dokanee' ) ); ?></span></div>
				<?php endif;

				if ( get_previous_posts_link() ) : ?>
					<div class="nav-next"><span class="next" title="<?php esc_attr_e( 'Next', 'dokanee' );?>"><?php previous_posts_link( __( 'Newer posts', 'dokanee' ) ); ?></span></div>
				<?php endif;

				if ( function_exists( 'the_posts_pagination' ) ) {
					the_posts_pagination( array(
						'mid_size' => apply_filters( 'dokanee_pagination_mid_size', 1 ),
						'prev_text' => apply_filters( 'dokanee_previous_link_text', __( '&larr;', 'dokanee' ) ),
						'next_text' => apply_filters( 'dokanee_next_link_text', __( '&rarr;', 'dokanee' ) ),
					) );
				}

				/**
				 * dokanee_paging_navigation hook.
				 *
				 * @since 0.1
				 */
				do_action( 'dokanee_paging_navigation' );

			endif; ?>
		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
		<?php
	}
}

if ( ! function_exists( 'dokanee_modify_posts_pagination_template' ) ) {
	add_filter( 'navigation_markup_template', 'dokanee_modify_posts_pagination_template', 10, 2 );
	/**
	 * Remove the container and screen reader text from the_posts_pagination()
	 * We add this in ourselves in dokanee_content_nav()
	 *
	 * @since 1.3.45
	 *
	 * @param string $template The default template.
	 * @param string $class The class passed by the calling function.
	 * @return string The HTML for the post navigation.
	 */
	function dokanee_modify_posts_pagination_template( $template, $class ) {
	    if ( ! empty( $class ) && false !== strpos( $class, 'pagination' ) ) {
	        $template = '<div class="nav-links">%3$s</div>';
	    }

	    return $template;
	}
}

if ( ! function_exists( 'dokanee_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 0.1
	 */
	function dokanee_posted_on() {
		$date = apply_filters( 'dokanee_post_date', true );
		$author = apply_filters( 'dokanee_post_author', true );

		$time_string = '<time class="entry-date published" datetime="%1$s" itemprop="datePublished">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="updated" datetime="%3$s" itemprop="dateModified">%4$s</time>' . $time_string;
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( 'c' ) ),
			esc_html( get_the_modified_date() )
		);

		// If our date is enabled, show it.
		if ( $date ) {
			echo apply_filters( 'dokanee_post_date_output', sprintf( '<span class="posted-on">%1$s</span>', // WPCS: XSS ok, sanitization ok.
				sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
					esc_url( get_permalink() ),
					esc_attr( get_the_time() ),
					$time_string
				)
			), $time_string );
		}

		// If our author is enabled, show it.
		if ( $author ) {
			echo apply_filters( 'dokanee_post_author_output', sprintf( ' <span class="byline">%1$s</span>', // WPCS: XSS ok, sanitization ok.
				sprintf( '<span class="author vcard" itemtype="https://schema.org/Person" itemscope="itemscope" itemprop="author">%1$s <a class="url fn n" href="%2$s" title="%3$s" rel="author" itemprop="url"><span class="author-name" itemprop="name">%4$s</span></a></span>',
					__( 'by','dokanee'),
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					/* translators: 1: Author name */
					esc_attr( sprintf( __( 'View all posts by %s', 'dokanee' ), get_the_author() ) ),
					esc_html( get_the_author() )
				)
			) );
		}
	}
}

if ( ! function_exists( 'dokanee_entry_meta' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * @since 1.2.5
	 */
	function dokanee_entry_meta() {
		$categories = apply_filters( 'dokanee_show_categories', true );
		$tags = apply_filters( 'dokanee_show_tags', true );
		$comments = apply_filters( 'dokanee_show_comments', true );

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'dokanee' ) );
		if ( $categories_list && $categories ) {
			echo apply_filters( 'dokanee_category_list_output', sprintf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', // WPCS: XSS ok, sanitization ok.
				esc_html_x( 'Categories', 'Used before category names.', 'dokanee' ),
				$categories_list
			) );
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'dokanee' ) );
		if ( $tags_list && $tags ) {
			echo apply_filters( 'dokanee_tag_list_output', sprintf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>', // WPCS: XSS ok, sanitization ok.
				esc_html_x( 'Tags', 'Used before tag names.', 'dokanee' ),
				$tags_list
			) );
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && $comments ) {
			echo '<span class="comments-link">';
				comments_popup_link( __( 'Leave a comment', 'dokanee' ), __( '1 Comment', 'dokanee' ), __( '% Comments', 'dokanee' ) );
			echo '</span>';
		}
	}
}

if ( ! function_exists( 'dokanee_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'dokanee_excerpt_more' );
	/**
	 * Prints the read more HTML to post excerpts.
	 *
	 * @since 0.1
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link.
	 */
	function dokanee_excerpt_more( $more ) {
		return apply_filters( 'dokanee_excerpt_more_output', sprintf( ' ... <a title="%1$s" class="read-more" href="%2$s">%3$s%4$s</a>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) ),
			__( 'Read more', 'dokanee' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		) );
	}
}

if ( ! function_exists( 'dokanee_content_more' ) ) {
	add_filter( 'the_content_more_link', 'dokanee_content_more' );
	/**
	 * Prints the read more HTML to post content using the more tag.
	 *
	 * @since 0.1
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link
	 */
	function dokanee_content_more( $more ) {
		return apply_filters( 'dokanee_content_more_link_output', sprintf( '<p class="read-more-container"><a title="%1$s" class="read-more content-read-more" href="%2$s">%3$s%4$s</a></p>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) . apply_filters( 'dokanee_more_jump','#more-' . get_the_ID() ) ),
			__( 'Read more', 'dokanee' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		) );
	}
}

if ( ! function_exists( 'dokanee_post_meta' ) ) {
	add_action( 'dokanee_after_entry_title', 'dokanee_post_meta' );
	/**
	 * Build the post meta.
	 *
	 * @since 1.3.29
	 */
	function dokanee_post_meta() {
		if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php dokanee_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif;
	}
}

if ( ! function_exists( 'dokanee_footer_meta' ) ) {
//	add_action( 'dokanee_after_entry_content', 'dokanee_footer_meta' );
	/**
	 * Build the footer post meta.
	 *
	 * @since 1.3.30
	 */
	function dokanee_footer_meta() {
		if ( 'post' == get_post_type() ) : ?>
			<footer class="entry-meta">
				<?php dokanee_entry_meta(); ?>
				<?php if ( is_single() ) dokanee_content_nav( 'nav-below' ); ?>
			</footer><!-- .entry-meta -->
		<?php endif;
	}
}
