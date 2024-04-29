<?php
/**
 * Dokan Seller Widget Map Content
 *
 * @since 1.0.0
 *
 * @package dokani
 */

if ( empty( $map_location ) ) {
	return;
}

?>

<div class="location-container">

	<div id="dokan-store-location"></div>

	<?php
		$source    = dokan_get_option( 'map_api_source', 'dokan_appearance', 'google_maps' );
		$location  = explode( ',', $map_location );
		$longitude = ! empty( $location[1] ) ? $location[1] : 90.40714300000002;
		$latitude  = ! empty( $location[0] ) ? $location[0] : 23.709921;

	if ( 'mapbox' === $source ) {
		$access_token = dokan_get_option( 'mapbox_access_token', 'dokan_appearance', null );

		if ( ! $access_token ) {
			esc_html_e( 'Mapbox Access Token not found', 'dokani' );
			return;
		}
		dokan_get_template_part(
			'widgets/store-map-mapbox',
			'',
			array(
				'map_location' => $map_location,
				'access_token' => $access_token,
				'location'     => array(
					'longitude' => $longitude,
					'latitude'  => $latitude,
					'zoom'      => 10,
				),
			)
		);
	} else {
		dokan_get_template_part(
			'widgets/store-map-google-maps',
			'',
			array(
				'map_location' => $map_location,
				'location'     => array(
					'longitude' => $longitude,
					'latitude'  => $latitude,
					'zoom'      => 15,
				),
			)
		);
	}

	$store_user    = dokan()->vendor->get( get_query_var( 'author' ) );
	$store_address = dokan_get_seller_short_address( $store_user->get_id(), false );

	if ( isset( $store_address ) && ! empty( $store_address ) ) :
		?>
		<div class="dokan-store-address"><i class="fas fa-map-marker"></i>
			<?php echo wp_kses_post( $store_address ); ?>
		</div>
	<?php endif; ?>

	<?php if ( ! empty( $store_user->get_phone() ) ) : ?>
		<div class="dokan-store-phone">
			<i class="fas fa-phone-alt"></i>
			<a href="tel:<?php echo esc_attr( $store_user->get_phone() ); ?>"><?php echo esc_html( $store_user->get_phone() ); ?></a>
		</div>
	<?php endif; ?>

</div>
