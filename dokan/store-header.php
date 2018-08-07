<?php
$store_user    = dokan()->vendor->get( get_query_var( 'author' ) );
$store_info    = $store_user->get_shop_info();
$social_info   = $store_user->get_social_profiles();
$store_tabs    = dokan_get_store_tabs( $store_user->get_id() );
$social_fields = dokan_get_social_profile_fields();

$dokan_appearance = get_option( 'dokan_appearance' );
$profile_layout   = empty( $dokan_appearance['store_header_template'] ) ? 'default' : $dokan_appearance['store_header_template'];
$store_address    = dokan_get_seller_short_address( $store_user->get_id(), false );

$general_settings = get_option( 'dokan_general', [] );
$banner_width     = ! empty( $general_settings['store_banner_width'] ) ? $general_settings['store_banner_width'] : 625;

$featured_seller = get_user_meta( $store_user->get_id(), 'dokan_feature_seller', true );
$seller_rating  = dokan_get_seller_rating( $store_user->get_id() );

if ( ( 'default' === $profile_layout ) || ( 'layout2' === $profile_layout ) ) {
    $profile_img_class = 'profile-img-circle';
} else {
    $profile_img_class = 'profile-img-square';
}

if ( 'layout3' === $profile_layout ) {
    unset( $store_info['banner'] );

    $no_banner_class = ' profile-frame-no-banner';
    $no_banner_class_tabs = ' dokan-store-tabs-no-banner';

} else {
    $no_banner_class = '';
    $no_banner_class_tabs = '';
}

?>
<div class="profile-frame<?php echo $no_banner_class; ?>">
    <div class="store-banner">
        <div class="profile-info-img-wrapper">
            <?php if ( $store_user->get_banner() ) : ?>
                <img src="<?php echo $store_user->get_banner(); ?>"
                     alt="<?php echo $store_user->get_shop_name(); ?>"
                     title="<?php echo $store_user->get_shop_name(); ?>"
                     class="profile-info-img">
            <?php else : ?>
                <div class="profile-info-img-default">&nbsp</div>
            <?php endif; ?>
        </div> <!-- .profile-info-img-wrapper -->

        <div class="profile-info-box profile-layout-<?php echo $profile_layout; ?>">
            <div class="grid-container">
                <div class="profile-info-summery">
                    <div class="profile-info-head">
                        <div class="profile-img <?php echo $profile_img_class; ?>">
						    <?php echo get_avatar( $store_user->get_id(), 100, '', $store_user->get_shop_name() ); ?>
                        </div>
                    </div>

                    <div class="profile-info">
	                    <?php if ( ! empty( $featured_seller ) && 'yes' == $featured_seller ): ?>
                            <span class="featured-label"><?php _e( 'Featured', 'dokanee' ); ?></span>
	                    <?php endif ?>

	                    <?php if ( ! empty( $store_user->get_shop_name() ) && 'default' === $profile_layout ) { ?>
                            <h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1>
	                    <?php } ?>

					    <?php if ( ! empty( $store_user->get_shop_name() ) && 'default' !== $profile_layout ) { ?>
                            <h1 class="store-name"><?php echo esc_html( $store_user->get_shop_name() ); ?></h1>
					    <?php } ?>

                        <ul class="dokan-store-info">
                            <li class="dokan-store-rating">

	                            <?php if ( !empty( $seller_rating['count'] ) ): ?>
                                    <div class="star-rating dokan-seller-rating" title="<?php echo sprintf( __( 'Rated %s out of 5', 'dokanee' ), $seller_rating['rating'] ) ?>">
                                        <span style="width: <?php echo ( ( $seller_rating['rating']/5 ) * 100 - 1 ); ?>%">
                                            <strong class="rating"><?php echo $seller_rating['rating']; ?></strong> out of 5
                                        </span>
                                    </div>
	                            <?php else: ?>

                                    <i class="fa fa-star"></i>
		                            <?php echo $seller_rating['rating']; ?>

                                <?php endif ?>

                            </li>

						    <?php do_action( 'dokan_store_header_info_fields',  $store_user->get_id() ); ?>
                        </ul>
                    </div> <!-- .profile-info -->
                </div><!-- .profile-info-summery -->
            </div><!-- .profile-info-summery-wrapper -->
        </div> <!-- .profile-info-box -->
    </div>
</div> <!-- .profile-frame -->

<?php if ( $store_tabs ) { ?>
    <div class="dokan-store-tab-wrapper">
        <div class="grid-container">
            <div class="dokan-store-tabs<?php echo $no_banner_class_tabs; ?>">
                <ul class="dokan-list-inline">
                    <?php foreach( $store_tabs as $key => $tab ) { ?>
                        <li><a href="<?php echo esc_url( $tab['url'] ); ?>"><?php echo $tab['title']; ?></a></li>
                    <?php } ?>
                    <?php do_action( 'dokan_after_store_tabs', $store_user->get_id() ); ?>
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
