<?php

$options = array();

$options[] = array(
	'id'          => 'jeg[module_loader]',
	'option_type' => 'option',
	'transport'   => 'postMessage',
	'default'     => 'dot',
	'type'        => 'jeg-select',
	'label'       => esc_html__( 'Module Loader Style', 'jeg-element' ),
	'description' => esc_html__( 'Choose loader style for general module element.', 'jeg-element' ),
	'choices'     => array(
		'dot'    => esc_html__( 'Dot', 'jeg-element' ),
		'circle' => esc_html__( 'Circle', 'jeg-element' ),
		'square' => esc_html__( 'Square', 'jeg-element' ),
	),
	'output'      => array(
		array(
			'method'   => 'class-masking',
			'element'  => '.module-overlay .preloader_type',
			'property' => array(
				'dot'    => 'preloader_dot',
				'circle' => 'preloader_circle',
				'square' => 'preloader_square',
			),
		),
	)
);

return $options;
