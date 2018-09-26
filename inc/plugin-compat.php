<?php
/**
 * Add compatibility for some popular third party plugins.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'after_setup_theme', 'dokanee_setup_woocommerce' );

/**
 * Set up WooCommerce
 *
 * @since 1.3.47
 */
function dokanee_setup_woocommerce() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Add support for WC features
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	//Remove default WooCommerce wrappers
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	add_action( 'woocommerce_sidebar', 'dokanee_construct_sidebars' );
}


if ( ! function_exists( 'dokanee_woocommerce_start' ) ) {

	add_action( 'woocommerce_before_main_content', 'dokanee_woocommerce_start', 10 );

	/**
	 * Add WooCommerce starting wrappers
	 *
	 * @since 1.3.22
	 */
	function dokanee_woocommerce_start() { ?>
		<div id="primary" <?php dokanee_content_class();?>>
			<main id="main" <?php dokanee_main_class(); ?>>
				<?php
				/**
				 * dokanee_before_main_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'dokanee_before_main_content' );
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> <?php dokanee_article_schema( 'CreativeWork' ); ?>>
					<div class="inside-article">
						<div class="entry-content" itemprop="text">
	<?php
	}
}

if ( ! function_exists( 'dokanee_woocommerce_end' ) ) {
	add_action( 'woocommerce_after_main_content', 'dokanee_woocommerce_end', 10 );
	/**
	 * Add WooCommerce ending wrappers
	 *
	 * @since 1.3.22
	 */
	function dokanee_woocommerce_end() { ?>
						</div><!-- .entry-content -->
						<?php
						/**
						 * dokanee_after_content hook.
						 *
						 * @since 0.1
						 */
						do_action( 'dokanee_after_content' );
						?>
					</div><!-- .inside-article -->
				</article><!-- #post-## -->
				<?php
				/**
				 * dokanee_after_main_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'dokanee_after_main_content' );
				?>
			</main><!-- #main -->
		</div><!-- #primary -->
	<?php
	}
}

if ( ! function_exists( 'dokanee_woocommerce_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'dokanee_woocommerce_css', 100 );
	/**
	 * Add WooCommerce CSS
	 *
	 * @since 1.3.45
	 */
	function dokanee_woocommerce_css() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$mobile = apply_filters( 'dokanee_mobile_media_query', '(max-width:768px)' );
		$css = '.woocommerce .page-header-image-single {
			display: none;
		}

		.woocommerce .entry-content,
		.woocommerce .product .entry-summary {
			margin-top: 0;
		}

		.related.products {
			clear: both;
		}

		.checkout-subscribe-prompt.clear {
			visibility: visible;
			height: initial;
			width: initial;
		}

		@media ' . esc_attr( $mobile ) . ' {
			.woocommerce .woocommerce-ordering,
			.woocommerce-page .woocommerce-ordering {
				float: none;
			}

			.woocommerce .woocommerce-ordering select {
				max-width: 100%;
			}

			.woocommerce ul.products li.product,
			.woocommerce-page ul.products li.product,
			.woocommerce-page[class*=columns-] ul.products li.product,
			.woocommerce[class*=columns-] ul.products li.product {
				width: 100%;
				float: none;
			}
		}';

		$css = str_replace(array("\r", "\n", "\t"), '', $css);
		wp_add_inline_style( 'woocommerce-general', $css );
	}
}

if ( ! function_exists( 'dokanee_bbpress_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'dokanee_bbpress_css', 100 );
	/**
	 * Add bbPress CSS
	 *
	 * @since 1.3.45
	 */
	function dokanee_bbpress_css() {
		$css = '#bbpress-forums ul.bbp-lead-topic,
		#bbpress-forums ul.bbp-topics,
		#bbpress-forums ul.bbp-forums,
		#bbpress-forums ul.bbp-replies,
		#bbpress-forums ul.bbp-search-results,
		#bbpress-forums,
		div.bbp-breadcrumb,
		div.bbp-topic-tags {
			font-size: inherit;
		}

		.single-forum #subscription-toggle {
			display: block;
			margin: 1em 0;
			clear: left;
		}

		#bbpress-forums .bbp-search-form {
			margin-bottom: 10px;
		}

		.bbp-login-form fieldset {
			border: 0;
			padding: 0;
		}';

		$css = str_replace(array("\r", "\n", "\t"), '', $css);
		wp_add_inline_style( 'bbp-default', $css );
	}
}

if ( ! function_exists( 'dokanee_buddypress_css' ) ) {
	add_action( 'wp_enqueue_scripts', 'dokanee_buddypress_css', 100 );
	/**
	 * Add BuddyPress CSS
	 *
	 * @since 1.3.45
	 */
	function dokanee_buddypress_css() {
		$css = '#buddypress form#whats-new-form #whats-new-options[style] {
			min-height: 6rem;
			overflow: visible;
		}';

		$css = str_replace(array("\r", "\n", "\t"), '', $css);
		wp_add_inline_style( 'bp-legacy-css', $css );
	}
}

