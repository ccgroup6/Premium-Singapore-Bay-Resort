<?php
/*
	Initiated when on the "public" web site,
	i.e. - not an Admin panel.
*/

// Exit if .php file accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
  Earliest Action Hook possible is 'template_redirect',
	AFTER Rewrite: URL changed with Pretty Permalinks and
	correcting the presence or absence of www. in domain name.

	Unfortunately, a wpengine.com (hosting site) mandatory plugin
	appears to be blocking this hook, so the next hook in time sequence
	is being used: 'get_header'

	Elementor was also screwing up the force login hook. By flipping bacl
	to template_redirect, it seems to work. Elementor on WPEngine? Who knows?
*/
$settings = get_option( 'jr_ps_settings' );
if ( isset( $settings['compatibility_mode'] ) ) {
	$compatibility_mode = $settings['compatibility_mode'];
} else {
	$compatibility_mode = 'STANDARD';
}
switch ( $compatibility_mode ) {
	case 'STANDARD':
		add_action( 'get_header', 'jr_ps_force_login' );
		break;
	case 'ELEMENTOR':
		add_action( 'template_redirect', 'jr_ps_force_login' );
		break;
}

add_action( 'login_init', 'jr_ps_login' );
add_filter( 'login_url', 'jr_ps_login_url' );
add_action( 'wp_login_failed', 'jr_ps_login_failed' );
add_action( 'wp_authenticate', 'jr_ps_wp_authenticate', 10, 2 );

if ( $settings['wplogin_php'] ) {
	/*
	  Run this Filter "last" (Priority=100) to be sure that Paid Memberships Pro
		has already runs its Filter.
	*/
	add_filter( 'login_redirect', 'jr_ps_login_redirect_filter', 100, 3 );
	/*
	  Since it is defined when the plugin is loaded,
		wait to check for the Paid Memberships Pro function.
	*/
	add_action( 'plugins_loaded', 'jr_ps_plugins_loaded' );
	function jr_ps_plugins_loaded() {
		if ( function_exists( 'pmpro_login_redirect' ) ) {
			add_filter( 'pmpro_login_redirect_url', 'jr_ps_pmpro_login_redirect_url_filter', 10, 3 );
			function jr_ps_pmpro_login_redirect_url_filter( $redirect_to, $requested_redirect_to, $user ) {
				$redirect = jr_ps_login_redirect_filter( $redirect_to, $requested_redirect_to, $user );
				DEFINE( 'JR_PS_PMPRO_RUN', true );

				return $redirect;
			}
		}
	}

	function jr_ps_login_redirect_filter( $redirect_to, $requested_redirect_to, $user ) {
		if ( ! defined( 'JR_PS_PMPRO_RUN' ) ) {
			if ( '' === $requested_redirect_to ) {
				$redirect_to = jr_ps_after_login_url();
			}
		}

		return $redirect_to;
	}
}

/**
 * Login Detection
 *
 * Set a global variable, $jr_ps_is_login, whenever a login occurs
 *
 * @return   NULL                Nothing is returned
 */
function jr_ps_login() {
	global $jr_ps_is_login;
	$jr_ps_is_login = true;

	return true;
}

/**
 * Present a login screen to anyone not logged in
 *
 * Check for already logged in or just logged in.
 * Only called when is_admin() is FALSE
 *
 * @return   NULL                Nothing is returned
 */
