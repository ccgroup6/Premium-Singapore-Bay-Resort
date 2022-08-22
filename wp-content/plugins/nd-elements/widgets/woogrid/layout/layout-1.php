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
    	<div class=" '.$woogrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

    		<div class="nd_elements_section nd_elements_background_color_fff nd_elements_box_shadow_0_0_15_0_0001">

	    		'.$nd_elements_output_image.'

	    		<div class="nd_elements_section nd_elements_padding_40 nd_elements_text_align_center nd_elements_padding_20_iphone nd_elements_box_sizing_border_box">

			    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
			    		<h3 class="nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_title.'</strong></h3>
			    	</a>
			    	<div class="nd_elements_section nd_elements_height_20"></div>
			    	<p class="nd_elements_font_size_17 nd_elements_line_height_17">'.get_woocommerce_currency_symbol().' '.$nd_elements_price.'</p>
			    	<div class="nd_elements_section nd_elements_height_20"></div>
			    	<a class="nd_elements_padding_10_20 nd_elements_display_inline_block nd_options_color_white nd_elements_font_size_13 nd_elements_line_height_13" style="background-color:'.$woogrid_color.';" href="'.$nd_elements_permalink.'"><strong>'.__('READ MORE','nd-elements').'</strong></a>


				</div>

			</div>
	    	
		</div>';


	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT