<?php

//START LAYOUT
$nd_elements_result .= '

<div class="nd_elements_section nd_elements_woocart_component">

	<div class="nd_elements_display_inline_block">

		<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
			<img class="nd_elements_float_left" src="'.$nd_elements_settings['woocart_image']['url'].'">
		</div>

		<div class="nd_elements_display_table_cell nd_elements_vertical_align_middle">
			<a class="nd_elements_woocart_component_long nd_elements_display_none" href="'.$nd_elements_cart_url.'">
				'.esc_html('Cart','nd-elements').' : '.$nd_elements_cart_count.' Items - '.$nd_elements_cart_total.'
			</a>
			<a class="nd_elements_woocart_component_short" href="'.$nd_elements_cart_url.'">
				'.esc_html('Cart','nd-elements').' ('.$nd_elements_cart_count.')
			</a>
		</div>

	</div>

</div>';
//END LAYOUT