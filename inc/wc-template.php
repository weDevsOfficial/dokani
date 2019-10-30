<?php
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 10 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
remove_action( 'woocommerce_review_before_comment_meta', 'woocommerce_review_display_rating', 20 );

add_action( 'woocommerce_before_quantity_input_field', 'dokanee_quantity_label' );

/*
 *
 *
 */
function dokanee_quantity_label() {
    echo "<label class='quantity_label'>" . __( 'Quantity', 'dokanee' ) . " :</label>";
}

/**
 * Pagination before shop loops.
 *
 * @see woocommerce_pagination()
 */
add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 40 );

/**
 * Renders item-bar of products in the loop
 *
 * @global WC_Product $product
 */
function dokanee_product_loop_price() {
    global $product;
	global $post;

	$store_info = dokan_get_store_info( $post->post_author );
	$url = dokan_get_store_url( $post->post_author );
    ?>

    <div class="item-content">
        <div class="item-header">
	        <?php woocommerce_template_loop_product_link_open(); ?>
	        <?php woocommerce_template_loop_product_title(); ?>
	        <?php woocommerce_template_loop_product_link_close(); ?>
	        <?php
	        echo '<div class="item-vendor"><span>Sold by </span><a href="' . $url . '">' . $store_info['store_name'] . '</a></div>';
	        ?>
	        <?php woocommerce_template_loop_rating(); ?>
        </div>
        <div class="item-bar">
            <div class="item-meta">
			    <?php woocommerce_template_loop_price(); ?>
			    <?php woocommerce_template_loop_rating();?>
            </div>

            <div class="item-button">
			    <?php woocommerce_template_loop_add_to_cart(); ?>

			    <?php if ( function_exists( 'dokan_add_to_wishlist_link' ) ) dokan_add_to_wishlist_link(); ?>
            </div>
        </div>
    </div>

    <?php
}

add_action( 'woocommerce_after_shop_loop_item', 'dokanee_product_loop_price' );


/**
 * Filters WC breadcrumb parameters
 *
 * @param type $args
 * @return type
 */
function dokan_woo_breadcrumb( $args ) {
    return array(
        'delimiter'   => '&nbsp; <i class="fa fa-angle-right"></i> &nbsp;',
        'wrap_before' => '<nav class="breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
        'wrap_after'  => '</nav>',
        'before'      => '<li>',
        'after'       => '</li>',
        'home'        => _x( 'Home', 'breadcrumb', 'dokanee' ),
    );
}

add_filter( 'woocommerce_breadcrumb_defaults', 'dokan_woo_breadcrumb' );

/**
 * Add cart total amount on add_to_cart_fragments
 *
 * @param array $fragment
 * @return array
 */
function dokan_add_to_cart_fragments( $fragment ) {
    $fragment['dokan_cart_amount'] = WC()->cart->get_cart_total();

    return $fragment;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'dokan_add_to_cart_fragments' );

if ( !class_exists( 'Dokan_Category_Walker' ) ) {

    /**
     * Category walker for generating dokan category
     */
    class Dokan_Category_Walker extends Walker {

        var $tree_type = 'category';
        var $db_fields = array( 'parent' => 'parent', 'id' => 'term_id' ); //TODO: decouple this

        function start_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );

            if ( $depth == 0 ) {
                $output .= $indent . '<ul class="children level-' . $depth . '">' . "\n";
            } else {
                $output .= "$indent<ul class='children level-$depth'>\n";
            }
        }

        function end_lvl( &$output, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );

            if ( $depth == 0 ) {
                $output .= "$indent</ul> <!-- .sub-category -->\n";
            } else {
                $output .= "$indent</ul>\n";
            }
        }

        function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
            extract( $args );
            $indent = str_repeat( "\t\r", $depth );

            if ( $depth == 0 ) {
                $caret      = $args['has_children'] ? ' <span class="caret-icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>' : '';
                $class_name = $args['has_children'] ? ' class="has-children parent-cat-wrap"' : ' class="parent-cat-wrap"';
                $output     .= $indent . '<li' . $class_name . '><a href="' . get_term_link( $category ) . '">' . $category->name . $caret . '</a>' . "\n";
            } else {
                $caret      = $args['has_children'] ? ' <span class="caret-icon"><i class="fa fa-angle-right" aria-hidden="true"></i></span>' : '';
                $class_name = $args['has_children'] ? ' class="has-children"' : '';
                $output     .= $indent . '<li' . $class_name . '><a href="' . get_term_link( $category ) . '">' . $category->name . $caret . '</a>';
            }
        }

        function end_el( &$output, $category, $depth = 0, $args = array() ) {
            $indent = str_repeat( "\t", $depth );

            if ( $depth == 1 ) {
                $output .= "$indent</li><!-- .sub-block -->\n";
            } else {
                $output .= "$indent</li>\n";
            }
        }

    }

}

