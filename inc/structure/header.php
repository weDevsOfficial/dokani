<?php
/**
 * Header elements.
 *
 * @package Dokanee
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! function_exists( 'dokanee_construct_header' ) ) {
	add_action( 'dokanee_header', 'dokanee_construct_header' );
	/**
	 * Build the header.
	 *
	 * @since 1.3.42
	 */
	function dokanee_construct_header() {
		?>
		<header itemtype="https://schema.org/WPHeader" itemscope="itemscope" id="masthead" <?php dokanee_header_class(); ?>>
			<div <?php dokanee_inside_header_class(); ?>>
				<?php
				/**
				 * dokanee_before_header_content hook.
				 *
				 * @since 0.1
				 */
				do_action( 'dokanee_before_header_content' );

				echo '<div class="header-left">';
                    // Display dokan category
                    if ( function_exists( 'dokan' ) ) {
	                    dokan_category_widget();
                    }

                    // Add our main header items.
                     dokanee_header_items();

                    /**
                     * dokanee_after_header_left hook.
                     *
                     * @since 0.1
                     *
                     * @hooked dokanee_responsive_nav - 5
                     */
                    if ( class_exists( 'WooCommerce' ) ) {
	                    do_action( 'dokanee_after_header_left' );
                    }

				echo '</div>';
				echo '<div class="header-right">';

				    dokanee_construct_header_widget();

                    /**
                     * dokanee_after_header_right hook.
                     *
                     * @since 0.1
                     *
                     * @hooked dokanee_add_navigation_float_right - 5
                     * @hooked dokanee_add_cart_menu_after_search - 10
                     */
                      do_action( 'dokanee_after_header_right' );

				echo '</div>';

				/**
				 * dokanee_after_header_content hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_add_navigation_float_right - 5
				 */
				do_action( 'dokanee_after_header_content' );
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
				'title' => __( 'Product Categories', 'dokanee' )
			), array(
					'before_widget' => '<div class="category-menu-wrapper"><div class="dokanee-category-menu">',
					'after_widget'  => '</div></div>',
					'before_title'  => '<h3 class="title">',
					'after_title'   => '</h3>',
				)
			);
		}
	}

endif;


if ( ! function_exists( 'dokanee_header_items' ) ) {
	/**
	 * Build the header contents.
	 * Wrapping this into a function allows us to customize the order.
	 *
	 * @since 1.2.9.7
	 */
	function dokanee_header_items() {
		dokanee_construct_site_title();
		dokanee_construct_logo();
	}
}

