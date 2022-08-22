<?php
/**
 * Jet_Search_Ajax_Handlers class
 *
 * @package   jet-search
 * @author    Zemez
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Jet_Search_Ajax_Handlers' ) ) {

	/**
	 * Define Jet_Search_Ajax_Handlers class
	 */
	class Jet_Search_Ajax_Handlers {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   Jet_Search_Ajax_Handlers
		 */
		private static $instance = null;

		/**
		 * Ajax action.
		 *
		 * @var string
		 */
		private $action = 'jet_ajax_search';

		/**
		 * Has navigation.
		 *
		 * @var bool
		 */
		public $has_navigation = false;

		/**
		 * Search query.
		 *
		 * @var array
		 */
		public $search_query = array();

		/**
		 * Table alias.
		 *
		 * @var string
		 */
		private $postmeta_table_alias = 'jetsearch';

		/**
		 * Constructor for the class
		 */
		public function init() {

			// Set search query settings on the search result page
			add_action( 'pre_get_posts', array( $this, 'set_search_query' ) );

			// Search in custom fields
			add_filter( 'posts_clauses', array( $this, 'cf_search_clauses' ), 99, 2 );

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {

				add_action( "wp_ajax_{$this->action}",        array( $this, 'get_search_results' ) );
				add_action( "wp_ajax_nopriv_{$this->action}", array( $this, 'get_search_results' ) );

				add_action( 'wp_ajax_jet_search_get_query_control_options', array( $this, 'get_query_control_options' ) );
				add_action( 'wp_ajax_jet_advanced_list_block_get_svg', array( $this, 'get_icon_svg' ) );
			}

			// Set Jet Smart Filters extra props
			add_filter( 'jet-smart-filters/filters/localized-data', array( $this, 'set_jet_smart_filters_extra_props' ) );

			// Set JetEngine extra props
			add_filter( 'jet-engine/listing/grid/posts-query-args', array( $this, 'set_jet_engine_extra_props' ), -10, 3 );
		}

		/**
		 * Get ajax action.
		 *
		 * @since  1.1.2
		 * @return string
		 */
		public function get_ajax_action() {
			return $this->action;
		}

		/**
		 * Returns a SVG code of selected icon
		 *
		 * @return [type] [description]
		 */
		public function get_icon_svg() {

			if ( ! current_user_can( 'upload_files' ) ) {
				wp_send_json_error( 'You are not allowed to do this' );
			}

			$media_id = ! empty( $_GET['media_id'] ) ? absint( $_GET['media_id'] ) : false;

			if ( ! $media_id ) {
				wp_send_json_error( 'Media ID not found in the request' );
			}

			$mime = get_post_mime_type( $media_id );

			if ( ! $mime || 'image/svg+xml' !== $mime ) {
				wp_send_json_error( 'This media type is not supported, please use SVG image' );
			}

			$file = get_attached_file( $media_id );

			ob_start();
			include $file;
			$content = apply_filters( 'jet-search/get-svg/content', ob_get_clean(), $media_id );

			wp_send_json_success( $content );

		}

		/**
		 * Set search query settings on the search result page.
		 *
		 * @param object $query
		 */
		public function set_search_query( $query ) {

			if ( ! is_admin() && is_search() && $query->is_main_query() ) {

				$form_settings = $this->get_form_settings();

				if ( ! empty( $form_settings ) ) {
					$this->set_query_settings( $form_settings );

					$query->query_vars = array_merge( $query->query_vars, $this->search_query );
				}
			}
		}

		/**
		* Set Jet Smart Filters extra props.
		*/
		public function set_jet_smart_filters_extra_props( $data ) {

			if ( ! is_search() ) {
				return $data;
			}

			$settings = $this->get_form_settings();

			if ( ! empty( $settings ) ) {
				$data['extra_props']['jet_ajax_search_settings'] = json_encode( $settings );
			}

			return $data;
		}

		/**
		 * Set JetEngine extra props.
		 */
		public function set_jet_engine_extra_props( $args, $render, $settings ) {

			$is_archive_template = isset( $settings['is_archive_template'] ) && 'yes' === $settings['is_archive_template'];

			if ( ! is_search() || ! $is_archive_template ) {
				return $args;
			}

			$settings = $this->get_form_settings();

			if ( ! empty( $settings ) ) {
				$args[ $this->action ] = true;
				$args['jet_ajax_search_settings'] = $settings;
			}

			return $args;
		}

		/**
		 * Get form settings on the search result page.
		 *
		 * @return array
		 */
		public function get_form_settings() {

			$form_settings = array();

			if ( ! empty( $_REQUEST['jet_ajax_search_settings'] ) ) {
				$form_settings = $_REQUEST['jet_ajax_search_settings'];
				$form_settings = stripcslashes( $form_settings );
				$form_settings = json_decode( $form_settings );
				$form_settings = get_object_vars( $form_settings );
			} elseif ( ! empty( $_REQUEST['query']['jet_ajax_search_settings'] ) ) {
				$form_settings = $_REQUEST['query']['jet_ajax_search_settings'];
			}

			return $form_settings;
		}

		/**
		 * Get search results.
		 */
		public function get_search_results() {

			//if ( ! isset( $_GET['nonce'] ) || ! wp_verify_nonce( $_GET['nonce'], $this->action ) ) {
			//	wp_send_json_error( array(
			//		'message' => 'Invalid Nonce!'
			//	) );
			//	return;
			//}

			$data = $this->get_search_data();

			if ( empty( $data ) ) {
				wp_send_json_error( array(
					'message' => 'Empty Search Data'
				) );
				return;
			}

			wp_send_json_success( $data );
		}

		/**
		 * Get search data.
		 *
		 * @return array|bool
		 */
		public function get_search_data() {
			if ( empty( $_GET['data'] ) ) {
				return false;
			}

			$data                                      = $_GET['data'];
			$this->search_query['s']                   = urldecode( $data['value'] );
			$this->search_query['nopaging']            = false;
			$this->search_query['ignore_sticky_posts'] = false;
			$this->search_query['posts_per_page']      = ( int ) $data['limit_query_in_result_area'];
			$this->search_query['post_status']         = 'publish';

			$this->set_query_settings( $data );

			add_filter( 'wp_query_search_exclusion_prefix', '__return_empty_string' );

			require jet_search()->plugin_path( 'includes/jet-search-tax-query.php' );

			if ( '' != $data['search_in_taxonomy'] && '' != $data['search_in_taxonomy_source'] ) {
				$taxonomies       = $data['search_in_taxonomy_source'];
				$tax_query_source = array( 'relation' => 'OR' );

				foreach ( $taxonomies as $key => $value ) {
					$tax_query_source[] = array(
						'taxonomy' => $value,
						'field'    => 'name', // keep this
						'terms'    => $this->search_query['s']
					);
				}

				$tax_query = new Jet_Search_Tax_Query( $tax_query_source );

				global $wpdb;

				$tax_sql = $tax_query->get_sql( $wpdb->posts, 'ID' );

				add_filter( 'posts_join', function( $sql ) use ( $tax_sql ) {
					return $sql . $tax_sql['join'] . ' ';
				} );

				add_filter( 'posts_where', function( $sql ) use ( $tax_sql ) {
					return $sql . $tax_sql['where'] . ' ';
				} );
			}

			$search   = new WP_Query( $this->search_query );

			if ( function_exists( 'relevanssi_do_query' ) ) {
				relevanssi_do_query( $search );
			}

			$response = array(
				'error'      => false,
				'post_count' => 0,
				'message'    => '',
				'posts'      => null,
			);

			remove_filter( 'wp_query_search_exclusion_prefix', '__return_empty_string' );

			if ( is_wp_error( $search ) ) {
				$response['error']   = true;
				$response['message'] = esc_html( $data['server_error'] );

				return $response;
			}

			if ( empty( $search->post_count ) ) {
				$response['message'] = esc_html( $data['negative_search'] );

				return $response;
			}

			$data['limit_query'] = $this->extract_limit_query( $data );

			$data['post_count'] = $search->post_count;
			$data['columns']    = ceil( $data['post_count'] / $data['limit_query'] );

			if ( '' != $data['highlight_searched_text'] ) {
				$response['search_value']     = $this->search_query['s'];
				$response['search_highlight'] = true;
			} else {
				$response['search_highlight'] = false;
			}

			$response['posts']              = array();
			$response['columns']            = $data['columns'];
			$response['limit_query']        = $data['limit_query'];
			$response['post_count']         = $data['post_count'];
			$response['results_navigation'] = $this->get_results_navigation( $data );

			$link_target_attr = ( isset( $data['show_result_new_tab'] ) && 'yes' === $data['show_result_new_tab'] ) ? '_blank' : '';

			foreach ( $search->posts as $key => $post ) {

				$response['posts'][ $key ] = array(
					'title'            => $post->post_title,
					'before_title'     => Jet_Search_Template_Functions::get_meta_fields( $data, $post, 'title_related', 'jet-search-title-fields', array( 'before' ) ),
					'after_title'      => Jet_Search_Template_Functions::get_meta_fields( $data, $post, 'title_related', 'jet-search-title-fields', array( 'after' ) ),
					'content'          => Jet_Search_Template_Functions::get_post_content( $data, $post ),
					'before_content'   => Jet_Search_Template_Functions::get_meta_fields( $data, $post, 'content_related', 'jet-search-content-fields', array( 'before' ) ),
					'after_content'    => Jet_Search_Template_Functions::get_meta_fields( $data, $post, 'content_related', 'jet-search-content-fields', array( 'after' ) ),
					'thumbnail'        => Jet_Search_Template_Functions::get_post_thumbnail( $data, $post ),
					'link'             => esc_url( get_permalink( $post->ID ) ),
					'link_target_attr' => $link_target_attr,
					'price'            => Jet_Search_Template_Functions::get_product_price( $data, $post ),
					'rating'           => Jet_Search_Template_Functions::get_product_rating( $data, $post ),
				);

				$custom_post_data = apply_filters( 'jet-search/ajax-search/custom-post-data', array(), $data, $post );

				if ( ! empty( $custom_post_data ) ) {
					$response['posts'][ $key ] = array_merge( $response['posts'][ $key ], $custom_post_data );
				}

				if ( ! $this->has_navigation && $key === $data['limit_query'] - 1 ) {
					break;
				}
			}

			return $response;
		}

		/**
		 * Set search query settings.
		 *
		 * @param array $args
		 */
		protected function set_query_settings( $args = array() ) {
			if ( $args ) {
				$this->search_query[ $this->action ] = true;
				$this->search_query['cache_results'] = true;
				$this->search_query['post_type']     = $args['search_source'];
				$this->search_query['order']         = isset( $args['results_order'] ) ? $args['results_order'] : '';
				$this->search_query['orderby']       = isset( $args['results_order_by'] ) ? $args['results_order_by'] : '';
				$this->search_query['tax_query']     = array( 'relation' => 'AND' );
				$this->search_query['sentence']      = isset( $args['sentence'] ) ? filter_var( $args['sentence'], FILTER_VALIDATE_BOOLEAN ) : false;
				$this->search_query['post_status']   = 'publish';

				// Include specific terms
				if ( ! empty( $args['category__in'] ) ) {
					$tax = ! empty( $args['search_taxonomy'] ) ? $args['search_taxonomy'] : 'category';

					array_push(
						$this->search_query['tax_query'],
						array(
							'taxonomy' => $tax,
							'field'    => 'id',
							'operator' => 'IN',
							'terms'    => $args['category__in'],
						)
					);
				} else if ( ! empty( $args['include_terms_ids'] ) ) {

					$include_tax_query = array( 'relation' => 'OR' );
					$terms_data        = $this->prepare_terms_data( $args['include_terms_ids'] );

					foreach ( $terms_data as $taxonomy => $terms_ids ) {
						$include_tax_query[] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'id',
							'operator' => 'IN',
							'terms'    => $terms_ids,
						);
					}

					array_push(
						$this->search_query['tax_query'],
						$include_tax_query
					);
				}

				// Exclude specific terms
				if ( ! empty( $args['exclude_terms_ids'] ) ) {

					$exclude_tax_query = array( 'relation' => 'OR' );
					$terms_data        = $this->prepare_terms_data( $args['exclude_terms_ids'] );

					foreach ( $terms_data as $taxonomy => $terms_ids ) {
						$exclude_tax_query[] = array(
							'taxonomy' => $taxonomy,
							'field'    => 'id',
							'operator' => 'NOT IN',
							'terms'    => $terms_ids,
						);
					}

					array_push(
						$this->search_query['tax_query'],
						$exclude_tax_query
					);
				}

				// Exclude specific posts
				if ( ! empty( $args['exclude_posts_ids'] ) ) {
					$this->search_query['post__not_in'] = $args['exclude_posts_ids'];
				}

				// Current Query
				if ( ! empty( $args['current_query'] ) ) {
					$this->search_query = array_merge( $this->search_query, (array) $args['current_query'] );
				}

				do_action( 'jet-search/ajax-search/search-query', $this, $args );
			}
		}

		/**
		 * Get Query control options list.
		 *
		 * @since  2.0.0
		 * @return void
		 */
		function get_query_control_options() {

			$data = $_REQUEST;

			if ( ! isset( $data['query_type'] ) ) {
				wp_send_json_error();
				return;
			}

			$results = array();

			switch ( $data['query_type'] ) {
				case 'terms':

					$terms_args = array(
						'hide_empty' => false,
					);

					if ( ! empty( $data['q'] ) ) {
						$terms_args['search'] = $data['q'];
					}

					if ( ! empty( $data['post_type'] ) ) {
						$terms_args['taxonomy'] = get_object_taxonomies( $data['post_type'], 'names' );
					} else {
						$terms_args['taxonomy'] = get_taxonomies( array( 'show_in_nav_menus' => true ), 'names' );
					}

					if ( ! empty( $data['ids'] ) ) {
						$terms_args['include'] = $data['ids'];
					}

					$terms = get_terms( $terms_args );

					global $wp_taxonomies;

					foreach ( $terms as $term ) {

						$results[] = array(
							'id'   => $term->term_id,
							'text' => sprintf( '%1$s: %2$s', $wp_taxonomies[ $term->taxonomy ]->label, $term->name ),
						);
					}

					break;

				case 'posts':

					$query_args = array(
						'post_type'           => 'any',
						'posts_per_page'      => - 1,
						'suppress_filters'    => false,
						'ignore_sticky_posts' => true,
					);

					if ( ! empty( $data['q'] ) ) {
						$query_args['s_title'] = $data['q'];
						$query_args['orderby'] = 'relevance';
					}

					if ( ! empty( $data['post_type'] ) ) {
						$query_args['post_type'] = $data['post_type'];
					}

					if ( ! empty( $data['ids'] ) ) {
						$query_args['post__in'] = $data['ids'];
					}

					add_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10, 2 );

					$posts = get_posts( $query_args );

					remove_filter( 'posts_where', array( $this, 'force_search_by_title' ), 10 );

					foreach ( $posts as $post ) {
						$results[] = array(
							'id'   => $post->ID,
							'text' => sprintf( '%1$s: %2$s', ucfirst( $post->post_type ), $post->post_title ),
						);
					}

					break;
			}

			$data = array(
				'results' => $results,
			);

			wp_send_json_success( $data );
		}

		/**
		 * Force query to look in post title while searching.
		 *
		 * @since  2.0.0
		 * @param  string $where
		 * @param  object $query
		 * @return string
		 */
		public function force_search_by_title( $where, $query ) {

			$args = $query->query;

			if ( ! isset( $args['s_title'] ) ) {
				return $where;
			}

			global $wpdb;

			$search = esc_sql( $wpdb->esc_like( $args['s_title'] ) );
			$where .= " AND {$wpdb->posts}.post_title LIKE '%$search%'";

			return $where;
		}

		/**
		 * Prepare terms data for tax query
		 *
		 * @since  2.0.0
		 * @param  array $terms_ids
		 * @return array
		 */
		public function prepare_terms_data( $terms_ids = array() ) {

			$result = array();

			foreach ( $terms_ids as $term_id ) {
				$term     = get_term( $term_id );
				$taxonomy = $term->taxonomy;

				$result[ $taxonomy ][] = $term_id;
			}

			return $result;
		}

		/**
		 * Get custom fields keys for search
		 *
		 * @since  2.0.0
		 * @return array|bool
		 */
		public function get_cf_search_keys() {

			if ( isset( $_GET['action'] ) && $this->action === $_GET['action'] && ! empty( $_GET['data']['custom_fields_source'] ) ) {
				$cf_source = $_GET['data']['custom_fields_source'];

			} else {
				$settings  = $this->get_form_settings();
				$cf_source = ! empty( $settings['custom_fields_source'] ) ? $settings['custom_fields_source'] : false;
			}

			if ( empty( $cf_source ) ) {
				return false;
			}

			return explode( ',', str_replace( ' ', '', $cf_source ) );
		}

		/**
		 * Modify the WHERE and JOIN clauses of the query.
		 *
		 * @param  array  $args
		 * @param  object $query
		 * @return array
		 */
		public function cf_search_clauses( $args, $query ) {

			if ( ! $query->get( $this->action ) ) {
				return $args;
			}

			$cf_keys = $this->get_cf_search_keys();

			if ( ! $cf_keys ) {
				return $args;
			}

			global $wpdb;

			// Modify the JOIN clause.
			$args['join'] .= " LEFT JOIN {$wpdb->postmeta} {$this->postmeta_table_alias} ON {$wpdb->posts}.ID = {$this->postmeta_table_alias}.post_id ";

			// Modify the WHERE clause.
			$cf_where = '';
			$or_op    = '';

			foreach ( $cf_keys as $cf_key ) {
				$cf_where .= "{$or_op}({$this->postmeta_table_alias}.meta_key = '{$cf_key}' AND {$this->postmeta_table_alias}.meta_value LIKE $1)";
				$or_op = ' OR ';
			}

			$args['where'] = preg_replace(
				"/\(\s*{$wpdb->posts}.post_content\s+LIKE\s*(\'[^\']+\')\s*\)/",
				"({$wpdb->posts}.post_content LIKE $1) OR {$cf_where}", $args['where'] );

			return $args;
		}

		/**
		 * Extract limit query from data array.
		 *
		 * @since  2.0.0
		 * @param  array $data
		 * @return int
		 */
		public function extract_limit_query( $data ) {
			$limit_query = ! empty( $data['limit_query'] ) ? $data['limit_query'] : 5;

			if ( empty( $data['deviceMode'] ) ) {
				return $limit_query;
			}

			$limit_query_tablet = ! empty( $data['limit_query_tablet'] ) ? $data['limit_query_tablet'] : $limit_query;
			$limit_query_mobile = ! empty( $data['limit_query_mobile'] ) ? $data['limit_query_mobile'] : $limit_query_tablet;

			switch ( $data['deviceMode'] ) {
				case 'tablet':
					$limit_query = $limit_query_tablet;
					break;

				case 'mobile':
					$limit_query = $limit_query_mobile;
					break;
			}

			return $limit_query;
		}

		/**
		 * Return result area navigation.
		 *
		 * @param array $settings
		 *
		 * @return array
		 */
		public function get_results_navigation( $settings = array() ) {
			$navigation_container_html = apply_filters(
				'jet-search/ajax-search/navigation-container-html',
				'<div class="jet-ajax-search__navigation-container">%s</div>'
			);

			$navigation_types = apply_filters(
				'jet-search/ajax-search/navigation-types',
				array( 'bullet_pagination', 'number_pagination', 'navigation_arrows' )
			);

			$header_navigation = '';
			$footer_navigation = '';
			if ( $settings['limit_query'] < $settings['post_count'] ) {

				foreach ( $navigation_types as $type ) {
					if ( ! isset( $settings[ $type ] ) ) {
						continue;
					}

					if ( ! $settings[ $type ] ) {
						continue;
					}

					$buttons = $this->get_navigation_buttons_html( $settings, $type );

					if ( empty( $buttons ) ) {
						continue;
					}

					$this->has_navigation = true;

					switch ( $settings[ $type ] ) {
						case 'in_header':
							$header_navigation .= sprintf( $navigation_container_html, $buttons );
							break;

						case 'in_footer':
							$footer_navigation .= sprintf( $navigation_container_html, $buttons );
							break;

						case 'both':
							$header_navigation .= sprintf( $navigation_container_html, $buttons );
							$footer_navigation .= sprintf( $navigation_container_html, $buttons );
							break;
					}
				}
			}

			return array(
				'in_header' => $header_navigation,
				'in_footer' => $footer_navigation,
			);
		}

		/**
		 * Get results navigation buttons html.
		 *
		 * @param array  $settings
		 * @param string $type
		 *
		 * @return string
		 */
		public function get_navigation_buttons_html( $settings = array(), $type = 'bullet_pagination' ) {
			$output_html = '';
			$bullet_html = apply_filters( 'jet-search/ajax-search/navigate-button-html', '<div role=button class="jet-ajax-search__navigate-button %1$s" data-number="%2$s"></div>' );

			switch ( $type ) {
				case 'bullet_pagination':
					$button_class = 'jet-ajax-search__bullet-button';

				case 'number_pagination':
					$button_class = isset( $button_class ) ? $button_class : 'jet-ajax-search__number-button';

					for ( $i = 0; $i < $settings['columns']; $i++ ) {
						$active_button_class = ( $i === 0 ) ? ' jet-ajax-search__active-button' : '' ;
						$output_html .= sprintf( $bullet_html, $button_class . $active_button_class, $i + 1 );
					}
					break;

				case 'navigation_arrows':
					$prev_button = apply_filters( 'jet-search/ajax-search/prev-button-html', '<div role=button class="jet-ajax-search__prev-button jet-ajax-search__arrow-button jet-ajax-search__navigate-button jet-ajax-search__navigate-button-disable" data-direction="-1">%s</div>' );
					$next_button = apply_filters( 'jet-search/ajax-search/next-button-html', '<div role=button class="jet-ajax-search__next-button jet-ajax-search__arrow-button jet-ajax-search__navigate-button" data-direction="1">%s</div>' );
					$arrow       = Jet_Search_Tools::get_svg_arrows( $settings['navigation_arrows_type'] );
					$output_html = sprintf( $prev_button . $next_button, $arrow['left'], $arrow['right'] );
					break;
			}

			return $output_html;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return Jet_Search_Ajax_Handlers
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

/**
 * Returns instance of Jet_Search_Ajax_Handlers
 *
 * @return Jet_Search_Ajax_Handlers
 */
function jet_search_ajax_handlers() {
	return Jet_Search_Ajax_Handlers::get_instance();
}
