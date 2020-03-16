<?php
/**
 * Post meta elements.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_content_nav' ) ) {
	/**
	 * Display navigation to next/previous pages when applicable.
	 *
	 * @since 1.0.0
	 *
	 * @param string $nav_id The id of our navigation.
	 */
	function dokani_content_nav( $nav_id ) {
		if ( ! apply_filters( 'dokani_show_post_navigation', true ) ) {
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
		$category_specific = apply_filters( 'dokani_category_post_navigation', false );
		?>
		<nav id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo esc_attr( $nav_class ); ?>">
			<span class="screen-reader-text"><?php esc_html_e( 'Post navigation', 'dokani' ); ?></span>

			<?php if ( is_single() ) : // navigation links for single posts.

				previous_post_link( '<div class="nav-previous"><span class="prev" title="' . esc_attr__( 'Previous', 'dokani' ) . '">%link</span></div>', '%title', $category_specific );
				next_post_link( '<div class="nav-next"><span class="next" title="' . esc_attr__( 'Next', 'dokani' ) . '">%link</span></div>', '%title', $category_specific );

			elseif ( is_home() || is_archive() || is_search() ) : // navigation links for home, archive, and search pages.

				if ( get_next_posts_link() ) : ?>
					<div class="nav-previous"><span class="prev" title="<?php esc_attr_e( 'Previous', 'dokani' );?>"><?php next_posts_link( __( 'Older posts', 'dokani' ) ); ?></span></div>
				<?php endif;

				if ( get_previous_posts_link() ) : ?>
					<div class="nav-next"><span class="next" title="<?php esc_attr_e( 'Next', 'dokani' );?>"><?php previous_posts_link( __( 'Newer posts', 'dokani' ) ); ?></span></div>
				<?php endif;

				if ( function_exists( 'the_posts_pagination' ) ) {
					the_posts_pagination( array(
						'mid_size' => apply_filters( 'dokani_pagination_mid_size', 1 ),
						'prev_text' => apply_filters( 'dokani_previous_link_text', __( '&larr;', 'dokani' ) ),
						'next_text' => apply_filters( 'dokani_next_link_text', __( '&rarr;', 'dokani' ) ),
					) );
				}

				/**
				 * dokani_paging_navigation hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_paging_navigation' );

			endif; ?>
		</nav><!-- #<?php echo esc_html( $nav_id ); ?> -->
		<?php
	}
}

if ( ! function_exists( 'dokani_modify_posts_pagination_template' ) ) {
	add_filter( 'navigation_markup_template', 'dokani_modify_posts_pagination_template', 10, 2 );
	/**
	 * Remove the container and screen reader text from the_posts_pagination()
	 * We add this in ourselves in dokani_content_nav()
	 *
	 * @since 1.0.0
	 *
	 * @param string $template The default template.
	 * @param string $class The class passed by the calling function.
	 * @return string The HTML for the post navigation.
	 */
	function dokani_modify_posts_pagination_template( $template, $class ) {
	    if ( ! empty( $class ) && false !== strpos( $class, 'pagination' ) ) {
	        $template = '<div class="nav-links">%3$s</div>';
	    }

	    return $template;
	}
}

if ( ! function_exists( 'dokani_posted_on' ) ) {
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 *
	 * @since 1.0.0
	 */
	function dokani_posted_on() {
		$date = apply_filters( 'dokani_post_date', true );
		$author = apply_filters( 'dokani_post_author', true );

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
			echo wp_kses_post( apply_filters( 'dokani_post_date_output', sprintf( '<span class="posted-on">%1$s</span>',
                sprintf( '<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
                    esc_url( get_permalink() ),
                    esc_attr( get_the_time() ),
                    wp_kses_post( $time_string )
                )
            ), $time_string ) );
		}

		// If our author is enabled, show it.
		if ( $author ) {
			echo wp_kses_post( apply_filters( 'dokani_post_author_output', sprintf( ' <span class="byline">%1$s</span>',
                sprintf( '<span class="author vcard" itemtype="https://schema.org/Person" itemscope="itemscope" itemprop="author">%1$s <a class="url fn n" href="%2$s" title="%3$s" rel="author" itemprop="url"><span class="author-name" itemprop="name">%4$s</span></a></span>',
                    __( 'by','dokani'),
                    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
                    /* translators: 1: Author name */
                    esc_attr( sprintf( __( 'View all posts by %s', 'dokani' ), esc_html( get_the_author() ) ) ),
                    esc_html( get_the_author() )
                )
            ) ) );
		}
	}
}

