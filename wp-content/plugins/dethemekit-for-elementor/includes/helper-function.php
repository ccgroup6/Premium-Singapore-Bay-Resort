<?php

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit();

/**
* Elementor Version check
* Return boolean value
*/
function dethemekit_is_elementor_version( $operator = '<', $version = '2.6.0' ) {
    return defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, $version, $operator );
}


/**
 *  Taxonomy List
 * @return array
 */
function dethemekit_taxonomy_list( $taxonomy = 'product_cat' ){
    $terms = get_terms( array(
        'taxonomy' => $taxonomy,
        'hide_empty' => true,
    ));
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ){
        foreach ( $terms as $term ) {
            $options[ $term->slug ] = $term->name;
        }
        return $options;
    }
}

/*
 * Get Post Type
 * return array
 */
function dethemekit_get_post_types( $args = [] ) {
    $post_type_args = [
        'show_in_nav_menus' => true,
    ];
    if ( ! empty( $args['post_type'] ) ) {
        $post_type_args['name'] = $args['post_type'];
    }
    $_post_types = get_post_types( $post_type_args , 'objects' );

    $post_types  = [];
    if( !empty( $args['defaultadd'] ) ){
        $post_types[ strtolower($args['defaultadd']) ] = ucfirst($args['defaultadd']);
    }
    foreach ( $_post_types as $post_type => $object ) {
        $post_types[ $post_type ] = $object->label;
    }
    return $post_types;
}


/**
 * Get Post List
 * return array
 */
function dethemekit_post_name( $post_type = 'post' ){
    $options = array();
    $options['0'] = __('Select','dethemekit');
    $perpage = dethemekit_get_option( 'loadproductlimit', 'dethemekit_others_tabs', '20' );
    $all_post = array( 'posts_per_page' => $perpage, 'post_type'=> $post_type );
    $post_terms = get_posts( $all_post );
    if ( ! empty( $post_terms ) && ! is_wp_error( $post_terms ) ){
        foreach ( $post_terms as $term ) {
            $options[ $term->ID ] = $term->post_title;
        }
        return $options;
    }
}

/*
 * Elementor Templates List
 * return array
 */
function dethemekit_elementor_template() {
    $templates = '';
    if( class_exists('\Elementor\Plugin') ){
        $templates = \Elementor\Plugin::instance()->templates_manager->get_source( 'local' )->get_items();
    }
    $types = array();
    if ( empty( $templates ) ) {
        $template_lists = [ '0' => __( 'Do not Saved Templates.', 'dethemekit' ) ];
    } else {
        $template_lists = [ '0' => __( 'Select Template', 'dethemekit' ) ];
        foreach ( $templates as $template ) {
            $template_lists[ $template['template_id'] ] = $template['title'] . ' (' . $template['type'] . ')';
        }
    }
    return $template_lists;
}

/*
 * Plugisn Options value
 * return on/off
 */
function dethemekit_get_option( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
    return $default;
}

function dethemekit_get_option_label_text( $option, $section, $default = '' ){
    $options = get_option( $section );
    if ( isset( $options[$option] ) ) {
        if( !empty($options[$option]) ){
            return $options[$option];
        }
        return $default;
    }
    return $default;
}

/**
* Woocommerce Product last product id return
*/
function dethemekit_get_last_product_id(){
    global $wpdb;
    
    // Getting last Product ID (max value)
    $results = $wpdb->get_col( "
        SELECT MAX(ID) FROM {$wpdb->prefix}posts
        WHERE post_type LIKE 'product'
        AND post_status = 'publish'" 
    );
    return reset($results);
}

/*
 * HTML Tag list
 * return array
 */
