<?php
/**
 * Display trust factors content
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="trust-factors-section">
    <div class="grid-container">
        <div class="factor-wrapper">

			<?php
			$dokanee_trusted_fact_1      = get_theme_mod( 'dokanee_trusted_fact_1', 'Fast & Free Delivery' );
			$dokanee_trusted_fact_1_icon = get_theme_mod( 'dokanee_trusted_fact_1_icon',
				'flaticon flaticon-transport' );

			$dokanee_trusted_fact_2      = get_theme_mod( 'dokanee_trusted_fact_2', 'Safe & Secure Payment' );
			$dokanee_trusted_fact_2_icon = get_theme_mod( 'dokanee_trusted_fact_2_icon',
				'flaticon flaticon-business-2' );

			$dokanee_trusted_fact_3      = get_theme_mod( 'dokanee_trusted_fact_3', '100% Money Back Guarantee' );
			$dokanee_trusted_fact_3_icon = get_theme_mod( 'dokanee_trusted_fact_3_icon',
				'flaticon flaticon-technology' );

			if ( $dokanee_trusted_fact_1 || $dokanee_trusted_fact_1_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokanee_trusted_fact_1_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php _e( $dokanee_trusted_fact_1, 'dokanee' ); ?></div>
                </div>

			<?php }

			if ( $dokanee_trusted_fact_2 || $dokanee_trusted_fact_2_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokanee_trusted_fact_2_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php _e( $dokanee_trusted_fact_2, 'dokanee' ); ?></div>
                </div>

			<?php }

			if ( $dokanee_trusted_fact_3 || $dokanee_trusted_fact_3_icon ) { ?>

                <div class="factor-box">
                    <div class="factor-icon">
                        <i class="<?php echo esc_attr( $dokanee_trusted_fact_3_icon ); ?>"></i>
                    </div>
                    <div class="factor-info"><?php _e( $dokanee_trusted_fact_3, 'dokanee' ); ?></div>
                </div>

			<?php } ?>

        </div>
    </div>
</div> <!-- .trust-factors-section -->