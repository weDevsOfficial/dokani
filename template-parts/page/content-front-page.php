<?php
/**
 * Displays content for front page
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php if ( get_theme_mod( 'show_slider', 'on' ) == 'on' ) { ?>
    <section class="slider-section">
		<?php 

            if ( get_theme_mod( 'show_dokani_slider', 'on' ) == 'on' ) {
                $slider_id = get_theme_mod( 'dokani_slider_id', '-1' );

                if ( $slider_id != '-1' ) {
                    Dokan_Slider::init()->get_slider( $slider_id );
                }

            }elseif ( get_theme_mod( 'show_externel_slider', 'on' ) == 'on' ) {
                echo do_shortcode( get_theme_mod( 'dokani_slider_shortcode' ) ); 
            }
        ?>
    </section> <!-- .slider-section -->
<?php } ?>

<?php if ( get_theme_mod( 'show_products_cat', 'on' ) == 'on' ) { ?>
	<?php
	$terms     = get_terms( array( 'taxonomy' => 'product_cat', 'parent' => 0 ) );
	$total_cat = count( $terms );
	$visible_item = get_theme_mod( 'products_cat_counter', 5 );

	if ( ! empty( $total_cat ) ) {
		?>
        <section class="product-cat-section">
            <div class="grid-container">
                <h2 class="section-title"><?php esc_html_e( 'Products Category', 'dokani' ); ?></h2>

                <div class="product-cat-wrapper">

					<?php
					$i = 0;

					foreach ( $terms as $product_term ) {

						if ( $i < $visible_item ) {
							echo '<div class="product-cat-box">';
							woocommerce_subcategory_thumbnail( $product_term );
							echo '<h3 itemprop="name" class="product-title entry-title">' . esc_html( $product_term->name ) . '</h3>';
							echo '<a href="' . esc_url( get_term_link( $product_term->term_id ) ) . '" class="btn btn-border btn-default">' . esc_html__( 'Show More',
									'dokani' ) . '<i class="flaticon flaticon-right"></i></a>';
							echo '</div>';
						}

						$i ++;
					}

					if ( $total_cat > $visible_item ) {
						?>

                        <div class="product-cat-box more">

                            <h3 itemprop="name" class="product-title entry-title">
								<?php
								echo esc_html( $total_cat - $visible_item . '+' );
								?>
                            </h3>

                            <a href="<?php site_url( '/' ); ?>product-category"
                               class="btn btn-border btn-default"><?php esc_html_e( 'Show More', 'dokani' ); ?><i
                                        class="flaticon flaticon-right"></i></a>
                        </div>

						<?php
					}
					?>
                </div>
            </div>
        </section> <!-- .product-cat-section -->
	<?php } ?>
<?php } ?>


<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<section class="products-section">
    <div class="grid-container container grid-parent">
        <div class="content-area grid-parent mobile-grid-100 grid-75 tablet-grid-75">

            <?php if ( get_theme_mod( 'show_featured', 'on' ) == 'on' ) { ?>
                <div class="slider-container woocommerce">
                    <h2 class="slider-heading"><?php esc_html_e( 'Featured Products', 'dokani' ); ?></h2>

                    <?php
                    if ( function_exists( 'dokan' ) ) {
                        $featured_query = dokan_get_featured_products();
                    } else {
	                    $args = array(
		                    'post_type' => 'product',
		                    'posts_per_page' => 20,
		                    'tax_query' => array(
			                    array(
				                    'taxonomy' => 'product_visibility',
				                    'field'    => 'name',
				                    'terms'    => 'featured',
			                    ),
		                    ),
	                    );

	                    $featured_query = new WP_Query( $args );
                    }


                    if ( $featured_query->have_posts() ) : ?>

                        <div class="product-sliders flexslider">
                            <ul class="slides products">
                                <?php
                                while ( $featured_query->have_posts() ) :
                                    $featured_query->the_post();
                                    wc_get_template_part( 'content', 'product' );
                                endwhile;
                                ?>
                            </ul>
                        </div>

                    <?php else :
                        wc_no_products_found();
                    endif;
                    ?>

                </div> <!-- .slider-container [featured products] -->
            <?php } ?>


			<?php
            $show_latest = get_theme_mod( 'show_latest_pro', 'on' );
            if ( $show_latest === true || $show_latest == 'on' ) {
                ?>
                <div class="slider-container woocommerce">
                    <h2 class="slider-heading"><?php esc_html_e( 'Latest Products', 'dokani' ); ?></h2>

                    <?php
                    if ( function_exists( 'dokan' ) ) {
                        $latest_query = dokan_get_latest_products();
                    } else {
	                    $args = array(
		                    'post_type'      => 'product',
		                    'posts_per_page' => 20,
	                    );

	                    $latest_query = new WP_Query( $args );
                    }


                    if ( $latest_query->have_posts() ) : ?>

                        <div class="product-sliders flexslider">
                            <ul class="slides products">
                                <?php
                                while ( $latest_query->have_posts() ) :
                                    $latest_query->the_post();
                                    wc_get_template_part( 'content', 'product' );
                                endwhile;
                                ?>
                            </ul>
                        </div>

                    <?php else :
                        wc_no_products_found();
                    endif;
                    ?>
                </div> <!-- .slider-container [ latest products] -->
            <?php } ?>


            <?php if ( get_theme_mod( 'show_best_selling', 'on' ) == 'on' ) { ?>
                <div class="slider-container woocommerce">
                    <h2 class="slider-heading"><?php esc_html_e( 'Best Selling Products', 'dokani' ); ?></h2>

                    <?php
                    $args = array(
	                    'post_type'      => 'product',
	                    'meta_key'       => 'total_sales',
	                    'orderby'        => 'meta_value_num',
	                    'posts_per_page' => 20,
                    );
                    $best_selling_query = new WP_Query( $args );

                    if ( $best_selling_query->have_posts() ) : ?>

                        <div class="product-sliders flexslider">
                            <ul class="slides products">
                                <?php while ( $best_selling_query->have_posts() ) : $best_selling_query->the_post(); ?>
                                    <?php wc_get_template_part( 'content', 'product' ); ?>
                                <?php endwhile; ?>
                            </ul>
                        </div>

                    <?php else :
                        wc_no_products_found();
                    endif;
                    ?>

                </div> <!-- .slider-container [best selling products] -->
            <?php } ?>

        </div>
        <div class="widget-area grid-25 tablet-grid-25 grid-parent sidebar">
            <div class="inside-right-sidebar">
				<?php
				if ( ! is_active_sidebar( 'home' ) ) { ?>

                    <aside id="search" class="widget widget_search">
						<?php get_search_form(); ?>
                    </aside>

                    <aside id="archives" class="widget">
                        <h2 class="widget-title"><?php esc_html_e( 'Archives', 'dokani' ); ?></h2>
                        <ul>
							<?php wp_get_archives( array( 'type' => 'monthly' ) ); ?>
                        </ul>
                    </aside>

				<?php } else {
					dynamic_sidebar( 'home' );
				} ?>
            </div>
        </div>
    </div>
</section> <!-- .products-section -->
<?php } ?>

<?php if ( get_theme_mod( 'show_store_list', 'on' ) == 'on' && function_exists( 'dokan' ) ) { ?>
    <section class="store-section">
        <div class="grid-container">
            <h2 class="section-title"><?php esc_html_e( 'Store List', 'dokani' ); ?></h2>

            <div class="store-wrapper">
                <div class="tabs">
                    <input type="radio" name="tabs" id="all-store" checked="checked">
                    <label for="all-store"><?php esc_html_e( 'All', 'dokani' ); ?></label>
                    <div class="tab">
						<?php
						$new_sellers = dokan()->vendor->all( array( 'number' => 8 ) );
						$image_size  = 'single-vendor-thumb';

						$template_args = array(
							'sellers'    => $new_sellers,
							'per_row'    => 4,
							'image_size' => $image_size,
						);

						dokan_get_template_part( 'new-store-lists-loop', false, $template_args );
						?>
                    </div>

					<?php
					$best_sellers = function_exists( 'dokan_get_best_sellers' ) ? dokan_get_best_sellers( 8 ) : false;

					if ( $best_sellers ) { ?>
                        <input type="radio" name="tabs" id="best-seller">
                        <label for="best-seller"><?php esc_html_e( 'Best seller', 'dokani' ); ?></label>
                        <div class="tab">
							<?php
							$image_size    = 'single-vendor-thumb';
							$template_args = array(
								'sellers'    => $best_sellers,
								'per_row'    => 4,
								'image_size' => $image_size,
							);

							dokan_get_template_part( 'best-store-lists-loop', false, $template_args );
							?>
                        </div>
					<?php } ?>

					<?php
					$feature_sellers = function_exists( 'dokan_get_feature_sellers' ) ? dokan_get_feature_sellers( 8 ) : false;

					if ( $feature_sellers ) { ?>
                        <input type="radio" name="tabs" id="featured-store">
                        <label for="featured-store"><?php esc_html_e( 'Featured', 'dokani' ); ?></label>
                        <div class="tab">
							<?php
							$image_size    = 'single-vendor-thumb';
							$template_args = array(
								'sellers'    => $feature_sellers,
								'per_row'    => 4,
								'image_size' => $image_size,
							);

							dokan_get_template_part( 'featured-store-lists-loop', false, $template_args );
							?>
                        </div>
					<?php } ?>

					<?php
					$new_sellers = dokan()->vendor->all( array( 'order' => 'DESC', 'number' => 8 ) );

					if ( $new_sellers ) { ?>
                        <input type="radio" name="tabs" id="latest-store">
                        <label for="latest-store"><?php esc_html_e( 'New', 'dokani' ); ?></label>

                        <div class="tab">
							<?php
							$image_size    = 'single-vendor-thumb';
							$template_args = array(
								'sellers'    => $new_sellers,
								'per_row'    => 4,
								'image_size' => $image_size,
							);

							dokan_get_template_part( 'new-store-lists-loop', false, $template_args );
							?>
                        </div>
					<?php } ?>
                </div>
            </div>
        </div>
    </section> <!-- .store-section -->
<?php } ?>





