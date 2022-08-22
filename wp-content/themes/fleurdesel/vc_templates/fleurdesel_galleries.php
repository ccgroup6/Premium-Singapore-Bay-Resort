<?php
/**
 * Template for shortcode Fleurdesel Galleries.
 *
 * @package Fleurdesel
 */

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

// Build galleries group fields.
$galleries = (array) vc_param_group_parse_atts( $atts['galleries'] );

$galleries = array_map( function ( $gallery ) {
	return shortcode_atts( array(
		'image'             	=> '',
		'title'            	    => '',
		'group'             	=> '',
	), $gallery );
}, $galleries );

$groups = array();
foreach ( $galleries as $key => $gallery ) {
	$group = sanitize_title( $gallery['group'] );
	$groups[ $group ] = $gallery['group'];
}

// Don't output anything if see empty $galleries.
if ( empty( $galleries ) || ( count( $galleries ) === 1 && ! $galleries[0]['image'] ) ) {
	return;
}

// Build element classes.
$el_class = ( 'masonry' == $atts['layout'] ) ? 'fleurdesel-gallery--masonry' : '';
$el_class .= $this->getExtraClass( $atts['el_class'] );
$el_class .= vc_shortcode_custom_css_class( $atts['css'], ' ' );
// Pass args to template part.
$pass_args = array(
	'galleries' => $galleries,
	'atts'      => $atts,
	'groups'    => $groups,
);
?>
<div class="fleurdesel-gallery <?php echo esc_attr( $el_class ); ?>" data-init="gallery">
	<?php $this->load_template_part( 'galleries_tpl/layout-' . $atts['layout'] . '.php', $pass_args ); ?>
</div>
