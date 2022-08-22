<?php
/**
 * This file represents an example of the code
 * that themes would use to register the required plugins.
 *
 * @see https://github.com/TGMPA/TGM-Plugin-Activation/blob/develop/example.php
 *
 * @package Fleurdesel
 */

if ( ! class_exists( 'TGM_Plugin_Activation' ) ) {
	require get_template_directory() . '/inc/class-tgm-plugin-activation.php';
}

/**
 * Register the required plugins for this theme.
 */
function fleurdesel_register_required_plugins() {
	/**
	 * Array of plugin arrays. Required keys are name and slug.
	 */
	$plugins = array(
		array(
			'name'     => esc_html__( 'Fleurdesel Required', 'fleurdesel' ),
			'slug'     => 'fleurdesel-required',
			'source'   => get_template_directory_uri() . '/plugins/fleurdesel-required.zip',
			'version'  => '2.0.1',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Flexible Posts Widget', 'fleurdesel' ),
			'slug'     => 'flexible-posts-widget',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'WPBakery Visual Composer', 'fleurdesel' ),
			'slug'     => 'js_composer',
			'source'   => get_template_directory_uri() . '/plugins/js_composer.zip',
			'version'  => '6.6.0',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Slider Revolution', 'fleurdesel' ),
			'slug'     => 'revslider',
			'source'   => get_template_directory_uri() . '/plugins/revslider.zip',
			'version'  => '6.4.6',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'Awebooking', 'fleurdesel' ),
			'slug'     => 'awebooking',
			'version'  => '3.1.*',
			'required' => true,
		),
		array(
			'name'     => esc_html__( 'Contact form 7', 'fleurdesel' ),
			'slug'     => 'contact-form-7',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'MailChimp for WordPress', 'fleurdesel' ),
			'slug'     => 'mailchimp-for-wp',
			'required' => false,
		),
		array(
			'name'     => esc_html__( 'WP Simple Iconfonts', 'fleurdesel' ),
			'slug'     => 'wp-simple-iconfonts',
			'required' => true,
		),
	);

	/**
	 * Register plugins required
	 */
	tgmpa( $plugins, array(
		'id' => 'fleurdesel',
		'is_automatic' => true,
		'strings' => array( 'nag_type' => 'notice-warning' ),
	) );
}
add_action( 'tgmpa_register', 'fleurdesel_register_required_plugins' );
