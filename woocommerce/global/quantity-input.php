<?php
/**
 * Product quantity inputs with plus/minus buttons
 *
 * Save this template to your theme as woocommerce/global/quantity-input.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     4.0.0
 */

defined( 'ABSPATH' ) || exit;

if ( $max_value && $min_value === $max_value ) {
	?>
	<input type="hidden" id="<?php echo esc_attr( $input_id ); ?>" class="qty" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
	<?php
} else {
	?>
	<div class="quantity">
        <span class="meta-title"><?php esc_html_e( 'Quantity:', 'dokani' ); ?></span>
        <div class="quantity_wrap">
            <?php do_action( 'woocommerce_before_quantity_input_field' ); ?>
            <input
                type="text" 
                id="<?php echo esc_attr( $input_id ); ?>"
                class="<?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
                readonly 
                step="<?php echo esc_attr( $step ); ?>" 
                min="<?php echo esc_attr( $min_value ); ?>" 
                max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" 
                name="<?php echo esc_attr( $input_name ); ?>" 
                value="<?php echo esc_attr( $input_value ); ?>" 
                title="<?php echo esc_attr_x( 'Qty', 'Product quantity input tooltip', 'dokani' ) ?>" 
                size="4"
                placeholder="<?php echo esc_attr( $placeholder ); ?>"
                pattern="<?php echo esc_attr( $pattern ); ?>" 
                inputmode="<?php echo esc_attr( $inputmode ); ?>" 
            />
            <?php do_action( 'woocommerce_after_quantity_input_field' ); ?>
            <div class="quantity-btn">
                <span class="plus">+</span>
                <span class="minus">-</span>
            </div>
        </div>
	</div>
	<?php
}