function dethemekit_html_tag_lists() {
    $html_tag_list = [
        'h1'   => __( 'H1', 'dethemekit' ),
        'h2'   => __( 'H2', 'dethemekit' ),
        'h3'   => __( 'H3', 'dethemekit' ),
        'h4'   => __( 'H4', 'dethemekit' ),
        'h5'   => __( 'H5', 'dethemekit' ),
        'h6'   => __( 'H6', 'dethemekit' ),
        'p'    => __( 'p', 'dethemekit' ),
        'div'  => __( 'div', 'dethemekit' ),
        'span' => __( 'span', 'dethemekit' ),
    ];
    return $html_tag_list;
}

/* 
* Category list
* return first one
*/
function dethemekit_get_product_category_list( $id = null, $taxonomy = 'product_cat', $limit = 1 ) { 
    $terms = get_the_terms( $id, $taxonomy );
    $i = 0;
    if ( is_wp_error( $terms ) )
        return $terms;

    if ( empty( $terms ) )
        return false;

    foreach ( $terms as $term ) {
        $i++;
        $link = get_term_link( $term, $taxonomy );
        if ( is_wp_error( $link ) ) {
            return $link;
        }
        echo '<a href="' . esc_url( $link ) . '">' . $term->name . '</a>';
        if( $i == $limit ){
            break;
        }else{ continue; }
    }
}

