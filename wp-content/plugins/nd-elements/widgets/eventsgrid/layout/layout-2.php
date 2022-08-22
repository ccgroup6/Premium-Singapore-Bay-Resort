<?php

//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_eventsgrid_compo_l2">';

	while ( $the_query->have_posts() ) : $the_query->the_post();

		//info
		$nd_elements_id = get_the_ID(); 
		$nd_elements_title = get_the_title();
		$nd_elements_excerpt = get_the_excerpt();
		$nd_elements_permalink = get_permalink( $nd_elements_id );

		//decide color - nd-shortcodes compatibility
		$nd_elements_meta_box_eventscalendar_color = get_post_meta( $nd_elements_id, 'nd_options_meta_box_eventscalendar_color', true );
		if ( $nd_elements_meta_box_eventscalendar_color != '' ) { 
			$eventsgrid_color_btn = $nd_elements_meta_box_eventscalendar_color;
		}

		//image
		$nd_elements_image_id = get_post_thumbnail_id( $nd_elements_id );
		$nd_elements_image_attributes = wp_get_attachment_image_src( $nd_elements_image_id, 'thumbnail' );
		if ( $nd_elements_image_attributes[0] == '' ) { $nd_elements_output_image = ''; }else{
		  $nd_elements_output_image = '

		  <div class="nd_elements_width_100 nd_elements_display_table_cell nd_elements_display_inline_iphone nd_elements_float_left_iphone nd_elements_width_100_percentage_iphone nd_elements_vertical_align_middle nd_elements_position_relative">
		  	
		  	<a href="'.$nd_elements_permalink.'"><img width="100" class="nd_elements_float_left" alt="" src="'.$nd_elements_image_attributes[0].'"></a>
		  
		  	<div class="nd_elements_section nd_elements_text_align_center nd_elements_width_100 nd_elements_position_absolute nd_elements_bottom_10_negative">
		  		<a href="'.$nd_elements_permalink.'"><p style="background-color:'.$eventsgrid_color_time.';" class="nd_elements_display_inline_block nd_elements_margin_0_important nd_elements_padding_5_10 nd_elements_font_size_11 nd_elements_line_height_11 nd_options_color_white">'.tribe_get_start_date($nd_elements_id,false,'g:i A').'</p></a>
		  	</div>
		  
		  </div>

		  ';
		}

		$nd_elements_result .= '
    	<div class="nd_elements_section nd_elements_position_relative">


    		<div class="nd_elements_background_color_fff nd_elements_position_absolute nd_elements_left_0 nd_elements_top_5 nd_elements_box_shadow_0_0_15_0_0001 nd_elements_float_left nd_elements_width_90 nd_elements_height_90 nd_elements_text_align_center">

    			<div class="nd_elements_section nd_elements_height_16"></div>
    			<a href="'.$nd_elements_permalink.'">
    				<h2 class="nd_elements_font_size_35 nd_elements_line_height_1  nd_elements_section nd_elements_letter_spacing_1"><strong>'.tribe_get_start_date($nd_elements_id,false,'d').'</strong></h2>
    			</a>
    			<div class="nd_elements_section nd_elements_height_5"></div>
    			<a href="'.$nd_elements_permalink.'">
    				<h4 class="nd_elements_font_size_15 nd_elements_line_height_1 nd_options_color_grey nd_elements_section nd_elements_text_transform_uppercase nd_elements_letter_spacing_2">'.tribe_get_start_date($nd_elements_id,false,'M').'</h4>
    			</a>

    		</div>


	    	<div class="nd_elements_float_left nd_elements_padding_left_130 nd_elements_padding_left_0_iphone nd_elements_padding_top_110_iphone">

	    		<a href="'.$nd_elements_permalink.'">
		    		<h3 class="nd_elements_letter_spacing_1">
		    			<strong class="nd_elements_float_left_iphone nd_elements_width_100_percentage_iphone">'.$nd_elements_title.'</strong>
		    			<span style="background-color:'.$nd_elements_meta_box_eventscalendar_color.';" class="nd_options_color_white nd_elements_font_size_11 nd_elements_line_height_11 nd_elements_padding_5_10 nd_elements_font_weight_500 nd_elements_display_inline_block nd_elements_vertical_align_middle nd_elements_margin_left_15 nd_elements_margin_left_0_iphone nd_elements_float_left_iphone">'.tribe_get_start_date($nd_elements_id,false,'g:i A').'</span>
		    		</h3>
	    		</a>
	    		<div class="nd_elements_section nd_elements_height_10"></div>
	    		<a href="'.$nd_elements_permalink.'"><p class="nd_elements_line_height_2 nd_elements_letter_spacing_1">'.$nd_elements_excerpt.'</p></a>

	    	</div>


    	</div>

    	<div class="nd_elements_section nd_elements_height_50"></div>




	    ';

	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT