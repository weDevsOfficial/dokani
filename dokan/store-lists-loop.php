<div class="dokan-seller-view buttons box-shadow">
    <button class="list"><i class="fa fa-bars"></i></button>
    <button class="grid active"><i class="fa fa-th-large"></i></button>
</div>

<div id="dokan-seller-listing-wrap">
    <div class="seller-listing-content">
        <?php if ( $sellers['users'] ) : ?>
            <ul class="dokan-seller-list grid">
                <?php
                foreach ( $sellers['users'] as $seller ) {
                    $store_info = dokan_get_store_info( $seller->ID );
                    $banner_id  = isset( $store_info['banner'] ) ? $store_info['banner'] : 0;
                    $store_name = isset( $store_info['store_name'] ) ? esc_html( $store_info['store_name'] ) : __( 'N/A', 'dokanee' );
                    $store_url  = dokan_get_store_url( $seller->ID );
                    $store_address  = dokan_get_seller_short_address( $seller->ID );
                    $seller_rating  = dokan_get_seller_rating( $seller->ID );
                    $banner_url = ( $banner_id ) ? wp_get_attachment_image_src( $banner_id, 'single-vendor-thumb' ) : get_template_directory_uri() . '/assets/images/single-default-store-banner.png';
                    $featured_seller = get_user_meta( $seller->ID, 'dokan_feature_seller', true );
                    ?>

                    <li class="dokan-single-seller woocommerce coloum-<?php echo $per_row; ?> <?php echo ( ! $banner_id ) ? 'no-banner-img' : ''; ?>">
                        <div class="store-content">
                            <div class="featured-favourite">
                                <?php if ( ! empty( $featured_seller ) && 'yes' == $featured_seller ): ?>
                                    <span class="featured-label"><?php _e( 'Featured', 'dokanee' ); ?></span>
                                <?php endif ?>

                                <?php do_action( 'dokan_seller_listing_after_featured', $seller, $store_info ); ?>
                            </div>
                            <div class="store-banner">
                                <?php
                                if( is_array( $banner_url ) ) { ?>
                                    <a href="<?php echo esc_url( $store_url ); ?>">
	                                    <img src="<?php echo esc_url( $banner_url[0] ); ?>" alt="<?php echo esc_attr( $store_name ); ?>">
                                    </a>
                                <?php } else { ?>
                                    <a href="<?php echo esc_url( $store_url ); ?>">
                                        <img src="<?php echo esc_url( $banner_url ); ?>" alt="<?php echo esc_attr( $store_name ); ?>">
                                    </a>
                                <?php }
                                ?>
                            </div>
                            <div class="seller-avatar">
		                        <?php echo get_avatar( $seller->ID, 55 ); ?>
                            </div>
                        </div>
                        <div class="store-footer">
                            <div class="store-data">
                                <h2><a href="<?php echo esc_url( $store_url ); ?>"><?php echo $store_name; ?></a></h2>

                                <?php if ( !empty( $seller_rating['count'] ) ): ?>
                                    <div class="star-rating dokan-seller-rating" title="<?php echo sprintf( __( 'Rated %s out of 5', 'dokanee' ), $seller_rating['rating'] ) ?>">
                                        <span style="width: <?php echo ( ( $seller_rating['rating']/5 ) * 100 - 1 ); ?>%">
                                            <strong class="rating"><?php echo $seller_rating['rating']; ?></strong> out of 5
                                        </span>
                                    </div>
                                <?php endif ?>

                                <?php if ( $store_address ): ?>
                                    <p class="store-address"><?php echo $store_address; ?></p>
                                <?php endif ?>

                                <?php do_action( 'dokan_seller_listing_after_store_data', $seller, $store_info ); ?>

                            </div>

                            <a href="<?php echo esc_url( $store_url ); ?>" class="dokan-btn dokan-btn-theme"><?php _e( 'Visit Store', 'dokanee' ); ?></a>

                            <?php do_action( 'dokan_seller_listing_footer_content', $seller->data, $store_info ); ?>
                        </div>
                    </li>

                <?php } ?>
                <div class="dokan-clearfix"></div>
            </ul> <!-- .dokan-seller-wrap -->

            <?php
            $user_count   = $sellers['count'];
            $num_of_pages = ceil( $user_count / $limit );

            if ( $num_of_pages > 1 ) {
                echo '<div class="pagination-container clearfix">';

                $pagination_args = array(
                    'current'   => $paged,
                    'total'     => $num_of_pages,
                    'base'      => $pagination_base,
                    'type'      => 'array',
                    'prev_text' => __( '&larr;', 'dokanee' ),
                    'next_text' => __( '&rarr;', 'dokanee' ),
                );

                if ( ! empty( $search_query ) ) {
                    $pagination_args['add_args'] = array(
                        'dokan_seller_search' => $search_query,
                    );
                }

                $page_links = paginate_links( $pagination_args );

                if ( $page_links ) {
                    $pagination_links  = '<div class="pagination-wrap">';
                    $pagination_links .= '<ul class="pagination"><li>';
                    $pagination_links .= join( "</li>\n\t<li>", $page_links );
                    $pagination_links .= "</li>\n</ul>\n";
                    $pagination_links .= '</div>';

                    echo $pagination_links;
                }

                echo '</div>';
            }
            ?>

        <?php else:  ?>
            <p class="dokan-error"><?php _e( 'No vendor found!', 'dokanee' ); ?></p>
        <?php endif; ?>
    </div>
</div>