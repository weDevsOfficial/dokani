<?php
/**
 * Displays Page Header
 *
 * @package Dokanee
 */
?>

<div class="page-header-section">
	<div class="grid-container">
		<div class="page-title text-center">
			<h1 itemprop="headline">
				<?php if ( is_home() ) {
					_e( 'Blog', 'dokanee' );
				} elseif ( is_tax() ) {
					echo single_cat_title( '', false );

				} elseif ( is_singular( 'product' ) ) {
					_e( 'Product', 'dokanee' );
				} elseif ( is_archive() ) {

					/**
					 * dokanee_archive_title hook.
					 *
					 * @since 0.1
					 *
					 * @hooked dokanee_archive_title - 10
					 */
					do_action( 'dokanee_archive_title' );

				} elseif ( is_post_type_archive( 'product' ) ) {
					_e( 'Product', 'dokanee' );
				} elseif ( is_404()) {
					_e( 'Oops! That page can&rsquo;t be found.', 'dokanee' );
                } elseif ( is_search() ) {

                    printf( // WPCS: XSS ok.
                        /* translators: 1: Search query name */
                        __( 'Search Results for: %s', 'dokanee' ),
                        '<span>' . get_search_query() . '</span>'
                    );

				} else {
					the_title();
				}
				?>

			</h1>
		</div>
	</div>
</div>