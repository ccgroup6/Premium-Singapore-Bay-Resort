<?php

//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_eventsgrid_compo_l1">';

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
		<div class="nd_elements_section nd_elements_display_table nd_elements_padding_15 nd_elements_box_sizing_border_box">

			<div class=" nd_elements_width_30_percentage nd_elements_width_100_percentage_responsive nd_elements_float_left_responsive nd_elements_display_table_cell nd_elements_display_inline_responsive nd_elements_vertical_align_middle">
				

				<div class="nd_elements_section nd_elements_display_table">

					'.$nd_elements_output_image.'

					<div class="nd_elements_display_table_cell nd_elements_display_inline_iphone nd_elements_float_left_iphone nd_elements_width_100_percentage_iphone nd_elements_vertical_align_middle nd_elements_padding_left_50 nd_elements_padding_left_0_iphone nd_elements_margin_top_20_iphone">
	    				<a href="'.$nd_elements_permalink.'"><h3 class="nd_elements_margin_0_important nd_elements_font_size_23 nd_elements_line_height_1_2"><strong>'.$nd_elements_title.'</strong></h3></a>
	    				<div class="nd_elements_section nd_elements_height_10"></div>
	    				<a href="'.$nd_elements_permalink.'"><h6 class="nd_elements_margin_0_important nd_options_color_grey nd_elements_font_size_15 nd_elements_line_height_15">'.tribe_get_start_date($nd_elements_id,false,'d M Y').'</h6></a>
	    			</div>

				</div>

			</div>
			
			<div class=" nd_elements_width_50_percentage nd_elements_margin_top_20_responsive nd_elements_margin_bottom_20_responsive nd_elements_width_100_percentage_responsive nd_elements_float_left_responsive nd_elements_display_table_cell nd_elements_display_inline_responsive nd_elements_vertical_align_middle nd_elements_padding_left_50 nd_elements_padding_left_0_responsive">
				<p class="nd_elements_line_height_2">'.$nd_elements_excerpt.'</p>
			</div>

			<div class=" nd_elements_width_20_percentage nd_elements_width_100_percentage_responsive nd_elements_float_left_responsive nd_elements_display_table_cell nd_elements_display_inline_responsive nd_elements_vertical_align_middle">
				<a style="background-color:'.$eventsgrid_color_btn.';" class="nd_elements_margin_bottom_20_responsive nd_options_color_white nd_elements_padding_10_20 nd_elements_font_size_10 nd_elements_line_height_10 nd_elements_float_right nd_elements_float_left_responsive" href="'.$nd_elements_permalink.'"><strong>'.__('VIEW DETAILS','nd-elements').'</strong></a>
			</div>

    	</div>
	    ';

	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT