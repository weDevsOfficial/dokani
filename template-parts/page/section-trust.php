<?php
/**
 * Display trust factors content
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<div class="trust-factors-section">
    <div class="grid-container">
        <div class="factor-wrapper">

			<?php
			$dokani_trusted_fact_1      = get_theme_mod( 'dokani_trusted_fact_1', 'Fast & Free Delivery' );
			$dokani_trusted_fact_1_icon = get_theme_mod( 'dokani_trusted_fact_1_icon',
				'flaticon flaticon-transport' );

			$dokani_trusted_fact_2      = get_theme_mod( 'dokani_trusted_fact_2', 'Safe & Secure Payment' );
			$dokani_trusted_fact_2_icon = get_theme_mod( 'dokani_trusted_fact_2_icon',
				'flaticon flaticon-business-2' );

			$dokani_trusted_fact_3      = get_theme_mod( 'dokani_trusted_fact_3', '100% Money Back Guarantee' );
			$dokani_trusted_fact_3_icon = get_theme_mod( 'dokani_trusted_fact_3_icon',
				'flaticon flaticon-technology' );

			if ( $dokani_trusted_fact_1 || $dokani_trusted_fact_1_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokani_trusted_fact_1_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php echo $dokani_trusted_fact_1; ?></div>
                </div>

			<?php }

			if ( $dokani_trusted_fact_2 || $dokani_trusted_fact_2_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokani_trusted_fact_2_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php echo $dokani_trusted_fact_2; ?></div>
                </div>

			<?php }

			if ( $dokani_trusted_fact_3 || $dokani_trusted_fact_3_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokani_trusted_fact_3_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php echo $dokani_trusted_fact_3; ?></div>
                </div>

			<?php } ?>

        </div>
    </div>
</div> <!-- .trust-factors-section -->
<?php } ?>