<?php
/**
 * Register options for the post
 * 
 * @return  void
 */
if (!class_exists('tf_meta_boxes')) {
    Class tf_meta_boxes {
        public $meta_boxes;
        public $options;
        public $controls;
        public $label;
        public $id;
        public $input_attrs;
        public $context;
        public $priority;
        public $sections;
        public $post_types;
        public $type;
        public function __construct($args) {     
           foreach ( array_keys( get_object_vars( $this ) ) as $key ) {
            if ( isset( $args[ $key ] ) )
                $this->$key = $args[ $key ];
            }
            foreach ($this->options as $key => $_options) {
                $_options['id'] = $key;
                $this->controls[$_options['section']][] = $_options;
            }
            
            $this->hook();
            $this->setup();
        }
        public function hook() {
            wp_enqueue_script( 'wp-plupload' );
            wp_enqueue_style( 'wp-color-picker' );
            add_action( 'save_post', array($this,'save')) ;
        }
        public function setup() {
            $callback = array( $this, 'render' );
            $context = ( isset($this->context) ? $this->context : 'normal');
            $priority = ( isset($this->priority) ? $this->priority : 'default');
            add_meta_box (
                $this->id,
                $this->label,
                $callback,
                $this->post_types,
                $context,
                $priority
            ); 
        }
        function render_content($key,$controls,$post) { ?>
            <div id="themesflat-options-section-<?php TF_Post_Format::themesflat_esc_attr( $key ) ?>">
                <ul class="themesflat-options-section-controls">
                    <?php
                        foreach ( $controls as  $control ):
                            $this->control_render($control);
                        endforeach;
                    ?>
                </ul>
            </div>
            <?php } 
        function themesflat_render_control_id($value) {
            return '#themesflat-options-control-'.$value;
        }
        public function control_render( $control ) {
            global $post;
            global $wp_registered_sidebars;
            if (get_post_meta( $post->ID, $control['id'], true ) == '') {
                $value = (isset($control['default'])?$control['default']:'');
            }
            else {
                $value = get_post_meta( $post->ID, $control['id'], true );
            }
            $class = '';
            if ( (int)$value == 1 ) {
                $class = 'active';
            }
            $name = "_themesflat_options[{$control['id']}]";
            $title = (isset($control['title']) ? $control['title'] : '');
            $choices = (isset($control['choices']) ? $control['choices'] : '');
            $children = (isset($control['children']) ? $control['children'] : array());
            $children = array_map(array($this,'themesflat_render_control_id'), $children);
            $children = implode( ",",$children);
            $description = (isset($control['description']) ? '<p>'.$control['description'].'</p>' : '');
            printf('<li class = "themesflat-options-control themesflat-options-control-%2$s %3$s" id="themesflat-options-control-%1$s">',$control['id'], $control['type'],$class);
            switch ($control['type']) {
                case 'switcher':
                    printf('<label class="options-%6$s-%7$s"><span class="themesflat-options-control-title">%4$s</span> %5$s <input value="0" name="%3$s" type="hidden"><input children = "%8$s" type="checkbox" value="1" %2$s name="%1$s">
                    <span class="themesflat-options-control-indicator">
                    <span></span>
                    </span></label>',$name, checked(TRUE,$value,FALSE),$name,$title,$description,$control['type'],$control['id'],$children);
                    break;
                case 'single-image-control':?>
                    <?php 
                    $showupload = '_show';
                    $showremove = '_hide';
                    if ( $value != '' ) {
                        $showupload = '_hide';
                        $showremove = '_show';
                    }
                    ?>
                    <div class="themesflat-options-control-media-picker background-image" data-customizer-link="<?php TF_Post_Format::themesflat_esc_attr($control['id']);?>">
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                        <div class="themesflat-options-control-inputs">
                            <div class="upload-dropzone">  
                                <input type="hidden" data-property="id"/>
                                <input type="hidden" data-property="thumbnail"/>
                                <ul class="upload-preview">
                                <?php
                                    printf('
                                        <li>
                                            <img src="%s"/>
                                            <a href="#" id="%s" class="themesflat-remove-media" title="Remove">
                                                <span class="dashicons dashicons-no-alt"></span>
                                            </a>
                                        </li>
                                        ',$value,$value);
                                ?>
                                </ul>
                                <span class="upload-message <?php echo esc_attr($showupload);?> ">
                                    <a href="#" class="browse-media"><?php esc_html_e( 'Add file', 'suri-elementor' ) ?></a>
                                    <a href="#" class="upload"></a>
                                </span>
                            </div>
                            <a href="#" class="button remove <?php echo esc_attr($showremove);?>"><?php esc_html_e( 'Remove', 'suri-elementor' ) ?></a>
                        </div>
                        <input class="image-value" type="hidden" name="<?php TF_Post_Format::themesflat_esc_attr($name);?>" value="<?php  TF_Post_Format::themesflat_esc_attr( $value ) ?>" />
                    </div>
                <?php 
                break;
                case 'power':
                    printf('<h6 class="themesflat-options-control-title %9$s">%4$s</h6>%5$s
                        <label class="themesflat-power options-%6$s-%7$s">
                          <input value="0" name="%3$s" type="hidden"><input children = "%8$s" type="checkbox" value="1" %2$s name="%1$s">
                          <div class="slider"></div>
                        </label>',$name, checked(TRUE,$value,FALSE),$name,$title,$description,$control['type'],$control['id'],$children,$class);
                break;
                case 'heading':
                    printf('<label class="options-%3$s-%4$s"><h3>%1$s</h3></label>%2$s',$title,$description,$control['type'],$control['id']);
                    break;
                case 'editor':
                printf('<label class="options-%3$s-%4$s"><span class="themesflat-options-control-title">%1$s</span></label> %2$s<div class="themesflat-options-control-inputs">',$title,$description,$control['type'],$control['id']);
                wp_editor( $value,$control['id'], array( 'textarea_name' => $name, 'drag_drop_upload' => true ) );
                echo '</div>';
                break;
                case 'radio-images': ?>
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                    <div class="themesflat-options-control-field">
                        <?php foreach ( $choices as $_value => $params ): ?>
                            <label> 
                                <input type="radio" value="<?php TF_Post_Format::themesflat_esc_attr( $_value ) ?>" name="<?php TF_Post_Format::themesflat_esc_attr($name);?>" <?php checked( $value, $_value ) ?> />
                                <span data-tooltip="<?php TF_Post_Format::themesflat_esc_attr( $params['tooltip'] ) ?>">
                                    <img src="<?php TF_Post_Format::themesflat_esc_attr( $params['src'] ) ?>" alt="<?php TF_Post_Format::themesflat_esc_attr( $_value ) ?>" />
                                </span>
                            </label>
                        <?php endforeach ;?>
                    </div>
                    <?php break;
                 case 'select': ?>
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                    <div class="themesflat-options-control-field">
                        <select name="<?php TF_Post_Format::themesflat_esc_attr( $name ) ?>">
                        <?php foreach ( $choices as $_value => $params ): 
                                    printf('<option value="%1$s" %2$s>%3$s</option>', $_value,  selected( $value, $_value ), $params); ?>
                        <?php endforeach ;?>
                        </select>
                    </div>
                    <?php break;
                case 'dropdown-sidebar': ?>
                     <label>
                        <span class="customize-category-select-control"><?php TF_Post_Format::themesflat_esc_html($title); ?></span>
                        <select name="<?php TF_Post_Format::themesflat_esc_attr( $name ) ?>">
                            <?php
                                foreach ( $wp_registered_sidebars as $sidebar ) {
                                    $selected = ( strcmp($value,$sidebar['id'])==0 ? 1 : 0 );
                                    printf('<option value="%1$s" %2$s>%3$s</option>', $sidebar['id'], selected($selected), $sidebar['name']);
                                }
                            ?>
                        </select>
                    </label>
                <?php break;
                 case 'textarea': ?>
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                    <div class="themesflat-options-control-inputs">
                        <textarea name="<?php TF_Post_Format::themesflat_esc_attr($name);?>" id="<?php TF_Post_Format::themesflat_esc_attr( $control['id'] ) ?>"><?php TF_Post_Format::themesflat_esc_html( $value ) ?></textarea>
                    </div>
                <?php break;
                case 'datetime': 
                 printf('<span class="themesflat-options-control-title">%3$s</span></label> %4$s<div class="themesflat-options-control-inputs">
                        <input name="_themesflat_options[%1$s]" id="flat-date-time" type="text" value="%2$s"/></div>',$control['id'],$value,$title,$description);
                 break;
                case 'box-controls' :
                     $id = $control['id']; ?>
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                    <?php TF_Post_Format::themesflat_render_box_control($name,$value,$id);
                break;
                case 'color-picker': ?>
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                    <div class="background-color">
                        <div class="themesflat-options-control-color-picker">
                            <div class="themesflat-options-control-inputs">
                                <input type="text" class='flat-color-picker wp-color-picker' id="<?php TF_Post_Format::themesflat_esc_attr( $name ) ?>-color" data-alpha="true" name="<?php TF_Post_Format::themesflat_esc_attr($name);?>" data-default-color value="<?php TF_Post_Format::themesflat_esc_attr( $value ) ?>" />
                            </div>
                        </div>
                    </div>
                <?php break;
                case 'image-control':?>
                    <?php 
                    $showupload = '_show';
                    $showremove = '_hide';
                    if ( $value != '' ) {
                        $showupload = '_hide';
                        $showremove = '_show';
                    }
                    $decoded_value = TF_Post_Format::themesflat_decode($value);
                    ?>
                    <div class="themesflat-options-control-media-picker background-image" data-customizer-link="<?php TF_Post_Format::themesflat_esc_attr($control['id']);?>">
                    <span class="themesflat-options-control-title"><?php TF_Post_Format::themesflat_esc_html($title);?></span>
                        <div class="themesflat-options-control-inputs">
                            <div class="upload-dropzone">
                                
                                <input type="hidden" data-property="id"/>
                                <input type="hidden" data-property="thumbnail"/>
                                <ul class="upload-preview">
                                <?php
                                    if (is_array($decoded_value)) { 
                                        foreach ($decoded_value as $val) :
                                            printf('
                                                <li>
                                                    %s
                                                    <a href="#" id="%d" class="themesflat-remove-media" title="Remove">
                                                        <span class="dashicons dashicons-no-alt"></span>
                                                    </a>
                                                </li>
                                                ',wp_get_attachment_image($val),$val);
                                        endforeach;
                                    }
                                ?>
                                </ul>
                                <span class="upload-message <?php echo esc_attr($showupload);?> ">
                                    <a href="#" class="browse-media"><?php esc_html_e( 'Add files', 'suri-elementor' ) ?></a>
                                    <a href="#" class="upload"></a>
                                </span>
                            </div>
                            <a href="#" class="button remove <?php echo esc_attr($showremove);?>"><?php esc_html_e( 'Remove', 'suri-elementor' ) ?></a>
                        </div>
                        <input class="image-value" type="hidden" name="<?php TF_Post_Format::themesflat_esc_attr($name);?>" value="<?php  TF_Post_Format::themesflat_esc_attr( $value ) ?>" />
                    </div>
                <?php 
                break;
                case 'number':
                    printf('<span class="themesflat-options-control-title">%3$s</span></label> %4$s<div class="themesflat-options-control-inputs">
                        <input name="_themesflat_options[%1$s]" %5$s type="number" value="%2$s"/></div>',$control['id'],esc_html($value),$title,$description,themesflat_render_attrs($control['input_attrs'],false));
                 break;
                default:
                    printf('<span class="themesflat-options-control-title">%3$s</span></label> %4$s<div class="themesflat-options-control-inputs">
                        <input name="_themesflat_options[%1$s]" type="text" value="%2$s"/></div>',$control['id'],esc_html($value),$title,$description);
                    break;
            }
            echo '</li>';
        }
        public function render($post) {
            $section  = $this->sections;
            $controls = $this->controls;
            $first = true;
            ?>
            <div class="themesflat-options-container themesflat-options-container-tabs">
                        <?php foreach( $this->sections as $id => $section ): ?>
                            <?php if ($first == true) {
                                $class ='ui-tabs-active';
                                $first = false;
                                }
                                else {
                                    $class = '';
                                }
                                $themesflat_setcion[$id] = $section['title'];
                        endforeach ?>
                <div class="themesflat-options-container-content flat-accordion">
                       
                    <?php
                        foreach( $controls as $key => $_controls ){?>
                            <div class="flat-toggle">
                                <h6 class="toggle-title"><?php echo esc_attr($themesflat_setcion[$key]);?></h6>
                                <div class="toggle-content">
                                      <?php    $this->render_content($key,$_controls,$post);?>
                                </div>
                            </div>
                      
                    <?php    }
                    ?>
                </div>
            </div>
            <?php 
            wp_nonce_field( 'custom_nonce_action', 'custom_nonce' );
            
        }
        function save( $post_id ) {
     
            /*
             * We need to verify this came from the our screen and with proper authorization,
             * because save_post can be triggered at other times.
             */
            $nonce_name   = isset( $_POST['custom_nonce'] ) ? $_POST['custom_nonce'] : '';
            $nonce_action = 'custom_nonce_action';
     
            // Check if nonce is set.
            if ( ! isset( $nonce_name ) ) {
                return;
            }
     
            // Check if nonce is valid.
            if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) ) {
                return;
            }
            if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
                return $post_id;
            }
     
            // Check the user's permissions.
            if ( 'page' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_page', $post_id ) ) {
                    return $post_id;
                }
            } else {
                if ( ! current_user_can( 'edit_post', $post_id ) ) {
                    return $post_id;
                }
            }
     
            /* OK, it's safe for us to save the data now. */
            if ( isset( $_REQUEST ) && isset( $_REQUEST['_themesflat_options'] ) ) {
                $datas = stripslashes_deep( $_REQUEST['_themesflat_options'] );
                foreach ($datas as $key => $value ) {
                    update_post_meta( $post_id, $key, $value );
                }
            }
        }
        public function page_meta_box() {
            $this -> setup($this->meta_boxes);
        }  
    }
}
?>