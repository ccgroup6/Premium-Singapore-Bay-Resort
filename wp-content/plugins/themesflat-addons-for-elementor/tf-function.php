<?php 
if(!function_exists('tf_header_enabled')){
    function tf_header_enabled() {
        $header_id = ThemesFlat_Addon_For_Elementor_Free::get_settings( 'type_header', '' );
        $status    = false;

        if ( '' !== $header_id ) {
            $status = true;
        }

        return apply_filters( 'tf_header_enabled', $status );
    }
}

if(!function_exists('tf_footer_enabled')){
    function tf_footer_enabled() {
        $header_id = ThemesFlat_Addon_For_Elementor_Free::get_settings( 'type_footer', '' );
        $status    = false;

        if ( '' !== $header_id ) {
            $status = true;
        }

        return apply_filters( 'tf_footer_enabled', $status );
    }
}

if(!function_exists('get_header_content')){
    function get_header_content() {
        $tf_get_header_id = ThemesFlat_Addon_For_Elementor_Free::tf_get_header_id();
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($tf_get_header_id);
    }
}


/*WOO
==============================================================================*/
if( !function_exists('themesflat_product_mini_add_to_cart_fragment')){
    add_filter( 'woocommerce_add_to_cart_fragments', 'themesflat_product_mini_add_to_cart_fragment' );
    function themesflat_product_mini_add_to_cart_fragment( $fragments ) { 
        if ( class_exists( 'woocommerce' ) ) {
            if (!function_exists('themesflat_header_add_to_cart_fragment')) {
                $product_id = (int) apply_filters('woocommerce_add_to_cart_product_id', $_POST['product_id']);
                $fragments['added_product'] = get_post($product_id)->post_title;
                ob_start();
                $count = WC()->cart->get_cart_contents_count();
                ?>               
                <?php if ( $count > 0 ): ?>
                    <span class="cart-contents-count"><?php echo esc_attr( $count ); ?></span>
                <?php else: ?>
                    <span class="cart-contents-count">0</span>
                <?php endif; ?>                           
                <?php
                $fragments['.wrap-count-content .cart-contents-count'] = ob_get_clean();
                ob_end_clean();
                return $fragments;
            }
        }
    }
}

if( !function_exists('tf_woo_custom_product_data_badge_tab')){
    function tf_woo_custom_product_data_badge_tab($tabs) {
        $tabs['tf_custom_product_settings'] = array(
            'label' => esc_html__( 'TF Product Badge', 'tf-addon-for-elementer' ),
            'target' => 'tf_custom_product_options_badge',
            'class' => '',
            'priority' => '80'
        );

        return $tabs;
    }
    add_filter('woocommerce_product_data_tabs', 'tf_woo_custom_product_data_badge_tab');
}
if( !function_exists('tf_woo_add_metabox_to_general_tab')){
    function tf_woo_add_metabox_to_general_tab(){
        echo '<div id="tf_custom_product_options_badge" class="panel woocommerce_options_panel">';
            woocommerce_wp_text_input( array(
                'id'          => '_tf_flashsale_text',
                'label'       => esc_html__( 'Custom Product Badge: ', 'tf-addon-for-elementer' ),
                'placeholder' => esc_html__( 'Enter your badge text. Ex: New / Free etc', 'tf-addon-for-elementer' ),
            ) );
            woocommerce_wp_text_input( array(
                'id'          => '_tf_flashsale_text_2',
                'label'       => esc_html__( 'Custom Product Badge: ', 'tf-addon-for-elementer' ),
                'placeholder' => esc_html__( 'Enter your badge text. Ex: Hot / Free etc', 'tf-addon-for-elementer' ),
            ) );
        echo '</div>';
    }
    add_action( 'woocommerce_product_data_panels', 'tf_woo_add_metabox_to_general_tab' );
}
if( !function_exists('tf_save_metabox_of_general_tab') ){
    function tf_save_metabox_of_general_tab( $post_id ){
        $tf_flashsale_text = wp_kses_post( stripslashes( $_POST['_tf_flashsale_text'] ) );
        $tf_flashsale_text_2 = wp_kses_post( stripslashes( $_POST['_tf_flashsale_text_2'] ) );
        update_post_meta( $post_id, '_tf_flashsale_text', $tf_flashsale_text);
        update_post_meta( $post_id, '_tf_flashsale_text_2', $tf_flashsale_text_2);
    }
    add_action( 'woocommerce_process_product_meta', 'tf_save_metabox_of_general_tab');
}

