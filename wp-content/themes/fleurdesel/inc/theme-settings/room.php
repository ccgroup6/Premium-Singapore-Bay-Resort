<?php
/**
 * Register room in theme options.
 *
 * @param  [type] $framework [description].
 */
function fleurdesel_room_options( $framework ) {
	$framework->add_section( 'room', function( $tab ) {

		$tab->set( array(
			'title'    => esc_html__( 'Room', 'fleurdesel' ),
			'icon'     => apply_filters( 'fleurdesel_room_settings_icon', 'dashicons-calendar' ),
			'priority' => 85,
		) );

		$tab->add_field(array(
			'id'   => 'room_area',
			'name' => esc_html__( 'Room Archive', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for Room Archive page.', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'      => 'room_layout',
			'name'    => esc_html__( 'Room layouts', 'fleurdesel' ),
			'type'    => 'select',
			'desc'    => esc_html__( 'Select a layout to show in List rooms page.', 'fleurdesel' ),
			'options' => array(
				'grid'                    => esc_html__( 'Grid (default)', 'fleurdesel' ),
				'standard'                => esc_html__( 'Standard', 'fleurdesel' ),
				'list'                    => esc_html__( 'List', 'fleurdesel' ),
				'zigzag'                  => esc_html__( 'Zigzag', 'fleurdesel' ),
				'grid_extra_info'         => esc_html__( 'Grid with extra info', 'fleurdesel' ),
				'grid_extra_info_icon'    => esc_html__( 'Grid with extra info icon', 'fleurdesel' ),
				'overlay_with_content'    => esc_html__( 'Overlay with content', 'fleurdesel' ),
				'overlay_with_extra_info' => esc_html__( 'Overlay with extra info', 'fleurdesel' ),
			),
		) );

		$tab->add_field(array(
			'id'      => 'room_content_alignment',
			'name'    => esc_html__( 'Alignment', 'fleurdesel' ),
			'type'    => 'select',
			'options' => array(
				'left'   => esc_html__( 'Left (default)', 'fleurdesel' ),
				'center' => esc_html__( 'Center', 'fleurdesel' ),
				'right'  => esc_html__( 'Right', 'fleurdesel' ),
			),
		) );

		$tab->add_field(array(
			'id'      => 'room_column',
			'name'    => esc_html__( 'Column', 'fleurdesel' ),
			'type'    => 'select',
			'options' => array(
				2 => esc_html__( '2', 'fleurdesel' ),
				3 => esc_html__( '3', 'fleurdesel' ),
				4 => esc_html__( '4', 'fleurdesel' ),
			),
			'deps'    => array( 'room_layout', 'any', 'grid,grid_extra_info,grid_extra_info_icon,overlay_with_content,overlay_with_extra_info' ),
		) );

		$tab->add_field(array(
			'id'   => 'room_detail_area',
			'name' => esc_html__( 'Room Detail', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for Room Detail page.', 'fleurdesel' ),
		) );

		$tab->add_field(array(
			'id'      => 'room_detail_layout',
			'name'    => esc_html__( 'Room detail layouts', 'fleurdesel' ),
			'type'    => 'select',
			'desc'    => esc_html__( 'Select a layout to show in Room detail page.', 'fleurdesel' ),
			'options' => array(
				'standard' => esc_html__( 'Standard', 'fleurdesel' ),
				'modern'   => esc_html__( 'Modern', 'fleurdesel' ),
			),
		) );
	} );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_room_options' );
