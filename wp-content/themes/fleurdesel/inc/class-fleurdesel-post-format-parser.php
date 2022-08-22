<?php
/**
 * A tiny class parser content by post format.
 *
 * @package Fleurdesel
 */

if ( ! class_exists( 'Fleurdesel_Post_Format_Parser' ) ) :
	/**
	 * Fleurdesel_Post_Format class.
	 */
	class Fleurdesel_Post_Format_Parser {
		/**
		 * Original post content.
		 *
		 * @var string
		 */
		protected $data;

		/**
		 * Post format of content.
		 *
		 * @var string
		 */
		protected $post_format;

		/**
		 * Original post content.
		 *
		 * @var string
		 */
		protected $raw_content;

		/**
		 * Constructor of class.
		 *
		 * @param string $raw_content Original post content via get_the_content().
		 * @param string $post_format Format type of the post.
		 */
		public function __construct( $raw_content, $post_format = null ) {
			// An array of post format supported by WordPress.
			$supported_format = get_post_format_strings();

			if ( is_null( $post_format ) && in_the_loop() ) {
				$post_format = get_post_format();
			}

			$this->post_format = isset( $supported_format[ $post_format ] ) ? $post_format : 'standard';
			$this->raw_content = $raw_content;

			// Doing parser the content.
			$call_method = 'do_parse_' . $this->post_format;
			if ( method_exists( $this, $call_method ) ) {
				$this->data = call_user_func( array( $this, $call_method ) );
			}
		}

		/**
		 * Get post format.
		 *
		 * @return string
		 */
		public function get_post_format() {
			return $this->post_format;
		}

		/**
		 * Get parser data.
		 *
		 * @return string
		 */
		public function get_data() {
			return $this->data;
		}

		/**
		 * Get origin content.
		 *
		 * @return string
		 */
		public function get_raw_content() {
			return $this->raw_content;
		}

		/**
		 * Get the post content.
		 *
		 * @return string
		 */
		public function get_content() {
			$content = $this->get_raw_content();

			if ( ! empty( $this->data['shortcode'] ) ) {
				$content = str_replace( $this->data['shortcode'], '', $content );
			}

			return $content;
		}

		/**
		 * Display the post content.
		 */
		public function the_content() {
			$content = $this->get_content();

			/**
			 * Filter the post content.
			 *
			 * @param string $content Content of the current post.
			 */
			$content = apply_filters( 'the_content', $content );
			$content = str_replace( ']]>', ']]&gt;', $content );

			echo $content; // phpcs:ignore WordPress.Security.EscapeOutput
		}

		/**
		 * Do parser post format quote.
		 *
		 * @return array
		 */
		protected function do_parse_quote() {
			preg_match( '/<blockquote.*?>((.|\n)*?)<\/blockquote>/', $this->raw_content, $matches );

			if ( ! isset( $matches[0] ) ) {
				return;
			}

			$cite = '';
			$shortcode = $matches[0];

			if ( preg_match( '/cite=["|\'](.*)["|\']/', $shortcode, $cites ) ||
				preg_match( '/<cite.*?>(.*)<\/cite>/', $shortcode, $cites ) ) {
				$cite = strip_tags( $cites[1] );
			}

			$quote = strip_tags( $matches[1] );
			$quote = trim( str_replace( $cite, '', $quote ) );

			return $this->return_args( [
				'cite'      => $cite,
				'quote'     => $quote,
				'output'    => $shortcode,
				'shortcode' => $shortcode,
			] );
		}

		/**
		 * Do parser post format gallery.
		 *
		 * @return array
		 */
		protected function do_parse_gallery() {
			preg_match( '/\[gallery.+?\]/', $this->raw_content, $matches );

			if ( ! isset( $matches[0] ) ) {
				return;
			}

			$shortcode = $matches[0];
			if ( ! preg_match( '/ids=["|\'](.*)["|\']/', $shortcode, $matches_ids ) ) {
				return;
			}

			$ids = array_filter( explode( ',', $matches_ids[1] ), 'is_numeric' );
			$ids = array_map( 'absint', $ids );

			if ( empty( $ids ) ) {
				return;
			}

			return $this->return_args( array(
				'ids' => array_values( $ids ),
				'output' => do_shortcode( $shortcode ),
				'shortcode' => $shortcode,
			) );
		}

		/**
		 * Do parser post format link.
		 *
		 * @return array
		 */
		protected function do_parse_link() {
			$links = wp_extract_urls( $this->raw_content );

			if ( ! isset( $links[0] ) ) {
				return;
			}

			return $this->return_args( array(
				'link' => $links[0],
				'output' => $links[0],
				'shortcode' => $links[0],
			) );
		}

		/**
		 * Do parser post format audio.
		 *
		 * @return array
		 */
		protected function do_parse_audio() {
			preg_match( '/(\[audio.+?\])(\[\/audio\])?/', $this->raw_content, $matches );

			if ( isset( $matches[0] ) ) {
				$output = do_shortcode( $matches[0] );

				if ( ! $output ) {
					return;
				}

				return $this->return_args( array(
					'output' => $output,
					'shortcode' => $matches[0],
				) );
			}

			return $this->parse_oembed( $this->raw_content );
		}

		/**
		 * Do parser post format video.
		 *
		 * @return array
		 */
		protected function do_parse_video() {
			preg_match( '/(\[video.+?\])(\[\/video\])?/', $this->raw_content, $matches );

			if ( isset( $matches[0] ) ) {
				$output = do_shortcode( $matches[0] );

				if ( ! $output ) {
					return;
				}

				return $this->return_args( array(
					'output' => $output,
					'shortcode' => $matches[0],
				) );
			}

			return $this->parse_oembed( $this->raw_content );
		}

		/**
		 * //
		 *
		 * @param  string $content The post content.
		 * @return array
		 */
		protected function parse_oembed( $content ) {
			global $wp_embed;

			// We parser from [embed] shortcode first.
			if ( preg_match( '/\[embed.+?\](.*)\[\/embed\]/', $content, $matches ) ) {
				$shortcode = $matches[0];

				$embed_shortcode = $wp_embed->run_shortcode( $shortcode );
				if ( ! $embed_shortcode ) {
					return;
				}

				return $this->return_args( array(
					'output' => $embed_shortcode,
					'shortcode' => $shortcode,
				) );
			}

			// If [embed] not found, try extract first link in content.
			$links = wp_extract_urls( $this->raw_content );
			if ( ! isset( $links[0] ) ) {
				return;
			}

			$embed_shortcode = wp_oembed_get( $links[0] );
			if ( ! $embed_shortcode ) {
				return;
			}

			return $this->return_args( array(
				'output' => $embed_shortcode,
				'shortcode' => $links[0],
			) );
		}

		/**
		 * Make sure we return data correctly.
		 *
		 * @param  array $args The response args.
		 * @return array
		 */
		protected function return_args( array $args ) {
			return wp_parse_args( $args, array( 'output' => '', 'shortcode' => '' ) );
		}
	}
endif;
