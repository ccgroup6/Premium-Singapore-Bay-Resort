<?php
/**
 * Fleurdesel Custom Layout.
 *
 * @package Fleurdesel
 */

/**
 * Get custom design meta data.
 *
 * @param  int    $page_id The page ID.
 * @param  string $key     Special key to get.
 * @return null|array
 */
function fleurdesel_get_custom_layout( $page_id = null, $key = null ) {
	$page_id = is_null( $page_id ) ? get_the_ID() : $page_id;

	if (  'page' !== get_post_type( $page_id ) ) {
		return;
	}

	$metadata = (array) get_post_meta( $page_id, 'fleurdesel_custom_layout', true );
	$metadata['site_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_site_logo', true );
	$metadata['header_minimal_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_header_minimal_logo', true );
	$metadata['header_classic_no_transparent_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_header_classic_no_transparent_logo', true );
	$metadata['header_centered_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_header_centered_logo', true );
	$metadata['footer_bg_image'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_footer_bg_image', true );
	$metadata['footer_left_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_footer_left_logo', true );
	$metadata['footer_centered_logo'] = get_post_meta( $page_id, 'fleurdesel_custom_layout_footer_centered_logo', true );

	if ( $key ) {
		return isset( $metadata[ $key ] ) ? $metadata[ $key ] : null;
	}
	return $metadata;
}

/**
 * Custom layout handler.
 *
 * @param  string $value Default option value.
 * @param  string $name  Option name.
 * @return mixed
 */
function fleurdesel_custom_layout_handler( $value, $name ) {
	$metadata = fleurdesel_get_custom_layout();
	if ( ! $metadata ) {
		return $value;
	}

	$custom_value = isset( $metadata[ $name ] ) ? $metadata[ $name ] : null;
	switch ( $name ) {
		// Select controls.
		case 'header_layout':
		case 'fs_panel_left_sidebar':
		case 'fs_panel_right_sidebar':
		case 'footer_layout':
		case 'footer_bg_type':
		case 'centered_logo_footer_column':
		case 'centered_logo_footer_sidebar_1':
		case 'centered_logo_footer_sidebar_2':
		case 'centered_logo_footer_sidebar_3':
		case 'classic_footer_column':
		case 'classic_footer_sidebar_1':
		case 'classic_footer_sidebar_2':
		case 'classic_footer_sidebar_3':
		case 'classic_footer_sidebar_4':
			if ( $custom_value ) {
				return $custom_value;
			}
			break;

		// Image controls.
		case 'site_logo':
		case 'header_minimal_logo':
		case 'header_classic_no_transparent_logo':
		case 'header_centered_logo':
		case 'footer_bg_image':
		case 'footer_left_logo':
		case 'footer_centered_logo':
			if ( $custom_value ) {
				return $custom_value;
			}
			break;

		// Toggle controls.
		case 'show_footer_social':
		case 'show_footer_centered_logo_columns':
		case 'show_footer_classic_columns':
		case 'header_sticky':
			if ( null != $custom_value ) {
				return $custom_value;
			}
			break;

		// Text controls.
		case 'header_top_left_content':
		case 'header_top_right_content':
		case 'header_slider':
		case 'copyright':
		case 'right_text':
		case 'footer_bg_color':
			if ( $custom_value ) {
				return $custom_value;
			}
			break;
	} // End switch().

	return $value;
}
add_filter( 'fleurdesel_option', 'fleurdesel_custom_layout_handler', 10, 2 );
