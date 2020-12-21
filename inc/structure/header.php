<?php
/**
 * Header elements.
 *
 * @package dokani
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokani_construct_header' ) ) {
	add_action( 'dokani_header', 'dokani_construct_header' );
	/**
	 * Build the header.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_header() {
		?>
		<header itemtype="https://schema.org/WPHeader" itemscope="itemscope" id="masthead" <?php dokani_header_class(); ?>>
			<div <?php dokani_inside_header_class(); ?>>
				<?php
				/**
				 * dokani_before_header_content hook.
				 *
				 * @since 1.0.0
				 */
				do_action( 'dokani_before_header_content' );

				echo '<div class="header-left">';
                    // Display dokan category
                    if ( function_exists( 'dokan' ) && get_theme_mod( 'show_product_cateogry_menu', 'on' ) == 'on' ) {
	                    dokan_category_widget();
                    }

                    // Add our main header items.
                     dokani_header_items();

                    /**
                     * dokani_after_header_left hook.
                     *
                     * @since 1.0.0
                     *
                     * @hooked dokani_responsive_nav - 5
                     */
                    if ( class_exists( 'WooCommerce' ) ) {
	                    do_action( 'dokani_after_header_left' );
                    }

				echo '</div>';
				echo '<div class="header-right">';

				    dokani_construct_header_widget();

                    /**
                     * dokani_after_header_right hook.
                     *
                     * @since 1.0.0
                     *
                     * @hooked dokani_add_navigation_float_right - 5
                     * @hooked dokani_add_cart_menu_after_search - 10
                     */
                      do_action( 'dokani_after_header_right' );

				echo '</div>';

				/**
				 * dokani_after_header_content hook.
				 *
				 * @since 1.0.0
				 *
				 * @hooked dokani_add_navigation_float_right - 5
				 */
				do_action( 'dokani_after_header_content' );
				?>
			</div>  <!-- .inside-header -->
		</header><!-- #masthead -->
		<?php
	}
}

if ( ! function_exists( 'dokan_category_widget' ) ) :
	/**
	 * Display the product category widget
	 *
	 * @return void
	 */
	function dokan_category_widget() {
		if ( class_exists( 'Dokan_Category_Widget' ) ) {
			the_widget( 'Dokan_Category_Widget', array(
				'title' => __( 'Product Categories', 'dokani' )
			), array(
					'before_widget' => '<div class="category-menu-wrapper"><div class="dokani-category-menu">',
					'after_widget'  => '</div></div>',
					'before_title'  => '<h3 class="title">',
					'after_title'   => '</h3>',
				)
			);
		}
	}

endif;


if ( ! function_exists( 'dokani_header_items' ) ) {
	/**
	 * Build the header contents.
	 * Wrapping this into a function allows us to customize the order.
	 *
	 * @since 1.0.0
	 */
	function dokani_header_items() {
		dokani_construct_site_title();
		dokani_construct_logo();
	}
}

if ( ! function_exists( 'dokani_construct_logo' ) ) {
	/**
	 * Build the logo
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_logo() {
		$logo_url = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
		$logo_url = ( $logo_url ) ? $logo_url[0] : dokani_get_setting( 'logo' );

		$logo_url = esc_url( apply_filters( 'dokani_logo', $logo_url ) );
		$retina_logo_url = esc_url( apply_filters( 'dokani_retina_logo', dokani_get_setting( 'retina_logo' ) ) );

		// If we don't have a logo, bail.
		if ( empty( $logo_url ) ) {
			return;
		}

		/**
		 * dokani_before_logo hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_before_logo' );

		$attr = apply_filters( 'dokani_logo_attributes', array(
			'class' => 'header-image',
			'alt'	=> esc_attr( apply_filters( 'dokani_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			'src'	=> $logo_url,
			'title'	=> esc_attr( apply_filters( 'dokani_logo_title', get_bloginfo( 'name', 'display' ) ) ),
		) );

		if ( '' !== $retina_logo_url ) {
			$attr[ 'srcset' ] = $logo_url . ' 1x, ' . $retina_logo_url . ' 2x';

			// Add dimensions to image if retina is set. This fixes a container width bug in Firefox.
			if ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) {
				$data = wp_get_attachment_metadata( get_theme_mod( 'custom_logo' ) );

				if ( ! empty( $data ) ) {
					$attr['width'] = $data['width'];
					$attr['height'] = $data['height'];
				}
			}
		}

		$attr = array_map( 'esc_attr', $attr );

		$html_attr = '';
		foreach ( $attr as $name => $value ) {
			$html_attr .= " $name=" . '"' . $value . '"';
		}

		// Print our HTML.
		echo wp_kses_post( apply_filters( 'dokani_logo_output', sprintf(
            '<div class="site-logo">
				<a href="%1$s" title="%2$s" rel="home">
					<img %3$s />
				</a>
			</div>',
            esc_url( apply_filters( 'dokani_logo_href' , home_url( '/' ) ) ),
            esc_attr( apply_filters( 'dokani_logo_title', get_bloginfo( 'name', 'display' ) ) ),
            $html_attr
        ), $logo_url, $html_attr ) );

		/**
		 * dokani_after_logo hook.
		 *
		 * @since 1.0.0
		 */
		do_action( 'dokani_after_logo' );
	}
}

if ( ! function_exists( 'dokani_construct_site_title' ) ) {
	/**
	 * Build the site title and tagline.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_site_title() {
		$dokani_settings = wp_parse_args(
			get_option( 'dokani_settings', array() ),
			dokani_get_defaults()
		);

		// Get the title and tagline.
		$title = get_bloginfo( 'title' );
		$tagline = get_bloginfo( 'description' );

		// If the disable title checkbox is checked, or the title field is empty, return true.
		$disable_title = ( '1' == $dokani_settings[ 'hide_title' ] || '' == $title ) ? true : false;

		// If the disable tagline checkbox is checked, or the tagline field is empty, return true.
		$disable_tagline = ( '1' == $dokani_settings[ 'hide_tagline' ] || '' == $tagline ) ? true : false;

		// Build our site title.
		$site_title = apply_filters( 'dokani_site_title_output', sprintf(
			'<%1$s class="main-title" itemprop="headline">
				<a href="%2$s" rel="home">
					%3$s
				</a>
			</%1$s>',
			( is_front_page() && is_home() ) ? 'h1' : 'p',
			esc_url( apply_filters( 'dokani_site_title_href', home_url( '/' ) ) ),
			get_bloginfo( 'name' )
		) );

		// Build our tagline.
		$site_tagline = apply_filters( 'dokani_site_description_output', sprintf(
			'<p class="site-description"><small>
				%1$s
			</small></p>',
			html_entity_decode( get_bloginfo( 'description', 'display' ) )
		) );

		// Site title and tagline.
		if ( false == $disable_title || false == $disable_tagline ) {
			echo wp_kses_post( apply_filters( 'dokani_site_branding_output', sprintf(
                '<div class="site-branding">
					%1$s
					%2$s
				</div>',
                ( ! $disable_title ) ? $site_title : '',
                ( ! $disable_tagline ) ? $site_tagline : ''
            ) ) );
		}
	}
}

if ( ! function_exists( 'dokani_construct_header_widget' ) ) {
	/**
	 * Build the header widget.
	 *
	 * @since 1.0.0
	 */
	function dokani_construct_header_widget() {
		if ( is_active_sidebar('header') ) : ?>
			<div class="header-widget">
				<?php dynamic_sidebar( 'header' ); ?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'dokani_top_bar' ) ) {
    add_action( 'dokani_before_header', 'dokani_top_bar', 5 );
	/**
	 * Build our top bar.
	 *
	 * @since 1.0.0
	 */
	function dokani_top_bar() {
	    if ( get_theme_mod( 'show_topbar' ) === 'disabled' ) return false;
	    ?>

		<div <?php dokani_top_bar_class(); ?>>
			<div class="inside-top-bar<?php if ( 'contained' == dokani_get_setting( 'top_bar_inner_width' ) ) echo ' grid-container grid-parent'; ?>">
				<?php
				if ( has_nav_menu( 'top' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'top',
							'container' => 'div',
							'container_class' => 'top-nav',
							'container_id' => 'top-menu',
							'menu_class' => '',
							'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', dokani_get_menu_class() ) . '">%3$s</ul>'
						)
					);
				} else {
                    echo '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add Top Menu', 'dokani' ) . '</a>';
				} ?>

                <div class="dokani-user-menu">
	                <?php
                    if ( function_exists( 'dokan_header_user_menu' ) && class_exists( 'WooCommerce' ) ) {
	                    dokan_header_user_menu();
                    } elseif ( ! function_exists( 'dokan_header_user_menu' ) && class_exists( 'WooCommerce' ) ) { ?>
                        <ul class="nav navbar-nav navbar-right">
		                    <?php
		                    $cart_topbar = dokani_get_setting( 'cart_position_setting' );

		                    if ( 'cart-topbar' == $cart_topbar){
			                    echo wp_kses_post( dokani_cart_position() );
		                    }
		                    ?>
                        </ul>
                    <?php } ?>
                </div>
			</div>
		</div>
		<?php
	}
}