if ( !class_exists( 'Dokan_Category_Widget' ) ) :

    /**
     * new WordPress Widget format
     * Wordpress 2.8 and above
     * @see http://codex.wordpress.org/Widgets_API#Developing_Widgets
     */
    class Dokan_Category_Widget extends WP_Widget {

        /**
         * Constructor
         *
         * @return void
         * */
        public function __construct() {
            $widget_ops = array( 'classname' => 'dokan-category-menu', 'description' => __( 'Dokan product category menu', 'dokanee' ) );
            parent::__construct( 'dokan-category-menu', 'Dokan: Product Category', $widget_ops );
        }

        /**
         * Outputs the HTML for this widget.
         *
         * @param array  An array of standard parameters for widgets in this theme
         * @param array  An array of settings for this widget instance
         * @return void Echoes it's output
         * */
        function widget( $args, $instance ) {
            extract( $args, EXTR_SKIP );

            $title = apply_filters( 'widget_title', $instance['title'] );

            echo $before_widget;

            if ( !empty( $title ) )
                echo $args['before_title'] . $title . $args['after_title'];
            ?>
            <div id="cat-drop-stack">
                <?php
                $args = apply_filters( 'dokan_category_widget', array(
                    'hide_empty' => false,
                    'orderby'    => 'name',
                    'depth'      => 3
                ) );

                $categories = get_terms( 'product_cat', $args );

                $args = array(
                    'taxonomy'      => 'product_cat',
                    'selected_cats' => ''
                );

                $walker = new Dokan_Category_Walker();
                echo "<ul>";
                echo call_user_func_array( array( &$walker, 'walk' ), array( $categories, 0, array() ) );
                echo "</ul>";
                ?>
            </div>
            <script>
                    ( function ( $ ) {

                        $( '#cat-drop-stack li.has-children' ).on( 'click', '> a span.caret-icon', function ( e ) {
                            e.preventDefault();
                            var self = $( this ),
                                liHasChildren = self.closest( 'li.has-children' );

                            if ( !liHasChildren.find( '> ul.children' ).is( ':visible' ) ) {
                                self.find( 'i.fa' ).addClass( 'fa-rotate-90' );
                                if ( liHasChildren.find( '> ul.children' ).hasClass( 'level-0' ) ) {
                                    self.closest( 'a' ).css( { 'borderBottom': 'none' } );
                                }
                            }

                            liHasChildren.find( '> ul.children' ).slideToggle( 'fast', function () {
                                if ( !$( this ).is( ':visible' ) ) {
                                    self.find( 'i.fa' ).removeClass( 'fa-rotate-90' );

                                    if ( liHasChildren.find( '> ul.children' ).hasClass( 'level-0' ) ) {
                                        self.closest( 'a' ).css( { 'borderBottom': '1px solid #eee' } );
                                    }
                                }
                            } );
                        } );
                    } )( jQuery );
            </script>
            <?php
            echo $after_widget;
        }

        /**
         * Deals with the settings when they are saved by the admin. Here is
         * where any validation should be dealt with.
         *
         * @param array  An array of new settings as submitted by the admin
         * @param array  An array of the previous settings
         * @return array The validated and (if necessary) amended settings
         * */
        function update( $new_instance, $old_instance ) {

            // update logic goes here
            $updated_instance = $new_instance;
            return $updated_instance;
        }

        /**
         * Displays the form for this widget on the Widgets page of the WP Admin area.
         *
         * @param array  An array of the current settings for this widget
         * @return void Echoes it's output
         * */
        function form( $instance ) {
            $instance = wp_parse_args( (array) $instance, array(
                'title' => __( 'Product Category', 'dokanee' )
            ) );

            $title = $instance['title'];
            ?>
            <p>
                <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'dokanee' ); ?></label>
                <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <?php
        }

    }

    add_action( 'widgets_init', function() { register_widget( 'Dokan_Category_Widget' ); } );

endif;

/**
 * Product display views [ List/Grid]
 */
function dokanee_products_view_type() {
	?>

    <div class="dokanee-products-view buttons box-shadow">
        <button class="list"><i class="fa fa-bars"></i></button>
        <button class="grid active"><i class="fa fa-th-large"></i></button>
    </div>

	<?php
}

add_action( 'woocommerce_before_shop_loop', 'dokanee_products_view_type' );

/**
 * Rename Additional Information tab
 */
function dokanee_woo_rename_tabs( $tabs ) {

	global $product;

	if( $product->has_attributes() || $product->has_dimensions() || $product->has_weight() ) { // Check if product has attributes, dimensions or weight
		$tabs['additional_information']['title'] = __( 'Additional Info', 'dokanee' );	// Rename the additional information tab
	}

	return $tabs;

}

add_filter( 'woocommerce_product_tabs', 'dokanee_woo_rename_tabs', 98 );

/**
 * Display product vendor name
 */
function dokanee_vendor_name() {
	global $post;

	$store_info = dokan_get_store_info( $post->post_author );
	$url = dokan_get_store_url( $post->post_author );

	echo '<div class="vendor-name"><span>Sold by </span><a href="' . $url . '">' . $store_info['store_name'] . '</a></div>';
}

add_action('woocommerce_single_product_summary', 'dokanee_vendor_name', 7);

/**
 * Display woo breadcrumb
 */
function dokanee_woo_breadcrumb() {

    if ( is_woocommerce() ) {
	    woocommerce_breadcrumb();
    }

}

add_action( 'dokanee_inside_container', 'dokanee_woo_breadcrumb', 5 );

/**
 * Output the related products.
 */
function dokanee_woo_related_products_args( $args ) {
	$args['posts_per_page'] = 3; // 4 related products
	$args['columns'] = 3; // arranged in 2 columns
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'dokanee_woo_related_products_args' );

/**
 * Change the placeholder image
 */
add_filter('woocommerce_placeholder_img_src', 'dokan_woo_placeholder_img_src');

function dokan_woo_placeholder_img_src( $src ) {
	$src = get_template_directory_uri() . '/assets/images/placeholder.png';;

	return $src;
}

/**
 * Redirect product category template
 */
add_action( 'template_redirect', function() {
	global $wp, $wp_query;

	if ( isset( $wp->query_vars['name'] ) && $wp->query_vars['name'] == 'product-category' ) {
		$wp_query->set( 'is_404', false );
		status_header( 200 );

		get_template_part( 'template-parts/page/product-category' );
		exit();
	}
}, 10, 1 );

