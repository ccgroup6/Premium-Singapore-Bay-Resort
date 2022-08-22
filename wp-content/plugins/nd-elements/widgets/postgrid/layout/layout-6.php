<?php


//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_masonry_content nd_elements_postgrid_compo_l6 ">';

	while ( $the_query->have_posts() ) : $the_query->the_post();

		//info
		$nd_elements_id = get_the_ID(); 
		$nd_elements_title = get_the_title();
		$nd_elements_excerpt = get_the_excerpt();
		$nd_elements_permalink = get_permalink( $nd_elements_id );

		//decide color - nd-shortcodes compatibility
		$nd_elements_meta_box_page_color = get_post_meta( $nd_elements_id, 'nd_options_meta_box_post_color', true );
		if ( $nd_elements_meta_box_page_color != '' ) { 
			$postgrid_color = $nd_elements_meta_box_page_color;
		}

		//image
		$nd_elements_image_id = get_post_thumbnail_id( $nd_elements_id );
		$nd_elements_image_attributes = wp_get_attachment_image_src( $nd_elements_image_id, $postgrid_image_size );

		//categories
		$nd_elements_post_categories = get_the_category($nd_elements_id);
		$nd_elements_post_categories_list = '<p class="nd_elements_margin_0_important nd_elements_postgrid_compo_l6_datecat nd_elements_font_size_12 nd_elements_padding_0 nd_elements_text_transform_uppercase nd_elements_font_weight_bold nd_elements_letter_spacing_2 nd_elements_line_height_1">'.get_the_time('j').' '.get_the_time('M').' - ';
		foreach ( $nd_elements_post_categories as $nd_elements_post_category ) {
		    $nd_elements_post_categories_list .= $nd_elements_post_category->name.' ';
		}
		$nd_elements_post_categories_list .= '</p>
		<div class="nd_elements_section nd_elements_height_20"></div>';

		/*START NORMAL POST*/
		$nd_elements_result .= '
    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

    		<div class="nd_elements_section nd_elements_background_color_fff nd_elements_box_shadow_0_0_15_0_0001 nd_elements_box_sizing_border_box nd_elements_display_table nd_elements_position_relative">

    			<div style="background-image:url('.$nd_elements_image_attributes[0].');" class="nd_elements_display_table_cell nd_elements_postgrid_compo_l6_image nd_elements_width_50_percentage nd_elements_width_100_percentage_responsive nd_elements_float_left_responsive nd_elements_height_300_responsive nd_elements_background_position_center nd_elements_background_repeat_no_repeat nd_elements_background_size_cover"></div>

    			<div class=" nd_elements_width_50_percentage nd_elements_width_100_percentage_responsive nd_elements_float_left_responsive nd_elements_postgrid_compo_l6_content nd_elements_display_table_cell nd_elements_vertical_align_middle nd_elements_padding_50 nd_elements_padding_20_iphone nd_elements_box_sizing_border_box">
    				
    				'.$nd_elements_post_categories_list.'
    				<h3 class="nd_elements_margin_0_important nd_elements_padding_0 nd_elements_font_weight_bold nd_elements_line_height_1_5 nd_elements_postgrid_compo_l6_title ">'.$nd_elements_title.'</h3>
    				<div class="nd_elements_section nd_elements_height_20"></div>
    				<p class="nd_elements_line_height_2 nd_elements_font_weight_normal nd_elements_postgrid_compo_l6_excerpt">'.$nd_elements_excerpt.'</p>
    				<div class="nd_elements_section nd_elements_height_20"></div>
    				<a class="nd_elements_line_height_1 nd_elements_padding_10_20 nd_elements_postgrid_compo_l6_btn nd_elements_font_size_12 nd_options_color_white nd_options_first_font nd_elements_font_weight_bold nd_elements_letter_spacing_2 " style="background-color:'.$postgrid_color.';" href="'.$nd_elements_permalink.'">'. __('READ MORE','nd-elements').'</a>

    			</div>

    		</div>

    	</div>';
		/*END NORMAL POST*/	


	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT