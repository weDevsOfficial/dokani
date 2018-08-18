<?php
/**
 * Displays content for front page
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<section class="product-cat-section">
    <div class="grid-container">
        <h2 class="section-title"><?php _e( 'Products Category', 'dokanee' ); ?></h2>

        <div class="product-cat-wrapper">
            <?php

            $terms = get_terms( $args = array( 'taxonomy' => 'product_cat' ) );

            foreach ($terms as $term) {

                echo '<div class="product-cat-box">';

                woocommerce_subcategory_thumbnail( $term );

                echo '<h3 itemprop="name" class="product-title entry-title">'.$term->name.'</h3>';

                echo '<a href="' . esc_url( get_term_link( $term->term_id ) ) . '" class="btn btn-border btn-default">Show More <i class="fa fa-arrow-right"></i></a>';

                echo '</div>';
            }

            ?>
        </div>
    </div>
</section> <!-- .product-cat-section -->

<section class="store-section">
    <div class="grid-container">
        <h2 class="section-title"><?php _e( 'Store List', 'dokanee' ); ?></h2>

        <div class="store-wrapper">
            <div class="tabs">
                <input type="radio" name="tabs" id="all-store" checked="checked">
                <label for="all-store">All</label>
                <div class="tab">
	                <?php
	                $new_sellers    = dokan()->vendor->all( array( 'number' => 8 ) );

	                $template_args = array(
		                'sellers'         => $new_sellers,
		                'limit'           => $limit,
		                'offset'          => $offset,
		                'paged'           => $paged,
		                'search_query'    => $search_query,
		                'pagination_base' => $pagination_base,
		                'per_row'         => 4,
		                'search_enabled'  => $search,
		                'image_size'      => $image_size,
	                );

	                dokan_get_template_part( 'new-store-lists-loop', false, $template_args );
	                ?>
                </div>

                <input type="radio" name="tabs" id="best-seller">
                <label for="best-seller">Best seller</label>
                <div class="tab">
	                <?php
	                $best_sellers = dokan_get_best_sellers(8);

	                $template_args = array(
		                'sellers'         => $best_sellers,
		                'limit'           => $limit,
		                'offset'          => $offset,
		                'paged'           => $paged,
		                'search_query'    => $search_query,
		                'pagination_base' => $pagination_base,
		                'per_row'         => 4,
		                'search_enabled'  => $search,
		                'image_size'      => $image_size,
	                );

	                ?>

	                <?php dokan_get_template_part( 'best-store-lists-loop', false, $template_args ); ?>
                </div>

                <input type="radio" name="tabs" id="featured-store">
                <label for="featured-store">Featured</label>
                <div class="tab">
	                <?php
	                $feature_sellers = dokan_get_feature_sellers(8);

	                $template_args = array(
		                'sellers'         => $feature_sellers,
		                'limit'           => $limit,
		                'offset'          => $offset,
		                'paged'           => $paged,
		                'search_query'    => $search_query,
		                'pagination_base' => $pagination_base,
		                'per_row'         => 4,
		                'search_enabled'  => $search,
		                'image_size'      => $image_size,
	                );

	                ?>

	                <?php dokan_get_template_part( 'featured-store-lists-loop', false, $template_args ); ?>
                </div>

                <input type="radio" name="tabs" id="latest-store">
                <label for="latest-store">New</label>

                <div class="tab">
                    <?php
                    $new_sellers    = dokan()->vendor->all( array( 'order' => 'DESC', 'number' => 8 ) );

                    $template_args = array(
	                    'sellers'         => $new_sellers,
	                    'limit'           => $limit,
	                    'offset'          => $offset,
	                    'paged'           => $paged,
	                    'search_query'    => $search_query,
	                    'pagination_base' => $pagination_base,
	                    'per_row'         => 4,
	                    'search_enabled'  => $search,
	                    'image_size'      => $image_size,
                    );

                    dokan_get_template_part( 'new-store-lists-loop', false, $template_args );
                    ?>
                </div>
            </div>
        </div>
    </div>
</section> <!-- .store-section -->