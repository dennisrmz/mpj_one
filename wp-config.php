<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'mpj_one' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'D(IDg9&@;h_UWM<#X2GQDGe(hn>:VJx)9qZ.wf#Xsm9z#u0Yau[9qP=? :Uv2K_)' );
define( 'SECURE_AUTH_KEY',  '~]*8M3F[pWv}|x-Y4@O ?$Q6d{lNKFcQ:lb@f/LlS,aTb9(!IQwqfLxRN7!8maKb' );
define( 'LOGGED_IN_KEY',    '8<gsIrH?/Ub^ xg=jA;gH$/YuZO$K=Y6s[C9]M(5Cp~M~$6;gQDTIzx]8jfH lLl' );
define( 'NONCE_KEY',        '1~loy/v$!KVtZRJqNZC5{(<|9VvXd^cEN57DB!G(NjA|m w~=y~-s}Q@$@GWqx*o' );
define( 'AUTH_SALT',        'ldboX^v5bO!,j&( b%sBM_PZXy6?@4wm}pG>Gq5x}j#@%u#z~&JP%[EKk2..t[7Z' );
define( 'SECURE_AUTH_SALT', 'h?#BYw`Kv;2>0Y23M;=Xu3wBX1!iSgP`FXb o:j0#]>o7$>kHUX8(!r5/U:#x<?&' );
define( 'LOGGED_IN_SALT',   'C!j78bS_h;y^[&sxlsbt=]mcGcA.;&|)KA*6gz5Y+A|[:K4zs7_5MDk].FfHgiQO' );
define( 'NONCE_SALT',       'Zu2ljZ`M#rn|w&!_oW3b@lU;/zw.YqH7P(u9E^On !clSNPeNk)2gW 0<[T[D]mV' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
