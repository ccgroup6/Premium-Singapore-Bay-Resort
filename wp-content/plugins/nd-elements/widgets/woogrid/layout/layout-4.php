<?php

//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_masonry_content">';

	while ( $the_query->have_posts() ) : $the_query->the_post();

		//info
		$nd_elements_id = get_the_ID(); 
		$nd_elements_title = get_the_title();
		$nd_elements_excerpt = get_the_excerpt();
		$nd_elements_permalink = get_permalink( $nd_elements_id );

		//woo info
		$nd_elements_price = get_post_meta( $nd_elements_id, '_price', true);

		//customizer
		$nd_elements_customizer_woocommerce_color_greydark = get_option( 'nd_options_customizer_woocommerce_color_greydark', '#444444' );

		//decide color - nd-shortcodes compatibility
		$nd_elements_meta_box_woocommerce_color = get_post_meta( $nd_elements_id, 'nd_options_meta_box_woocommerce_color', true );
		if ( $nd_elements_meta_box_woocommerce_color != '' ) { 
			$woogrid_color = $nd_elements_meta_box_woocommerce_color;
		}

		//image
		$nd_elements_image_id = get_post_thumbnail_id( $nd_elements_id );
		$nd_elements_image_attributes = wp_get_attachment_image_src( $nd_elements_image_id, 'large' );
		if ( $nd_elements_image_attributes[0] == '' ) { $nd_elements_output_image = ''; }else{
		  $nd_elements_output_image = '<a href="'.$nd_elements_permalink.'"><img class="nd_elements_section" alt="" src="'.$nd_elements_image_attributes[0].'"></a>';
		}

		$nd_elements_result .= '
    	<div class=" '.$woogrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box nd_elements_text_align_center">

	    	'.$nd_elements_output_image.'
	    	<div class="nd_elements_section nd_elements_height_20"></div>	
	    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'"><h5 class="nd_elements_line_height_1 nd_elements_font_weight_bold nd_elements_letter_spacing_2 nd_elements_e_woo_postgrid_l4_title">'.$nd_elements_title.'</h5></a>
	    	<div class="nd_elements_section nd_elements_height_15"></div>
	    	<p class="nd_elements_line_height_1 nd_elements_margin_0_important nd_elements_padding_0 nd_elements_font_weight_normal nd_elements_e_woo_postgrid_l4_price">'.get_woocommerce_currency_symbol().' '.$nd_elements_price.'</p>
	    	<div class="nd_elements_section nd_elements_height_15"></div>	    	
	    	<a class="nd_elements_line_height_1 nd_elements_padding_10_20 nd_elements_font_size_12 nd_options_color_white nd_options_first_font nd_elements_font_weight_bold nd_elements_letter_spacing_2 nd_elements_e_woo_postgrid_l4_button" style="background-color:'.$woogrid_color.';" href="'.$nd_elements_permalink.'">'. __('READ MORE','nd-elements').'</a>

		</div>';


	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT