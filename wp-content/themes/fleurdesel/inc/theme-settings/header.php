<?php
/**
 * Register header option.
 *
 * @param  Theme_Options object $framework Theme_Options object.
 */
function fleurdesel_options_header( $framework ) {
	$framework->add_panel( 'header', array(
		'title'    => esc_html__( 'Header', 'fleurdesel' ),
		'icon'     => 'dashicons-welcome-widgets-menus',
		'priority' => 10,
	) );

	$framework->add_section( 'header_desktop', function( $tab ) {
		$opt_sidebars = fleurdesel_get_sidebars_option();

		$tab->set( array(
			'title' => esc_html__( 'Header desktop', 'fleurdesel' ),
			'panel' => 'header',
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Header layout', 'fleurdesel' ),
			'desc'    => esc_html__( 'Select header layout', 'fleurdesel' ),
			'id'      => 'header_layout',
			'type'    => 'select',
			// 'default' => fleurdesel_default( 'header_layout' ),
			'options' => array(
				'classic'                => esc_html__( 'Classic header', 'fleurdesel' ),
				'minimal_fullscreen'     => esc_html__( 'Minimal with fullscreen menu', 'fleurdesel' ),
				'minimal_side'           => esc_html__( 'Mobile menu', 'fleurdesel' ),
				'classic_no_transparent' => esc_html__( 'Classic without transparent', 'fleurdesel' ),
				'classic_centered_logo'  => esc_html__( 'Classic with centered logo', 'fleurdesel' ),
				'classic_slider'         => esc_html__( 'With slider', 'fleurdesel' ),
			),
		) );

		$tab->add_field( array(
			'id'   => 'header_sticky',
			'name' => esc_html__( 'Header Sticky', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
			'deps' => array( 'header_layout', 'any', 'classic,classic_no_transparent' ),
		));

		// Header classic.
		$tab->add_field( array(
			'id'   => 'site_logo',
			'name' => esc_html__( 'Header classic logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'header_layout', '==', 'classic' ),
		) );

		// Header top.
		$tab->add_field( array(
			'name' => esc_html__( 'Header top left content', 'fleurdesel' ),
			'id'   => 'header_top_left_content',
			'type' => 'wysiwyg',
			'deps' => array( 'header_layout|header_layout', '!=|!=', 'minimal_fullscreen|minimal_side' ),
		) );

		$tab->add_field( array(
			'name' => esc_html__( 'Header top right content', 'fleurdesel' ),
			'id'   => 'header_top_right_content',
			'type' => 'wysiwyg',
			'deps' => array( 'header_layout|header_layout', '!=|!=', 'minimal_fullscreen|minimal_side' ),
		) );

		// Layout minimal_fullscreen.
		$tab->add_field( array(
			'id'   => 'header_minimal_logo',
			'name' => esc_html__( 'Header minimal logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'header_layout', '==', 'minimal_fullscreen' ),
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Fullscreen panel left sidebar', 'fleurdesel' ),
			'id'      => 'fs_panel_left_sidebar',
			'type'    => 'select',
			'deps'    => array( 'header_layout', '==', 'minimal_fullscreen' ),
			'options' => $opt_sidebars,
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Fullscreen panel right sidebar', 'fleurdesel' ),
			'id'      => 'fs_panel_right_sidebar',
			'type'    => 'select',
			'deps'    => array( 'header_layout', '==', 'minimal_fullscreen' ),
			'options' => $opt_sidebars,
		) );

		// Layout classic no transparent.
		$tab->add_field( array(
			'id'   => 'header_classic_no_transparent_logo',
			'name' => esc_html__( 'Header classic no transparent logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'header_layout', '==', 'classic_no_transparent' ),
		) );

		// Layout centered logo.
		$tab->add_field( array(
			'id'   => 'header_centered_logo',
			'name' => esc_html__( 'Header centered logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
			'deps' => array( 'header_layout', '==', 'classic_centered_logo' ),
		) );

		// Layout with slider.
		$tab->add_field( array(
			'id'   => 'header_slider',
			'name' => esc_html__( 'Header slider shortcode', 'fleurdesel' ),
			'type' => 'textarea',
			'deps' => array( 'header_layout', '==', 'classic_slider' ),
		) );
	}, 20 );

	$framework->add_section( 'header_mobile', function( $tab ) {
		$opt_sidebars = fleurdesel_get_sidebars_option();

		$tab->set( array(
			'title' => esc_html__( 'Header mobile', 'fleurdesel' ),
			'panel' => 'header',
		) );

		// Layout minimal_side, mobile menu.
		$tab->add_field( array(
			'id'   => 'small_logo',
			'name' => esc_html__( 'Mobile small panel logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
		) );

		$tab->add_field( array(
			'id'   => 'header_mobile_show_book_btn',
			'name' => esc_html__( 'Book button | Show', 'fleurdesel' ),
			'type' => 'toggle',
			'desc' => esc_html__( 'Off / On', 'fleurdesel' ),
		));

		$tab->add_field( array(
			'id'   => 'side_logo',
			'name' => esc_html__( 'Mobile side panel logo', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
		) );

		$tab->add_field( array(
			'id'   => 'bg_side_logo',
			'name' => esc_html__( 'Mobile side panel logo background', 'fleurdesel' ),
			'desc' => esc_html__( 'Select an image.', 'fleurdesel' ),
			'type' => 'file',
		) );

		$tab->add_field( array(
			'name'    => esc_html__( 'Mobile side panel sidebar', 'fleurdesel' ),
			'id'      => 'side_panel_sidebar',
			'type'    => 'select',
			'options' => $opt_sidebars,
		) );
	}, 20 );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_options_header' );
