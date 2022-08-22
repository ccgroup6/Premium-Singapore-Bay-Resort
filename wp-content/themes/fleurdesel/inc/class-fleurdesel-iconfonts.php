<?php
/**
 * Register Iconfonts via plugin: WP Simple Iconfonts.
 *
 * @link https://wordpress.org/plugins/wp-simple-iconfonts/
 * @package Fleurdesel
 */

use WP_Simple_Iconfonts\Iconpack;

/**
 * Register Fleurdesel Iconfonts.
 */
class Fleurdesel_Iconfonts {
	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp_simple_iconfonts', array( $this, 'register_iconfonts' ) );
	}

	/**
	 * Register iconfonts.
	 *
	 * @param  obj $manager manager.
	 * @return void
	 */
	public function register_iconfonts( $manager ) {
		$fl_icon = array(
			'id' => 'fl',
			'name' => esc_html__( 'Fleurdesel Hotel', 'fleurdesel' ),
			'stylesheet_id' => 'fleurdesel-icons',
			'stylesheet_uri' => get_template_directory_uri() . '/dist/css/font-fleurdeselhotel.css',
			'icons' => static::get_iconfonts(),
		);

		$manager->register( new Iconpack( $fl_icon ) );
	}

	/**
	 * Gets iconfonts.
	 *
	 * @return array
	 */
	public static function get_iconfonts() {
		return apply_filters( 'fleurdesel_iconfonts',
			array(
				array(
					'id'   => 'fh fh-champagne',
					'name' => 'fh-champagne',
				),
				array(
					'id'   => 'fh fh-chat',
					'name' => 'fh-chat',
				),
				array(
					'id'   => 'fh fh-chat-1',
					'name' => 'fh-chat-1',
				),
				array(
					'id'   => 'fh fh-church',
					'name' => 'fh-church',
				),
				array(
					'id'   => 'fh fh-compact-disc',
					'name' => 'fh-compact-disc',
				),
				array(
					'id'   => 'fh fh-cupcake',
					'name' => 'fh-cupcake',
				),
				array(
					'id'   => 'fh fh-cupid',
					'name' => 'fh-cupid',
				),
				array(
					'id'   => 'fh fh-diamond',
					'name' => 'fh-diamond',
				),
				array(
					'id'   => 'fh fh-doorknob',
					'name' => 'fh-doorknob',
				),
				array(
					'id'   => 'fh fh-earrings',
					'name' => 'fh-earrings',
				),
				array(
					'id'   => 'fh fh-engagement-ring',
					'name' => 'fh-engagement-ring',
				),
				array(
					'id'   => 'fh fh-engagement-ring-1',
					'name' => 'fh-engagement-ring-1',
				),
				array(
					'id'   => 'fh fh-engagement-ring-2',
					'name' => 'fh-engagement-ring-2',
				),
				array(
					'id'   => 'fh fh-female',
					'name' => 'fh-female',
				),
				array(
					'id'   => 'fh fh-film',
					'name' => 'fh-film',
				),
				array(
					'id'   => 'fh fh-gift',
					'name' => 'fh-gift',
				),
				array(
					'id'   => 'fh fh-heart',
					'name' => 'fh-heart',
				),
				array(
					'id'   => 'fh fh-heart-1',
					'name' => 'fh-heart-1',
				),
				array(
					'id'   => 'fh fh-hearts',
					'name' => 'fh-hearts',
				),
				array(
					'id'   => 'fh fh-hot-drink',
					'name' => 'fh-hot-drink',
				),
				array(
					'id'   => 'fh fh-house',
					'name' => 'fh-house',
				),
				array(
					'id'   => 'fh fh-key',
					'name' => 'fh-key',
				),
				array(
					'id'   => 'fh fh-list',
					'name' => 'fh-list',
				),
				array(
					'id'   => 'fh fh-love-letter',
					'name' => 'fh-love-letter',
				),
				array(
					'id'   => 'fh fh-love-letter-1',
					'name' => 'fh-love-letter-1',
				),
				array(
					'id'   => 'fh fh-love-letter-2',
					'name' => 'fh-love-letter-2',
				),
				array(
					'id'   => 'fh fh-male',
					'name' => 'fh-male',
				),
				array(
					'id'   => 'fh fh-padlock',
					'name' => 'fh-padlock',
				),
				array(
					'id'   => 'fh fh-padlock-1',
					'name' => 'fh-padlock-1',
				),
				array(
					'id'   => 'fh fh-picture',
					'name' => 'fh-picture',
				),
				array(
					'id'   => 'fh fh-placeholder',
					'name' => 'fh-placeholder',
				),
				array(
					'id'   => 'fh fh-romantic-music',
					'name' => 'fh-romantic-music',
				),
				array(
					'id'   => 'fh fh-shopping-bag2',
					'name' => 'fh-shopping-bag2',
				),
				array(
					'id'   => 'fh fh-suitcase',
					'name' => 'fh-suitcase',
				),
				array(
					'id'   => 'fh fh-video-camera',
					'name' => 'fh-video-camera',
				),
				array(
					'id'   => 'fh fh-wedding-cake',
					'name' => 'fh-wedding-cake',
				),
				array(
					'id'   => 'fh fh-wedding-invitation',
					'name' => 'fh-wedding-invitation',
				),
				array(
					'id'   => 'fh fh-wedding-invitation-1',
					'name' => 'fh-wedding-invitation-1',
				),
				array(
					'id'   => 'fh fh-wedding-rings',
					'name' => 'fh-wedding-rings',
				),
				array(
					'id'   => 'fh fh-balloons',
					'name' => 'fh-balloons',
				),
				array(
					'id'   => 'fh fh-balloons-1',
					'name' => 'fh-balloons-1',
				),
				array(
					'id'   => 'fh fh-bed2',
					'name' => 'fh-bed2',
				),
				array(
					'id'   => 'fh fh-bell2',
					'name' => 'fh-bell2',
				),
				array(
					'id'   => 'fh fh-bible',
					'name' => 'fh-bible',
				),
				array(
					'id'   => 'fh fh-bow',
					'name' => 'fh-bow',
				),
				array(
					'id'   => 'fh fh-cake',
					'name' => 'fh-cake',
				),
				array(
					'id'   => 'fh fh-calendar',
					'name' => 'fh-calendar',
				),
				array(
					'id'   => 'fh fh-camera',
					'name' => 'fh-camera',
				),
				array(
					'id'   => 'fh fh-candles',
					'name' => 'fh-candles',
				),
				array(
					'id'   => 'fh fh-candles-1',
					'name' => 'fh-candles-1',
				),
				array(
					'id'   => 'fh fh-icicle',
					'name' => 'fh-icicle',
				),
				array(
					'id'   => 'fh fh-moon',
					'name' => 'fh-moon',
				),
				array(
					'id'   => 'fh fh-moon-1',
					'name' => 'fh-moon-1',
				),
				array(
					'id'   => 'fh fh-moon-2',
					'name' => 'fh-moon-2',
				),
				array(
					'id'   => 'fh fh-moon-3',
					'name' => 'fh-moon-3',
				),
				array(
					'id'   => 'fh fh-moon-4',
					'name' => 'fh-moon-4',
				),
				array(
					'id'   => 'fh fh-moon-5',
					'name' => 'fh-moon-5',
				),
				array(
					'id'   => 'fh fh-moon-6',
					'name' => 'fh-moon-6',
				),
				array(
					'id'   => 'fh fh-moon-7',
					'name' => 'fh-moon-7',
				),
				array(
					'id'   => 'fh fh-moon-8',
					'name' => 'fh-moon-8',
				),
				array(
					'id'   => 'fh fh-moon-9',
					'name' => 'fh-moon-9',
				),
				array(
					'id'   => 'fh fh-moon-10',
					'name' => 'fh-moon-10',
				),
				array(
					'id'   => 'fh fh-moon-11',
					'name' => 'fh-moon-11',
				),
				array(
					'id'   => 'fh fh-moon-12',
					'name' => 'fh-moon-12',
				),
				array(
					'id'   => 'fh fh-moon-13',
					'name' => 'fh-moon-13',
				),
				array(
					'id'   => 'fh fh-morning-rain',
					'name' => 'fh-morning-rain',
				),
				array(
					'id'   => 'fh fh-morning-snow',
					'name' => 'fh-morning-snow',
				),
				array(
					'id'   => 'fh fh-night-rain',
					'name' => 'fh-night-rain',
				),
				array(
					'id'   => 'fh fh-night-snow',
					'name' => 'fh-night-snow',
				),
				array(
					'id'   => 'fh fh-rainbow',
					'name' => 'fh-rainbow',
				),
				array(
					'id'   => 'fh fh-rainbow-1',
					'name' => 'fh-rainbow-1',
				),
				array(
					'id'   => 'fh fh-rainbow-2',
					'name' => 'fh-rainbow-2',
				),
				array(
					'id'   => 'fh fh-raindrops',
					'name' => 'fh-raindrops',
				),
				array(
					'id'   => 'fh fh-raining',
					'name' => 'fh-raining',
				),
				array(
					'id'   => 'fh fh-rainy',
					'name' => 'fh-rainy',
				),
				array(
					'id'   => 'fh fh-snow',
					'name' => 'fh-snow',
				),
				array(
					'id'   => 'fh fh-snowflake',
					'name' => 'fh-snowflake',
				),
				array(
					'id'   => 'fh fh-snowing',
					'name' => 'fh-snowing',
				),
				array(
					'id'   => 'fh fh-snowing-1',
					'name' => 'fh-snowing-1',
				),
				array(
					'id'   => 'fh fh-snowy',
					'name' => 'fh-snowy',
				),
				array(
					'id'   => 'fh fh-stars',
					'name' => 'fh-stars',
				),
				array(
					'id'   => 'fh fh-stars-1',
					'name' => 'fh-stars-1',
				),
				array(
					'id'   => 'fh fh-stars-2',
					'name' => 'fh-stars-2',
				),
				array(
					'id'   => 'fh fh-storm',
					'name' => 'fh-storm',
				),
				array(
					'id'   => 'fh fh-storm-1',
					'name' => 'fh-storm-1',
				),
				array(
					'id'   => 'fh fh-storm-2',
					'name' => 'fh-storm-2',
				),
				array(
					'id'   => 'fh fh-summer-rain',
					'name' => 'fh-summer-rain',
				),
				array(
					'id'   => 'fh fh-sunny',
					'name' => 'fh-sunny',
				),
				array(
					'id'   => 'fh fh-sunrise',
					'name' => 'fh-sunrise',
				),
				array(
					'id'   => 'fh fh-sunset',
					'name' => 'fh-sunset',
				),
				array(
					'id'   => 'fh fh-sunset-1',
					'name' => 'fh-sunset-1',
				),
				array(
					'id'   => 'fh fh-sunset-2',
					'name' => 'fh-sunset-2',
				),
				array(
					'id'   => 'fh fh-temperature',
					'name' => 'fh-temperature',
				),
				array(
					'id'   => 'fh fh-temperature-1',
					'name' => 'fh-temperature-1',
				),
				array(
					'id'   => 'fh fh-temperature-2',
					'name' => 'fh-temperature-2',
				),
				array(
					'id'   => 'fh fh-tide',
					'name' => 'fh-tide',
				),
				array(
					'id'   => 'fh fh-tide-1',
					'name' => 'fh-tide-1',
				),
				array(
					'id'   => 'fh fh-tornado',
					'name' => 'fh-tornado',
				),
				array(
					'id'   => 'fh fh-umbrella2',
					'name' => 'fh-umbrella2',
				),
				array(
					'id'   => 'fh fh-umbrella-1',
					'name' => 'fh-umbrella-1',
				),
				array(
					'id'   => 'fh fh-umbrellas',
					'name' => 'fh-umbrellas',
				),
				array(
					'id'   => 'fh fh-waves',
					'name' => 'fh-waves',
				),
				array(
					'id'   => 'fh fh-weather',
					'name' => 'fh-weather',
				),
				array(
					'id'   => 'fh fh-weather-1',
					'name' => 'fh-weather-1',
				),
				array(
					'id'   => 'fh fh-wind',
					'name' => 'fh-wind',
				),
				array(
					'id'   => 'fh fh-wind-1',
					'name' => 'fh-wind-1',
				),
				array(
					'id'   => 'fh fh-wind-2',
					'name' => 'fh-wind-2',
				),
				array(
					'id'   => 'fh fh-wind-3',
					'name' => 'fh-wind-3',
				),
				array(
					'id'   => 'fh fh-wind-4',
					'name' => 'fh-wind-4',
				),
				array(
					'id'   => 'fh fh-windy',
					'name' => 'fh-windy',
				),
				array(
					'id'   => 'fh fh-bolt',
					'name' => 'fh-bolt',
				),
				array(
					'id'   => 'fh fh-calm',
					'name' => 'fh-calm',
				),
				array(
					'id'   => 'fh fh-celsius',
					'name' => 'fh-celsius',
				),
				array(
					'id'   => 'fh fh-clouds',
					'name' => 'fh-clouds',
				),
				array(
					'id'   => 'fh fh-clouds-1',
					'name' => 'fh-clouds-1',
				),
				array(
					'id'   => 'fh fh-clouds-and-sun',
					'name' => 'fh-clouds-and-sun',
				),
				array(
					'id'   => 'fh fh-cloudy-night',
					'name' => 'fh-cloudy-night',
				),
				array(
					'id'   => 'fh fh-eclipse',
					'name' => 'fh-eclipse',
				),
				array(
					'id'   => 'fh fh-farenheit',
					'name' => 'fh-farenheit',
				),
				array(
					'id'   => 'fh fh-hail',
					'name' => 'fh-hail',
				),
				array(
					'id'   => 'fh fh-relaxation',
					'name' => 'fh-relaxation',
				),
				array(
					'id'   => 'fh fh-robe',
					'name' => 'fh-robe',
				),
				array(
					'id'   => 'fh fh-sauna',
					'name' => 'fh-sauna',
				),
				array(
					'id'   => 'fh fh-scent',
					'name' => 'fh-scent',
				),
				array(
					'id'   => 'fh fh-shower',
					'name' => 'fh-shower',
				),
				array(
					'id'   => 'fh fh-slippers',
					'name' => 'fh-slippers',
				),
				array(
					'id'   => 'fh fh-spa',
					'name' => 'fh-spa',
				),
				array(
					'id'   => 'fh fh-teapot',
					'name' => 'fh-teapot',
				),
				array(
					'id'   => 'fh fh-towel',
					'name' => 'fh-towel',
				),
				array(
					'id'   => 'fh fh-towels2',
					'name' => 'fh-towels2',
				),
				array(
					'id'   => 'fh fh-washbowl',
					'name' => 'fh-washbowl',
				),
				array(
					'id'   => 'fh fh-washbowl-1',
					'name' => 'fh-washbowl-1',
				),
				array(
					'id'   => 'fh fh-water',
					'name' => 'fh-water',
				),
				array(
					'id'   => 'fh fh-wax',
					'name' => 'fh-wax',
				),
				array(
					'id'   => 'fh fh-aromatherapy',
					'name' => 'fh-aromatherapy',
				),
				array(
					'id'   => 'fh fh-bamboo',
					'name' => 'fh-bamboo',
				),
				array(
					'id'   => 'fh fh-candle',
					'name' => 'fh-candle',
				),
				array(
					'id'   => 'fh fh-citrus',
					'name' => 'fh-citrus',
				),
				array(
					'id'   => 'fh fh-cream',
					'name' => 'fh-cream',
				),
				array(
					'id'   => 'fh fh-cream-1',
					'name' => 'fh-cream-1',
				),
				array(
					'id'   => 'fh fh-face-mask',
					'name' => 'fh-face-mask',
				),
				array(
					'id'   => 'fh fh-flower',
					'name' => 'fh-flower',
				),
				array(
					'id'   => 'fh fh-herbs',
					'name' => 'fh-herbs',
				),
				array(
					'id'   => 'fh fh-honey',
					'name' => 'fh-honey',
				),
				array(
					'id'   => 'fh fh-hot-stones',
					'name' => 'fh-hot-stones',
				),
				array(
					'id'   => 'fh fh-jacuzzi',
					'name' => 'fh-jacuzzi',
				),
				array(
					'id'   => 'fh fh-lotion',
					'name' => 'fh-lotion',
				),
				array(
					'id'   => 'fh fh-mortar',
					'name' => 'fh-mortar',
				),
				array(
					'id'   => 'fh fh-music',
					'name' => 'fh-music',
				),
				array(
					'id'   => 'fh fh-olive-oil',
					'name' => 'fh-olive-oil',
				),
				array(
					'id'   => 'fh fh-caravan',
					'name' => 'fh-caravan',
				),
				array(
					'id'   => 'fh fh-cctv',
					'name' => 'fh-cctv',
				),
				array(
					'id'   => 'fh fh-champagne2',
					'name' => 'fh-champagne2',
				),
				array(
					'id'   => 'fh fh-cocktail',
					'name' => 'fh-cocktail',
				),
				array(
					'id'   => 'fh fh-coffee-cup',
					'name' => 'fh-coffee-cup',
				),
				array(
					'id'   => 'fh fh-coffee-machine',
					'name' => 'fh-coffee-machine',
				),
				array(
					'id'   => 'fh fh-comb',
					'name' => 'fh-comb',
				),
				array(
					'id'   => 'fh fh-credit-card',
					'name' => 'fh-credit-card',
				),
				array(
					'id'   => 'fh fh-customer-service',
					'name' => 'fh-customer-service',
				),
				array(
					'id'   => 'fh fh-cutlery',
					'name' => 'fh-cutlery',
				),
				array(
					'id'   => 'fh fh-door-hanger',
					'name' => 'fh-door-hanger',
				),
				array(
					'id'   => 'fh fh-door-hanger-1',
					'name' => 'fh-door-hanger-1',
				),
				array(
					'id'   => 'fh fh-door-key',
					'name' => 'fh-door-key',
				),
				array(
					'id'   => 'fh fh-door-key-1',
					'name' => 'fh-door-key-1',
				),
				array(
					'id'   => 'fh fh-double-bed',
					'name' => 'fh-double-bed',
				),
				array(
					'id'   => 'fh fh-dumbbell',
					'name' => 'fh-dumbbell',
				),
				array(
					'id'   => 'fh fh-entrance',
					'name' => 'fh-entrance',
				),
				array(
					'id'   => 'fh fh-exit',
					'name' => 'fh-exit',
				),
				array(
					'id'   => 'fh fh-exit-1',
					'name' => 'fh-exit-1',
				),
				array(
					'id'   => 'fh fh-fast-food',
					'name' => 'fh-fast-food',
				),
				array(
					'id'   => 'fh fh-fire-extinguisher',
					'name' => 'fh-fire-extinguisher',
				),
				array(
					'id'   => 'fh fh-flip-flops',
					'name' => 'fh-flip-flops',
				),
				array(
					'id'   => 'fh fh-gel',
					'name' => 'fh-gel',
				),
				array(
					'id'   => 'fh fh-golf',
					'name' => 'fh-golf',
				),
				array(
					'id'   => 'fh fh-golf-1',
					'name' => 'fh-golf-1',
				),
				array(
					'id'   => 'fh fh-golf-2',
					'name' => 'fh-golf-2',
				),
				array(
					'id'   => 'fh fh-golf-3',
					'name' => 'fh-golf-3',
				),
				array(
					'id'   => 'fh fh-gym',
					'name' => 'fh-gym',
				),
				array(
					'id'   => 'fh fh-hairdryer',
					'name' => 'fh-hairdryer',
				),
				array(
					'id'   => 'fh fh-hammock',
					'name' => 'fh-hammock',
				),
				array(
					'id'   => 'fh fh-hanger',
					'name' => 'fh-hanger',
				),
				array(
					'id'   => 'fh fh-hose',
					'name' => 'fh-hose',
				),
				array(
					'id'   => 'fh fh-hotel',
					'name' => 'fh-hotel',
				),
				array(
					'id'   => 'fh fh-hotel-1',
					'name' => 'fh-hotel-1',
				),
				array(
					'id'   => 'fh fh-hotel-2',
					'name' => 'fh-hotel-2',
				),
				array(
					'id'   => 'fh fh-iron',
					'name' => 'fh-iron',
				),
				array(
					'id'   => 'fh fh-lamp',
					'name' => 'fh-lamp',
				),
				array(
					'id'   => 'fh fh-lifesaver',
					'name' => 'fh-lifesaver',
				),
				array(
					'id'   => 'fh fh-lift',
					'name' => 'fh-lift',
				),
				array(
					'id'   => 'fh fh-lift-1',
					'name' => 'fh-lift-1',
				),
				array(
					'id'   => 'fh fh-location',
					'name' => 'fh-location',
				),
				array(
					'id'   => 'fh fh-map',
					'name' => 'fh-map',
				),
				array(
					'id'   => 'fh fh-microwave',
					'name' => 'fh-microwave',
				),
				array(
					'id'   => 'fh fh-minibar',
					'name' => 'fh-minibar',
				),
				array(
					'id'   => 'fh fh-no-smoking',
					'name' => 'fh-no-smoking',
				),
				array(
					'id'   => 'fh fh-open',
					'name' => 'fh-open',
				),
				array(
					'id'   => 'fh fh-oxygen-tank',
					'name' => 'fh-oxygen-tank',
				),
				array(
					'id'   => 'fh fh-parking',
					'name' => 'fh-parking',
				),
				array(
					'id'   => 'fh fh-reception',
					'name' => 'fh-reception',
				),
				array(
					'id'   => 'fh fh-rent-a-car',
					'name' => 'fh-rent-a-car',
				),
				array(
					'id'   => 'fh fh-restaurant',
					'name' => 'fh-restaurant',
				),
				array(
					'id'   => 'fh fh-restaurant-1',
					'name' => 'fh-restaurant-1',
				),
				array(
					'id'   => 'fh fh-room',
					'name' => 'fh-room',
				),
				array(
					'id'   => 'fh fh-room-key',
					'name' => 'fh-room-key',
				),
				array(
					'id'   => 'fh fh-room-service',
					'name' => 'fh-room-service',
				),
				array(
					'id'   => 'fh fh-room-service-1',
					'name' => 'fh-room-service-1',
				),
				array(
					'id'   => 'fh fh-safebox',
					'name' => 'fh-safebox',
				),
				array(
					'id'   => 'fh fh-safebox-1',
					'name' => 'fh-safebox-1',
				),
				array(
					'id'   => 'fh fh-shopping-bag',
					'name' => 'fh-shopping-bag',
				),
				array(
					'id'   => 'fh fh-shower2',
					'name' => 'fh-shower2',
				),
				array(
					'id'   => 'fh fh-shower-1',
					'name' => 'fh-shower-1',
				),
				array(
					'id'   => 'fh fh-soda',
					'name' => 'fh-soda',
				),
				array(
					'id'   => 'fh fh-soup',
					'name' => 'fh-soup',
				),
				array(
					'id'   => 'fh fh-stairs',
					'name' => 'fh-stairs',
				),
				array(
					'id'   => 'fh fh-stairs-1',
					'name' => 'fh-stairs-1',
				),
				array(
					'id'   => 'fh fh-suitcase2',
					'name' => 'fh-suitcase2',
				),
				array(
					'id'   => 'fh fh-swimming-pool',
					'name' => 'fh-swimming-pool',
				),
				array(
					'id'   => 'fh fh-swimming-pool-1',
					'name' => 'fh-swimming-pool-1',
				),
				array(
					'id'   => 'fh fh-taxi',
					'name' => 'fh-taxi',
				),
				array(
					'id'   => 'fh fh-telephone',
					'name' => 'fh-telephone',
				),
				array(
					'id'   => 'fh fh-television',
					'name' => 'fh-television',
				),
				array(
					'id'   => 'fh fh-television-1',
					'name' => 'fh-television-1',
				),
				array(
					'id'   => 'fh fh-ticket',
					'name' => 'fh-ticket',
				),
				array(
					'id'   => 'fh fh-ticket-1',
					'name' => 'fh-ticket-1',
				),
				array(
					'id'   => 'fh fh-toilet-paper',
					'name' => 'fh-toilet-paper',
				),
				array(
					'id'   => 'fh fh-toilets',
					'name' => 'fh-toilets',
				),
				array(
					'id'   => 'fh fh-toothpaste',
					'name' => 'fh-toothpaste',
				),
				array(
					'id'   => 'fh fh-towel2',
					'name' => 'fh-towel2',
				),
				array(
					'id'   => 'fh fh-towels',
					'name' => 'fh-towels',
				),
				array(
					'id'   => 'fh fh-trolley',
					'name' => 'fh-trolley',
				),
				array(
					'id'   => 'fh fh-trolley-1',
					'name' => 'fh-trolley-1',
				),
				array(
					'id'   => 'fh fh-waiter',
					'name' => 'fh-waiter',
				),
				array(
					'id'   => 'fh fh-washing-machine',
					'name' => 'fh-washing-machine',
				),
				array(
					'id'   => 'fh fh-whiskey',
					'name' => 'fh-whiskey',
				),
				array(
					'id'   => 'fh fh-wifi',
					'name' => 'fh-wifi',
				),
				array(
					'id'   => 'fh fh-wine',
					'name' => 'fh-wine',
				),
				array(
					'id'   => 'fh fh-air-conditioner',
					'name' => 'fh-air-conditioner',
				),
				array(
					'id'   => 'fh fh-backpack',
					'name' => 'fh-backpack',
				),
				array(
					'id'   => 'fh fh-bar',
					'name' => 'fh-bar',
				),
				array(
					'id'   => 'fh fh-bath',
					'name' => 'fh-bath',
				),
				array(
					'id'   => 'fh fh-bed',
					'name' => 'fh-bed',
				),
				array(
					'id'   => 'fh fh-beds',
					'name' => 'fh-beds',
				),
				array(
					'id'   => 'fh fh-bell',
					'name' => 'fh-bell',
				),
				array(
					'id'   => 'fh fh-bellboy',
					'name' => 'fh-bellboy',
				),
				array(
					'id'   => 'fh fh-bicycle',
					'name' => 'fh-bicycle',
				),
				array(
					'id'   => 'fh fh-bin',
					'name' => 'fh-bin',
				),
				array(
					'id'   => 'fh fh-bunk',
					'name' => 'fh-bunk',
				),
				array(
					'id'   => 'fh fh-bus',
					'name' => 'fh-bus',
				),
				array(
					'id'   => 'fh fh-cake2',
					'name' => 'fh-cake2',
				),
				array(
					'id'   => 'fh fh-calendar2',
					'name' => 'fh-calendar2',
				),
				array(
					'id'   => 'fh fh-wood-floor',
					'name' => 'fh-wood-floor',
				),
				array(
					'id'   => 'fh fh-door-curtains',
					'name' => 'fh-door-curtains',
				),
				array(
					'id'   => 'fh fh-sea-view',
					'name' => 'fh-sea-view',
				),
				array(
					'id'   => 'fh fh-kid-play',
					'name' => 'fh-kid-play',
				),
				array(
					'id'   => 'fh fh-ping-pong',
					'name' => 'fh-ping-pong',
				),
				array(
					'id'   => 'fh fh-tennis-place',
					'name' => 'fh-tennis-place',
				),
				array(
					'id'   => 'fh fh-fridge',
					'name' => 'fh-fridge',
				),
				array(
					'id'   => 'fh fh-no-pet-allowed',
					'name' => 'fh-no-pet-allowed',
				),
				array(
					'id'   => 'fh fh-iconboxung-11',
					'name' => 'fh-iconboxung-11',
				),
				array(
					'id'   => 'fh fh-room-size',
					'name' => 'fh-room-size',
				),
				array(
					'id'   => 'fh fh-newspapers',
					'name' => 'fh-newspapers',
				),
				array(
					'id'   => 'fh fh-airport-pick-up',
					'name' => 'fh-airport-pick-up',
				),
				array(
					'id'   => 'fh fh-kitchenette',
					'name' => 'fh-kitchenette',
				),
				array(
					'id'   => 'fh fh-golf-place',
					'name' => 'fh-golf-place',
				),
				array(
					'id'   => 'fh fh-dining-area',
					'name' => 'fh-dining-area',
				),
				array(
					'id'   => 'fh fh-bedroom',
					'name' => 'fh-bedroom',
				),
				array(
					'id'   => 'fh fh-guest',
					'name' => 'fh-guest',
				),
				array(
					'id'   => 'fh fh-email',
					'name' => 'fh-email',
				),
				array(
					'id'   => 'fh fh-balcony',
					'name' => 'fh-balcony',
				),
				array(
					'id'   => 'fh fh-umbrella',
					'name' => 'fh-umbrella',
				),
				array(
					'id'   => 'fh fh-close',
					'name' => 'fh-close',
				),
				array(
					'id'   => 'fh fh-full',
					'name' => 'fh-full',
				),
				array(
					'id'   => 'fh fh-book',
					'name' => 'fh-book',
				),
			)
		);
	}
}
new Fleurdesel_Iconfonts();


