<?php 


if ( ! function_exists( 'de_global_style' ) ) :

	/**
	 * De_Global_Style_Elementor class.
	 *
	 * @param array $classes Inline Class.
	 */
	function de_global_style( ) {

        // GET POST ID FROM SLUG DI CUSTOM POSTYPE
        $args = [
            'post_type'      => 'elementor_library',
            'posts_per_page' => 1,
            'post_name__in'  => ['default-kit'],
            'fields'         => 'ids' 
        ];
        $default_kit_id = get_posts( $args );
        // var_dump($default_kit_id);
        // GET META_VALUE FROM POST META WITH POST ID ABOVE
        $post_id = !empty($default_kit_id[0])?get_post_meta( $default_kit_id[0], '_elementor_page_settings' ):'';
        // var_dump ($post_id);
        // GET COLOR CSS FROM DEFAULT-KIT
        $primary_color = !empty($post_id[0]["system_colors"][0]["color"])?$post_id[0]["system_colors"][0]["color"]:'';
        $secondary_color = !empty($post_id[0]["system_colors"][1]["color"])?$post_id[0]["system_colors"][1]["color"]:'';
        $text_color = !empty($post_id[0]["system_colors"][2]["color"])?$post_id[0]["system_colors"][2]["color"]:'';
        $custom_color_1 = !empty($post_id[0]["custom_colors"][0]["color"])?$post_id[0]["custom_colors"][0]["color"]:'#F9F7F5';
        $custom_color_1_title = !empty($post_id[0]["custom_colors"][0]["title"])?$post_id[0]["custom_colors"][0]["title"]:'';

        $custom_color_2 = !empty($post_id[0]["custom_colors"][1]["color"])?$post_id[0]["custom_colors"][1]["color"]:'#F9F7F5';
        $custom_color_2_title = !empty($post_id[0]["custom_colors"][1]["title"])?$post_id[0]["custom_colors"][1]["title"]:'';

        $custom_color_3 = !empty($post_id[0]["custom_colors"][2]["color"])?$post_id[0]["custom_colors"][2]["color"]:'#F9F7F5';
        $custom_color_3_title = !empty($post_id[0]["custom_colors"][2]["title"])?$post_id[0]["custom_colors"][2]["title"]:'';

        $custom_color_4 = !empty($post_id[0]["custom_colors"][3]["color"])?$post_id[0]["custom_colors"][3]["color"]:'#F9F7F5';
        $custom_color_4_title = !empty($post_id[0]["custom_colors"][3]["title"])?$post_id[0]["custom_colors"][3]["title"]:'';

        $button_text_color = !empty($post_id[0]["button_text_color"])?$post_id[0]["button_text_color"]:$primary_color;
        $button_background_color = !empty($post_id[0]["button_background_color"])?$post_id[0]["button_background_color"]:$secondary_color;

        $button_border = !empty($post_id[0]["button_border_border"])?$post_id[0]["button_border_border"]:'';
        $button_border_color = !empty($post_id[0]["button_border_color"])?$post_id[0]["button_border_color"]:'';

        // SET COLOR CSS FROM DEFAULT-KIT

        $css = array();
        $css[] = array(
            'element' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce a.button,.woocommerce a.button:hover,.woocommerce button.button,.woocommerce button.button:hover,.woocommerce a.remove:hover,.woocommerce a.button.wc-backward,.woocommerce a.button.wc-backward:hover',
            'rules'   => array(
                'background-color' => $button_background_color,
            ),
        );
        $css[] = array(
            'element' => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.woocommerce a.button,.woocommerce a.button:hover,.woocommerce button.button,.woocommerce button.button:hover, .woocommerce a.button.wc-backward,.woocommerce button.button:disabled, .woocommerce button.button:disabled[disabled],.woocommerce .cart-collaterals .cart_totals .wc-proceed-to-checkout a.wc-forward',
            'rules'   => array(
                'color' => $button_text_color,
            ),
        );
        $css[] = array(
            'element' => '.woocommerce a.remove',
            'rules'   => array(
                'color' => $button_background_color .' !important',
            ),
        );
        $css[] = array(
            'element' => '.woocommerce .woocommerce-cart-form a.button, .woocommerce .woocommerce-cart-form button.button[type="submit"], .woocommerce .cart-collaterals a.checkout-button, .woocommerce .return-to-shop a.button.wc-backward',
            'rules'   => array(
                'border' => '1px '.$button_border.' '.$button_border_color,
            ),
        );
        $css[] = array(
            'element' => '.woocommerce-info,.woocommerce-message,.woocommerce-error',
            'rules'   => array(
                'border-top-color' => $primary_color,
            ),
        );
    
        $css[] = array(
            'element' => '.woocommerce-info::before,.woocommerce-message::before,.woocommerce-error::before',
            'rules'   => array(
                'color' => $primary_color .' !important',
            ),
        );
        $css[] = array(
            'element' => $custom_color_1_title,
            'rules'   => array(
                'color' => $custom_color_1 .' !important',
            ),
        );
        $css[] = array(
            'element' => $custom_color_3_title,
            'rules'   => array(
                'color' => $custom_color_3 .' !important',
            ),
        );
        $css[] = array(
            'element' => $custom_color_4_title,
            'rules'   => array(
                'color' => $custom_color_4 .' !important',
            ),
        );
        $css[] = array(
            'element' => $custom_color_2_title,
            'rules'   => array(
                'color' => $custom_color_2 .' !important',
            ),
        );
        

        $css[] = array(
            'element' => 'h1, h2, h3, h4, h5, h6',
            'rules'   => array(
                'color' => $secondary_color,
            ),
        );

        $css[] = array(
            'element' => 'body, a',
            'rules'   => array(
                'color' => $text_color,
            ),
        );



        $css_output = array();

		foreach ( $css as $_css ) {
			$css_output[] = $_css['element'] . '{';

			foreach ( $_css['rules'] as $rule => $props ) {
				$css_output[] = $rule . ':' . $props;
			}

			$css_output[] = '}';
		}

		wp_add_inline_style( 'dethemekit-widgets', implode( '', $css_output ) );
	}

    add_action( 'wp_enqueue_scripts', 'de_global_style' );
endif;

?>