if ( ! function_exists( 'dokanee_construct_logo' ) ) {
	/**
	 * Build the logo
	 *
	 * @since 1.3.28
	 */
	function dokanee_construct_logo() {
		$logo_url = ( function_exists( 'the_custom_logo' ) && get_theme_mod( 'custom_logo' ) ) ? wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' ) : false;
		$logo_url = ( $logo_url ) ? $logo_url[0] : dokanee_get_setting( 'logo' );

		$logo_url = esc_url( apply_filters( 'dokanee_logo', $logo_url ) );
		$retina_logo_url = esc_url( apply_filters( 'dokanee_retina_logo', dokanee_get_setting( 'retina_logo' ) ) );

		// If we don't have a logo, bail.
		if ( empty( $logo_url ) ) {
			return;
		}

		/**
		 * dokanee_before_logo hook.
		 *
		 * @since 0.1
		 */
		do_action( 'dokanee_before_logo' );

		$attr = apply_filters( 'dokanee_logo_attributes', array(
			'class' => 'header-image',
			'alt'	=> esc_attr( apply_filters( 'dokanee_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			'src'	=> $logo_url,
			'title'	=> esc_attr( apply_filters( 'dokanee_logo_title', get_bloginfo( 'name', 'display' ) ) ),
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
		echo apply_filters( 'dokanee_logo_output', sprintf( // WPCS: XSS ok, sanitization ok.
			'<div class="site-logo">
				<a href="%1$s" title="%2$s" rel="home">
					<img %3$s />
				</a>
			</div>',
			esc_url( apply_filters( 'dokanee_logo_href' , home_url( '/' ) ) ),
			esc_attr( apply_filters( 'dokanee_logo_title', get_bloginfo( 'name', 'display' ) ) ),
			$html_attr
		), $logo_url, $html_attr );

		/**
		 * dokanee_after_logo hook.
		 *
		 * @since 0.1
		 */
		do_action( 'dokanee_after_logo' );
	}
}

if ( ! function_exists( 'dokanee_construct_site_title' ) ) {
	/**
	 * Build the site title and tagline.
	 *
	 * @since 1.3.28
	 */
	function dokanee_construct_site_title() {
		$dokanee_settings = wp_parse_args(
			get_option( 'dokanee_settings', array() ),
			dokanee_get_defaults()
		);

		// Get the title and tagline.
		$title = get_bloginfo( 'title' );
		$tagline = get_bloginfo( 'description' );

		// If the disable title checkbox is checked, or the title field is empty, return true.
		$disable_title = ( '1' == $dokanee_settings[ 'hide_title' ] || '' == $title ) ? true : false;

		// If the disable tagline checkbox is checked, or the tagline field is empty, return true.
		$disable_tagline = ( '1' == $dokanee_settings[ 'hide_tagline' ] || '' == $tagline ) ? true : false;

		// Build our site title.
		$site_title = apply_filters( 'dokanee_site_title_output', sprintf(
			'<%1$s class="main-title" itemprop="headline">
				<a href="%2$s" rel="home">
					%3$s
				</a>
			</%1$s>',
			( is_front_page() && is_home() ) ? 'h1' : 'p',
			esc_url( apply_filters( 'dokanee_site_title_href', home_url( '/' ) ) ),
			get_bloginfo( 'name' )
		) );

		// Build our tagline.
		$site_tagline = apply_filters( 'dokanee_site_description_output', sprintf(
			'<p class="site-description"><small>
				%1$s
			</small></p>',
			html_entity_decode( get_bloginfo( 'description', 'display' ) )
		) );

		// Site title and tagline.
		if ( false == $disable_title || false == $disable_tagline ) {
			echo apply_filters( 'dokanee_site_branding_output', sprintf( // WPCS: XSS ok, sanitization ok.
				'<div class="site-branding">
					%1$s
					%2$s
				</div>',
				( ! $disable_title ) ? $site_title : '',
				( ! $disable_tagline ) ? $site_tagline : ''
			) );
		}
	}
}

if ( ! function_exists( 'dokanee_construct_header_widget' ) ) {
	/**
	 * Build the header widget.
	 *
	 * @since 1.3.28
	 */
	function dokanee_construct_header_widget() {
		if ( is_active_sidebar('header') ) : ?>
			<div class="header-widget">
				<?php dynamic_sidebar( 'header' ); ?>
			</div>
		<?php endif;
	}
}

if ( ! function_exists( 'dokanee_top_bar' ) ) {
	add_action( 'dokanee_before_header', 'dokanee_top_bar', 5 );
	/**
	 * Build our top bar.
	 *
	 * @since 1.3.45
	 */
	function dokanee_top_bar() { ?>

		<div <?php dokanee_top_bar_class(); ?>>
			<div class="inside-top-bar<?php if ( 'contained' == dokanee_get_setting( 'top_bar_inner_width' ) ) echo ' grid-container grid-parent'; ?>">
				<?php
				if ( has_nav_menu( 'top' ) ) {
					wp_nav_menu(
						array(
							'theme_location' => 'top',
							'container' => 'div',
							'container_class' => 'top-nav',
							'container_id' => 'top-menu',
							'menu_class' => '',
							'items_wrap' => '<ul id="%1$s" class="%2$s ' . join( ' ', dokanee_get_menu_class() ) . '">%3$s</ul>'
						)
					);
				} else {
                    echo '<a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a>';
				} ?>

                <div class="dokanee-user-menu">
	                <?php
                    if ( function_exists( 'dokan_header_user_menu' ) && class_exists( 'WooCommerce' ) ) {
	                    dokan_header_user_menu();
                    } elseif ( ! function_exists( 'dokan_header_user_menu' ) && class_exists( 'WooCommerce' ) ) { ?>
                        <ul class="nav navbar-nav navbar-right">
		                    <?php
		                    $cart_topbar = dokanee_get_setting( 'cart_position_setting' );

		                    if ( 'cart-topbar' == $cart_topbar){
			                    echo dokanee_cart_position();
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

if ( ! function_exists( 'dokanee_pingback_header' ) ) {
	add_action( 'wp_head', 'dokanee_pingback_header' );
	/**
	 * Add a pingback url auto-discovery header for singularly identifiable articles.
	 *
	 * @since 1.3.42
	 */
	function dokanee_pingback_header() {
		if ( is_singular() && pings_open() ) {
			printf( '<link rel="pingback" href="%s">' . "\n", esc_url( get_bloginfo( 'pingback_url' ) ) );
		}
	}
}

if ( ! function_exists( 'dokanee_add_viewport' ) ) {
	add_action( 'wp_head', 'dokanee_add_viewport' );
	/**
	 * Add viewport to wp_head.
	 *
	 * @since 1.1.0
	 */
	function dokanee_add_viewport() {
		echo apply_filters( 'dokanee_meta_viewport', '<meta name="viewport" content="width=device-width, initial-scale=1">' ); // WPCS: XSS ok.
	}
}

add_action( 'dokanee_before_header', 'dokanee_do_skip_to_content_link', 2 );
/**
 * Add skip to content link before the header.
 *
 * @since 2.0
 */
function dokanee_do_skip_to_content_link() {
	printf( '<a class="screen-reader-text skip-link" href="#content" title="%1$s">%2$s</a>',
		esc_attr__( 'Skip to content', 'dokanee' ),
		esc_html__( 'Skip to content', 'dokanee' )
	);
}

if ( ! function_exists( 'dokanee_responsive_nav' ) ) {
	add_action( 'dokanee_after_header_left', 'dokanee_responsive_nav', 5 );

	/**
	 * Build responsive menu.
	 *
	 * @since 1.3.45
	 */
	function dokanee_responsive_nav() {
	    echo '<div class="responsive-nav">';
		dokanee_navigation_position();
	    echo '</div>';
	}
}

if ( ! function_exists( 'dokanee_responsive_user_menu' ) ) {
    if ( class_exists( 'WooCommerce' ) ) {
	    add_action( 'dokanee_inside_navigation', 'dokanee_responsive_user_menu', 5 );
    }

	/**
	 * Build responsive user menu.
	 *
	 * @since 1.3.45
	 */
	function dokanee_responsive_user_menu() {
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
            <a href="#" class="dropdown-toggle dokanee-menu-cart" data-toggle="dropdown">
                <i class="flaticon flaticon-commerce-1"></i>
                <span class="screen-reader-text"><?php _e( 'Cart', 'dokanee' )?></span>
                <span class="dokan-cart-amount-top"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
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
                <a href="#" class="dropdown-toggle dokanee-menu-user" data-toggle="dropdown">
                    <i class="flaticon flaticon-people"></i>
                    <span class="screen-reader-text"><?php echo esc_html( $current_user->display_name ); ?> <i class="fa fa-angle-down"></i></span>
                </a>
                <ul class="dropdown-menu">
                    <?php if ( function_exists( 'dokan_get_page_url' ) ) { ?>
                    <li><a href="<?php echo dokan_get_page_url( 'my_orders' ); ?>"><?php _e( 'My Orders', 'dokanee' ); ?></a></li>
                    <li><a href="<?php echo dokan_get_page_url( 'myaccount', 'woocommerce' ); ?>"><?php _e( 'My Account', 'dokanee' ); ?></a></li>
                    <?php } ?>
                    <li><a href="<?php echo wc_customer_edit_account_url(); ?>"><?php _e( 'Edit Account', 'dokanee' ); ?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'billing', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Billing Address', 'dokanee' ); ?></a></li>
                    <li><a href="<?php echo wc_get_endpoint_url( 'edit-address', 'shipping', get_permalink( wc_get_page_id( 'myaccount' ) ) ); ?>"><?php _e( 'Shipping Address', 'dokanee' ); ?></a></li>
                    <li><?php wp_loginout( home_url() ); ?></li>
                </ul>
            </li>

        <?php } else { ?>
            <li><a href="<?php echo wc_get_page_permalink( 'myaccount', 'woocommerce' ); ?>" class="dokanee-menu-login"><?php _e( 'Login / Register', 'dokanee' ); ?></a></li>
        <?php }
	}

endif;