if ( ! function_exists( 'dokani_pingback_header' ) ) {
	add_action( 'wp_head', 'dokani_pingback_header' );
	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 *
	 * @since 1.0.0
	 */
	function dokani_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

if ( ! function_exists( 'dokani_add_viewport' ) ) {
	add_action( 'wp_head', 'dokani_add_viewport' );
	/**
	 * Add viewport to wp_head.
	 *
	 * @since 1.0.0
	 */
	function dokani_add_viewport() {
		echo wp_kses_post( apply_filters( 'dokani_meta_viewport', '<meta name="viewport" content="width=device-width, initial-scale=1">' ) );
	}
}

add_action( 'dokani_before_header', 'dokani_do_skip_to_content_link', 2 );
/**
 * Add skip to content link before the header.
 *
 * @since 1.0.0
 */
function dokani_do_skip_to_content_link() {
	printf( '<a class="screen-reader-text skip-link" href="#content" title="%1$s">%2$s</a>',
		esc_attr__( 'Skip to content', 'dokani' ),
		esc_html__( 'Skip to content', 'dokani' )
	);
}

if ( ! function_exists( 'dokani_responsive_nav' ) ) {
	add_action( 'dokani_after_header_left', 'dokani_responsive_nav', 5 );

	/**
	 * Build responsive menu.
	 *
	 * @since 1.0.0
	 */
	function dokani_responsive_nav() {
	    echo '<div class="responsive-nav">';
		dokani_navigation_position();
	    echo '</div>';
	}
}

if ( ! function_exists( 'dokani_responsive_user_menu' ) ) {
    if ( class_exists( 'WooCommerce' ) ) {
	    add_action( 'dokani_inside_navigation', 'dokani_responsive_user_menu', 5 );
    }

	/**
	 * Build responsive user menu.
	 *
	 * @since 1.0.0
	 */
	function dokani_responsive_user_menu() {
	    echo '<ul class="responsive-user-menu no-list-style">';
		 dokan_responsive_user_menu();
	    echo '</ul>';
	}
}

if ( ! function_exists( 'dokan_responsive_user_menu' ) ) :

	/**
	 * Responsive User menu
	 */
	function dokan_responsive_user_menu() {
		?>
		<li id="dokane-menu-cart-wrapper">
            <a href="#" class="dropdown-toggle dokani-menu-cart" data-toggle="dropdown">
                <i class="flaticon flaticon-commerce-1"></i>
                <span class="screen-reader-text"><?php esc_html_e( 'Cart', 'dokani' )?></span>
                <span class="dokan-cart-amount-top"><?php echo wp_kses_post( WC()->cart->get_cart_contents_count() ); ?></span>
            </a>

            <ul class="dropdown-menu">
                <li>
                    <div class="widget_shopping_cart_content"></div>
                </li>
            </ul>
		</li>

		<?php
            if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
        ?>

            <li class="dropdown">
                <a href="#" class="dropdown-toggle dokani-menu-user" data-toggle="dropdown">
                    <i class="flaticon flaticon-people"></i>
                    <span class="screen-reader-text"><?php echo esc_html( $current_user->display_name ); ?> <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <?php if ( function_exists( 'dokan_get_page_url' ) ) { ?>
                    <li><a href="<?php echo esc_url( dokan_get_page_url( 'my_orders' ) ); ?>"><?php esc_html_e( 'My Orders', 'dokani' ); ?></a></li>
                    <li><a href="<?php echo esc_url( 'myaccount', 'woocommerce' ); ?>"><?php esc_html_e( 'My Account', 'dokani' ); ?></a></li>
                    <?php } ?>
                    <li><a href="<?php echo esc_url( wc_customer_edit_account_url() ); ?>"><?php esc_html_e( 'Edit Account', 'dokani' ); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ) ); ?>"><?php esc_html_e( 'Billing Address', 'dokani' ); ?></a></li>
                    <li><a href="<?php echo esc_url( wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ) ); ?>"><?php esc_html_e( 'Shipping Address', 'dokani' ); ?></a></li>
                    <li><?php wp_loginout( home_url() ); ?></li>
                </ul>
            </li>

        <?php } else { ?>
            <li><a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount', 'woocommerce' ) ); ?>" class="dokani-menu-login"><?php esc_html_e( 'Login / Register', 'dokani' ); ?></a></li>
        <?php }
	}

endif;
