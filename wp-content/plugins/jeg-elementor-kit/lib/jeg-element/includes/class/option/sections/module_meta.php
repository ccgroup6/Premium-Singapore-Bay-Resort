<?php

$options = array();

$options[] = array(
	'id'          => 'jeg[block_notice]',
	'option_type' => 'option',
	'type'        => 'jeg-alert',
	'default'     => 'info',
	'label'       => esc_html__( 'Notice', 'jeg-element' ),
	'description' => wp_kses( __(
		'<ul>
            <li>Every element will behave differently when option changed depend on default meta on each element</li>
        </ul>',
		'jeg-element' ), wp_kses_allowed_html() ),
);

$options[] = array(
	'id'          => 'jeg[show_block_meta]',
	'option_type' => 'option',
	'transport'   => 'refresh',
	'default'     => true,
	'type'        => 'jeg-toggle',
	'label'       => esc_html__( 'Show Block Meta', 'jeg-element' ),
	'description' => esc_html__( 'Show meta for block', 'jeg-element' ),
);

$options[] = array(
	'id'              => 'jeg[show_block_meta_author]',
	'option_type'     => 'option',
	'transport'       => 'refresh',
	'default'         => true,
	'type'            => 'jeg-toggle',
	'label'           => esc_html__( 'Show Block Meta - Author', 'jeg-element' ),
	'description'     => esc_html__( 'Show author on meta block', 'jeg-element' ),
	'active_callback' => array(
		array(
			'setting'  => 'jeg[show_block_meta]',
			'operator' => '==',
			'value'    => true,
		)
	),
);

$options[] = array(
	'id'              => 'jeg[show_block_meta_date]',
	'option_type'     => 'option',
	'transport'       => 'refresh',
	'default'         => true,
	'type'            => 'jeg-toggle',
	'label'           => esc_html__( 'Show Block Meta - Date', 'jeg-element' ),
	'description'     => esc_html__( 'Show date on meta block', 'jeg-element' ),
	'active_callback' => array(
		array(
			'setting'  => 'jeg[show_block_meta]',
			'operator' => '==',
			'value'    => true,
		)
	),
);

$options[] = array(
	'id'              => 'jeg[global_post_date]',
	'option_type'     => 'option',
	'transport'       => 'refresh',
	'default'         => 'modified',
	'type'            => 'jeg-select',
	'label'           => esc_html__( 'Post Date Meta', 'jeg-element' ),
	'description'     => esc_html__( 'Choose which post date type that you want to show for global post date meta.', 'jeg-element' ),
	'choices'         => array(
		'publish'  => esc_attr__( 'Publish Date', 'jeg-element' ),
		'modified' => esc_attr__( 'Modified Date', 'jeg-element' ),
	),
	'active_callback' => array(
		array(
			'setting'  => 'jeg[show_block_meta]',
			'operator' => '==',
			'value'    => true,
		),
		array(
			'setting'  => 'jeg[show_block_meta_date]',
			'operator' => '==',
			'value'    => true,
		)
	),
);

$options[] = array(
	'id'              => 'jeg[show_block_meta_comment]',
	'option_type'     => 'option',
	'transport'       => 'refresh',
	'default'         => true,
	'type'            => 'jeg-toggle',
	'label'           => esc_html__( 'Show Block Meta - Comment', 'jeg-element' ),
	'description'     => esc_html__( 'Show comment icon on meta block', 'jeg-element' ),
	'active_callback' => array(
		array(
			'setting'  => 'jeg[show_block_meta]',
			'operator' => '==',
			'value'    => true,
		)
	),
);

return $options;
