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

// MENU ////
function my_private_site_admin_selective_content_menu() {
	$args = array(
		'id'           => 'my_private_site_tab_selective_content_page',
		'title'        => 'My Private Site - Shortcodes',
		// page title
		'menu_title'   => 'Shortcodes',
		// title on left sidebar
		'tab_title'    => 'Shortcodes',
		// title displayed on the tab
		'object_types' => array( 'options-page' ),
		'option_key'   => 'my_private_site_tab_selective_content',
		'parent_slug'  => 'my_private_site_tab_main',
		'tab_group'    => 'my_private_site_tab_set',

	);

	// 'tab_group' property is supported in > 2.4.0.
	if ( version_compare( CMB2_VERSION, '2.4.0' ) ) {
		$args['display_cb'] = 'my_private_site_cmb_options_display_with_tabs';
	}

	do_action( 'my_private_site_tab_selective_content_before', $args );

	// call on button hit for page save
	add_action( 'admin_post_my_private_site_tab_selective_content', 'my_private_site_tab_selective_content_process_buttons' );

	// clear previous error messages if coming from another page
	my_private_site_clear_cmb2_submit_button_messages( $args['option_key'] );

	$args          = apply_filters( 'my_private_site_tab_selective_content_menu', $args );
	$addon_options = new_cmb2_box( $args );

	my_private_site_admin_selective_content_shortcodes_section_data( $addon_options );

	do_action( 'my_private_site_tab_selective_content_after', $addon_options );
}

add_action( 'cmb2_admin_init', 'my_private_site_admin_selective_content_menu' );

function my_private_site_admin_selective_content_shortcodes_section_data( $section_options ) {
	$handler_function = 'my_private_site_admin_selective_content_preload'; // setup the preload handler function

	$section_options = apply_filters( 'my_private_site_tab_selective_content_section_data', $section_options );

	$section_desc = '<i>Hide areas of content based on user access conditions.</i><br>';

	// promo
	$feature_desc  = 'Selective Content allows you to hide, scramble, and truncate blocks of text based ';
	$feature_desc .= 'on login, editor, or admin status. Also allows you to selectively hide widgets ';
	$feature_desc .= 'or entire sidebars based on access conditions.';
	$feature_url   = 'https://zatzlabs.com/project/my-private-site-plugins-and-extensions/';
	$section_desc .= my_private_site_get_feature_promo( $feature_desc, $feature_url, 'UPGRADE', ' ' );

	$section_desc .= '<br><br><B >SYNTAX:</B> PRIVACY HIDE-IF<br>';
	$section_desc .= '<B>HIDE-IF PARAMETERS:</B> logged-in, logged-out <br>';
	$section_desc .= '<B>EXAMPLES:</B><br>';
	$section_desc .= '<div style="margin-top: 10px; background-color:darkslategrey; padding:8px">';
	$section_desc .= '<span style="color:#fdd79a">[privacy hide-if="logged-in"]</span>';
	$section_desc .= '<span style="color:white">This will be hidden if the user is logged in.</span>';
	$section_desc .= '<span style="color:#fdd79a">[/privacy]</span><br>';
	$section_desc .= '<span style="color:#fdd79a">[privacy hide-if="logged-out"]</span>';
	$section_desc .= '<span style="color:white">This will be hidden if the user is logged out.</span>';
	$section_desc .= '<span style="color:#fdd79a">[/privacy]</span><br>';
	$section_desc .= '</div>';

	$section_options->add_field(
		array(
			'name'        => 'Manage Access With Shortcodes',
			'id'          => 'jr_ps_admin_selective_shortcodes_title',
			'type'        => 'title',
			'after_field' => $section_desc,
		)
	);

	$section_options = apply_filters( 'my_private_site_tab_selective_content_section_data_options', $section_options );
}



