<?php
/**
 * Implementation of the Custom Header feature as Page Title.
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Fleurdesel
 */

use Skeleton\Metabox;

/**
 * Parse custom page title
 *
 * @param  [array] $metadata meta data.
 * @return [array]           meta data.
 */
function fleurdesel_parse_custom_pagetitle( $metadata ) {

	unset( $metadata['is_custom_page_title'] );

	$metadata = wp_parse_args( $metadata, array(
		'page_title_bg_color' => fleurdesel_default( 'page_title_bg_color' ),
		'page_title_bg_image' => fleurdesel_default( 'page_title_bg_image' ),
		'page_title_show'     => fleurdesel_default( 'page_title_show' ),
	) );

	if ( is_numeric( $metadata['page_title_bg_image'] ) ) {
		$metadata['page_title_bg_image'] = wp_get_attachment_image_url( $metadata['page_title_bg_image'], 'full' );
	}

	return $metadata;
}

/**
 * Register page title in theme options.
 *
 * @param  [type] $framework [description].
 */
function fleurdesel_page_title_options( $framework ) {
	$framework->add_section( 'page_title', function( $tab ) {
		$tab->add_field(array(
			'id'   => 'page_title_show',
			'name' => esc_html__( 'Show page title?', 'fleurdesel' ),
			'type' => 'toggle',
		) );

		$tab->set( array(
			'title'    => esc_html__( 'Page title', 'fleurdesel' ),
			'icon'     => 'dashicons-schedule',
			'priority' => 30,
		) );

		$tab->add_field( array(
			'id'   => 'page_title_bg_color',
			'name' => esc_html__( 'Background color', 'fleurdesel' ),
			'type' => 'rgba_colorpicker',
			'deps' => array( 'page_title_show', '==', '1' ),
		) );

		$tab->add_field( array(
			'id'   => 'page_title_bg_image',
			'name' => esc_html__( 'Background image', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'page_title_show', '==', '1' ),
		) );

	}, 60 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_page_title_options' );

/**
 * Register metabox and term-metabox for blog header.
 * */
function fleurdesel_page_title_metabox() {

	$page_title = new Metabox( 'fleurdesel-page-title', array(
		'id'           => 'fleurdesel-page-title',
		'title'        => esc_html__( 'Page title', 'fleurdesel' ),
		'object_types' => apply_filters( 'fleurdesel_page_title_pt_support', array( 'page', 'post', 'fl_event' ) ),
		'taxonomies'   => apply_filters( 'fleurdesel_page_title_tax_support', array( 'category', 'tags' ) ),
		// 'context'   => 'side',
		'priority'     => 'low',
	) );

	$page_title->add_field( array(
		'name' => esc_html__( 'Use custom page title setting?', 'fleurdesel' ),
		'id'   => 'is_custom_page_title',
		'type' => 'toggle',
	) );

	$page_title->add_field( array(
		'id'   => 'page_title_bg_color',
		'name' => esc_html__( 'Background color', 'fleurdesel' ),
		'type' => 'rgba_colorpicker',
		'deps' => array( 'is_custom_page_title|page_title_show', '==|==', '1|1' ),
	) );

	$page_title->add_field( array(
		'id'   => 'page_title_bg_image',
		'name' => esc_html__( 'Background image', 'fleurdesel' ),
		'type' => 'file',
		'deps' => array( 'is_custom_page_title|page_title_show', '==|==', '1|1' ),
	) );

	$page_title->add_field(array(
		'id'   => 'page_title_show',
		'name' => esc_html__( 'Show page title?', 'fleurdesel' ),
		'type' => 'checkbox',
		'deps' => array( 'is_custom_page_title', '==', '1' ),
	) );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_page_title_metabox' );
