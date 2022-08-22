<?php


$nd_elements_rand_id = rand(0,1000);


//START LAYOUT
$nd_elements_result .= '

<style>
  
#nd_elements_beforeafter_component_'.$nd_elements_rand_id.' img { float:left; width:100%; }

#nd_elements_beforeafter_component_'.$nd_elements_rand_id.' { 
float: left;
width: 100% !important;
margin: 0px !important;
padding: 0px !important;
position: relative;
}

#nd_elements_beforeafter_component_'.$nd_elements_rand_id.' .ui-slider-range {
background-image: url("'.$nd_elements_image_after.'");
background-color: #000;
z-index: 9;
position: absolute;
height: 100%;
background-repeat: no-repeat;
background-size: cover;
}

#nd_elements_beforeafter_component_'.$nd_elements_rand_id.' .ui-slider-handle {
background-color: #000;
width: 4px;
position: absolute;
height: 100%;
outline: 0px;
cursor: ew-resize;  
}

#nd_elements_beforeafter_component_'.$nd_elements_rand_id.' .ui-slider-handle:after {
content: "";
width: 40px;
height: 40px;
background-color: #ccc;
position: absolute;
top: 50%;
margin-top: -20px;
margin-left: -18px;
z-index: 9;
border-radius: 100%;
background-image:url("'.$nd_elements_icon.'");
background-size: 30px;
background-position: center;
background-repeat: no-repeat;
}

</style>


<script>
jQuery( function() {

jQuery( "#nd_elements_beforeafter_component_'.$nd_elements_rand_id.'" ).slider({
  value: 50,
  orientation: "horizontal",
  range: "min",
  animate: true
});

} );
</script>


<div class="nd_elements_section nd_elements_beforeafter_component">

	<div class="nd_elements_section">
		<div id="nd_elements_beforeafter_component_'.$nd_elements_rand_id.'">
			<img src="'.$nd_elements_image_before.'">
		</div>
	</div>
	
</div>';
//END LAYOUT