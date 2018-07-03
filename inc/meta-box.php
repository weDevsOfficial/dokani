<?php
/**
 * Builds our main Layout meta box.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_action( 'admin_enqueue_scripts', 'dokanee_enqueue_meta_box_scripts' );
/**
 * Adds any scripts for this meta box.
 *
 * @since 2.0
 *
 * @param string $hook The current admin page.
 */
function dokanee_enqueue_meta_box_scripts( $hook ) {
	if ( in_array( $hook, array( 'post.php', 'post-new.php' ) ) ) {
		$post_types = get_post_types( array( 'public' => true ) );
		$screen = get_current_screen();
		$post_type = $screen->id;

		if ( in_array( $post_type, ( array ) $post_types ) ) {
			wp_enqueue_style( 'dokanee-layout-metabox', get_template_directory_uri() . '/css/admin/meta-box.css', array(), GENERATE_VERSION );
		}
	}
}

add_action( 'add_meta_boxes', 'dokanee_register_layout_meta_box' );
/**
 * Generate the layout metabox
 *
 * @since 2.0
 */
function dokanee_register_layout_meta_box() {
	if ( ! current_user_can( apply_filters( 'dokanee_metabox_capability', 'edit_theme_options' ) ) ) {
		return;
	}

	if ( ! defined( 'GENERATE_LAYOUT_META_BOX' ) ) {
		define( 'GENERATE_LAYOUT_META_BOX', true );
	}

	$post_types = get_post_types( array( 'public' => true ) );
	foreach ($post_types as $type) {
		if ( 'attachment' !== $type ) {
			add_meta_box (
				'dokanee_layout_options_meta_box',
				esc_html__( 'Layout', 'dokanee' ),
				'dokanee_do_layout_meta_box',
				$type,
				'normal',
				'high'
			);
		}
	}
}

/**
 * Build our meta box.
 *
 * @since 2.0
 *
 * @param object $post All post information.
 */
function dokanee_do_layout_meta_box( $post ) {
	wp_nonce_field( basename( __FILE__ ), 'dokanee_layout_nonce' );
	$stored_meta = (array) get_post_meta( $post->ID );
	$stored_meta['_dokanee-sidebar-layout-meta'][0] = ( isset( $stored_meta['_dokanee-sidebar-layout-meta'][0] ) ) ? $stored_meta['_dokanee-sidebar-layout-meta'][0] : '';
	$stored_meta['_dokanee-footer-widget-meta'][0] = ( isset( $stored_meta['_dokanee-footer-widget-meta'][0] ) ) ? $stored_meta['_dokanee-footer-widget-meta'][0] : '';
	$stored_meta['_dokanee-full-width-content'][0] = ( isset( $stored_meta['_dokanee-full-width-content'][0] ) ) ? $stored_meta['_dokanee-full-width-content'][0] : '';
	$stored_meta['_dokanee-disable-headline'][0] = ( isset( $stored_meta['_dokanee-disable-headline'][0] ) ) ? $stored_meta['_dokanee-disable-headline'][0] : '';

	$tabs = apply_filters( 'dokanee_metabox_tabs',
		array(
			'sidebars' => array(
				'title' => esc_html__( 'Sidebars', 'dokanee' ),
				'target' => '#dokanee-layout-sidebars',
				'class' => 'current',
			),
			'footer_widgets' => array(
				'title' => esc_html__( 'Footer Widgets', 'dokanee' ),
				'target' => '#dokanee-layout-footer-widgets',
				'class' => '',
			),
			'disable_elements' => array(
				'title' => esc_html__( 'Disable Elements', 'dokanee' ),
				'target' => '#dokanee-layout-disable-elements',
				'class' => '',
			),
			'container' => array(
				'title' => esc_html__( 'Page Builder Container', 'dokanee' ),
				'target' => '#dokanee-layout-page-builder-container',
				'class' => '',
			),
		)
	);
	?>
	<script>
		jQuery(document).ready(function($) {
			$( '.dokanee-meta-box-menu li a' ).on( 'click', function( event ) {
				event.preventDefault();
				$( this ).parent().addClass( 'current' );
				$( this ).parent().siblings().removeClass( 'current' );
				var tab = $( this ).attr( 'data-target' );

				// Page header module still using href.
				if ( ! tab ) {
					tab = $( this ).attr( 'href' );
				}

				$( '.dokanee-meta-box-content' ).children( 'div' ).not( tab ).css( 'display', 'none' );
				$( tab ).fadeIn( 100 );
			});
		});
	</script>
	<div id="dokanee-meta-box-container">
		<ul class="dokanee-meta-box-menu">
			<?php
			foreach ( ( array ) $tabs as $tab => $data ) {
				echo '<li class="' . esc_attr( $data['class'] ) . '"><a data-target="' . esc_attr( $data['target'] ) . '" href="#">' . esc_html( $data['title'] ) . '</a></li>';
			}

			do_action( 'dokanee_layout_meta_box_menu_item' );
			?>
		</ul>
		<div class="dokanee-meta-box-content">
			<div id="dokanee-layout-sidebars">
				<div class="dokanee_layouts">
					<label for="meta-dokanee-layout-global" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-global" value="" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], '' ); ?>>
						<?php esc_html_e( 'Default', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-layout-one" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'Right Sidebar', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-one" value="right-sidebar" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'right-sidebar' ); ?>>
						<?php esc_html_e( 'Content', 'dokanee' );?> / <strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong>
					</label>

					<label for="meta-dokanee-layout-two" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'Left Sidebar', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-two" value="left-sidebar" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'left-sidebar' ); ?>>
						<strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong> / <?php esc_html_e( 'Content', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-layout-three" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'No Sidebars', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-three" value="no-sidebar" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'no-sidebar' ); ?>>
						<?php esc_html_e( 'Content (no sidebars)', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-layout-four" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'Both Sidebars', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-four" value="both-sidebars" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'both-sidebars' ); ?>>
						<strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong> / <?php esc_html_e( 'Content', 'dokanee' );?> / <strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong>
					</label>

					<label for="meta-dokanee-layout-five" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'Both Sidebars on Left', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-five" value="both-left" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'both-left' ); ?>>
						<strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong> / <strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong> / <?php esc_html_e( 'Content', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-layout-six" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( 'Both Sidebars on Right', 'dokanee' );?>">
						<input type="radio" name="_dokanee-sidebar-layout-meta" id="meta-dokanee-layout-six" value="both-right" <?php checked( $stored_meta['_dokanee-sidebar-layout-meta'][0], 'both-right' ); ?>>
						<?php esc_html_e( 'Content', 'dokanee' );?> / <strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong> / <strong><?php echo esc_html_x( 'Sidebar', 'Short name for meta box', 'dokanee' ); ?></strong>
					</label>
				</div>
			</div>
			<div id="dokanee-layout-footer-widgets" style="display: none;">
				<div class="dokanee_footer_widget">
					<label for="meta-dokanee-footer-widget-global" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-global" value="" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '' ); ?>>
						<?php esc_html_e( 'Default', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-zero" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '0 Widgets', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-zero" value="0" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '0' ); ?>>
						<?php esc_html_e( '0 Widgets', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-one" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '1 Widget', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-one" value="1" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '1' ); ?>>
						<?php esc_html_e( '1 Widget', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-two" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '2 Widgets', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-two" value="2" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '2' ); ?>>
						<?php esc_html_e( '2 Widgets', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-three" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '3 Widgets', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-three" value="3" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '3' ); ?>>
						<?php esc_html_e( '3 Widgets', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-four" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '4 Widgets', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-four" value="4" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '4' ); ?>>
						<?php esc_html_e( '4 Widgets', 'dokanee' );?>
					</label>

					<label for="meta-dokanee-footer-widget-five" style="display:block;margin-bottom:3px;" title="<?php esc_attr_e( '5 Widgets', 'dokanee' );?>">
						<input type="radio" name="_dokanee-footer-widget-meta" id="meta-dokanee-footer-widget-five" value="5" <?php checked( $stored_meta['_dokanee-footer-widget-meta'][0], '5' ); ?>>
						<?php esc_html_e( '5 Widgets', 'dokanee' );?>
					</label>
				</div>
			</div>
			<div id="dokanee-layout-page-builder-container" style="display: none;">
				<p class="page-builder-content" style="color:#666;font-size:13px;margin-top:0;">
					<?php esc_html_e( 'Choose your page builder content container type. Both options remove the content padding for you.', 'dokanee' ) ;?>
				</p>

				<p class="dokanee_full_width_template">
					<label for="default-content" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_dokanee-full-width-content" id="default-content" value="" <?php checked( $stored_meta['_dokanee-full-width-content'][0], '' ); ?>>
						<?php esc_html_e( 'Default', 'dokanee' );?>
					</label>

					<label id="full-width-content" for="_dokanee-full-width-content" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_dokanee-full-width-content" id="_dokanee-full-width-content" value="true" <?php checked( $stored_meta['_dokanee-full-width-content'][0], 'true' ); ?>>
						<?php esc_html_e( 'Full Width', 'dokanee' );?>
					</label>

					<label id="dokanee-remove-padding" for="_dokanee-remove-content-padding" style="display:block;margin-bottom:10px;">
						<input type="radio" name="_dokanee-full-width-content" id="_dokanee-remove-content-padding" value="contained" <?php checked( $stored_meta['_dokanee-full-width-content'][0], 'contained' ); ?>>
						<?php esc_html_e( 'Contained', 'dokanee' );?>
					</label>
				</p>
			</div>
			<div id="dokanee-layout-disable-elements" style="display: none;">
				<?php if ( ! defined( 'GENERATE_DE_VERSION' ) ) : ?>
					<div class="dokanee_disable_elements">
						<label for="meta-dokanee-disable-headline" style="display:block;margin: 0 0 1em;" title="<?php esc_attr_e( 'Content Title', 'dokanee' );?>">
							<input type="checkbox" name="_dokanee-disable-headline" id="meta-dokanee-disable-headline" value="true" <?php checked( $stored_meta['_dokanee-disable-headline'][0], 'true' ); ?>>
							<?php esc_html_e( 'Content Title', 'dokanee' );?>
						</label>

						<?php if ( ! defined( 'GP_PREMIUM_VERSION' ) ) : ?>
							<span style="display:block;padding-top:1em;border-top:1px solid #EFEFEF;">
								<a href="<?php echo dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-disable-elements' ); // WPCS: XSS ok, sanitization ok. ?>" target="_blank"><?php esc_html_e( 'Premium module available', 'dokanee' ); ?></a>
							</span>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php do_action( 'dokanee_layout_disable_elements_section', $stored_meta ); ?>
			</div>
			<?php do_action( 'dokanee_layout_meta_box_content', $stored_meta ); ?>
		</div>
	</div>
    <?php
}

add_action( 'save_post', 'dokanee_save_layout_meta_data' );
/**
 * Saves the sidebar layout meta data.
 *
 * @since 2.0
 *
 * @param int Post ID.
 */
function dokanee_save_layout_meta_data( $post_id ) {
	$is_autosave = wp_is_post_autosave( $post_id );
	$is_revision = wp_is_post_revision( $post_id );
	$is_valid_nonce = ( isset( $_POST[ 'dokanee_layout_nonce' ] ) && wp_verify_nonce( sanitize_key( $_POST[ 'dokanee_layout_nonce' ] ), basename( __FILE__ ) ) ) ? true : false;

	if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	$sidebar_layout_key   = '_dokanee-sidebar-layout-meta';
	$sidebar_layout_value = filter_input( INPUT_POST, $sidebar_layout_key, FILTER_SANITIZE_STRING );

	if ( $sidebar_layout_value ) {
		update_post_meta( $post_id, $sidebar_layout_key, $sidebar_layout_value );
	} else {
		delete_post_meta( $post_id, $sidebar_layout_key );
	}

	$footer_widget_key   = '_dokanee-footer-widget-meta';
	$footer_widget_value = filter_input( INPUT_POST, $footer_widget_key, FILTER_SANITIZE_STRING );

	// Check for empty string to allow 0 as a value.
	if ( '' !== $footer_widget_value ) {
		update_post_meta( $post_id, $footer_widget_key, $footer_widget_value );
	} else {
		delete_post_meta( $post_id, $footer_widget_key );
	}

	$page_builder_container_key   = '_dokanee-full-width-content';
	$page_builder_container_value = filter_input( INPUT_POST, $page_builder_container_key, FILTER_SANITIZE_STRING );

	if ( $page_builder_container_value ) {
		update_post_meta( $post_id, $page_builder_container_key, $page_builder_container_value );
	} else {
		delete_post_meta( $post_id, $page_builder_container_key );
	}

	// We only need this if the Disable Elements module doesn't exist
	if ( ! defined( 'GENERATE_DE_VERSION' ) ) {
		$disable_content_title_key   = '_dokanee-disable-headline';
		$disable_content_title_value = filter_input( INPUT_POST, $disable_content_title_key, FILTER_SANITIZE_STRING );

		if ( $disable_content_title_value ) {
			update_post_meta( $post_id, $disable_content_title_key, $disable_content_title_value );
		} else {
			delete_post_meta( $post_id, $disable_content_title_key );
		}
	}

	do_action( 'dokanee_layout_meta_box_save', $post_id );
}
