<?php
/**
 * Fleurdesel social core class.
 *
 * @package Fleurdesel
 */

if ( ! class_exists( 'Fleurdesel_Social_Core' ) ) :
	/**
	 * Fleurdesel_Social_Core class.
	 */
	final class Fleurdesel_Social_Core {
		/**
		 * Singleton reference to singleton instance.
		 *
		 * @var self
		 */
		protected static $instance;

		/**
		 * Gets the instance via lazy initialization.
		 *
		 * @return self
		 */
		public static function get_instance() {
			if ( null === static::$instance ) {
				static::$instance = new static;
			}

			return static::$instance;
		}

		/**
		 * A list of social profile providers.
		 *
		 * @var array
		 */
		public $profile_providers = array();

		/**
		 * A list of social share providers.
		 *
		 * @var array
		 */
		public $share_providers = array();

		/**
		 * Private constructor of class, use static::get_instance() instead of.
		 */
		private function __construct() {
			/**
			 * Social share providers.
			 *
			 * @var array
			 */
			$this->profile_providers = apply_filters( 'fleurdesel_social_profile_providers', array(
				'facebook'  => esc_html__( 'Facebook', 'fleurdesel' ),
				'google'    => esc_html__( 'Google plus', 'fleurdesel' ),
				'twitter'   => esc_html__( 'Twitter', 'fleurdesel' ),
				'github'    => esc_html__( 'Github', 'fleurdesel' ),
				'instagram' => esc_html__( 'Instagram', 'fleurdesel' ),
				'pinterest' => esc_html__( 'Pinterest', 'fleurdesel' ),
				'linkedin'  => esc_html__( 'LinkedIn', 'fleurdesel' ),
				'skype'     => esc_html__( 'Skype', 'fleurdesel' ),
				'tumblr'    => esc_html__( 'Tumblr', 'fleurdesel' ),
				'youtube'   => esc_html__( 'Youtube', 'fleurdesel' ),
				'vimeo'     => esc_html__( 'Vimeo', 'fleurdesel' ),
				'flickr'    => esc_html__( 'Flickr', 'fleurdesel' ),
				'dribbble'  => esc_html__( 'Dribbble', 'fleurdesel' ),
			) );

			/**
			 * Social share providers.
			 *
			 * @var array
			 */
			$this->share_providers = apply_filters( 'fleurdesel_social_share_providers', array(
				'facebook'    => array(
					'name' => esc_html__( 'Facebook', 'fleurdesel' ),
					'link' => 'http://www.facebook.com/sharer.php?u={url}',
				),

				'twitter'     => array(
					'name' => esc_html__( 'Twitter', 'fleurdesel' ),
					'link' => 'https://twitter.com/share?url={url}&text={title}',
				),

				'google-plus' => array(
					'name' => esc_html__( 'Google Plus', 'fleurdesel' ),
					'link' => 'https://plus.google.com/share?url={url}',
				),

				'pinterest'   => array(
					'name' => esc_html__( 'Pinterest', 'fleurdesel' ),
					'link' => 'https://pinterest.com/pin/create/bookmarklet/?url={url}&description={title}',
				),

				'linkedin'    => array(
					'name' => esc_html__( 'LinkedIn', 'fleurdesel' ),
					'link' => 'http://www.linkedin.com/shareArticle?url={url}&title={title}',
				),

				'digg'        => array(
					'name' => esc_html__( 'Digg', 'fleurdesel' ),
					'link' => 'http://digg.com/submit?url={url}&title={title}',
				),

				'tumblr'      => array(
					'name' => esc_html__( 'Tumblr', 'fleurdesel' ),
					'link' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl={url}&title={title}',
				),

				'reddit'      => array(
					'name' => esc_html__( 'Reddit', 'fleurdesel' ),
					'link' => 'http://reddit.com/submit?url={url}&title={title}',
				),

				'stumbleupon' => array(
					'name' => esc_html__( 'Stumbleupon', 'fleurdesel' ),
					'link' => 'http://www.stumbleupon.com/submit?url={url}&title={title}',
				),
			) );

			// Theme Options register.
			add_action( 'awethemes/theme_options/registers', array( $this, 'social_options' ), 9 );
		}

		/**
		 * Add settings to the Theme Options.
		 *
		 * @access private
		 *
		 * @param Awethemes Framework $framework object.
		 */
		public function social_options( $framework ) {
			if ( ! function_exists( 'fleurdesel_sanitize_value' ) ) {
				require_once get_template_directory() . '/inc/sanitization-callbacks.php';
			}

			$framework->add_panel( 'social', array(
				'title'    => esc_html__( 'Social Network', 'fleurdesel' ),
				'icon'     => 'dashicons-share',
				'priority' => 80,
			) );

			$framework->add_section( 'social_general', function( $tab ) {


				$tab->set( array(
					'title' => esc_html__( 'Social General', 'fleurdesel' ),
					'panel' => 'social',
				) );

				$tab->add_field( array(
					'name' => esc_html__( 'Open link in a new tab', 'fleurdesel' ),
					'id'   => 'social_blank',
					'type' => 'toggle',
				) );

			}, 45 );

			$framework->add_section( 'social_share', function( $tab ) {

				$tab->set( array(
					'title' => esc_html__( 'Social Share', 'fleurdesel' ),
					'panel' => 'social',
				) );

				$tab->add_field(array(
					'id'   => 'fleurdesel_social_share',
					'name' => esc_html__( 'Social Sharing', 'fleurdesel' ),
					'type' => 'title',
					'desc'	=> esc_html__( 'Select sharing providers to display.', 'fleurdesel' ),
				) );

				foreach ( $this->share_providers as $id => $provider ) {
					$id = sprintf( 'fleurdesel_social_share_%s', sanitize_key( $id ) );

					$tab->add_field( array(
						'name' => sprintf( esc_html__( 'Share on %s', 'fleurdesel' ), $provider['name'] ),
						'id'   => $id,
						'type' => 'toggle',
					) );
				}
			}, 50 );

			$framework->add_section( 'social_links', function( $tab ) {

				$tab->set( array(
					'title' => esc_html__( 'Social Follow', 'fleurdesel' ),
					'panel' => 'social',
				) );

				$tab->add_field( array(
					'id'   => 'fleurdesel_social_profile',
					'name' => esc_html__( 'Social Links', 'fleurdesel' ),
					'type' => 'title',
					'desc' => esc_html__( 'Edit your social profiles.', 'fleurdesel' ),
				) );

				foreach ( $this->profile_providers as $id => $name ) {
					$id = sprintf( 'fleurdesel_social_profile_%1$s', sanitize_key( $id ) );

					$tab->add_field( array(
						'name' => $name,
						'id'   => $id,
						'type' => 'text_url',
					) );
				}
			}, 55 );
		}

		/**
		 * Get user active share.
		 *
		 * @return array
		 */
		public function get_share() {
			$return = array();

			foreach ( $this->share_providers as $key => $value ) {
				$options[ $key ] = 'fleurdesel_social_share_' . $key;
			}

			foreach ( $options as $id => $active ) {

				$active = fleurdesel_option( $active );

				if ( ! $active || ! isset( $this->share_providers[ $id ] ) ) {
					continue;
				}

				$return[ $id ] = $this->share_providers[ $id ];
				$return[ $id ]['link'] = $this->parse_share_link( $return[ $id ]['link'] );
			}

			return $return;
		}

		/**
		 * Get user active profile.
		 *
		 * @return array
		 */
		public function get_profile() {
			$return = array();

			foreach ( $this->profile_providers as $key => $value ) {
				$options[ $key ] = 'fleurdesel_social_profile_' . $key;
			}

			foreach ( $options as $id => $link ) {

				$link = fleurdesel_option( $link );

				if ( empty( $link ) || ! isset( $this->profile_providers[ $id ] ) ) {
					continue;
				}

				$return[ $id ] = array(
					'name' => $this->profile_providers[ $id ],
					'link' => $link,
				);
			}

			return $return;
		}

		/**
		 * Parse share raw link with the_post.
		 *
		 * @param  string $link Raw link to parser.
		 * @return string
		 */
		private function parse_share_link( $link ) {
			/*
			if ( ! in_the_loop() ) {
				return '';
			}
			*/

			$link = str_replace( array( '{url}', '{title}' ), array( get_permalink(), get_the_title() ), $link );

			return apply_filters( 'fleurdesel_social_parse_sharing', $link );
		}
	}
endif;

if ( ! function_exists( 'fleurdesel_social' ) ) :
	/**
	 * Social core class instance.
	 *
	 * @return Fleurdesel_Social_Core
	 */
	function fleurdesel_social() {
		return Fleurdesel_Social_Core::get_instance();
	}
endif;

/**
 * Init the social core.
 */
$GLOBALS['Fleurdesel_Social_Core'] = fleurdesel_social();