if( !function_exists('get_flash_sale')){
    function get_flash_sale($show = 'yes') {  
        global $product;
        $text_flash_sale = get_post_meta(get_the_ID(), '_tf_flashsale_text', true);
        $text_flash_sale_2 = get_post_meta(get_the_ID(), '_tf_flashsale_text_2', true);
        if( $show == 'yes' ){
            $badge = '';
            $badge = '<span class="wrap-badge">';
            if( !empty( $text_flash_sale ) && $product->is_in_stock() ){
                
                if( $product->is_featured() ){                
                    $badge .= '<span class="badge badge01 flash-sale hot">'.$text_flash_sale.'</span>';              
                }else{
                    $badge .= '<span class="badge badge01 flash-sale flash-sale-right">'.$text_flash_sale.'</span>';
                }
            }

            if( !empty( $text_flash_sale_2 ) && $product->is_in_stock() ){
                
                if( $product->is_featured() ){                
                    $badge .= '<span class="badge badge02 flash-sale hot">'.$text_flash_sale_2.'</span>';              
                }else{
                    $badge .= '<span class="badge badge02 flash-sale flash-sale-right">'.$text_flash_sale_2.'</span>';
                }
            }
            $badge .= '</span>';

            return $badge;
        }
    }
}

if( !function_exists('get_sale_price')){
    function get_sale_price($show = 'yes', $sale_style='sale_text', $text_sale='Sale!', $prefix_percent='-') {  
        if( function_exists('get_product') ){    
            if ($show == 'yes') {
                $sale_price = get_post_meta( get_the_ID(), '_sale_price', true);            
                $regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
                $sale_price = intval($sale_price);
                $regular_price = intval($regular_price);
                $percentage = 0;
                if ($regular_price > 0 && $sale_price > 0) {
                    $percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
                }
                
                if ($sale_style == 'sale_percent') {
                    if ($percentage != 0 && $percentage != 100 ) {
                        return '<span class="onsale sale-percent">'.$prefix_percent.$percentage.'%</span>';
                    }                
                }else {
                    if ($percentage != 0 && $percentage != 100 ) {
                        return '<span class="onsale">'.$text_sale.'</span>';
                    } 
                } 
            }     
        }  
    }
}

