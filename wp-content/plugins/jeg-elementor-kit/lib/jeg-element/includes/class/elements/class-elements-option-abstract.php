<?php
/**
 * Jeg News Element Background Load Class
 *
 * @author Jegstudio
 * @since 1.0.0
 * @package jeg-news-element
 */

namespace Jeg\Element\Elements;

use Elementor\Core\Base\Base_Object;
use Jeg\Element\Element;

/**
 * Class Jeg Module
 */
abstract class Elements_Option_Abstract {
	/**
	 * Array of option.
	 *
	 * @var array
	 */
	protected $options = array();

	/**
	 * Array of Segments
	 *
	 * @var array
	 */
	protected $segments = array();

	/**
	 * ID of this element. also used as shortcode name.
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Class path for every element related.
	 *
	 * @var array
	 */
	protected $classes;

	/**
	 * Elements_Option_Abstract constructor.
	 *
	 * @param string $id Shortcode name for this instance.
	 * @param string $classes Classes Related To this Instance.
	 */
	public function __construct( $id, $classes ) {
		$this->set_data( $id, $classes );
		$this->setup_hook();
	}

	/**
	 * Setup hook.
	 */
	public function setup_hook() {
		add_filter( 'jeg_shortcode_elements', array( $this, 'register_shortcode' ) );
		add_action( 'wp_ajax_' . $this->id, array( $this, 'get_ajax_option' ) );
		add_action( 'init', array( $this, 'map_vc' ) );

		// do action
		do_action('setup_hook_element_option', $this->id, $this->classes);
	}

	/**
	 * Map WPBakery Page Builder Option
	 */
	public function map_vc() {
		if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {
			$vc_options['base']        = $this->id;
			$vc_options['params']      = jeg_options_to_vc( $this->get_options(), $this->get_segments() );
			$vc_options['name']        = $this->get_element_name();
			$vc_options['category']    = $this->get_category();
			$vc_options['icon']        = $this->id;
			$vc_options['description'] = $this->get_element_name();

			vc_map( $vc_options );
		}
	}

	/**
	 * Register shortcode for Shortcode Builder
	 *
	 * @param array $elements Collection of element.
	 *
	 * @return mixed
	 */
	public function register_shortcode( $elements ) {
		$element             = array();
		$element['name']     = $this->get_element_name();
		$element['category'] = $this->get_category();

		$elements[ $this->id ] = $element;

		return $elements;
	}

	/**
	 * Get Ajax Option
	 */
	public function get_ajax_option() {
		$segments = $this->get_segments();
		$options  = $this->get_options();
		$segments = Element::instance()->shortcode->prepare_segments( $segments );
		$fields   = Element::instance()->shortcode->prepare_fields( $options );

		wp_send_json_success( array(
			'segments' => $segments,
			'fields'   => $fields,
		) );
	}

	/**
	 * Get option for this element.
	 *
	 * @return array
	 */
	public function get_options() {
		if ( empty( $this->options ) ) {
			$this->set_segments();
			$this->set_compatible_column_option();
			$this->set_options();
		}

		return $this->options;
	}

	/**
	 * Get more simplify option
	 */
	public function get_simple_options() {
		$options  = $this->get_options();
		$settings = array();

		foreach ( $options as $id => $option ) {
			$settings[ $id ] = isset( $option['default'] ) ? $option['default'] : '';
		}

		return $settings;
	}

	/**
	 * Get first registered segment ID.
	 */
	public function get_first_segment_id() {
		$id      = null;
		$compare = PHP_INT_MAX;

		foreach ( $this->segments as $key => $segment ) {
			if ( $segment['priority'] < $compare ) {
				$id      = $key;
				$compare = $segment['priority'];
			}
		}

		return $id;
	}

	/**
	 * Set compatible column option
	 */
	public function set_compatible_column_option() {
		$this->options['compatible_column_notice'] = array(
			'type'        => 'alert',
			'title'       => esc_html__( 'Compatible Column: ', 'jeg-element' ) . implode( ', ', $this->compatible_column() ),
			'description' => apply_filters( 'jeg_element_compatible_column_notice', esc_html__( 'Please check style / design tab to change Module / Block width and make it fit with your current column width', 'jeg-element' ) ),
			'segment'     => $this->get_first_segment_id(),
			'default'     => 'info',
		);
	}

	/**
	 * Get segments for this element.
	 *
	 * @return array
	 */
	public function get_segments() {
		if ( empty( $this->segments ) ) {
			$this->set_segments();
		}

		return $this->segments;
	}