if ( ! function_exists( 'dokani_entry_meta' ) ) {
	/**
	 * Prints HTML with meta information for the categories, tags.
	 *
	 * @since 1.0.0
	 */
	function dokani_entry_meta() {
		$is_show_categories = get_theme_mod( 'blog_single_show_category' );
		$is_show_tags = get_theme_mod( 'blog_single_show_tag' );
		$comments = apply_filters( 'dokani_show_comments', true );

		$categories_list = get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'dokani' ) );
		if ( $categories_list && $is_show_categories ) {
			echo wp_kses_post( apply_filters( 'dokani_category_list_output', sprintf( '<span class="cat-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                esc_html_x( 'Categories', 'Used before category names.', 'dokani' ),
                $categories_list
            ) ) );
		}

		$tags_list = get_the_tag_list( '', _x( ', ', 'Used between list items, there is a space after the comma.', 'dokani' ) );
		if ( $tags_list && $is_show_tags ) {
			echo wp_kses_post( apply_filters( 'dokani_tag_list_output', sprintf( '<span class="tags-links"><span class="screen-reader-text">%1$s </span>%2$s</span>',
                esc_html_x( 'Tags', 'Used before tag names.', 'dokani' ),
                $tags_list
            ) ) );
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) && $comments ) {
			echo '<span class="comments-link">';
				comments_popup_link( esc_html__( 'Leave a comment', 'dokani' ), esc_html__( '1 Comment', 'dokani' ), esc_html__( '% Comments', 'dokani' ) );
			echo '</span>';
		}
	}
}

if ( ! function_exists( 'dokani_excerpt_more' ) ) {
	add_filter( 'excerpt_more', 'dokani_excerpt_more' );
	/**
	 * Prints the read more HTML to post excerpts.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link.
	 */
	function dokani_excerpt_more( $more ) {
		return apply_filters( 'dokani_excerpt_more_output', sprintf( ' ... <a title="%1$s" class="read-more" href="%2$s">%3$s%4$s</a>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) ),
			__( 'Read more', 'dokani' ),
			'<span class="screen-reader-text">' . get_the_title() . '</span>'
		) );
	}
}

if ( ! function_exists( 'dokani_author_profile' ) ) {
	/**
	 * Prints the author profile.
	 */
	function dokani_author_profile() {
	    $author_id = get_the_author_meta( 'ID' );
	    $author_name = get_the_author_meta( 'display_name' );
	    $author_email = get_the_author_meta( 'user_email' );
	    $author_avatar = get_avatar( $author_email, 60 );
	    $author_description = get_the_author_meta( 'user_description' );
	    $author_url = get_author_posts_url( $author_id );
    ?>
        <div class="author-profile">
            <div class="author-thumb">
                <?php
                echo wp_kses_post( $author_avatar );
                ?>
            </div>
            <div class="author-bio">
                <h3>
                    <span><?php echo esc_html_e( 'About', 'dokani' ); ?></span>
                    <a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
                </h3>
                <div class="author-description">
                    <?php echo wp_kses_post( $author_description ); ?>
                </div>
            </div>
        </div>
	<?php }
}

if ( ! function_exists( 'dokani_content_more' ) ) {
	add_filter( 'the_content_more_link', 'dokani_content_more' );
	/**
	 * Prints the read more HTML to post content using the more tag.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The HTML for the more link
	 */
	function dokani_content_more( $more ) {
		return apply_filters( 'dokani_content_more_link_output', sprintf( '<p class="read-more-container"><a title="%1$s" class="read-more content-read-more" href="%2$s">%3$s%4$s</a></p>',
			the_title_attribute( 'echo=0' ),
			esc_url( get_permalink( get_the_ID() ) . apply_filters( 'dokani_more_jump','#more-' . get_the_ID() ) ),
			__( 'Read more', 'dokani' ),
			'<span class="screen-reader-text">' . esc_html( get_the_title() ) . '</span>'
		) );
	}
}

if ( ! function_exists( 'dokani_post_meta' ) ) {
	add_action( 'dokani_after_entry_title', 'dokani_post_meta' );
	/**
	 * Build the post meta.
	 *
	 * @since 1.0.0
	 */
	function dokani_post_meta() {
		if ( 'post' == get_post_type() ) : ?>
			<div class="entry-meta">
				<?php dokani_posted_on(); ?>
			</div><!-- .entry-meta -->
		<?php endif;
	}
}

if ( ! function_exists( 'dokani_get_post_nav' ) ) {
	add_action( 'dokani_post_nav', 'dokani_get_post_nav' );
	/**
	 * Build the footer post meta.
	 *
	 * @since 1.0.0
	 */
	function dokani_get_post_nav() {
		if ( 'post' == get_post_type() ) : ?>
			<footer class="entry-meta">
				<?php if ( is_single() && get_theme_mod( 'show_post_nav' ) ) dokani_content_nav( 'nav-below' ); ?>
			</footer><!-- .entry-meta -->
		<?php endif;
	}
}

if ( ! function_exists( 'dokani_get_author_profile' ) ) {
	add_action( 'dokani_post_author_profile', 'dokani_get_author_profile' );
	/**
	 * Build the author profile.
	 *
	 * @since 1.0.0
	 */
	function dokani_get_author_profile() {
		if ( 'post' == get_post_type() && is_single() && get_theme_mod( 'blog_single_show_author_profile' ) ) {
			dokani_author_profile();
        }
	}
}

if ( ! function_exists( 'dokane_get_post_meta' ) ) {
	add_action( 'dokane_post_meta', 'dokane_get_post_meta' );
	/**
	 * Build the footer post meta.
	 *
	 * @since 1.0.0
	 */
	function dokane_get_post_meta() {
		if ( 'post' == get_post_type() && is_single() ) {
		    ?>
            <div class="dokani-entry-meta-wrap">
                <?php dokani_entry_meta(); ?>
            </div>
            <?php
        }
	}
}

