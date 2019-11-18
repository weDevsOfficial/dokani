<?php
/**
 * Dokan Seller Widget Map Content
 *
 * @since 1.0.0
 *
 * @package Dokanee
 */
?>

<div class="location-container">

    <?php if ( ! empty( $map_location ) ) { ?>
        <div id="dokan-store-location"></div>

        <script type="text/javascript">
            jQuery(function($) {
                <?php
                $locations = explode( ',', $map_location );
                $def_lat = isset( $locations[0] ) ? $locations[0] : 90.40714300000002;
                $def_long = isset( $locations[1] ) ? $locations[1] : 23.709921;
                ?>

                var def_longval = <?php echo $def_long; ?>;
                var def_latval = <?php echo $def_lat; ?>;

                var curpoint = new google.maps.LatLng(def_latval, def_longval),
                    $map_area = $('#dokan-store-location');

                var gmap = new google.maps.Map( $map_area[0], {
                    center: curpoint,
                    zoom: 15,
                    mapTypeId: window.google.maps.MapTypeId.ROADMAP
                });

                var marker = new window.google.maps.Marker({
                    position: curpoint,
                    map: gmap
                });
            })

        </script>
    <?php } ?>

	<?php
	$store_user    = dokan()->vendor->get( get_query_var( 'author' ) );
	$store_address = dokan_get_seller_short_address( $store_user->get_id(), false );

    if ( isset( $store_address ) && ! empty( $store_address ) ) { ?>
        <div class="dokan-store-address"><i class="fa fa-map-marker"></i>
			<?php echo $store_address; ?>
        </div>
	<?php } ?>

	<?php if ( ! empty( $store_user->get_phone() ) ) { ?>
        <div class="dokan-store-phone">
            <i class="fa fa-phone"></i>
            <a href="tel:<?php echo esc_attr( $store_user->get_phone() ); ?>"><?php echo esc_html( $store_user->get_phone() ); ?></a>
        </div>
	<?php } ?>

</div>