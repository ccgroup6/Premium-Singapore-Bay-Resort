<?php
/**
 * Post Content View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 2.2.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

/**
 * Class Post_Content_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Post_Content_View extends View_Abstract {
	/**
	 * Build block content
	 *
	 * @return mixed
	 */
	public function build_content() {
		if ( jeg_is_editor_elementor() ) {
			$post = get_posts(
				array(
					'post_type'   => 'post',
					'orderby'     => 'rand',
					'numberposts' => 1,
				)
			);
			$post = $post[0];
		} else {
			$post = get_post();
		}
		return $this->render_wrapper( 'post-content', do_shortcode( $post->post_content ) );
	}
}
