<?php
/**
 * Register general settings.
 *
 * @param  Object $framework object.
 *
 * @package Fleurdesel
 */
function fleurdesel_general_options( $framework ) {
	$framework->add_section( 'general', function( $tab ) {
		$tab->set( array(
			'title'    => esc_html__( 'General', 'fleurdesel' ),
			'icon'     => 'dashicons-dashboard',
			'priority' => 5,
		) );

		$tab->add_field( array(
			'id'         => 'favicon',
			'name'       => esc_html__( 'Favicon', 'fleurdesel' ),
			'type'       => 'text',
			'save_field' => false,
			'desc'       => esc_html__( 'Please go to WP Admin > Appearance > Customize > Site Identity > Site Icon to change favicon.', 'fleurdesel' ),
			'attributes' => array(
				'disabled' => 'disabled',
				'readonly' => 'readonly',
			),
		));

	} );
}
add_action( 'awethemes/theme_options/registers', 'fleurdesel_general_options' );