/*
* If Active WooCommerce
*/
if( class_exists('WooCommerce') ){

    /* Custom product badge */
    function dethemekit_custom_product_badge( $show = 'yes' ){
        global $product;
        $custom_saleflash_text = get_post_meta( get_the_ID(), '_saleflash_text', true );
        if( $show == 'yes' ){
            if( !empty( $custom_saleflash_text ) && $product->is_in_stock() ){
                if( $product->is_featured() ){
                    echo '<span class="ht-product-label ht-product-label-left hot">' . esc_html( $custom_saleflash_text ) . '</span>';
                }else{
                    echo '<span class="ht-product-label ht-product-label-left">' . esc_html( $custom_saleflash_text ) . '</span>';
                }
            }
        }
    }

    /* Sale badge */
    function dethemekit_sale_flash( $offertype = 'default' ){
        global $product;
        if( $product->is_on_sale() && $product->is_in_stock() ){
            if( $offertype !='default' && $product->get_regular_price() > 0 ){
                $_off_percent = (1 - round($product->get_price() / $product->get_regular_price(), 2))*100;
                $_off_price = round($product->get_regular_price() - $product->get_price(), 0);
                $_price_symbol = get_woocommerce_currency_symbol();
                $symbol_pos = get_option('woocommerce_currency_pos', 'left');
                $price_display = '';
                switch( $symbol_pos ){
                    case 'left':
                        $price_display = '-'.$_price_symbol.$_off_price;
                    break;
                    case 'right':
                        $price_display = '-'.$_off_price.$_price_symbol;
                    break;
                    case 'left_space':
                        $price_display = '-'.$_price_symbol.' '.$_off_price;
                    break;
                    default: /* right_space */
                        $price_display = '-'.$_off_price.' '.$_price_symbol;
                    break;
                }
                if( $offertype == 'number' ){
                    echo '<span class="ht-product-label ht-product-label-right">'.$price_display.'</span>';
                }elseif( $offertype == 'percent'){
                    echo '<span class="ht-product-label ht-product-label-right">'.$_off_percent.'%</span>';
                }else{ echo ' '; }

            }else{
                echo '<span class="ht-product-label ht-product-label-right">'.esc_html__( 'Sale!', 'dethemekit' ).'</span>';
            }
        }else{
            $out_of_stock = get_post_meta( get_the_ID(), '_stock_status', true );
            $out_of_stock_text = apply_filters( 'dethemekit_shop_out_of_stock_text', __( 'Out of stock', 'dethemekit' ) );
            if ( 'outofstock' === $out_of_stock ) {
                echo '<span class="ht-stockout ht-product-label ht-product-label-right">'.esc_html( $out_of_stock_text ).'</span>';
            }
        }

    }

    // Shop page header result count
    function dethemekit_product_result_count( $total, $perpage, $paged ){
        wc_set_loop_prop( 'total', $total );
        wc_set_loop_prop( 'per_page', $perpage );
        wc_set_loop_prop( 'current_page', $paged );
        $geargs = array(
            'total'    => wc_get_loop_prop( 'total' ),
            'per_page' => wc_get_loop_prop( 'per_page' ),
            'current'  => wc_get_loop_prop( 'current_page' ),
        );
        wc_get_template( 'loop/result-count.php', $geargs );
    }

    // product shorting
    function dethemekit_product_shorting( $getorderby ){
        ?>
        <div class="dethemekit-custom-sorting">
            <form class="woocommerce-ordering" method="get">
                <select name="orderby" class="orderby">
                    <?php
                        $catalog_orderby = apply_filters( 'woocommerce_catalog_orderby', array(
                            'menu_order' => __( 'Default sorting', 'dethemekit' ),
                            'popularity' => __( 'Sort by popularity', 'dethemekit' ),
                            'rating'     => __( 'Sort by average rating', 'dethemekit' ),
                            'date'       => __( 'Sort by latest', 'dethemekit' ),
                            'price'      => __( 'Sort by price: low to high', 'dethemekit' ),
                            'price-desc' => __( 'Sort by price: high to low', 'dethemekit' ),
                        ) );
                        foreach ( $catalog_orderby as $id => $name ){
                            echo '<option value="' . esc_attr( $id ) . '" ' . selected( $getorderby, $id, false ) . '>' . esc_attr( $name ) . '</option>';
                        }
                    ?>
                </select>
                <?php
                    // Keep query string vars intact
                    foreach ( $_GET as $key => $val ) {
                        if ( 'orderby' === $key || 'submit' === $key )
                            continue;
                        if ( is_array( $val ) ) {
                            foreach( $val as $innerVal ) {
                                echo '<input type="hidden" name="' . esc_attr( $key ) . '[]" value="' . esc_attr( $innerVal ) . '" />';
                            }
                        } else {
                            echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $val ) . '" />';
                        }
                    }
                ?>
            </form>
        </div>
        <?php
    }

    // Custom page pagination
    function dethemekit_custom_pagination( $totalpage ){
        echo '<div class="ht-row woocommerce"><div class="ht-col-xs-12"><nav class="woocommerce-pagination">';
            echo paginate_links( apply_filters(
                    'woocommerce_pagination_args', array(
                        'base'=> esc_url( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) ), 
                        'format'    => '', 
                        'current'   => max( 1, get_query_var( 'paged' ) ), 
                        'total'     => $totalpage, 
                        'prev_text' => '&larr;', 
                        'next_text' => '&rarr;', 
                        'type'      => 'list', 
                        'end_size'  => 3, 
                        'mid_size'  => 3 
                    )
                )       
            );
        echo '</div></div></div>';
    }

    // Change Product Per page
    if( dethemekit_get_option( 'enablecustomlayout', 'dethemekit_woo_template_tabs', 'on' ) == 'on' ){
        function dethemekit_custom_number_of_posts() {
            $limit = dethemekit_get_option( 'shoppageproductlimit', 'dethemekit_woo_template_tabs', 20 );
            $postsperpage = apply_filters( 'product_custom_limit', $limit );
            return $postsperpage;
        }
        add_filter( 'loop_shop_per_page', 'dethemekit_custom_number_of_posts' );
    }

    // Customize rating html
    if( !function_exists('dethemekit_wc_get_rating_html') ){
        function dethemekit_wc_get_rating_html(){
            if ( get_option( 'woocommerce_enable_review_rating' ) === 'no' ) { return; }
            global $product;
            $rating_count = $product->get_rating_count();
            $average      = $product->get_average_rating();
            $rating_whole = floor($average);
            $rating_fraction = $average - $rating_whole;
            $flug = 0;   
            
            if ( $rating_count > 0 ) {
                $wrapper_class = is_single() ? 'rating-number' : 'top-rated-rating';
                ob_start();
            ?>
                <div class="<?php echo esc_attr( $wrapper_class ); ?>">
                    <span class="ht-product-ratting">
                        <span class="ht-product-user-ratting">
                            <?php for($i = 1; $i <= 5; $i++){
                                if( $i <= $rating_whole ){
                                    echo '<i class="fas fa-star"></i>';
                                } else {
                                    if( $rating_fraction > 0 && $flug == 0 ){
                                        echo '<i class="fas fa-star-half-alt"></i>';
                                        $flug = 1;
                                    } else {
                                        echo '<i class="far fa-star empty"></i>';
                                    }
                                }
                            } ?>
                        </span>
                    </span>
                </div>
                 <?php
                    $html = ob_get_clean();
                } else {
                    $html  = '';
                }

                return $html;
        }
    }

    // Quick View Markup
    function dethemekit_quick_view_html(){
        echo '<div class="woocommerce" id="htwlquick-viewmodal"><div class="htwl-modal-dialog product"><div class="htwl-modal-content"><button type="button" class="htcloseqv"><span class="sli sli-close"></span></button><div class="htwl-modal-body"></div></div></div></div>';
    }
    add_action( 'dethemekit_footer_render_content', 'dethemekit_quick_view_html', 10 );

    // HTML Markup Render in footer
    function dethemekit_html_render_infooter(){
        do_action( 'dethemekit_footer_render_content' );
    }
    add_action( 'wp_footer', 'dethemekit_html_render_infooter' );

    // Quick view Ajax Callback
    function dethemekit_wc_quickview() {
        // Get product from request.
        if ( isset( $_POST['id'] ) && (int) $_POST['id'] ) {
            global $post, $product, $woocommerce;
            $id      = ( int ) $_POST['id'];
            $post    = get_post( $id );
            $product = get_product( $id );
            if ( $product ) { 
                include ( apply_filters( 'dethemekit_quickview_tmp', DETHEMEKIT_ADDONS_PATH.'includes/quickview-content.php' ) ); 
                
            }
            
        }
        wp_die();
    }
    add_action( 'wp_ajax_dethemekit_quickview', 'dethemekit_wc_quickview' );
    add_action( 'wp_ajax_nopriv_dethemekit_quickview', 'dethemekit_wc_quickview' );


    /**
     * [dethemekit_stock_status]
     */
    function dethemekit_stock_status( $order_text, $available_text, $product_id ){

        $product_id  = $product_id;
        if ( get_post_meta( $product_id, '_manage_stock', true ) == 'yes' ) {

            $total_stock = get_post_meta( $product_id, 'dethemekit_total_stock_quantity', true );

            if ( ! $total_stock ) { echo '<div class="stock-management-progressbar">'.__('Do not set stock amount for progress bar','dethemekit-pro').'</div>'; return; }

            $current_stock = round( get_post_meta( $product_id, '_stock', true ) );

            $total_sold = $total_stock > $current_stock ? $total_stock - $current_stock : 0;
            $percentage = $total_sold > 0 ? round( $total_sold / $total_stock * 100 ) : 0;

            if ( $current_stock > 0 ) {
                echo '<div class="dethemekit-stock-progress-bar">';
                    echo '<div class="wlstock-info">';
                        echo '<div class="wltotal-sold">' . __( $order_text, 'dethemekit-pro' ) . '<span>' . esc_html( $total_sold ) . '</span></div>';
                        echo '<div class="wlcurrent-stock">' . __( $available_text, 'dethemekit-pro' ) . '<span>' . esc_html( $current_stock ) . '</span></div>';
                    echo '</div>';
                    echo '<div class="wlprogress-area" title="' . __( 'Sold', 'dethemekit-pro' ) . ' ' . esc_attr( $percentage ) . '%">';
                        echo '<div class="wlprogress-bar"style="width:' . esc_attr( $percentage ) . '%;"></div>';
                    echo '</div>';
                echo '</div>';
            }else{
                echo '<div class="stock-management-progressbar">'.__('Do not set stock amount for progress bar','dethemekit-pro').'</div>';
            }

        }

    }

}

