<?php

/**
 * WeDevs Slider Engine
 *
 * @package WeDevs Framework
 */
class Dokan_Slider {

    private static $instance = null;

    private $post_type = 'dokan_slider';
    private $slider_meta = array(
        'slider_effect', 'slider_speed', 'slider_pagination',
        'direction_nav', 'slide_direction', 'touch', 'randomize', 'pauseOnHover', 'pausePlay');
    private $slide_default = array(
        'slide_type' => 'text-image',
        'slide_title' => '',
        'slide_content' => '',
        'slide_image' => '',
        'slide_background' => '',
        'slide_video' => '',
        'slide_link' => '',
        'slide_link_open' => '_self'
    );

    private function __construct() {
        $this->actions();
    }

    public static function init() {
        if ( !self::$instance ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    function actions() {
        add_action( 'init', array($this, 'post_types') );
        add_action( 'do_meta_boxes', array($this, 'do_metaboxes' ) );
        add_action( 'admin_head', array($this, 'enqueue_scripts' ) );

        add_action( 'save_post', array($this, 'save_meta'), 10, 2 );
    }

	function do_metaboxes() {
        add_meta_box( 'slider-meta-box', __( 'Slides', 'dokani-theme' ), array( $this, 'meta_boxes' ), $this->post_type );
        add_meta_box( 'slider-options-box', __('Slider Options', 'dokani-theme' ), array( $this, 'meta_boxes_option' ), $this->post_type, 'side' );
	}

    function enqueue_scripts() {
        global $wp;

        if ( isset( $wp->query_vars['post_type'] ) && $wp->query_vars['post_type'] == 'dokan_slider' ) {
            wp_enqueue_script( 'media-upload' );
            wp_enqueue_script( 'thickbox' );

            wp_enqueue_style( 'thickbox' );
            $help_text = sprintf( __( 'Learn More: <a target="_blank" href="%s">How to Create Slider in Dokan</a>', 'dokani-theme' ), 'https://wedevs.com/docs/dokan/tutorials/how-to-create-a-slider-for-homepage-using-dokan/' );
            ?>
            <script>
                jQuery(function($) {
                    $( '.wp-header-end' ).before( '<p><?php echo $help_text ?></p>' );
                });
            </script>
            <?php
        }
    }

    function post_types() {
        register_post_type( $this->post_type, array(
            'label' => __( 'Slider', 'dokani-theme' ),
            'description' => '',
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'capability_type' => 'post',
            'hierarchical' => false,
            'rewrite' => array('slug' => ''),
            'query_var' => false,
            'supports' => array('title'),
            'labels' => array(
                'name' => __( 'Slider', 'dokani-theme' ),
                'singular_name' => __( 'Slider', 'dokani-theme' ),
                'menu_name' => __( 'Dokan Slider', 'dokani-theme' ),
                'add_new' => __( 'Add Slider', 'dokani-theme' ),
                'add_new_item' => __( 'Add New Slider', 'dokani-theme' ),
                'edit' => __( 'Edit', 'dokani-theme' ),
                'edit_item' => __( 'Edit Slider', 'dokani-theme' ),
                'new_item' => __( 'New Slider', 'dokani-theme' ),
                'view' => __( 'View Slider', 'dokani-theme' ),
                'view_item' => __( 'View Slider', 'dokani-theme' ),
                'search_items' => __( 'Search Slider', 'dokani-theme' ),
                'not_found' => __( 'No Slider Found', 'dokani-theme' ),
                'not_found_in_trash' => __( 'No Slider found in trash', 'dokani-theme' ),
                'parent' => __( 'Parent Slider', 'dokani-theme' )
            ),
        ) );
    }

    function meta_boxes_option() {
        global $post;

        $metas = array();
        foreach ($this->slider_meta as $meta) {
            $metas[$meta] = get_post_meta( $post->ID, $meta, true );
        }
        extract( $metas );

        $slider_speed = ($slider_speed == '') ? '7000' : $slider_speed;
        ?>
            <table class="form-table">
                <tr>
                    <th><?php _e( 'Effect', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="slider_effect" id="slider-effect">
                            <option value="fade"<?php selected( $slider_effect, 'fade' ); ?>>fade</option>
                            <option value="slide"<?php selected( $slider_effect, 'slide' ); ?>>slide</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Slide Speed', 'dokani-theme' ); ?></th>
                    <td><input type="text" class="small-text" value="<?php echo esc_attr( $slider_speed ); ?>" name="slider_speed"></td>
                </tr>
                <tr>
                    <th><?php _e( 'Pagination', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="slider_pagination">
                            <option value="true"<?php selected( $slider_pagination, 'true' ); ?>><?php _e( 'Show', 'dokani-theme' ); ?></option>
                            <option value="false"<?php selected( $slider_pagination, 'false' ); ?>><?php _e( 'Hide', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Direction Navigation', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="direction_nav">
                            <option value="true"<?php selected( $direction_nav, 'true' ); ?>><?php _e( 'Show', 'dokani-theme' ); ?></option>
                            <option value="false"<?php selected( $direction_nav, 'false' ); ?>><?php _e( 'Hide', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Sliding Direction', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="slide_direction">
                            <option value="horizontal"<?php selected( $slide_direction, 'horizontal' ); ?>><?php _e( 'horizontal', 'dokani-theme' ); ?></option>
                            <option value="vertical"<?php selected( $slide_direction, 'vertical' ); ?>><?php _e( 'vertical', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Touch Swipe', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="touch">
                            <option value="true"<?php selected( $touch, 'true' ); ?>><?php _e( 'Yes', 'dokani-theme' ); ?></option>
                            <option value="false"<?php selected( $touch, 'false' ); ?>><?php _e( 'No', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Randomize', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="randomize">
                            <option value="false"<?php selected( $randomize, 'false' ); ?>><?php _e( 'No', 'dokani-theme' ); ?></option>
                            <option value="true"<?php selected( $randomize, 'true' ); ?>><?php _e( 'Yes', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Pause on Hover', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="pauseOnHover">
                            <option value="false"<?php selected( $pauseOnHover, 'false' ); ?>><?php _e( 'No', 'dokani-theme' ); ?></option>
                            <option value="true"<?php selected( $pauseOnHover, 'true' ); ?>><?php _e( 'Yes', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><?php _e( 'Pause/Play', 'dokani-theme' ); ?></th>
                    <td>
                        <select name="pausePlay">
                            <option value="false"<?php selected( $pausePlay, 'false' ); ?>><?php _e( 'No', 'dokani-theme' ); ?></option>
                            <option value="true"<?php selected( $pausePlay, 'true' ); ?>><?php _e( 'Yes', 'dokani-theme' ); ?></option>
                        </select>
                    </td>
                </tr>
            </table>
        <?php
    }

    function meta_boxes() {
		global $post;
		?>
        <input type="hidden" name="wedevs-slider" value="<?php echo wp_create_nonce( basename( __FILE__ ) ); ?>" />
		<div id="slider-table">

            <p><a href="#" class="button add-slide"><?php _e( 'Add Slide', 'dokani-theme' ); ?></a></p>

            <ul class="slide-holder">
                <?php
                $slides = get_post_meta( $post->ID, 'slide_detail' );
                if ($slides) {
                    foreach ($slides as $slide) {
                        $this->slide_html( $slide );
                    }
                }
                ?>
            </ul>
		</div>
        <script type="text/tmpl" id="slide-template">
            <?php $this->slide_html(); ?>
        </script>

        <script type="text/javascript">
            (function($){
                var WeDevs_Slider = {
                    init: function () {

                        $('#slider-table').on('change', 'select.slide_type', this.showHideField);
                        $('#slider-table').on('click', 'a.add-slide', this.addSlide);
                        $('#slider-table').on('click', '.slide-hndle', this.toggleSlide);
                        $('#slider-table').on('click', '.submitdelete', this.removeSlide);
                        $('#slider-table').on('click', 'a.image_upload', this.imageUpload);
                        $('#slider-table').on('click', 'a.remove-image', this.removeImage);
                        $('ul.slide-holder').sortable();
                    },

                    showHideField: function() {
                        var self = $(this),
                            table = self.parents('.form-table'),
                            val = $(this).val(),
                            title = table.find('tr.slide-title'),
                            slide_content = table.find('tr.slide-content'),
                            slide_image = table.find('tr.slide-image'),
                            video_link = table.find('tr.video-link'),
                            slide_link = table.find('tr.slide-link'),
                            slide_open_link = table.find('tr.slide-open-link')

                        //show all
                        title.show();
                        slide_content.show();
                        slide_image.show();
                        video_link.show();
                        slide_link.show();
                        slide_open_link.show();

                        if( val == 'text-image' || val == 'image-text' ) {
                            video_link.hide();
                            slide_link.show();
                            slide_open_link.show();
                        } else if( val == 'text-video' || val == 'video-text' ) {
                            slide_image.hide();
                        } else if( val == 'image' ) {
                            title.show();
                            slide_content.show();
                            video_link.show();
                            slide_image.hide();
                            video_link.hide();
                            slide_link.show();
                            slide_open_link.show();
                        } else if( val == 'full-image' ) {
                            slide_image.show();
                            title.hide();
                            slide_content.hide();
                            video_link.hide();
                            video_link.hide();
                            slide_link.hide();
                            slide_open_link.hide();

                        } else if( val == 'video' ) {
                            title.hide();
                            slide_content.hide();
                            slide_image.hide();
                        } else if( val == 'text' ) {
                            slide_image.hide();
                            video_link.hide();
                        }
                    },

                    addSlide: function (e) {
                        e.preventDefault();

                        var tmpl = $('#slide-template').html();

                        $('#slider-table ul.slide-holder').append(tmpl);
                    },

                    removeSlide: function (e) {
                        e.preventDefault();

                        $(this).parents('.slide-table').parent().remove();
                    },

                    toggleSlide: function (e) {
                        e.preventDefault();
                        $(this).next('.form-table').toggle();
                    },

                    imageUpload: function (e) {
                        e.preventDefault();

                        var self = $(this),
                            inputField = self.siblings('input.image_url');

                        tb_show('', 'media-upload.php?post_id=0&amp;type=image&amp;TB_iframe=true');

                        window.send_to_editor = function (html) {
                            var url = $(html).attr('src');
                            //if we find an image, get the src
                            if($(html).find('img').length > 0) {
                                url = $(html).find('img').attr('src');
                            }

                            inputField.val(url);

                            var image = '<img src="' + url + '" alt="image" />';
                                image += '<a href="#" class="remove-image"><span>Remove</span></a>';

                            self.siblings('.image_placeholder').empty().append(image);
                            tb_remove();
                        }
                    },

                    removeImage: function (e) {
                        e.preventDefault();
                        var self = $(this);

                        self.parent('.image_placeholder').siblings('input.image_url').val('');
                        self.parent('.image_placeholder').empty();
                    }

                };

                $(function() {
                    WeDevs_Slider.init();
                });

            })(jQuery);
        </script>
        <style type="text/css">
            .slide-table {
                background: #FAFAFA;
                margin-bottom: 5px;
                border-radius: 5px;
                border: 1px solid #dfdfdf;
            }

            .slide-table .form-table td {
                vertical-align: top;
            }

            .slide-table .submitbox {
                padding: 5px;
                margin: 5px;
            }

            .image_placeholder {
                width: 300px;
            }

            .image_placeholder img{
                background: #ffffff;
                border: 1px solid #ccc;
                padding: 5px;
                border-radius: 5px;
                width: 100%;
                margin-top: 5px;
            }

            .image_placeholder a.remove-image span{
                background: url('<?php echo ''; ?>/assets/images/ico-delete.png') no-repeat;
                width: 16px;
                height: 16px;
                display: inline-block;
                text-indent: -9999px;
            }

        </style>
		<?php
	}

    function slide_html( $args = array() ) {

        $args = wp_parse_args( $args, $this->slide_default );
        extract( $args );

        $slide_title_css = in_array( $slide_type, array('image', 'video') ) ? 'none' : 'table-row';
        $slide_content_css = in_array( $slide_type, array('image', 'video') ) ? 'none' : 'table-row';
        $slide_image_css = in_array( $slide_type, array('video', 'image', 'text', 'text-video', 'video-text') ) ? 'none' : 'table-row';
        $slide_video_css = in_array( $slide_type, array('video', 'text-video', 'video-text') ) ? 'table-row' : 'none';
        $slide_full_image_css = in_array( $slide_type, array('video', 'text', 'text-video') ) ? 'none' : 'table-row';
        ?>
        <li>
            <div class="slide-table">
                <h3 class="slide-hndle"><span><?php _e( 'Slide', 'dokani-theme' ); ?></span></h3>
                <table class="form-table">
                    <tr class="slider_type_select">
                        <td><?php _e( 'Slide Type', 'dokani-theme' ); ?></td>
                        <td>
                            <select class="slide_type" name="slide_type[]">
                                <option value="text-image"<?php selected( $slide_type, 'text-image' ); ?>><?php _e( 'Text with Image', 'dokani-theme' ); ?></option>
                                <option value="image"<?php selected( $slide_type, 'image' ); ?>><?php _e( 'Only Text', 'dokani-theme' ); ?></option>
                                <option value="full-image"<?php selected( $slide_type, 'full-image' ); ?>><?php _e( 'Full Image', 'dokani-theme' ); ?></option>
                               
                            </select>
                        </td>
                    </tr>
                    <tr class="slide-title" style="<?php echo esc_attr( $slide_full_image_css ); ?>">
                        <td><?php _e( 'Title Text', 'dokani-theme' ); ?></td>
                        <td>
                            <input type="text" class="regular-text" name="slide_title[]" value="<?php echo esc_attr( $slide_title ); ?>">
                        </td>
                    </tr>
                    <tr class="slide-content" style="<?php echo esc_attr( $slide_full_image_css ); ?>">
                        <td><?php _e( 'Detail Text', 'dokani-theme' ); ?></td>
                        <td>
                            <textarea name="slide_content[]" rows="5" cols="55"><?php echo esc_textarea( $slide_content ); ?></textarea>
                        </td>
                    </tr>
                    <tr class="slide-image" style="display: <?php echo $slide_image_css; ?>">
                        <td><?php _e( 'Slide Image', 'dokani-theme' ); ?></td>
                        <td>
                            <input type="text" class="regular-text image_url" name="slide_image[]" value="<?php echo esc_url( $slide_image ) ?>" />
                            <a href="#" class="image_upload button">Upload Image</a>
                            <div class="image_placeholder">
                                <?php if ($slide_image != '') { ?>
                                    <img src="<?php echo esc_url( $slide_image ); ?>" alt="image" /><a href="#" class="remove-image"><span>Remove</span></a>
                                <?php } ?>
                            </div>
                        </td>
                    </tr>
                    <tr class="slide-link" style="display: <?php echo esc_attr( $slide_full_image_css ); ?>">
                        <td><?php _e( 'Slide Link Url', 'dokani-theme' ); ?></td>
                        <td>
                            <input type="text" class="regular-text" value="<?php echo esc_url( $slide_link ); ?>" placeholder="http://example.com" name="slide_link[]" />
                        </td>
                    </tr>
                    <tr class="slide-open-link" style="display: <?php echo esc_attr( $slide_full_image_css ); ?>">
                        <td><?php _e( 'Open Link', 'dokani-theme' ); ?></td>
                        <td>
                            <select name="slide_link_open[]">
                                <option value="_self"<?php selected( $slide_link_open, '_self'); ?>><?php _e( 'Open link in same window', 'dokani-theme' ); ?></option>
                                <option value="_blank"<?php selected( $slide_link_open, '_blank'); ?>><?php _e( 'Open link in new window', 'dokani-theme' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="submitbox">
                            <a href="#" class="submitdelete"><?php _e( 'Remove', 'dokani-theme' ); ?></a>
                        </td>
                    </tr>
                </table>
            </div>
        </li>
        <?php
    }

    /**
     * Save slide meta fields
     *
     * @param int $post_id
     * @param object $post
     */
    function save_meta( $post_id, $post ) {
        /* Verify the nonce before proceeding. */
        if ( !isset( $_POST['wedevs-slider'] ) || !wp_verify_nonce( $_POST['wedevs-slider'], basename( __FILE__ ) ) )
            return $post_id;

        /* Get the post type object. */
        $post_type = get_post_type_object( $post->post_type );

        /* Check if the current user has permission to edit the post. */
        if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
            return $post_id;

        $posted = $_POST;
        // var_dump($_POST);
        // die();

        foreach ($this->slider_meta as $meta) {
            update_post_meta( $post_id, $meta, trim( $posted[$meta] ) );
        }

        //if any slides not added, bail out
        if( !isset( $_POST['slide_type'] ) ) {
            return $post_id;
        }

        //delete all previous meta field values
        delete_post_meta( $post_id, 'slide_detail' );

        foreach ($posted['slide_type'] as $key => $value) {
            $data = array(
                'slide_type' => $posted['slide_type'][$key],
                'slide_title' => trim( $posted['slide_title'][$key] ),
                'slide_content' => trim($posted['slide_content'][$key]),
                'slide_image' => trim( $posted['slide_image'][$key] ),
                'slide_link' => trim( $posted['slide_link'][$key] ),
                'slide_link_open' => $posted['slide_link_open'][$key],
            );

            add_post_meta( $post_id, 'slide_detail', $data);
        }
    }

    public function get_slider( $slider_id ) {
        $metas = array();
        foreach ($this->slider_meta as $meta) {
            $metas[$meta] = get_post_meta( $slider_id, $meta, true );
        }
        extract( $metas );

        $slides = get_post_meta( $slider_id, 'slide_detail' );

        ob_start();

        if ( $slides ) {

            printf( '<div class="flexslider" id="flexslider-%d">', $slider_id );
            printf( '<ul class="slides">' );

            $slides     = array_reverse( $slides );

            foreach ($slides as $slide) {
                $slide_html = '<li>';
                $slide_html .= '<div class="slide-container">';
                

                extract( $slide );
                $class_of_slide = '<div class="single-slide '. $slide_type .'">';
                $link_content = sprintf('<a href="%s" class="button" target="%s">%s</a>', esc_url( $slide_link ), $slide_link_open, __( 'View Details', 'dokani-theme' ) );
                
                $class_of_slide_type = '<div class="slide-bg '. $slide_type .'" style="background-image:url('.$slide_image.');">';
                $text_content = '<div class="slide-textarea">';
                $text_content .= '<h2>' . $slide_title . '</h2>';
                $text_content .= '<div class="slide-detail"><p>' . do_shortcode( $slide_content ) . '</p></div>';
                $text_content .= ( !empty( $slide_link ) ) ? "<div class='more'>$link_content</div>" : '';
                $text_content .= '</div>';

                $grid_container = sprintf('<div class="grid-container">%s</div>', $text_content);

                $image = sprintf( '<img src="%s" alt="%s" />', $slide_image, esc_attr( $slide_title ) );

                $image_content = sprintf( '<div class="slide-image">%s</div>', $image );

                switch ( $slide_type ) {
                    case 'text-image':
                        $slide_html .= $class_of_slide_type . "\n";
                        $slide_html .= $text_content . "\n";
                        
                        break;

                    case 'image-text':
                        $slide_html .= $class_of_slide_type . "\n";
                        $slide_html .= $text_content . "\n";
                        break;

                    case 'image':
                        $slide_html .= $class_of_slide_type . "\n";
                        $slide_html .= $text_content . "\n";
                        break;
                    
                    case 'full-image':
                        $slide_html .= $class_of_slide . "\n";
                        $slide_html .= $image_content . "\n";

                    case 'text':
                        $slide_html .= $text_content . "\n";
                        break;
                }

                $slide_html .= "</div>";
                $slide_html .= "</li>";

                $slide_html = apply_filters( 'dokan_slider_item_html', $slide_html, $slide );
                echo $slide_html;
            }

            echo "</ul>";
            echo '</div>';
        }
        ?>
        <script type="text/javascript">
        jQuery(function($) {
            $('#flexslider-<?php echo $slider_id; ?>').flexslider({
                animation: '<?php echo $slider_effect; ?>',
                slideshowSpeed: <?php echo absint( $slider_speed ); ?>,
                mousewheel: false,
                directionNav: <?php echo $direction_nav == 'true' ? 'true' : 'false'; ?>,
                controlNav: <?php echo $slider_pagination == 'true' ? 'true' : 'false'; ?>,
                direction: '<?php echo $slide_direction; ?>',
                touch: <?php echo $touch == 'true' ? 'true' : 'false'; ?>,
                randomize: <?php echo $randomize == 'true' ? 'true' : 'false'; ?>,
                pauseOnHover: <?php echo $pauseOnHover == 'true' ? 'true' : 'false'; ?>,
                pausePlay: <?php echo $pausePlay == 'true' ? 'true' : 'false'; ?>,
                before: function(slider_id){
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea h2').each(function(){
                        $(this).removeClass("animated fadeInDown").css("opacity","0");
                    });
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea p').each(function(){
                        $(this).removeClass("animated fadeInUp").css("opacity","0");
                    });
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea .more').each(function(){
                        $(this).removeClass("animated fadeInUp").css("opacity","0");
                    });
                },
                after: function(slider_id){
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea h2').addClass("animated fadeInDown").css("opacity","1");
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea p').addClass("animated fadeInUp").css("opacity","1");
                    $('#flexslider-<?php echo $slider_id; ?>').find(".flex-active-slide").find('.slide-textarea .more').addClass("animated fadeInUp").css("opacity","1");
                },
            });
        });
        // 'slide_direction', 'touch', 'randomize', 'pauseOnHover', 'pausePlay');
        </script>
        <?php

        $slider_content = ob_get_clean();
        $slider_content = apply_filters( 'dokan_slider_html', $slider_content, $slides, $metas );

        echo $slider_content;
    }
}