/* Pagination Woo */
if(!function_exists('tf_custom_pagination_woo')){
    function tf_custom_pagination_woo($query_products='', $paged='', $style='', $align='') {

        if($style == 'numeric'){
            ?>
            <nav class="navigation navigation-numeric" role="navigation">
                <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
                <?php   
                if (  $query_products->max_num_pages > 1 ){        
                    echo paginate_links( array(
                    'base' => str_replace( $query_products->max_num_pages, '%#%', esc_url( get_pagenum_link( $query_products->max_num_pages ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $query_products->max_num_pages,
                    'prev_next' => false
                    ));
                }
                ?>
                </div>
            </nav>
            <?php
        }elseif ($style == 'numeric-link') {
            ?>
            <nav class="navigation navigation-numeric-link" role="navigation">
                <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
                <?php
                if (  $query_products->max_num_pages > 1 ){
                    echo paginate_links( array(
                    'base' => str_replace( $query_products->max_num_pages, '%#%', esc_url( get_pagenum_link( $query_products->max_num_pages ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $query_products->max_num_pages,               
                    'prev_text' => ( '<i class="fas fa-angle-left"></i>' ),
                    'next_text' => ( '<i class="fas fa-angle-right"></i>' ),
                    ));
                }
                ?>
                </div>
            </nav>
            <?php
        }elseif ($style == 'link') {
            ?>
            <nav class="navigation navigation-link" role="navigation">
                <div class="pagination loop-pagination clearfix">
                <?php
                if (  $query_products->max_num_pages > 1 ){
                    echo paginate_links( array(
                    'base' => str_replace( $query_products->max_num_pages, '%#%', esc_url( get_pagenum_link( $query_products->max_num_pages ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $query_products->max_num_pages,
                    'prev_text' => esc_html__( 'Previous', 'tf-addon-for-elementer' ),
                    'next_text' => esc_html__( 'Next', 'tf-addon-for-elementer' ),
                    ));
                }
                ?>
                </div>
            </nav>
            <?php
        }elseif ($style == 'loadmore') {
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
                ?>
                <nav class="navigation loadmore" role="navigation">
                    <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">            
                    <a href=" <?php echo esc_url( get_next_posts_page_link() ); ?> "><?php echo esc_html__('Load More', 'tf-addon-for-elementer'); ?></a>
                    </div>
                </nav>
                <?php
            }else {
                ?>
                <nav class="navigation loadmore" role="navigation">
                    <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
                        <div class="wrap-text">
                            <span class="loading-icon">
                                <span class="bubble">
                                    <span class="dot"></span>
                                </span>
                                <span class="bubble">
                                    <span class="dot"></span>
                                </span>
                                <span class="bubble">
                                    <span class="dot"></span>
                                </span>
                            </span>
                            <span class="text"><?php echo next_posts_link( esc_html__( 'Load More', 'tf-addon-for-elementer' ), $query_products->max_num_pages ); ?></span>                            
                        </div>
                    </div>
                </nav>
                <?php
            }
        }else {
            ?>
            <nav class="navigation navigation-numeric-link" role="navigation">
                <div class="pagination loop-pagination <?php echo esc_attr($align); ?>">
                <?php
                if (  $query_products->max_num_pages > 1 ){
                    echo paginate_links( array(
                    'base' => str_replace( $query_products->max_num_pages, '%#%', esc_url( get_pagenum_link( $query_products->max_num_pages ) ) ),
                    'format' => '?paged=%#%',
                    'current' => max( 1, $paged ),
                    'total' => $query_products->max_num_pages,               
                    'prev_text' => ( '<i class="fas fa-angle-left"></i>' ),
                    'next_text' => ( '<i class="fas fa-angle-right"></i>' ),
                    ));
                }
                ?>
                </div>
            </nav>
            <?php
        }      
    }
}

/* Wishlist Woo */
if ( defined( 'YITH_WCWL' ) && ! function_exists( 'tf_yith_wcwl_ajax_update_count' ) ) {
  function tf_yith_wcwl_ajax_update_count() {
    wp_send_json( array(
      'count' => yith_wcwl_count_all_products()
    ) );
  }
  add_action( 'wp_ajax_yith_wcwl_update_wishlist_count', 'tf_yith_wcwl_ajax_update_count' );
  add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', 'tf_yith_wcwl_ajax_update_count' );
}

if ( defined( 'YITH_WCWL' ) && ! function_exists( 'tf_yith_wcwl_enqueue_custom_script' ) ) {
    function tf_yith_wcwl_enqueue_custom_script() {
        wp_add_inline_script(
            'jquery-yith-wcwl',
            "jQuery( function( $ ) {
                    $( document ).on( 'added_to_wishlist removed_from_wishlist', function() {
                        $.get( yith_wcwl_l10n.ajax_url, {
                            action: 'yith_wcwl_update_wishlist_count'
                        }, function( data ) {
                            $('.wishlist-counter').html( data.count );
                        } );
                    } );
            } );"
        );
    }
    add_action( 'wp_enqueue_scripts', 'tf_yith_wcwl_enqueue_custom_script', 20 );
}

/* Compare Woo */
if( !function_exists('tf_yith_compare_button')){
    function tf_yith_compare_button(){
        if( !class_exists('YITH_Woocompare') ) return;
        global $product;
        $product_id = $product->get_id();
        $comp_link = home_url() . '?action=yith-woocompare-add-product';
        $comp_link = add_query_arg('id', $product_id, $comp_link);    
        $output = '<div class="woocommerce product compare-button tf-compare-button"><a href="'. esc_url( $comp_link ) .'" class="compare button" data-product_id="'. esc_attr( $product_id ) .'" rel="nofollow" title="'.esc_html__( 'Compare', 'tf-addon-for-elementer' ).'">'.esc_html__( 'Compare', 'tf-addon-for-elementer' ).'</a></div>';
        return $output;
    }
}

/* QuickView Woo */
if( !function_exists('tf_yith_quick_view_button')){
    function tf_yith_quick_view_button(){
        if( !class_exists('YITH_WCQV') ) return;
        global $product;
        $product_id = $product->get_id();
        $output = '<div class="woocommerce product tf-quick-view-button"><a href="'.esc_url( $product->get_permalink()).'" class="button yith-wcqv-button" data-product_id="'. esc_attr( $product_id ) .'" title="'.esc_html__( 'Quick View', 'tf-addon-for-elementer' ).'">'.esc_html__( 'Quick View', 'tf-addon-for-elementer' ).'</a></div>';
        return $output;
    }
}

/* Get All ID Product */
if( !function_exists('tf_get_all_id_product')){
    function tf_get_all_id_product() {
        $list_products = get_posts( array(
            'post_type' => 'product',
            'numberposts' => -1,
            'post_status' => 'publish',
            'fields' => 'ids,post_title',
       ) );
       foreach ( $list_products as $product ) {
            $select_product[$product->ID] = $product->post_title; 
       }
       return $select_product;
    }
}

if( !function_exists('tf_product_render')){
    add_action('wp_ajax_tf_product_filter', 'tf_product_render'); 
    add_action('wp_ajax_nopriv_tf_product_filter', 'tf_product_render');
     
    function tf_product_render(){     
        $settings = unserialize(base64_decode($_POST['settings']));

        $args = array(
            'post_type'             => 'product',
            'post_status'           => 'publish',
            'ignore_sticky_posts'   => 1,
            'posts_per_page' => -1,
        ); 

        // $args = array(
        //     'meta_query' => array(
        //         'relation' => 'and',
        //         array(
        //             'relation'  => 'or',
        //             array(
        //                 'key' => '_sku',
        //                 'value' => 'clearance',
        //                 'compare' => 'NOT LIKE',
        //             ),
        //         ),
        //         array(
        //             'relation' => 'or',
        //             array(
        //                 'key' => '_sku',
        //                 'value' => 'fxpe',
        //                 'compare' => 'NOT LIKE',
        //             ),
        //         ),
        //     )
        // );

        $tax_query_tab[] = array(
            'taxonomy' => 'product_visibility',
            'field'    => 'name',
            'terms'    => 'featured',
            'operator' => 'IN',
        );
        if ( isset( $_POST['product_cats'] ) && isset( $_POST['product_tab_badge'] ) && $_POST['product_tab_badge'] == 'sale') {
            if ($_POST['product_cats'] == 'all') {
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'tax_query' => $tax_query_tab,
                    'meta_query'    => array(
                        'relation'  => 'AND',
                        array(
                            'key'       => '_sale_price',
                            'value'     => 0,
                            'compare'   => '!='
                        ),
                        array(
                            'key'       => '_sale_price',
                            'value'     => 0,
                            'compare'   => '>'
                        )
                    )
                );
            }else {
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'meta_query'    => array(
                        'relation'  => 'AND',
                        array(
                            'key'       => '_sale_price',
                            'value'     => 0,
                            'compare'   => '!='
                        ),
                        array(
                            'key'       => '_sale_price',
                            'value'     => 0,
                            'compare'   => '>'
                        )
                    ),
                    'tax_query' => array(
                        'relation'  => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $_POST['product_cats']
                        ),
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        )
                    ),
                );
            }            
        } elseif( isset( $_POST['product_cats'] ) && isset( $_POST['product_tab_badge'] ) ) {        
            if ($_POST['product_cats'] == 'all' && $_POST['product_tab_badge'] == 'all') {
                $args = array(  
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'ignore_sticky_posts'   => 1,
                    'tax_query' => $tax_query_tab,             
                );
            } elseif ($_POST['product_cats'] == 'all') {
                $args = array(
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'tax_query' => $tax_query_tab,
                    'meta_query'    => array(
                        'relation'  => 'OR',
                        array(
                            'key'       => '_tf_flashsale_text',
                            'value'     => $_POST['product_tab_badge'],
                            'compare'   => '='
                        ),
                        array(
                            'key'       => '_tf_flashsale_text_2',
                            'value'     => $_POST['product_tab_badge'],
                            'compare'   => '='
                        )
                    )
                );
            } elseif ($_POST['product_tab_badge'] == 'all') {
                $args = array(  
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,      
                    'tax_query' => array(
                        'relation'  => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $_POST['product_cats']
                        ),
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        )
                    ),                
                );
            } else {
                $args = array(
                    'meta_query'    => array(
                        'relation'  => 'OR',
                        array(
                            'key'       => '_tf_flashsale_text',
                            'value'     => $_POST['product_tab_badge'],
                            'compare'   => '='
                        ),
                        array(
                            'key'       => '_tf_flashsale_text_2',
                            'value'     => $_POST['product_tab_badge'],
                            'compare'   => '='
                        )
                    ),
                    'tax_query' => array(
                        'relation'  => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $_POST['product_cats']
                        ),
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        )
                    ),
                );  
            }  
        } else {
            if( isset( $_POST['product_cats'] ) ){
                $args = array(  
                    'post_type' => 'product',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,      
                    'tax_query' => array(
                        'relation'  => 'AND',
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => $_POST['product_cats']
                        ),
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms'    => 'featured',
                            'operator' => 'IN',
                        )
                    ),                
                );
            }
        }    
        

        $query_products = new WP_Query( $args );
        if( $query_products->have_posts() ):
            while( $query_products->have_posts() ): $query_products->the_post(); ?>
                <div class="product-item">
                    <div class="product-inner">
                        <?php if( has_post_thumbnail() ): ?>
                            <div class="product-thumb">
                                <a href="<?php the_permalink();?>" class="image">
                                    <?php 
                                    if ($settings['custom_sale'] == 'yes') {
                                        echo get_sale_price($settings['product_sale'] ,$settings['sale_style'], $settings['enter_sale_text'], $settings['prefix_sale_percent']);
                                    }else {
                                        woocommerce_show_product_loop_sale_flash();
                                    }
                                    echo get_flash_sale($settings['badge']);
                                    
                                    $get_post_thumbnail = get_post_thumbnail_id();
                                    if ( !empty( wc_get_product()->get_gallery_image_ids() ) ){
                                        $image_id = wc_get_product()->get_gallery_image_ids()[1]; 
                                        $image_src = wp_get_attachment_image_src ($image_id, 'full');
                                    }                                               
                                    if ( !empty( wc_get_product()->get_gallery_image_ids() ) && $image_src != '' && $settings['image_hover_gallery'] == 'yes' ):
                                        $image_id = wc_get_product()->get_gallery_image_ids()[1];                                       
                                        ?>
                                        <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="image_on">    
                                        <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'thumbnail', $settings ); ?>" alt="image" class="image_off">
                                        <?php
                                    else: ?>                                            
                                        <img src="<?php echo \Elementor\Group_Control_Image_Size::get_attachment_image_src( $get_post_thumbnail, 'thumbnail', $settings ); ?>" alt="image" class="<?php echo 'elementor-animation-'.esc_attr($settings['hover_animation']); ?>">    
                                    <?php endif ?>
                                </a>

                                <?php if ( $settings['product_layout'] == 'layout-2' ): ?>
                                    <div class="product-actions">
                                        <?php woocommerce_template_loop_add_to_cart(); ?>
                                        <?php 
                                        if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
                                            echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                                        }
                                        if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
                                            echo tf_yith_compare_button();
                                        }
                                        if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
                                            echo tf_yith_quick_view_button();
                                        }                                   
                                        ?>
                                    </div>  
                                <?php endif; ?>

                                <?php if ( $settings['product_layout'] == 'layout-3' ): ?>
                                    <div class="product-actions">
                                        <?php 
                                        if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
                                            echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                                        }
                                        if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
                                            echo tf_yith_compare_button();
                                        }
                                        if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
                                            echo tf_yith_quick_view_button();
                                        }                                   
                                        ?>
                                    </div>  
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>
                        <div class="product-content">
                            <h4 class="title"><a href="<?php the_permalink();?>"><?php echo get_the_title();?></a></h4>
                            <?php woocommerce_template_loop_price();?>  

                            <?php if ($settings['product_layout'] == 'layout-1'): ?>                            
                                <div class="product-actions">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                    <?php 
                                    if ( class_exists( 'YITH_WCWL' ) && shortcode_exists( 'yith_wcwl_add_to_wishlist') && $settings['wishlist'] == 'yes' ){
                                        echo do_shortcode( '[yith_wcwl_add_to_wishlist]' );
                                    }
                                    if ( class_exists( 'YITH_Woocompare' ) && $settings['compare'] == 'yes' ){
                                        echo tf_yith_compare_button();
                                    }
                                    if ( class_exists( 'YITH_WCQV' ) && $settings['quickview'] == 'yes' ){
                                        echo tf_yith_quick_view_button();
                                    }                                   
                                    ?>
                                </div>  
                            <?php endif; ?>

                            <?php if ($settings['product_layout'] == 'layout-3'): ?>                            
                                <div class="product-actions">
                                    <?php woocommerce_template_loop_add_to_cart(); ?>
                                </div>  
                            <?php endif; ?>  

                        </div>
                    </div>
                </div>
            <?php endwhile;
            wp_reset_postdata();
            wp_die(); 
        else:
            echo '<div class="no-found">';
                esc_html_e('No product found', 'tf-addon-for-elementer');
            echo '</div>';
            wp_die();
        endif;
        wp_reset_postdata();
    }
}