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

		//image
		$nd_elements_image_id = get_post_thumbnail_id( $nd_elements_id );
		$nd_elements_image_attributes = wp_get_attachment_image_src( $nd_elements_image_id, $postgrid_image_size );
		if ( $nd_elements_image_attributes[0] == '' ) { $nd_elements_output_image = ''; }else{
		  $nd_elements_output_image = '<a href="'.$nd_elements_permalink.'"><img class="nd_elements_section" alt="" src="'.$nd_elements_image_attributes[0].'"></a>';
		}

		if ( has_post_format('quote') ) {

			//get datas
			$nd_elements_meta_box_post_quote = get_post_meta( $nd_elements_id, 'nd_options_meta_box_post_quote', true );
			if ( $nd_elements_meta_box_post_quote == '' ) { $nd_elements_meta_box_post_quote = __('Insert Quote','nd-elements'); }
			$nd_elements_meta_box_post_quote_author = get_post_meta( $nd_elements_id, 'nd_options_meta_box_post_quote_author', true );
			if ( $nd_elements_meta_box_post_quote_author == '' ) { $nd_elements_meta_box_post_quote_author = __('Insert Author','nd-elements'); }

			$nd_elements_result .= '
	    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

				<div class="nd_elements_section nd_elements_position_relative">
                        
				    '.$nd_elements_output_image.'

				    <div class="nd_elements_position_absolute nd_elements_left_0 nd_elements_height_100_percentage nd_elements_width_100_percentage nd_elements_padding_20_iphone nd_elements_padding_40 nd_elements_box_sizing_border_box">

				        <div class="nd_elements_display_table nd_elements_width_100_percentage nd_elements_height_100_percentage nd_elements_text_align_center">

				            <div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">

				            	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
					    			<h3 class="nd_options_color_white nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_30 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_meta_box_post_quote.'</strong></h3>
					    		</a>
					    		<div class="nd_elements_section nd_elements_height_20"></div>
					    		<p class="nd_options_color_white nd_elements_margin_0_important nd_elements_padding_0 nd_elements_letter_spacing_1 nd_elements_font_size_13 nd_elements_text_transform_uppercase nd_elements_line_height_13 ">'.$nd_elements_meta_box_post_quote_author.'</p>
					    		<div class="nd_elements_section nd_elements_height_10"></div>
					    		<img alt="" class="" width="25" height="25" src="'.esc_url(plugins_url('img/quote-icon.png', __FILE__ )).'">
					    		
				            </div>

				        </div>

				    </div>

				</div>

			</div>';


		}elseif ( has_post_format('link') ){


			//get datas
			$nd_elements_meta_box_post_link_title = get_post_meta( $nd_options_id, 'nd_options_meta_box_post_link_title', true );
			if ( $nd_elements_meta_box_post_link_title == '' ) { $nd_elements_meta_box_post_link_title = 'www.nicdark.com'; }
			$nd_elements_meta_box_post_link_url = get_post_meta( $nd_options_id, 'nd_options_meta_box_post_link_url', true );
			if ( $nd_elements_meta_box_post_link_url == '' ) { $nd_elements_meta_box_post_link_url = 'http://www.nicdark.com'; }

			$nd_elements_result .= '
	    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

				<div class="nd_elements_section nd_elements_position_relative">
                        
				    '.$nd_elements_output_image.'

				    <div class="nd_elements_position_absolute nd_elements_left_0 nd_elements_height_100_percentage nd_elements_width_100_percentage nd_elements_padding_20_iphone nd_elements_padding_40 nd_elements_box_sizing_border_box">

				        <div class="nd_elements_display_table nd_elements_width_100_percentage nd_elements_height_100_percentage nd_elements_text_align_center">

				            <div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">

				            	<a class="nd_elements_section" href="'.$nd_elements_meta_box_post_link_url.'">
					    			<h3 class="nd_options_color_white nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_meta_box_post_link_title.'</strong></h3>
					    		</a>
					    		<div class="nd_elements_section nd_elements_height_10"></div>
					    		<img alt="" class="" width="25" height="25" src="'.esc_url(plugins_url('img/icon-link.png', __FILE__ )).'">
					    		
				            </div>

				        </div>

				    </div>

				</div>

			</div>';


		}elseif ( has_post_format('image') ){

			//categories
			$nd_elements_post_categories = get_the_category($nd_elements_id);
			foreach ( $nd_elements_post_categories as $nd_elements_post_category ) {
				$nd_elements_post_categories_list = '';
			    $nd_elements_post_categories_list .= '
			    <p style="border-left-color:#fff" class="nd_options_color_white nd_elements_margin_0_important nd_elements_padding_0 nd_elements_letter_spacing_1 nd_elements_font_size_13 nd_elements_text_transform_uppercase nd_elements_line_height_13 nd_elements_padding_left_15 nd_elements_border_left_style_solid nd_elements_border_width_2">'.$nd_elements_post_category->name.'</p>
			    <div class="nd_elements_section nd_elements_height_10"></div>
			    ';
			}


			$nd_elements_result .= '
	    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

				<div class="nd_elements_section nd_elements_position_relative">
            
			  		<div class="nd_elements_section nd_elements_position_relative">
			        	
			        	'.$nd_elements_output_image.'
			        
			        	<div style="background: -webkit-linear-gradient(top, '.$postgrid_color.'00 40%,'.$postgrid_color.'cc 100%)" class="nd_elements_position_absolute nd_elements_left_0 nd_elements_height_100_percentage nd_elements_width_100_percentage nd_elements_padding_40 nd_elements_padding_20_iphone nd_elements_box_sizing_border_box">
				    	
					    	<div class="nd_elements_position_absolute nd_elements_bottom_40 nd_elements_bottom_20_iphone">

					    		'.$nd_elements_post_categories_list.'

						    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
						    		<h3 class="nd_options_color_white nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_title.'</strong></h3>
						    	</a>

					    	</div>

						</div>


			    	</div>
			    
			    </div>

			</div>';

		}else{

			//categories
			$nd_elements_post_categories = get_the_category($nd_elements_id);
			foreach ( $nd_elements_post_categories as $nd_elements_post_category ) {
				$nd_elements_post_categories_list = '';
			    $nd_elements_post_categories_list .= '
			    <p style="border-left-color:'.$postgrid_color.'" class="nd_elements_margin_0_important nd_elements_padding_0 nd_elements_letter_spacing_1 nd_elements_font_size_13 nd_elements_text_transform_uppercase nd_elements_line_height_13 nd_elements_padding_left_15 nd_elements_border_left_style_solid nd_elements_border_width_2">'.$nd_elements_post_category->name.'</p>
			    <div class="nd_elements_section nd_elements_height_10"></div>
			    ';
			}

			/*START NORMAL POST*/
			$nd_elements_result .= '
	    	<div class=" '.$postgrid_width.' nd_elements_width_100_percentage_responsive nd_elements_float_left nd_elements_masonry_item nd_elements_padding_15 nd_elements_box_sizing_border_box">

	    		<div class="nd_elements_section nd_elements_background_color_fff nd_elements_box_shadow_0_0_15_0_0001">

		    		'.$nd_elements_output_image.'

		    		<div class="nd_elements_section nd_elements_position_relative">
			    		<div style="background-color:'.$postgrid_color.'" class="nd_elements_display_block nd_elements_width_60 nd_elements_height_60 nd_elements_text_align_center nd_elements_position_absolute nd_elements_right_30 nd_elements_top_30_negative nd_elements_top_60_negative_iphone nd_elements_right_20_iphone ">
			    			<div class="nd_elements_section nd_elements_height_10"></div>
			    			<a href="'.$nd_elements_permalink.'"><h3 class="nd_elements_font_size_23 nd_elements_line_height_23 nd_elements_section nd_elements_letter_spacing_1 nd_options_color_white nd_elements_margin_0_important nd_elements_padding_0"><strong>'.get_the_time('j').'</strong></h3></a>
			    			<div class="nd_elements_section nd_elements_height_5"></div>
			    			<a href="'.$nd_elements_permalink.'"><h6 class="nd_elements_font_size_13 nd_elements_line_height_13 nd_elements_section nd_elements_letter_spacing_1 nd_elements_text_transform_uppercase nd_options_color_white nd_elements_margin_0_important nd_elements_padding_0">'.get_the_time('F').'</h6></a>
			    		</div>
		    		</div>


		    		<div class="nd_elements_section nd_elements_padding_40 nd_elements_padding_20_iphone nd_elements_box_sizing_border_box">
		    		 

		    			'.$nd_elements_post_categories_list.'

				    	<a class="nd_elements_section" href="'.$nd_elements_permalink.'">
				    		<h3 class="nd_elements_font_size_23 nd_elements_word_break_break_word nd_elements_font_size_20_iphone nd_elements_line_height_23 nd_elements_margin_0_important nd_elements_letter_spacing_1"><strong>'.$nd_elements_title.'</strong></h3>
				    	</a>
				    	<div class="nd_elements_section nd_elements_height_20"></div>
				    	<p class="nd_elements_font_size_15 nd_elements_section nd_elements_margin_0_important nd_elements_line_height_2">'.$nd_elements_excerpt.'</p>
				    	<div class="nd_elements_section nd_elements_height_30"></div>

				    	<div class="nd_elements_section">
					    	<div class="nd_elements_float_left nd_elements_width_50_percentage nd_elements_width_100_percentage_iphone">

					    		<div class="nd_elements_display_table">
					    			<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
					    				<img alt="" class=" nd_elements_float_left nd_elements_border_radius_100_percentage_important " width="25" height="25" src="'.get_avatar_url(get_the_author_meta('ID')).'">
					    			</div>
					    			<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
					    				<p class="nd_elements_margin_0_important nd_elements_padding_left_10 nd_elements_font_size_15 nd_elements_line_height_15">'.get_the_author().'</p>
					    			</div>
					    		</div>

					    		
								
					    	</div>
					    	<div class="nd_elements_float_left nd_elements_width_50_percentage nd_elements_width_100_percentage_iphone nd_elements_margin_top_20_iphone">

					    		<div class="nd_elements_display_table">
					    			<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
					    				<img alt="" class=" nd_elements_float_left " width="25" height="25" src="'.esc_url(plugins_url('img/comment-icon.png', __FILE__ )).'">
					    			</div>
					    			<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
					    				<p class="nd_elements_margin_0_important nd_elements_padding_left_10 nd_elements_font_size_15 nd_elements_line_height_15">'.get_comments_number().' '.__('Comments','nd-elements').'</p>
					    			</div>
					    		</div>

					    	</div>
				    	</div>

				    	
						

					</div>

				</div>
		    	
			</div>';
			/*END NORMAL POST*/	


		}



	endwhile;

$nd_elements_result .= '
</div>';
//END LAYOUT