function jr_ps_force_login() {
	/*
	  return statements are performed only if User does not need to login.

		First, check if User is on a Login panel.
	*/
	global $jr_ps_is_login, $jr_ps_plugin_data;
	if ( isset( $jr_ps_is_login ) ) {
		return;
	}

	$settings = get_option( 'jr_ps_settings' );

	/*
	  Next, check if User is already logged in, and has a role on this site.
	*/
	$role = true;
	if ( is_user_logged_in() ) {
		if ( is_multisite() ) {
			if ( is_user_member_of_blog() ) {
				return;
			} else {
				/*
				  User is logged on to a Site where he/she has no Role.
				*/
				if ( $settings['check_role'] ) {
					$role = false;
				} else {
					/*
					  User can see all of public site.
					*/
					return;
				}
			}
		} else {
			return;
		}
	}

	/*
	  URL of current page without http://, i.e. - starting with domain
	*/
	if ( isset( $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI'] ) ) {
		$current_url = sanitize_text_field( $_SERVER['SERVER_NAME'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );
	}

	if ( $settings['excl_home'] && jr_v1_same_url( get_home_url(), $current_url ) ) {
		return;
	}
	if ( $settings['custom_login'] && ! empty( $settings['login_url'] ) && jr_v1_same_url( $settings['login_url'], $current_url ) ) {
		return;
	}

	//
	// PROCESS ALLOW SITE ACCESS OPERATIONS
	//
	// FILTERS:
	// my_private_site_public_replace_page_access_processing:
	// Determines if main page processing is overwritten by external code. Before any page
	// access processing code is called, this must return true.
	// my_private_site_public_check_access_by_page:
	// Checks to see if access is granted based on page. This calls any replacement page
	// access processing code. If it returns true, the user may see the page.

	// when this function returns, it's allowing the user into the site
	$replace_page_access_processing = false;

	// check to see if we're using an external page access processing routine
	$replace_page_access_processing = apply_filters(
		'my_private_site_public_replace_page_access_processing',
		$replace_page_access_processing
	);

	if ( $replace_page_access_processing ) {
		$allow_site_access = apply_filters( 'my_private_site_public_check_access_by_page', $current_url );
		if ( $allow_site_access ) {
			return; // access granted
		}
	} else {
		// do all the base page access functionality
		if ( isset( $settings['excl_url'] ) ) {
			foreach ( $settings['excl_url'] as $arr ) {
				// Test the pre-parsed URL in the URL Exclusion list
				if ( jr_v1_same_url( $arr[1], $current_url ) ) {
					return;
				}
			}
		}
		if ( isset( $settings['excl_url_prefix'] ) ) {
			foreach ( $settings['excl_url_prefix'] as $arr ) {
				// Test the pre-parsed URL in the Prefix URL Exclusion list
				if ( jr_v1_same_prefix_url( $arr[1], $current_url ) ) {
					return;
				}
			}
		}
	}

	//
	// IF YOU REACH HERE, THE PAGE IS BLOCKED
	//
	if ( $settings['reveal_registration'] ) {
		$buddypress_path   = 'buddypress/bp-loader.php';
		$buddypress_active = is_plugin_active( $buddypress_path );
		/*
		  URL of Registration Page varies between Multisite (Network)
			and Single Site WordPress.
			Plus, wp_registration_url function was introduced in
			WordPress Version 3.6.
		*/
		if ( is_multisite() ) {
			$reg_url           = get_site_url( 0, 'wp-signup.php' );
			$buddypress_active = $buddypress_active || is_plugin_active_for_network( $buddypress_path );
		} else {
			if ( function_exists( 'wp_registration_url' ) ) {
				$reg_url = wp_registration_url();
			} else {
				$reg_url = get_site_url( 0, 'wp-login.php?action=register' );
			}
		}
		if ( jr_v1_same_url( $reg_url, $current_url )
		     || ( $buddypress_active
		          && ( jr_v1_same_url( get_site_url( 0, 'register' ), $current_url )
		               || jr_v1_same_url(
			               get_site_url( 0, 'activate' ),
			               wp_parse_url( $current_url, PHP_URL_HOST )
			               . wp_parse_url( $current_url, PHP_URL_PATH )
		               ) ) ) ) {
			/*
			  BuddyPress plugin redirects Registration URL to
				either {current site}/register/ or {main site}/register/
				and has its own Activation at /activate/?key=...
			*/
			return;
		}
	}

	/*
	  Must exclude all of the pages generated by the Theme My Login plugin
	*/
	$theme_my_login_path   = 'theme-my-login/theme-my-login.php';
	$theme_my_login_active = is_plugin_active( $theme_my_login_path );
	if ( is_multisite() ) {
		$theme_my_login_active = $theme_my_login_active || is_plugin_active_for_network( $theme_my_login_path );
	}
	if ( $theme_my_login_active ) {
		$page = get_post( $null = NULL );
		if ( NULL !== $page ) {
			/*
			  Some Versions of WordPress required that get_post() have a parameter
			*/
			if ( ( 'page' === $page->post_type )
			     && in_array(
				     $page->post_name,
				     array(
					     'login',
					     'logout',
					     'lostpassword',
					     'register',
					     'resetpass',
				     )
			     )
			     && stripos( $page->post_content, 'theme-my-login' ) ) {
				return;
			}
		}
	}

	/*
	  Point of No Return:
		We now know that the Visitor must be forced to login
		if the Visitor wants to see the current URL.
	*/
	if ( ! $role ) {
		/*
		  User is logged on to a Site where he/she has no Role.
		*/
		$message = 'You (User "'
		           . wp_get_current_user()->user_login
		           . '") cannot view this Site ("'
		           . get_bloginfo( 'name', 'display' )
		           . '").<hr />'
		           . 'Your User ID has not been defined to this Site. '
		           . 'If you believe that you should be able to access this Site, '
		           . 'please contact your network administrator or this site\'s webmaster, '
		           . 'and mention that your access was blocked by the <em>'
		           . $jr_ps_plugin_data['Name']
		           . '</em> plugin.';
		wp_die( esc_textarea( $message ) );
	}

	if ( $settings['custom_login'] && ! empty( $settings['login_url'] ) ) {
		$url = jr_ps_login_url( $settings['login_url'] );
	} else {
		/*
		  wp_login_url() returns the standard WordPress login URL,
			but the login_url Filter adds the ?redirect_to= query in the URL.
		*/
		$url = wp_login_url();
	}
	// looks like wp_login_url is returning the current page
	/*
	  wp_redirect( $url ) goes to $url right after exit on the line that follows.
	*/
	wp_safe_redirect( $url );
	exit();
}

/**
 * Add Landing Location to Login URL
 *
 * Although written to modify the Login URL in the Meta Widget,
 * to implement Landing Location, wp_login_url() is also called
 * near the end of jr_ps_force_login() above.
 *
 * @param string $login_url Login URL
 * @param string $redirect  Path to redirect to on login.
 *
 * @return    string                Login URL
 */
function jr_ps_login_url( $login_url ) {
	/*
	  remove_query_arg() simply returns $login_url if a ?redirect_to= query is not present in the URL.
	*/
	$url = remove_query_arg( 'redirect_to', $login_url );
	/*
	  $redirect_to is the URL passed to the standard WordPress login URL,
		via the ?redirect_to= URL query parameter, to go to after login is complete.
	*/
	$redirect_to = jr_ps_after_login_url();
	/*
	  Also avoids situations where specific URL is requested,
		but URL is blank.
	*/
	if ( ! empty( $redirect_to ) ) {
		$url = add_query_arg( 'redirect_to', urlencode( $redirect_to ), $url );
	}

	return $url;
}

function jr_ps_after_login_url() {
	$settings = get_option( 'jr_ps_settings' );
	switch ( $settings['landing'] ) {
		case 'return':
			// $_SERVER['HTTPS'] can be off in IIS
			if ( empty( $_SERVER['HTTPS'] ) || ( $_SERVER['HTTPS'] == 'off' ) ) {
				$http = 'http://';
			} else {
				$http = 'https://';
			}
			if ( isset( $_SERVER['REQUEST_URI'] ) && strpos( sanitize_url( $_SERVER['REQUEST_URI'] ), 'resetpass' ) !== false ) {
				$after_login_url = get_home_url();
			} else {
				$after_login_url = '';
				if ( isset( $_SERVER['SERVER_NAME'], $_SERVER['REQUEST_URI'] ) ) {
					$after_login_url = $http . sanitize_text_field( $_SERVER['SERVER_NAME'] ) . sanitize_text_field( $_SERVER['REQUEST_URI'] );
				}
			}
			break;
		case 'home':
			$after_login_url = get_home_url();
			break;
		case 'admin':
			$after_login_url = get_admin_url();
			break;
		case 'url':
			if ( ! isset( $after_login_url ) ) {
				$after_login_url = '';
			}
			if ( strpos( $after_login_url, 'resetpass' ) !== false ) {
				$after_login_url = get_home_url();
			} else {
				$after_login_url = trim( $settings['specific_url'] );
			}
			break;
		case 'omit':
			$after_login_url = '';
			break;
	}

	return $after_login_url;
}

function jr_ps_login_failed() {
	$settings = get_option( 'jr_ps_settings' );
	if ( $settings['custom_login'] && ! empty( $settings['login_url'] ) ) {
		/*
		  wp_redirect( $url ) goes to $url right after exit on the line that follows.
		*/
		wp_safe_redirect( jr_ps_login_url( $settings['login_url'] ) );
		exit();
	} else {
		return;
	}
}

function jr_ps_wp_authenticate( $username, $password ) {
	foreach (
		array(
			$username,
			$password,
		) as $auth
	) {
		if ( empty( $auth ) ) {
			jr_ps_login_failed();
		} else {
			/*
			  Also catch blanks.
			*/
			$trim_auth = rtrim( $auth );
			if ( empty( $auth ) ) {
				jr_ps_login_failed();
			}
		}
	}
}


