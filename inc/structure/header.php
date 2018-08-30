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

				// Display dokan category
				dokan_category_widget();

				// Add our main header items.
				dokanee_header_items();

				/**
				 * dokanee_after_header_content hook.
				 *
				 * @since 0.1
				 *
				 * @hooked dokanee_add_navigation_float_right - 5
				 */
				do_action( 'dokanee_after_header_content' );
				?>
			</div><!-- .inside-header -->

            <?php
            if ( !is_front_page() && !dokan_is_store_page() && !is_single() && !is_404() ) {
	            get_template_part( 'template-parts/page', 'header' );
            }
            ?>
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
				'title' => __( 'Product Categories', 'dokan-theme' )
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
		dokanee_construct_header_widget();
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
			'<p class="site-description">
				%1$s
			</p>',
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
                if ( ! is_active_sidebar( 'top-bar' ) ) {
	                echo '<a href="' . admin_url( 'nav-menus.php' ) . '">Add a menu</a>';
                } else {
	                dynamic_sidebar( 'top-bar' );
                }
                ?>

                <div class="dokanee-user-menu">
	                <?php dokan_header_user_menu(); ?>
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
