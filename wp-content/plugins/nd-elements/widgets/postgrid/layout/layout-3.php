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
		    <p class="nd_elements_section nd_elements_margin_0_important nd_elements_padding_0 nd_elements_letter_spacing_1 nd_elements_font_size_13 nd_elements_text_transform_uppercase nd_elements_line_height_13">'.$nd_elements_post_category->name.'</p>
		    ';
		}

		//image
		$nd_elements_image_id = get_post_thumbnail_id( $nd_elements_id );
		$nd_elements_image_attributes = wp_get_attachment_image_src( $nd_elements_image_id, $postgrid_image_size );
		if ( $nd_elements_image_attributes[0] == '' ) { $nd_elements_output_image = ''; }else{
		  $nd_elements_output_image = '<a href="'.$nd_elements_permalink.'"><img width="100" class="nd_elements_position_absolute nd_elements_top_0 nd_elements_left_0" alt="" src="'.$nd_elements_image_attributes[0].'"></a>';
		}

		/*START NORMAL POST*/
		$nd_elements_result .= '
    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

    		<div class="nd_elements_section nd_elements_position_relative ">

    			'.$nd_elements_output_image.'

	    		<div class="nd_elements_section nd_elements_padding_left_120 nd_elements_box_sizing_border_box">

			    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
			    		<h4 class="nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_title.'</strong></h4>
			    	</a>
			    	
			    	<div class="nd_elements_section nd_elements_height_15"></div>

			    	'.$nd_elements_post_categories_list.'

			    	<div class="nd_elements_section nd_elements_height_20"></div>
			    	
			    	<a class="nd_elements_padding_5_10 nd_options_color_white nd_elements_font_size_13 nd_elements_line_height_13" style="background-color:'.$postgrid_color.';" href="'.$nd_elements_permalink.'"><strong>'.__('READ MORE','nd-elements').'</strong></a>

			    	'.$test.'
			    	
				</div>

			</div>
	    	
		</div>';
		/*END NORMAL POST*/	



	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT