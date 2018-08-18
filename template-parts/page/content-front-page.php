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