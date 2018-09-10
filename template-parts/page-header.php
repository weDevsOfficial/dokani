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

                } elseif ( is_account_page() ) {
                    global $wp;
                    $query_vars = $wp->query_vars;

					if ( isset( $query_vars['downloads'] ) ) {
						_e( 'Downloads', 'dokanee' );
					} elseif ( isset( $query_vars['orders'] ) ) {
	                    _e( 'Orders', 'dokanee' );
					} elseif ( isset( $query_vars['edit-address'] ) ) {
	                    _e( 'Addresses', 'dokanee' );
					} elseif ( isset( $query_vars['edit-account'] ) ) {
	                    _e( 'Account details', 'dokanee' );
					} elseif ( isset( $query_vars['bookings'] ) ) {
	                    _e( 'Bookings', 'dokanee' );
					} elseif ( isset( $query_vars['auctions-endpoint'] ) ) {
	                    _e( 'Auctions', 'dokanee' );
					} elseif ( isset( $query_vars['support-tickets'] ) ) {
	                    _e( 'Support Tickets', 'dokanee' );
					} else {
						echo apply_filters( 'dokanee_get_my_account_sub_page_title', __( 'My Account', 'dokanee' ) );
                    }

				} else {
					the_title();
				}
				?>

			</h1>
		</div>
	</div>
</div>