	/**
	 * Set content filter segment
	 */
	public function set_content_filter_segment() {
		$this->segments['content-filter'] = array(
			'name'     => esc_html__( 'Content Filter', 'jeg-element' ),
			'priority' => 10,
		);
	}

	/**
	 * Set content filter option
	 *
	 * @param int  $number Default number of element.
	 * @param bool $hide_number_post Hide number of post flag if we don't allow user to change number directly.
	 */
	public function set_content_filter_option( $number = 10, $hide_number_post = false ) {
		$dependency = array(
			'field'    => 'sort_by',
			'operator' => 'in',
			'value'    => array(
				'post_type',
				'latest',
				'oldest',
				'alphabet_asc',
				'alphabet_desc',
				'random',
				'random_week',
				'random_month',
				'most_comment',
				'most_comment_day',
				'most_comment_week',
				'most_comment_month',
				'popular_post_day',
				'popular_post_week',
				'popular_post_month',
				'popular_post',
				'rate',
				'like',
				'share',
			),
		);

		$this->options['post_type'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Include Post Type', 'jeg-element' ),
			'description' => esc_html__( 'Choose post type for this content.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => 'post',
			'options'     => 'jeg_get_enable_post_type',
			'dependency'  => array(
				$dependency,
			),
		);

		if ( ! $hide_number_post ) {
			$this->options['number_post'] = array(
				'type'        => 'slider',
				'title'       => esc_html__( 'Number of Post', 'jeg-element' ),
				'description' => esc_html__( 'Show number of post on this module.', 'jeg-element' ),
				'segment'     => 'content-filter',
				'options'     => array(
					'min'  => 1,
					'max'  => 30,
					'step' => 1,
				),
				'default'     => $number,
			);
		}

		if ( $hide_number_post && $number > 0 ) {
			$this->options['content_filter_number_alert'] = array(
				'type'        => 'alert',
				'title'       => esc_html__( 'Number of post', 'jeg-element' ),
				/* translators: 1: Number of element */
				'description' => sprintf( esc_html__( 'This module will require you to choose %s number of post.', 'jeg-element' ), $number ),
				'segment'     => 'content-filter',
				'default'     => 'info',
			);
		}

		$this->options['post_offset'] = array(
			'type'        => 'number',
			'title'       => esc_html__( 'Post Offset', 'jeg-element' ),
			'description' => esc_html__( 'Number of post offset (start of content).', 'jeg-element' ),
			'segment'     => 'content-filter',
			'options'     => array(
				'min'  => 0,
				'max'  => PHP_INT_MAX,
				'step' => 1,
			),
			'default'     => 0,
			'dependency'  => array(
				$dependency,
			),
		);

		$this->options['unique_content'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Include into Unique Content Group', 'jeg-element' ),
			'description' => esc_html__( 'Choose unique content option, and this module will be included into unique content group. It won\'t duplicate content across the group. Ajax loaded content won\'t affect this unique content feature.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => 'disable',
			'options'     => array(
				'disable' => esc_html__( 'Disable', 'jeg-element' ),
				'unique1' => esc_html__( 'Unique Content - Group 1', 'jeg-element' ),
				'unique2' => esc_html__( 'Unique Content - Group 2', 'jeg-element' ),
				'unique3' => esc_html__( 'Unique Content - Group 3', 'jeg-element' ),
				'unique4' => esc_html__( 'Unique Content - Group 4', 'jeg-element' ),
				'unique5' => esc_html__( 'Unique Content - Group 5', 'jeg-element' ),
			),
			'dependency'  => array(
				$dependency,
			),
		);

		$this->options['include_post'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_post',
			'options'     => 'jeg_get_post_option',
			'nonce'       => wp_create_nonce( 'jeg_find_post' ),
			'title'       => esc_html__( 'Include Post ID', 'jeg-element' ),
			'description' => wp_kses( __( 'Tips :<br/> - You can search post id by inputing title, clicking search title, and you will have your post id.<br/>- You can also directly insert your post id, and click enter to add it on the list.', 'jeg-element' ), wp_kses_allowed_html() ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$dependency,
			),
		);

		$this->options['exclude_post'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_post',
			'options'     => 'jeg_get_post_option',
			'nonce'       => wp_create_nonce( 'jeg_find_post' ),
			'title'       => esc_html__( 'Exclude Post ID', 'jeg-element' ),
			'description' => wp_kses( __( 'Tips :<br/> - You can search post id by inputing title, clicking search title, and you will have your post id.<br/>- You can also directly insert your post id, and click enter to add it on the list.', 'jeg-element' ), wp_kses_allowed_html() ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$dependency,
			),
		);

		$this->options['include_category'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'title'       => esc_html__( 'Include Category', 'jeg-element' ),
			'description' => esc_html__( 'Choose which category you want to show on this module.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		);

		$this->options['exclude_category'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_category',
			'options'     => 'jeg_get_category_option',
			'nonce'       => wp_create_nonce( 'jeg_find_category' ),
			'title'       => esc_html__( 'Exclude Category', 'jeg-element' ),
			'description' => esc_html__( 'Choose excluded category for this module.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		);

		$this->options['include_author'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_author',
			'options'     => 'jeg_get_author_option',
			'nonce'       => wp_create_nonce( 'jeg_find_author' ),
			'title'       => esc_html__( 'Author', 'jeg-element' ),
			'description' => esc_html__( 'Write to search post author.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				$dependency,
			),
		);

		$this->options['include_tag'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'title'       => esc_html__( 'Include Tags', 'jeg-element' ),
			'description' => esc_html__( 'Write to search post tag.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		);

		$this->options['exclude_tag'] = array(
			'type'        => 'select',
			'multiple'    => PHP_INT_MAX,
			'ajax'        => 'jeg_find_tag',
			'options'     => 'jeg_get_tag_option',
			'nonce'       => wp_create_nonce( 'jeg_find_tag' ),
			'title'       => esc_html__( 'Exclude Tags', 'jeg-element' ),
			'description' => esc_html__( 'Write to search post tag.', 'jeg-element' ),
			'segment'     => 'content-filter',
			'default'     => '',
			'dependency'  => array(
				array(
					'field'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
		);

		$this->set_taxonomy_option();

		$this->options['sort_by'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Sort by', 'jeg-element' ),
			'description' => wp_kses( __( 'Sort post by this option<br/>* <strong>Jetpack :</strong> Need <strong>Jetpack</strong> plugin & Stat module enabled.<br/>', 'jeg-element' ), wp_kses_allowed_html() ),
			'segment'     => 'content-filter',
			'default'     => 'latest',
			'options'     => array(
				'latest'                     => esc_html__( 'Latest Post', 'jeg-element' ),
				'oldest'                     => esc_html__( 'Oldest Post', 'jeg-element' ),
				'alphabet_asc'               => esc_html__( 'Alphabet Asc', 'jeg-element' ),
				'alphabet_desc'              => esc_html__( 'Alphabet Desc', 'jeg-element' ),
				'random'                     => esc_html__( 'Random Post', 'jeg-element' ),
				'random_week'                => esc_html__( 'Random Post (7 Days)', 'jeg-element' ),
				'random_month'               => esc_html__( 'Random Post (30 Days)', 'jeg-element' ),
				'most_comment'               => esc_html__( 'Most Comment', 'jeg-element' ),
				'popular_post_jetpack_day'   => esc_html__( 'Popular Post (1 Day - Jetpack)', 'jeg-element' ),
				'popular_post_jetpack_week'  => esc_html__( 'Popular Post (7 Days - Jetpack)', 'jeg-element' ),
				'popular_post_jetpack_month' => esc_html__( 'Popular Post (30 Days - Jetpack)', 'jeg-element' ),
				'popular_post_jetpack_all'   => esc_html__( 'Popular Post (All Time - Jetpack)', 'jeg-element' ),
			),
		);
	}

	/**
	 * Custom Taxonomy option
	 */
	public function set_taxonomy_option() {
		$taxonomies = jeg_get_enabled_custom_taxonomy();

		foreach ( $taxonomies as $key => $value ) {
			$this->options[ $key ] = array(
				'type'        => 'select',
				'multiple'    => PHP_INT_MAX,
				'title'       => $value['name'],
				'description' => sprintf( esc_html__( 'Write to search the %s that you want to include as filter.', 'jeg-element' ), strtolower( $value['name'] ) ),
				'ajax'        => 'jeg_find_custom_term',
				'nonce'       => wp_create_nonce( 'jeg_find_custom_term' ),
				'options'     => 'jeg_get_custom_term_option',
				'ajaxoptions' => 'jeg_get_custom_term_option',
				'slug'        => $key,
				'segment'     => 'content-filter',
				'dependency'  => array(
					array(
						'field'    => 'post_type',
						'operator' => '==',
						'value'    => $value['post_types'],
					),
				),
			);
		}
	}

	/**
	 * Set content filter segment
	 */
	public function set_style_segment() {
		$this->segments['design'] = array(
			'name'     => esc_html__( 'Design', 'jeg-element' ),
			'priority' => 30,
		);
	}

	/**
	 * Style Option for element
	 */
	public function set_style_option() {
		$width = array(
			'auto' => esc_html__( 'Auto', 'jeg_element' ),
		);

		if ( in_array( 4, $this->compatible_column(), true ) ) {
			$width = array_merge( $width, array(
				4 => esc_html__( '4 Column Design ( 1 Block )', 'jeg_element' ),
			) );
		}

		if ( in_array( 8, $this->compatible_column(), true ) ) {
			$width = array_merge( $width, array(
				8 => esc_html__( '8 Column Design ( 2 Block )', 'jeg_element' ),
			) );
		}

		if ( in_array( 12, $this->compatible_column(), true ) ) {
			$width = array_merge( $width, array(
				12 => esc_html__( '12 Column Design ( 3 Block )', 'jeg_element' ),
			) );
		}

		$this->options['el_id'] = array(
			'type'        => 'text',
			'title'       => esc_html__( 'Element ID', 'jeg_element' ),
			/* translators: 1: URL of direction */
			'description' => wp_kses( sprintf( __( 'Enter element ID (Note: make sure it is unique and valid according to <a href="%s">w3c specification</a>).', 'jeg_element' ), 'http://www.w3schools.com/tags/att_global_id.asp' ), wp_kses_allowed_html() ),
			'segment'     => 'design',
		);

		$this->options['el_class'] = array(
			'type'        => 'text',
			'title'       => esc_html__( 'Extra class name', 'jeg_element' ),
			'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS.', 'jeg_element' ),
			'segment'     => 'design',
		);

		if ( $this->show_color_scheme() ) {
			$this->options['scheme'] = array(
				'type'        => 'select',
				'title'       => esc_html__( 'Element Color Scheme', 'jeg_element' ),
				'description' => esc_html__( 'Choose element color scheme for your element.', 'jeg_element' ),
				'segment'     => 'design',
				'default'     => 'normal',
				'options'     => array(
					'normal' => esc_html__( 'Light', 'jeg_element' ),
					'alt'    => esc_html__( 'Dark', 'jeg_element' ),
				),
			);
		}

		$this->options['column_width'] = array(
			'type'        => 'select',
			'title'       => esc_html__( 'Block / Column Width', 'jeg_element' ),
			'description' => esc_html__( 'Please choose width of column you want to use on this block. 1 Block represents 4 columns.', 'jeg_element' ),
			'segment'     => 'design',
			'default'     => 'auto',
			'options'     => $width,
		);

		$this->additional_style();

		$this->options['css'] = array(
			'type'    => 'css_editor',
			'title'   => esc_html__( 'CSS Box', 'jeg_element' ),
			'segment' => 'design',
		);
	}

	/**
	 * Add Additional Style on Child.
	 */
	public function additional_style() {
		// Empty.
	}

	/**
	 * Show color scheme flag for element.
	 *
	 * @return bool
	 */
	public function show_color_scheme() {
		return true;
	}

	/**
	 * Get category name of this element
	 *
	 * @return string
	 */
	public function get_category() {
		return esc_html__( 'JEG - Element', 'jeg-element' );
	}

	/**
	 * Set shortcode
	 *
	 * @param string $id Shortcode name for this instance.
	 * @param string $classes Classes Related To this Instance.
	 */
	public function set_data( $id, $classes ) {
		$this->id      = $id;
		$this->classes = $classes;
	}

	/**
	 * Set typography option for Elementor
	 *
	 * @param Base_Object $instance Instance class of Elementor_Abstract
	 *
	 * @return mixed
	 */
	public function set_typography_option( $instance ) {
		return $instance;
	}

	/**
	 * Get Module Name
	 *
	 * @return string
	 */
	abstract public function get_element_name();

	/**
	 * Get Compatible Column
	 *
	 * @return array
	 */
	abstract public function compatible_column();

	/**
	 * Set Option
	 */
	abstract public function set_options();

	/**
	 * Set Segments
	 */
	abstract public function set_segments();

	/**
	 * Get allowed device especially for header builder
	 */
	public function get_allowed_devices() {
		return array();
	}
}
