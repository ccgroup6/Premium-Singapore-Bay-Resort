<?php


//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_masonry_content nd_elements_posgrid_widget_l2">';

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
		
		//categories
		$nd_elements_post_categories = get_the_category($nd_elements_id);
		foreach ( $nd_elements_post_categories as $nd_elements_post_category ) {
			$nd_elements_post_categories_list = '';
		    $nd_elements_post_categories_list .= '
		    <p style="border-left-color:'.$postgrid_color.'" class="nd_elements_margin_0_important nd_elements_padding_0 nd_elements_posgrid_widget_l2_cat nd_elements_letter_spacing_1 nd_elements_font_size_13 nd_elements_text_transform_uppercase nd_elements_line_height_13 nd_elements_padding_left_15 nd_elements_border_left_style_solid nd_elements_border_width_2">'.$nd_elements_post_category->name.'</p>
		    <div class="nd_elements_section nd_elements_height_10"></div>
		    ';
		}

		/*START NORMAL POST*/
		$nd_elements_result .= '
    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

    		<div class="nd_elements_section nd_elements_background_color_fff nd_elements_box_shadow_0_0_15_0_0001">

	    		<div class="nd_elements_section nd_elements_padding_40 nd_elements_padding_20_iphone nd_elements_box_sizing_border_box">
	    		 
	    			'.$nd_elements_post_categories_list.'

			    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
			    		<h3 class="nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_posgrid_widget_l2_title nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_title.'</strong></h3>
			    	</a>
			    	
			    	<div class="nd_elements_section nd_elements_height_20"></div>
			    	<p class="nd_elements_font_size_15 nd_elements_section nd_elements_posgrid_widget_l2_excerpt nd_elements_margin_0_important">'.$nd_elements_excerpt.'</p>
			    	<div class="nd_elements_section nd_elements_height_20"></div>
			    	
			    	<a class="nd_elements_padding_10_20 nd_options_color_white nd_elements_font_size_13 nd_elements_posgrid_widget_l2_button nd_elements_line_height_13" style="background-color:'.$postgrid_color.';" href="'.$nd_elements_permalink.'"><strong>'.__('READ MORE','nd-elements').'</strong></a>
			    	
				</div>

			</div>
	    	
		</div>';
		/*END NORMAL POST*/	



	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT