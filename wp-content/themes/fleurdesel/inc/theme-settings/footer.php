<?php
/**
 * Register footer settings.
 *
 * @param  Object $framework object.
 */
function fleurdesel_footer_options( $framework ) {
	$framework->add_section( 'footer', function( $tab ) {

		$sidebars = fleurdesel_get_sidebars();
		$opt_sidebars = array();

		foreach ( $sidebars as $key => $sidebar ) {
			$opt_sidebars[ $key ] = $sidebar['name'];
		}

		$tab->set( array(
			'title'    => esc_html__( 'Footer', 'fleurdesel' ),
			'icon'     => 'dashicons-welcome-widgets-menus',
			'priority' => 20,
		) );

		$tab->add_field(array(
			'id'   => 'footer_area',
			'name' => esc_html__( 'Footer', 'fleurdesel' ),
			'type' => 'title',
			'desc' => esc_html__( 'Settings for footer', 'fleurdesel' ),
		) );

		// General settings.
		$tab->add_field( array(
			'id'   => 'copyright',
			'name' => esc_html__( 'Copyright', 'fleurdesel' ),
			'type' => 'textarea',
		));

		$tab->add_field( array(
			'id'   => 'show_footer_social',
			'name' => esc_html__( 'Social follow | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
			'deps' => array( 'footer_layout', '!=', 'classic' ),
		));

		$tab->add_field(array(
			'id'      => 'footer_layout',
			'name'    => esc_html__( 'Footer layout', 'fleurdesel' ),
			'type'    => 'select',
			'desc'    => esc_html__( 'Select a layout to show in Footer page.', 'fleurdesel' ),
			'options' => array(
				'classic'       => esc_html__( 'Classic', 'fleurdesel' ),
				'left-logo'     => esc_html__( 'Minimal with left logo', 'fleurdesel' ),
				'centered-logo' => esc_html__( 'Minimal with centered logo', 'fleurdesel' ),
			),
		) );

		$tab->add_field( array(
			'id'   => 'right_text',
			'name' => esc_html__( 'Right text', 'fleurdesel' ),
			'type' => 'textarea',
			'deps' => array( 'footer_layout', '==', 'classic' ),
		));

		$tab->add_field( array(
			'name'    => esc_html__( 'Footer Background Type', 'fleurdesel' ),
			'id'      => 'footer_bg_type',
			'type'    => 'select',
			'options' => array(
				'color' => esc_html__( 'Color', 'fleurdesel' ),
				'image' => esc_html__( 'Image', 'fleurdesel' ),
			),
		) );

		$tab->add_field( array(
			'id'   => 'footer_bg_color',
			'name' => esc_html__( 'Background color', 'fleurdesel' ),
			'type' => 'rgba_colorpicker',
			'deps' => array( 'footer_bg_type', '==', 'color' ),
		) );

		$tab->add_field( array(
			'id'   => 'footer_bg_image',
			'name' => esc_html__( 'Background image', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'footer_bg_type', '==', 'image' ),
		) );

		// Layout left logo settings.
		$tab->add_field( array(
			'id'   => 'footer_left_logo',
			'name' => esc_html__( 'Footer left logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'footer_layout', '==', 'left-logo' ),
		) );

		// Layout centered logo settings.
		$tab->add_field( array(
			'id'   => 'footer_centered_logo',
			'name' => esc_html__( 'Footer centered logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'footer_layout', '==', 'centered-logo' ),
		) );

		$tab->add_field( array(
			'id'   => 'show_footer_centered_logo_columns',
			'name' => esc_html__( 'Footer columns | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
			'deps' => array( 'footer_layout', '==', 'centered-logo' ),
		));

		$tab->add_field( array(
			'name'    => esc_html__( 'Footer Columns', 'fleurdesel' ),
			'id'      => 'centered_logo_footer_column',
			'type'    => 'select',
			'deps'    => array( 'footer_layout|show_footer_centered_logo_columns', '==|==', 'centered-logo|1' ),
			'options' => array(
				'1' => esc_html__( '1', 'fleurdesel' ),
				'2' => esc_html__( '2', 'fleurdesel' ),
				'3' => esc_html__( '3', 'fleurdesel' ),
			),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Centered Logo Footer Sidebar 1', 'fleurdesel' ),
			'id'      => 'centered_logo_footer_sidebar_1',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_centered_logo_columns', '==|==', 'centered-logo|1' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Centered Logo Footer Sidebar 2', 'fleurdesel' ),
			'id'      => 'centered_logo_footer_sidebar_2',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_centered_logo_columns', '==|==', 'centered-logo|1' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Centered Logo Footer Sidebar 3', 'fleurdesel' ),
			'id'      => 'centered_logo_footer_sidebar_3',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_centered_logo_columns', '==|==', 'centered-logo|1' ),
		) );

		$tab->add_field( array(
			'id'   => 'show_footer_classic_columns',
			'name' => esc_html__( 'Footer columns | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
			'deps' => array( 'footer_layout', '==', 'classic' ),
		));

		$tab->add_field( array(
			'name'    => esc_html__( 'Footer Columns', 'fleurdesel' ),
			'id'      => 'classic_footer_column',
			'type'    => 'select',
			'deps'    => array( 'footer_layout|show_footer_classic_columns', '==|==', 'classic|1' ),
			'options' => array(
				'1' => esc_html__( '1', 'fleurdesel' ),
				'2' => esc_html__( '2', 'fleurdesel' ),
				'3' => esc_html__( '3', 'fleurdesel' ),
				'4' => esc_html__( '4', 'fleurdesel' ),
			),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Classic Footer Sidebar 1', 'fleurdesel' ),
			'id'      => 'classic_footer_sidebar_1',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_classic_columns', '==|==', 'classic|1' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Classic Footer Sidebar 2', 'fleurdesel' ),
			'id'      => 'classic_footer_sidebar_2',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_classic_columns', '==|==', 'classic|1' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Classic Footer Sidebar 3', 'fleurdesel' ),
			'id'      => 'classic_footer_sidebar_3',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_classic_columns', '==|==', 'classic|1' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Classic Footer Sidebar 4', 'fleurdesel' ),
			'id'      => 'classic_footer_sidebar_4',
			'type'    => 'select',
			'options' => $opt_sidebars,
			'deps'    => array( 'footer_layout|show_footer_classic_columns', '==|==', 'classic|1' ),
		) );

	}, 20 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_footer_options' );

/**
 * Register backup.
 *
 * @param   Object $framework object.
 */
function fleurdesel_backup_options( $framework ) {
	$framework->add_section( 'backup', function( $tab ) {
		$tab->set( array(
			'title'    => esc_html__( 'Backup', 'fleurdesel' ),
			'icon'     => 'dashicons-schedule',
			'priority' => 200,
		) );

		$tab->add_field( array(
			'id'   => 'backups',
			'type' => 'backups',
		) );
	}, 200 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_backup_options' );
