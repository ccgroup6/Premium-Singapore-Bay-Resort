<?php
if (!class_exists('TF_Post_Format')) {
    Class TF_Post_Format {
        private static $_instance = null;
        public static function instance() {
            if ( is_null( self::$_instance ) ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }
        
        public function __construct() {   
            add_action( 'after_setup_theme', [$this,'tf_theme_suport'] );
            add_action( 'admin_init', [$this,'themesflat_page_options_init'] );                
            add_action( 'admin_enqueue_scripts', [ $this, 'tf_metabox_scripts' ] );
            add_action( 'wp_enqueue_scripts', [ $this, 'tf_post_format_scripts' ] );
            add_action( 'elementor/frontend/after_register_scripts', [ $this, 'tf_post_format_scripts' ], 100 );
            $this->inc();
        }

        public function tf_metabox_scripts() {
            wp_register_style( 'tf-meta-boxes', plugins_url( '/post-format/assets/css/meta-boxes.css', __FILE__ ) );
            wp_enqueue_style( 'tf-meta-boxes' );
            wp_register_script( 'tf-meta-boxes', plugins_url( '/post-format/assets/js/meta-boxes.js', __FILE__ ), [ 'jquery' ], false, true );
            wp_enqueue_script( 'tf-meta-boxes' );
        }

        public function tf_post_format_scripts() {
            wp_register_style( 'iziModal', plugins_url( '/post-format/assets/css/iziModal.css', __FILE__ ) );
            wp_enqueue_style( 'iziModal' );
            wp_register_script( 'iziModal', plugins_url( '/post-format/assets/js/iziModal.js', __FILE__ ), [ 'jquery' ], false, true );
            wp_enqueue_script( 'iziModal' );
            wp_register_script( 'jquery-mb-ytplayer', plugins_url( '/post-format/assets/js/jquery.mb.YTPlayer.js', __FILE__ ), [ 'jquery' ], false, true );
            wp_enqueue_script( 'jquery-mb-ytplayer' );
        }

        public function inc() {
            require_once plugin_dir_path( __FILE__ ).'post-format/options.php';
        }

        public function tf_theme_suport() {
            add_theme_support( 'post-formats', array(
                'aside', 'gallery', 'video', 'quote', 'audio'
            ));
        }

        public function themesflat_post_options_fields() {
            $options['blog_heading'] = array(
                'type' => 'heading',
                'section' => 'blog',
                'title' => esc_html__( 'Option just view if post format is Gallery, Video, Audio or Quote.', 'themesflat-addons-for-elementor' )
            );
            $options['gallery_images_heading'] = array(
                'type' => 'heading',
                'section' => 'blog',
                'title' => esc_html__( 'Post Format: Gallery', 'themesflat-addons-for-elementor' ),
                'description' => esc_html__( 'You can select multiple images.', 'themesflat-addons-for-elementor' )
            );
            $options['gallery_images'] = array(
                'type'    => 'image-control',
                'section' => 'blog',
                'title' => esc_html__( 'Images', 'themesflat-addons-for-elementor' ),
                'default' => ''
            );
            $options['video_url_heading'] = array(
                'type' => 'heading',
                'section' => 'blog',
                'title' => esc_html__( 'Post Format: Video', 'themesflat-addons-for-elementor' ),
                'description' => esc_html__( 'Enter iframe ( Embeded video from youtube, vimeo ...)', 'themesflat-addons-for-elementor' )
            );
            $options['video_url'] = array(
                'type'    => 'textarea',
                'section' => 'blog',
                'title' => esc_html__( 'video link', 'themesflat-addons-for-elementor' ),
                'default' => 'https://www.youtube.com/embed/nrJtHemSPW4',
            );
            $options['audio_url_heading'] = array(
                'type' => 'heading',
                'section' => 'blog',
                'title' => esc_html__( 'Post Format: Audio', 'themesflat-addons-for-elementor' ),
                'description' => esc_html__( 'Enter iframe', 'themesflat-addons-for-elementor' )
            );
            $options['audio_url'] = array(
                'type'    => 'textarea',
                'section' => 'blog',
                'title' => esc_html__( 'iframe audio', 'themesflat-addons-for-elementor' ),
                'default' => ''
            );
            $options['quote_text_heading'] = array(
                'type' => 'heading',
                'section' => 'blog',
                'title' => esc_html__( 'Post Format: Quote', 'themesflat-addons-for-elementor' ),
                'description' => esc_html__( 'Enter Text', 'themesflat-addons-for-elementor' )
            );
            $options['quote_text'] = array(
                'type'    => 'textarea',
                'section' => 'blog',
                'title' => esc_html__( 'Quote Text', 'themesflat-addons-for-elementor' ),
                'default' => ''
            );
            return $options;
        }
        
        public function themesflat_page_options_init() {  
            new tf_meta_boxes(array(
                // event
                'id'    => 'blog-options',
                'label' => esc_html__( 'Post settings', 'themesflat-addons-for-elementor' ),
                'post_types'    => array('post'),
                'context'     => 'normal',
                'priority'    => 'default',
                'sections' => array(
                    'blog'   => array( 'title' => esc_html__( 'Blog', 'themesflat-addons-for-elementor' ) ),
                    ),
                'options' => $this->themesflat_post_options_fields()
            ));
        }

        public function themesflat_render_attrs($atts,$echo = true) {
            $attr = '';
            if (is_array($atts)) {
                foreach ($atts as $key => $value) {
                    if ( $value !='') {
                        $attr .= $key . '="' . esc_attr( $value ) . '" ';
                    }
                }
            }
            if ($echo == true) {
                echo esc_attr($attr);
            }
            return $attr;
        }

        public static function themesflat_decode($value) {
            if (!is_array($value)) {
                $decoded_value = json_decode(str_replace('&quot;', '"',  $value) , true );
            }
            else {
                $decoded_value = $value;
            }
            return $decoded_value;
        }

        public static function themesflat_meta( $key,$ID = '') {
            global $post;
            if ( $ID =='' && !is_null($post)) :
                return get_post_meta( $post->ID,$key, true );
            else:
                return get_post_meta($ID,$key,true);
            endif;
        }

        public static function themesflat_esc_attr($attr) {
            echo esc_attr($attr);
        }

        public static function themesflat_esc_html($attr) {
            echo esc_html($attr);
        }

        public static  function themesflat_render_box_control($name,$control=array(),$id='') {            
            $default = array(
                'margin-top' => '',
                'margin-bottom' => '',
                'margin-left' => '',
                'margin-right' => '',
                'padding-top' => '',
                'padding-bottom' => '',
                'padding-left' => '',
                'padding-right' => '',
                'border-top-width' => '',
                'border-bottom-width' => '',
                'border-left-width' => '',
                'border-right-width' => ''
                );
            $controls = TF_Post_Format::themesflat_decode($control);
            if (!is_array($controls)) {
                $controls = array();
            }
            $controls = array_merge($default,$controls);
            ?>
            <div class="themesflat_box_control">
                <div class="themesflat_box_position">
                    <div class="themesflat_box_margin">
                        <label class="themesflat_box_label"><?php echo esc_html('Margin');?></label>
                        <input placeholder="-" data-position='margin-top' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['margin-top']));?>" class="top" type="text"/>
                        <input placeholder="-" data-position='margin-bottom' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['margin-bottom']));?>" class="bottom" type="text"/>
                        <input placeholder="-" data-position='margin-left' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['margin-left']));?>" class="left" type="text"/>
                        <input placeholder="-" data-position='margin-right' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['margin-right']));?>" class="right" type="text"/>
                    </div>

                    <div class="themesflat_box_padding">
                        <label class="themesflat_box_label"><?php echo esc_html('Padding');?></label>
                        <input placeholder="-" data-position='padding-top' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['padding-top']));?>" class="top" type="text"/>
                        <input placeholder="-" data-position='padding-bottom' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['padding-bottom']));?>" class="bottom" type="text"/>
                        <input placeholder="-" data-position='padding-left' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['padding-left']));?>" class="left" type="text"/>
                        <input placeholder="-" data-position='padding-right' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['padding-right']));?>" class="right" type="text"/>
                    </div>

                    <div class="themesflat_box_border">
                        <label class="themesflat_box_label"><?php echo esc_html('Border');?></label>
                        <input placeholder="-" data-position='border-top-width' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['border-top-width']));?>" class="top" type="text"/>
                        <input placeholder="-" data-position='border-bottom-width' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['border-bottom-width']));?>" class="bottom" type="text"/>
                        <input placeholder="-" data-position='border-left-width' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['border-left-width']));?>" class="left" type="text"/>
                        <input placeholder="-" data-position='border-right-width' value ="<?php TF_Post_Format::themesflat_esc_attr(($controls['border-right-width']));?>" class="right" type="text"/>
                    </div>
                    <div class="themesflat_control_logo"></div>
                </div>
            </div>
            <input name="<?php echo esc_attr($name);?>" data-customize-setting-link="<?php echo  esc_attr($id);?>" value="<?php echo esc_attr(json_encode($controls));?>" type="hidden"/>
            <?php 
        }
        
    }
}

new TF_Post_Format();