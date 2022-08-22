<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'HotelBookingWeb' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'cJOYS)4_CWh32QP3<0Y65e~(wqSQkt84x}{D~!Lh@M%lQ$-3DP3%YN&-o^;Z5e%f' );
define( 'SECURE_AUTH_KEY',  'Y8HK3P%-;n=Nd-D|O(0dT:290Fv^P <oOZ#Qf=O!-_Vb1XWYVA;l)Q~3A{Lq2e2b' );
define( 'LOGGED_IN_KEY',    'yK1#lp)@1~2iL=[ k(d8kb7_{;nb_G[x!P!s}+Y) d{-}oFr$JsAqLx6QPtQq6gr' );
define( 'NONCE_KEY',        '|)j=^~9M97F!KEz<b`<^]N+PH;PP~T/r|zUR>XhXt.iRBj6x3R#)jB*uXf/jBsu%' );
define( 'AUTH_SALT',        'vrl-WdwG6&a3q wa$FT[ +p|Xv/ 7BuK:T)b5{7Jc7bDG}}e}V*8[q;Omn!Q%B7^' );
define( 'SECURE_AUTH_SALT', 'X.[YKcCx}W;Uq?e][Y5-s8h@s2LH#|hHy?]gfn*AEiq{P/F,|]6u^WTZ) $W#zG}' );
define( 'LOGGED_IN_SALT',   'nK+d<{y@|@mraf#e8#G+sT[343,jy@<;5k^yn9aHXSumfsV{[ *G8r{Tp.~Xp<Nc' );
define( 'NONCE_SALT',       '`gRV=8qlhKra;;5GnQc^/t(N@|=IL$z8m<jtR. octRHUS}:yS)x!rp@TG_mD$fB' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
