<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;
?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<div class="sku_wrapper">
            <span class="meta-title"><?php esc_html_e( 'SKU:', 'dokani' ); ?></span>
            <span class="sku meta-content"><?php echo ( $sku = $product->get_sku() ) ? $sku : esc_html__( 'N/A', 'dokani' ); ?></span>
        </div>

	<?php endif; ?>

	<?php
    echo '<div class="posted_in">';
    echo '<span class="meta-title">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'dokani' ) . '</span>';

	echo wc_get_product_category_list( $product->get_id(), ' ', '<span class="meta-content">', '</span>' );
    echo '</div>';
	?>

    <?php
    echo '<div class="tagged_as">';
    echo '<span class="meta-title">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'dokani' ) . '</span>';

	echo wc_get_product_tag_list( $product->get_id(), ' ', '<span class="meta-content">', '</span>' );
    echo '</div>';
	?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