/**
* Usages: Compare button shortcode [yith_compare_button] From "YITH WooCommerce Compare" plugins.
* Plugins URL: https://wordpress.org/plugins/yith-woocommerce-compare/
* File Path: yith-woocommerce-compare/includes/class.yith-woocompare-frontend.php
* The Function "dethemekit_compare_button" Depends on YITH WooCommerce Compare plugins. If YITH WooCommerce Compare is installed and actived, then it will work.
*/
function dethemekit_compare_button( $buttonstyle = 1 ){
    if( !class_exists('YITH_Woocompare') ) return;
    global $product;
    $product_id = $product->get_id();
    $comp_link = home_url() . '?action=yith-woocompare-add-product';
    $comp_link = add_query_arg('id', $product_id, $comp_link);

    if( $buttonstyle == 1 ){
        echo do_shortcode('[yith_compare_button]');
    }else{
        echo '<a title="'. esc_attr__('Add to Compare', 'dethemekit') .'" href="'. esc_url( $comp_link ) .'" class="dethemekit-compare compare" data-product_id="'. esc_attr( $product_id ) .'" rel="nofollow">'.esc_html__( 'Compare', 'dethemekit' ).'</a>';
    }

}

/**
* Usages: "dethemekit_add_to_wishlist_button()" function is used  to modify the wishlist button from "YITH WooCommerce Wishlist" plugins.
* Plugins URL: https://wordpress.org/plugins/yith-woocommerce-wishlist/
* File Path: yith-woocommerce-wishlist/templates/add-to-wishlist.php
* The below Function depends on YITH WooCommerce Wishlist plugins. If YITH WooCommerce Wishlist is installed and actived, then it will work.
*/

