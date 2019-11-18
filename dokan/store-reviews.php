<?php
/**
 * The Template for displaying all reviews.
 *
 * @package dokani
 */

$store_user   = get_userdata( get_query_var( 'author' ) );
$store_info   = dokan_get_store_info( $store_user->ID );
$map_location = isset( $store_info['location'] ) ? esc_attr( $store_info['location'] ) : '';

get_header();

?>

<?php dokan_get_template_part( 'store-header' ); ?>

    <div class="grid-container">

        <div id="primary" <?php dokani_content_class(); ?>>

            <main id="main" <?php dokani_main_class(); ?>>

				<?php

				/**
				 * dokani_before_main_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_before_main_content' );

				?>

				<?php do_action( 'dokan_store_profile_frame_after', $store_user->data, $store_info ); ?>

                <div class="store-review-wrap woocommerce">

					<?php
					$dokan_template_reviews = Dokan_Pro_Reviews::init();
					$id                     = $store_user->ID;
					$post_type              = 'product';
					$limit                  = 20;
					$status                 = '1';
					$comments               = $dokan_template_reviews->comment_query( $id, $post_type, $limit, $status );
					?>

                    <div id="reviews">
                        <div id="comments">

							<?php do_action( 'dokan_review_tab_before_comments' ); ?>

                            <h2 class="headline"><?php _e( 'Vendor Review', 'dokani' ); ?></h2>

                            <ol class="commentlist">
								<?php echo $dokan_template_reviews->render_store_tab_comment_list( $comments, $store_user->ID ); ?>
                            </ol>

                        </div>
                    </div>

					<?php
					echo $dokan_template_reviews->review_pagination( $id, $post_type, $limit, $status );
					?>

                </div><!-- #content .site-content -->

				<?php

				/**
				 * dokani_after_main_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_after_main_content' );

				?>

            </main><!-- #main -->

        </div><!-- #primary -->

		<?php

		/**
		 * dokani_after_primary_content_area hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_primary_content_area' );

		dokani_construct_sidebars();

		?>

    </div>

<?php get_footer();
