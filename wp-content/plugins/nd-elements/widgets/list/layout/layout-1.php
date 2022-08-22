<?php


//DEPENDENCY


//title
if ( $list_title != '' ) {
	$list_title_content = '
	<div class="nd_elements_float_left nd_elements_width_100_percentage_iphone_important" style="width:'.$list_content_column_width_1.'%;">
		<h4><span class="nd_elements_list_component_title">'.$list_title.'</span></h4>
	</div>
	';
}else { 
	$list_title_content = ''; 
}

//label
if ( $list_label != '' ) {
	$list_label_content = '
	<div class="nd_elements_float_left nd_elements_width_100_percentage_iphone_important nd_elements_text_align_right nd_elements_text_align_left_iphone" style="width:'.$list_content_column_width_2.'%;">
		<p class="nd_elements_list_component_label">'.$list_label.'</p>
	</div>
	';
}else { 
	$list_label_content = ''; 
}

//description
if ( $list_description != '' ) {
	$list_description_content = '
	<div class="nd_elements_float_left nd_elements_width_100_percentage_iphone_important" style="width:'.$list_content_column_width_1.'%;">
		<p class="nd_elements_list_component_description">'.$list_description.'</p>
	</div>
	';
}else { 
	$list_description_content = ''; 
}

//cta
if ( $list_cta != '' ) {
	$list_cta_content = '
		<div class="nd_elements_float_left nd_elements_margin_top_20_iphone nd_elements_text_align_right nd_elements_text_align_left_iphone nd_elements_width_100_percentage_iphone_important" style="width:'.$list_content_column_width_2.'%;">
			<a '.$list_link_nofollow.' '.$list_link_target.' href="'.$list_link_url.'"><p class="nd_elements_list_component_cta"><span>'.$list_cta.'</span></p></a>
		</div>
	';
}else { 
	$list_cta_content = ''; 
}

//image
if ( $list_image != '' ) {
	$list_image_content = '<img class="nd_elements_position_absolute nd_elements_position_initial_iphone nd_elements_top_0 nd_elements_left_0 nd_elements_list_component_image" src="'.$list_image.'">';
}else { 
	$list_image_content = ''; 
}



//START LAYOUT
$nd_elements_result .= '
<div class="nd_elements_section nd_elements_list_component">

	<div class="nd_elements_section nd_elements_position_relative">

		'.$list_image_content.'

		<div class="nd_elements_section nd_elements_list_component_content nd_elements_padding_0_iphone_important">

			'.$list_title_content.'
			'.$list_label_content.'

			<div style="height:10px;" class="nd_elements_section"></div>

			'.$list_description_content.'
			'.$list_cta_content.'
			
		</div>

	</div>

</div>';
//END LAYOUT