function dethemekit_add_to_wishlist_button( $normalicon = '<i class="fa fa-heart-o"></i>', $addedicon = '<i class="fa fa-heart"></i>', $tooltip = 'no' ) {
    global $product, $yith_wcwl;

    if ( ! class_exists( 'YITH_WCWL' ) || empty(get_option( 'yith_wcwl_wishlist_page_id' ))) return;

    $url          = YITH_WCWL()->get_wishlist_url();
    $product_type = $product->get_type();
    $exists       = $yith_wcwl->is_product_in_wishlist( $product->get_id() );
    $classes      = 'class="add_to_wishlist"';
    $add          = get_option( 'yith_wcwl_add_to_wishlist_text' );
    $browse       = get_option( 'yith_wcwl_browse_wishlist_text' );
    $added        = get_option( 'yith_wcwl_product_added_text' );

    $output = '';

    $output  .= '<div class="'.( $tooltip == 'yes' ? '' : 'tooltip_no' ).' wishlist button-default yith-wcwl-add-to-wishlist add-to-wishlist-' . esc_attr( $product->get_id() ) . '">';
        $output .= '<div class="yith-wcwl-add-button';
            $output .= $exists ? ' hide" style="display:none;"' : ' show"';
            $output .= '><a href="' . esc_url( htmlspecialchars( YITH_WCWL()->get_wishlist_url() ) ) . '" data-product-id="' . esc_attr( $product->get_id() ) . '" data-product-type="' . esc_attr( $product_type ) . '" ' . $classes . ' >'.$normalicon.'<span class="ht-product-action-tooltip">'.esc_html( $add ).'</span></a>';
            $output .= '<i class="fa fa-spinner fa-pulse ajax-loading" style="visibility:hidden"></i>';
        $output .= '</div>';

        $output .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><a class="" href="' . esc_url( $url ) . '">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $browse ).'</span></a></div>';
        $output .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><a href="' . esc_url( $url ) . '" class="">'.$addedicon.'<span class="ht-product-action-tooltip">'.esc_html( $added ).'</span></a></div>';
    $output .= '</div>';
    return $output;


}