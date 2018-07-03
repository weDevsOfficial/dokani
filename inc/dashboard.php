<?php
/**
 * Builds our admin page.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_create_menu' ) ) {
	add_action( 'admin_menu', 'dokanee_create_menu' );
	/**
	 * Adds our "Dokanee" dashboard menu item
	 *
	 * @since 0.1
	 */
	function dokanee_create_menu() {
		$dokanee_page = add_theme_page( 'Dokanee', 'Dokanee', apply_filters( 'dokanee_dashboard_page_capability', 'edit_theme_options' ), 'dokanee-options', 'dokanee_settings_page' );
		add_action( "admin_print_styles-$dokanee_page", 'dokanee_options_styles' );
	}
}

if ( ! function_exists( 'dokanee_options_styles' ) ) {
	/**
	 * Adds any necessary scripts to the GP dashboard page
	 *
	 * @since 0.1
	 */
	function dokanee_options_styles() {
		wp_enqueue_style( 'dokanee-options', get_template_directory_uri() . '/css/admin/style.css', array(), GENERATE_VERSION );
	}
}

if ( ! function_exists( 'dokanee_settings_page' ) ) {
	/**
	 * Builds the content of our GP dashboard page
	 *
	 * @since 0.1
	 */
	function dokanee_settings_page() {
		?>
		<div class="wrap">
			<div class="metabox-holder">
				<div class="gp-masthead clearfix">
					<div class="gp-container">
						<div class="gp-title">
							<a href="<?php echo dokanee_get_premium_url( 'https://generatepress.com' ); // WPCS: XSS ok, sanitization ok. ?>" target="_blank">Dokanee</a> <span class="gp-version"><?php echo GENERATE_VERSION; // WPCS: XSS ok ?></span>
						</div>
						<div class="gp-masthead-links">
							<?php if ( ! defined( 'GP_PREMIUM_VERSION' ) ) : ?>
								<a style="font-weight: bold;" href="<?php echo dokanee_get_premium_url( 'https://generatepress.com/premium/' ); // WPCS: XSS ok, sanitization ok. ?>" target="_blank"><?php esc_html_e( 'Premium', 'dokanee' );?></a>
							<?php endif; ?>
							<a href="<?php echo esc_url( 'https://generatepress.com/support' ); ?>" target="_blank"><?php esc_html_e( 'Support', 'dokanee' ); ?></a>
							<a href="<?php echo esc_url( 'https://docs.generatepress.com' ); ?>" target="_blank"><?php esc_html_e( 'Documentation', 'dokanee' );?></a>
						</div>
					</div>
				</div>

				<?php
				/**
				 * dokanee_dashboard_after_header hook.
				 *
				 * @since 2.0
				 */
				 do_action( 'dokanee_dashboard_after_header' );
				 ?>

				<div class="gp-container">
					<div class="postbox-container clearfix" style="float: none;">
						<div class="grid-container grid-parent">

							<?php
							/**
							 * dokanee_dashboard_inside_container hook.
							 *
							 * @since 2.0
							 */
							 do_action( 'dokanee_dashboard_inside_container' );
							 ?>

							<div class="form-metabox grid-70" style="padding-left: 0;">
								<h2 style="height:0;margin:0;"><!-- admin notices below this element --></h2>
								<form method="post" action="options.php">
									<?php settings_fields( 'dokanee-settings-group' ); ?>
									<?php do_settings_sections( 'dokanee-settings-group' ); ?>
									<div class="customize-button hide-on-desktop">
										<?php
										printf( '<a id="dokanee_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
											esc_url( admin_url( 'customize.php' ) ),
											esc_html__( 'Customize', 'dokanee' )
										);
										?>
									</div>

									<?php
									/**
									 * dokanee_inside_options_form hook.
									 *
									 * @since 0.1
									 */
									 do_action( 'dokanee_inside_options_form' );
									 ?>
								</form>

								<?php
								$modules = array(
									'Backgrounds' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-backgrounds/' ),
									),
									'Blog' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-blog/' ),
									),
									'Colors' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-colors/' ),
									),
									'Copyright' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-copyright/' ),
									),
									'Disable Elements' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-disable-elements/' ),
									),
									'Hooks' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-hooks/' ),
									),
									'Import / Export' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-import-export/' ),
									),
									'Menu Plus' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-menu-plus/' ),
									),
									'Page Header' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-page-header/' ),
									),
									'Secondary Nav' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-secondary-nav/' ),
									),
									'Sections' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-sections/' ),
									),
									'Spacing' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-spacing/' ),
									),
									'Typography' => array(
											'url' => dokanee_get_premium_url( 'https://generatepress.com/downloads/dokanee-typography/' ),
									)
								);

								if ( ! defined( 'GP_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox dokanee-metabox">
										<h3 class="hndle"><?php esc_html_e( 'Premium Modules', 'dokanee' ); ?></h3>
										<div class="inside" style="margin:0;padding:0;">
											<div class="premium-addons">
												<?php foreach( $modules as $module => $info ) { ?>
												<div class="add-on activated gp-clear addon-container grid-parent">
													<div class="addon-name column-addon-name" style="">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php echo esc_html( $module ); ?></a>
													</div>
													<div class="addon-action addon-addon-action" style="text-align:right;">
														<a href="<?php echo esc_url( $info[ 'url' ] ); ?>" target="_blank"><?php esc_html_e( 'Learn more', 'dokanee' ); ?></a>
													</div>
												</div>
												<div class="gp-clear"></div>
												<?php } ?>
											</div>
										</div>
									</div>
								<?php
								endif;

								/**
								 * dokanee_options_items hook.
								 *
								 * @since 0.1
								 */
								do_action( 'dokanee_options_items' );
								?>
							</div>

							<div class="dokanee-right-sidebar grid-30" style="padding-right: 0;">
								<div class="customize-button hide-on-mobile">
									<?php
									printf( '<a id="dokanee_customize_button" class="button button-primary" href="%1$s">%2$s</a>',
										esc_url( admin_url( 'customize.php' ) ),
										esc_html__( 'Customize', 'dokanee' )
									);
									?>
								</div>

								<?php
								/**
								 * dokanee_admin_right_panel hook.
								 *
								 * @since 0.1
								 */
								 do_action( 'dokanee_admin_right_panel' );

								 if ( ! defined( 'GP_PREMIUM_VERSION' ) ) : ?>
									<div class="postbox dokanee-metabox popular-articles">
										<h3 class="hndle"><a href="https://docs.generatepress.com" target="_blank"><?php esc_html_e( 'View all', 'dokanee' ); ?></a><?php esc_html_e( 'Documentation', 'dokanee' ); ?></h3>
										<div class="inside">
											<ul>
												<li><a href="https://docs.generatepress.com/article/adding-header-logo/" target="_blank"><?php esc_html_e( 'Adding a Logo', 'dokanee' ); ?></a></li>
												<li><a href="https://docs.generatepress.com/article/sidebar-layout/" target="_blank"><?php esc_html_e( 'Sidebar Layout', 'dokanee' ); ?></a></li>
												<li><a href="https://docs.generatepress.com/article/container-width/" target="_blank"><?php esc_html_e( 'Container Width', 'dokanee' ); ?></a></li>
												<li><a href="https://docs.generatepress.com/article/navigation-location/" target="_blank"><?php esc_html_e( 'Navigation Location', 'dokanee' ); ?></a></li>
												<li><a href="https://docs.generatepress.com/article/footer-widgets/" target="_blank"><?php esc_html_e( 'Footer Widgets', 'dokanee' ); ?></a></li>
											</ul>
										</div>
									</div>
								<?php endif; ?>

								<div class="postbox dokanee-metabox" id="gen-delete">
									<h3 class="hndle"><?php esc_html_e( 'Delete Customizer Settings', 'dokanee' );?></h3>
									<div class="inside">
										<p><?php esc_html_e( 'Deleting your settings can not be undone.', 'dokanee' ); ?></p>
										<form method="post">
											<p><input type="hidden" name="dokanee_reset_customizer" value="dokanee_reset_customizer_settings" /></p>
											<p>
												<?php
												$warning = 'return confirm("' . esc_html__( 'Warning: This will delete your settings.', 'dokanee' ) . '")';
												wp_nonce_field( 'dokanee_reset_customizer_nonce', 'dokanee_reset_customizer_nonce' );
												submit_button( esc_attr__( 'Delete Default Settings', 'dokanee' ), 'button', 'submit', false, array( 'onclick' => esc_js( $warning ) ) );
												?>
											</p>

										</form>
										<?php
										/**
										 * dokanee_delete_settings_form hook.
										 *
										 * @since 0.1
										 */
										 do_action( 'dokanee_delete_settings_form' );
										 ?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="gp-options-footer">
						<span>
							<?php
							printf( // WPCS: XSS ok
								/* translators: %s: Heart icon */
								_x( 'Made with %s by Tom Usborne', 'made with love', 'dokanee' ),
								'<span style="color:#D04848" class="dashicons dashicons-heart"></span>'
							);
							?>
						</span>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'dokanee_reset_customizer_settings' ) ) {
	add_action( 'admin_init', 'dokanee_reset_customizer_settings' );
	/**
	 * Reset customizer settings
	 *
	 * @since 0.1
	 */
	function dokanee_reset_customizer_settings() {
		if ( empty( $_POST['dokanee_reset_customizer'] ) || 'dokanee_reset_customizer_settings' !== $_POST['dokanee_reset_customizer'] ) {
			return;
		}

		$nonce = isset( $_POST['dokanee_reset_customizer_nonce'] ) ? sanitize_key( $_POST['dokanee_reset_customizer_nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'dokanee_reset_customizer_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		delete_option( 'dokanee_settings' );
		delete_option( 'dokanee_dynamic_css_output' );
		delete_option( 'dokanee_dynamic_css_cached_version' );
		remove_theme_mod( 'font_body_variants' );
		remove_theme_mod( 'font_body_category' );

		wp_safe_redirect( admin_url( 'themes.php?page=dokanee-options&status=reset' ) );
		exit;
	}
}

if ( ! function_exists( 'dokanee_admin_errors' ) ) {
	add_action( 'admin_notices', 'dokanee_admin_errors' );
	/**
	 * Add our admin notices
	 *
	 * @since 0.1
	 */
	function dokanee_admin_errors() {
		$screen = get_current_screen();

		if ( 'appearance_page_dokanee-options' !== $screen->base ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) {
			 add_settings_error( 'dokanee-notices', 'true', esc_html__( 'Settings saved.', 'dokanee' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'imported' == $_GET['status'] ) {
			 add_settings_error( 'dokanee-notices', 'imported', esc_html__( 'Import successful.', 'dokanee' ), 'updated' );
		}

		if ( isset( $_GET['status'] ) && 'reset' == $_GET['status'] ) {
			 add_settings_error( 'dokanee-notices', 'reset', esc_html__( 'Settings removed.', 'dokanee' ), 'updated' );
		}

		settings_errors( 'dokanee-notices' );
	}
}
