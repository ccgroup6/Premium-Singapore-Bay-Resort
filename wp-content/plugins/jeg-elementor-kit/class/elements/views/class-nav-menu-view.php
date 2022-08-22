<?php
/**
 * Nav Menu View Class
 *
 * @package jeg-elementor-kit
 * @author Jegstudio
 * @since 1.0.0
 */

namespace Jeg\Elementor_Kit\Elements\Views;

/**
 * Class Nav_Menu_View
 *
 * @package Jeg\Elementor_Kit\Elements\Views
 */
class Nav_Menu_View extends View_Abstract {
	/**
	 * Build block content
	 */
	public function build_content() {
		$menu_direction         = esc_attr( $this->attribute['sg_menu_direction'] );
		$menu_breakpoint        = esc_attr( $this->attribute['sg_menu_breakpoint'] );
		$submenu_position       = esc_attr( $this->attribute['sg_menu_sub_position'] );
		$mobile_logo_size       = esc_attr( $this->attribute['sg_mobile_menu_logo_size_imagesize_size'] );
		$mobile_logo_image      = $this->render_image_element( $this->attribute['sg_mobile_menu_logo'], $mobile_logo_size );
		$submenu_click_on_title = 'yes' === $this->attribute['sg_mobile_menu_submenu_click'] ? 'submenu-click-title' : 'submenu-click-icon';
		$mobile_menu_icon       = $this->render_icon_element( $this->attribute['sg_mobile_menu_icon'] );
		$mobile_close_icon      = $this->render_icon_element( $this->attribute['sg_mobile_close_icon'] );
		$item_indicator         = $this->render_icon_element( $this->attribute['st_submenu_item_indicator'] );
		$item_indicator         = esc_attr( preg_replace( '~[\r\n\s]+~', ' ', $item_indicator ) );

		$menu = wp_nav_menu(
			array(
				'menu'            => esc_attr( $this->attribute['sg_menu_choose'] ),
				'menu_class'      => 'jkit-menu jkit-menu-direction-' . $menu_direction . ' jkit-submenu-position-' . $submenu_position,
				'container_class' => 'jkit-menu-container',
				'echo'            => false,
			)
		);

		if ( 'default' === $this->attribute['sg_mobile_menu_link'] ) {
			$mobile_logo_image = '<a href="' . home_url() . '" class="jkit-nav-logo">' . $mobile_logo_image . '</a>';
		} else {
			$mobile_logo_image = $this->render_url_element( $this->attribute['sg_mobile_menu_custom_link'], null, 'jkit-nav-logo', $mobile_logo_image );
		}

		$output =
		'<button class="jkit-hamburger-menu">' . $mobile_menu_icon . '</button>
        <div class="jkit-menu-wrapper">' . $menu . '
            <div class="jkit-nav-identity-panel">
                <div class="jkit-nav-site-title">' . $mobile_logo_image . '</div>
                <button class="jkit-close-menu">' . $mobile_close_icon . '</button>
            </div>
        </div>
        <div class="jkit-overlay"></div>';

		return $this->render_wrapper( 'nav-menu', $output, array( 'break-point-' . $menu_breakpoint, $submenu_click_on_title ), array( 'item-indicator' => $item_indicator ) );
	}
}
