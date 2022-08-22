<?php
/**
 * My Private Site by David Gewirtz, adopted from Jon ‘jonradio’ Pearkins
 *
 * Lab Notes: http://zatzlabs.com/lab-notes/
 * Plugin Page: https://zatzlabs.com/project/my-private-site/
 * Contact: http://zatzlabs.com/contact-us/
 *
 * Copyright (c) 2015-2020 by David Gewirtz
 */


// MAIN - MENU ////
function my_private_site_admin_main_menu() {
	// turn off v3.0 first-run notice
	my_private_site_clear_jr_ps_30_update_alert();

	$args = array(
		'id'           => 'my_private_site_tab_main_page',
		'title'        => 'My Private Site',
		// page title
		'menu_title'   => 'My Private Site',
		// title on left sidebar
		'tab_title'    => 'My Private Site',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',
		'icon_url'     => 'dashicons-lock',
	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_main_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_main', 'my_private_site_tab_main_process_buttons' );

	$args         = apply_filters( 'my_private_site_tab_main_menu', $args );
	$main_options = new_cmb2_box( $args );
	my_private_site_admin_main_section_data( $main_options );

	do_action( 'my_private_site_tab_main_after', $main_options );

}

// Remove primary Save button
// derived from https://github.com/CMB2/CMB2-Snippet-Library/blob/master/filters-and-actions/custom-css-for-specific-metabox.php
function my_private_site_delete_welcome_button( $post_id, $cmb ) {
	?>
	<style type="text/css" media="screen">
		input#submit-cmb.button.button-primary {
			display: none;
		}
	</style>
	<?php
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_main_menu' );

$object = 'options-page'; // could also be post | term
$cmb_id = 'my_private_site_tab_main_page';
add_action( "cmb2_after_{$object}_form_{$cmb_id}", 'my_private_site_delete_welcome_button', 10, 2 );

// MAIN - SECTION - DATA ////
function my_private_site_admin_main_section_data( $section_options ) {
	$section_options = apply_filters( 'my_private_site_tab_main_section_data', $section_options );

	$section_options->add_field(
		array(
			'name'          => 'Welcome to My Private Site Donations',
			'id'            => 'my_private_site_welcome_area',
			'type'          => 'text',
			'savetxt'       => '',
			'render_row_cb' => 'my_private_site_render_main_tab_html',
		// this builds static text as provided
		)
	);
	$section_options = apply_filters( 'my_private_site_tab_main_section_data_options', $section_options );
}

function my_private_site_render_main_tab_html( $field_args, $field ) {
	$html_folder = dirname( dirname( __FILE__ ) ) . '/html/';
	$html_file   = $html_folder . 'admin-main.html';
	$html_readme = file_get_contents( $html_file );

	$html_readme = apply_filters( 'my_private_site_admin_main_section_data_options', $html_readme );

	$allowed_html = array(
		'a'      => array(
			'href'  => array(),
			'title' => array(),
			'class' => array(),
		),
		'div'    => array(
			'id'    => array(),
			'class' => array(),
		),
		'form'   => array(
			'action' => array(),
			'method' => array(),
			'id'     => array(),
			'name'   => array(),
			'class'  => array(),
			'target' => array(),
		),
		'h3'     => array(),
		'h4'     => array(),
		'iframe' => array(
			'width'           => array(),
			'height'          => array(),
			'src'             => array(),
			'frameborder'     => array(),
			'allowfullscreen' => array(),
		),
		'img'    => array(
			'src' => array(),
		),
		'input'  => array(
			'type'     => array(),
			'value'    => array(),
			'name'     => array(),
			'class`'   => array(),
			'id'       => array(),
			'tabindex' => array(),
		),
		'label'  => array(
			'for'   => array(),
			'class' => array(),
		),
		'li'=> array(),
		'link'   => array(
			'href' => array(),
			'rel'  => array(),
			'type' => array(),
		),
		'p'      => array(),
		'script' => array(
			'type' => array(),
			'src'  => array(),
		),
		'span'   => array(
			'class' => array(),
		),
		'strong' => array(),
		'style'  => array(
			'type' => array(),
		),
		'ul'=>array(),
	);
	echo wp_kses( $html_readme, $allowed_html );

	echo $html_readme;
}

// ADDONS - PROCESS FORM SUBMISSIONS
function my_private_site_tab_main_process_buttons() {
	// Process Save changes button
	// This is a callback that has to be passed the full array for consideration
	// phpcs:ignore WordPress.Security.NonceVerification
	$_POST = apply_filters( 'validate_page_slug_my_private_site_tab_main', $_POST );
}
