<?php
/**
 * Register event settings.
 *
 * @param  Object $framework object.
 *
 * @package Fleurdesel
 */

// if ( ! class_exists( 'Fleurdesel_Event_Post_Type' ) ) {
// 	return;
// }

/**
 * Register Event options.
 *
 * @param  void $framework frame.
 * @return void
 */
function fleurdesel_event_options( $framework ) {
	$framework->add_section( 'event', function( $tab ) {

		$tab->set( array(
			'title'    => esc_html__( 'Event', 'fleurdesel' ),
			'icon'     => 'dashicons-calendar-alt',
			'priority' => 50,
		) );

		$tab->add_field(array(
			'id'   => 'event_archive_area',
			'name' => esc_html__( 'Archive Event', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for archive event page.', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'      => 'event_column',
			'name'    => esc_html__( 'Event column', 'fleurdesel' ),
			'type'    => 'select',
			'options' => array(
				'1' => esc_html__( '1', 'fleurdesel' ),
				'2' => esc_html__( '2', 'fleurdesel' ),
				'3' => esc_html__( '3', 'fleurdesel' ),
			),
		));

		$tab->add_field( array(
			'id'   => 'event_page_title_bg_color',
			'name' => esc_html__( 'Page title Background color', 'fleurdesel' ),
			'type' => 'rgba_colorpicker',
			'desc' => esc_html__( 'This option works if "Page title > Show page title?" chosen.', 'fleurdesel' ),
		) );

		$tab->add_field( array(
			'id'   => 'event_page_title_bg_image',
			'name' => esc_html__( 'Page title Background image', 'fleurdesel' ),
			'type' => 'file',
			'desc' => esc_html__( 'This option works if "Page title > Show page title?" chosen.', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'   => 'event_single_area',
			'name' => esc_html__( 'Single Event', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for single event page.', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'      => 'event_detail_layout',
			'name'    => esc_html__( 'Single Event Layout', 'fleurdesel' ),
			'type'    => 'select',
			'options' => array(
				'right' => esc_html__( 'Right', 'fleurdesel' ),
				'left'  => esc_html__( 'Left', 'fleurdesel' ),
			),
		));

	} );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_event_options' );
