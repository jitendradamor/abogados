<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'abogados' );

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
define( 'AUTH_KEY',         '[MPc)IaY]|mH0lM6(~j2;,,kAKTn*RLjsSXn[1Djcu g}GCCzVSD6.-.5|tgssD+' );
define( 'SECURE_AUTH_KEY',  'lCKDHJHBwYL U8CI%_D;y?3NVA)Pj`ofM!]-X^sX3yIyIJ%wt3Pha~kEL$b$Q7-V' );
define( 'LOGGED_IN_KEY',    '6{]q~rmKLm/:QjX}`*;Il~b0o7&DgonR12]%o_~zT(XjG8u`McJupgs)tsW|>B2w' );
define( 'NONCE_KEY',        'Beq}50=CJtG(rmm^<b|Mz((&`YMDIYTj#4Xw!RB&}b#ZD)HWv=J1X<Mr8%]; i1)' );
define( 'AUTH_SALT',        'MB6*JGH@aYLNT-);bzeOMFB8Z`JE8b[jaBIZ~WV6$O~Z%R3+FR8z6mR !#)K6*{5' );
define( 'SECURE_AUTH_SALT', '2MrHlfsSM-o1/<v}&%B_&E/^i+%ZCM1V}lf>=QFxQsm5BMLBHbl,]Hl:f[foM-Af' );
define( 'LOGGED_IN_SALT',   'csGPb:@hfF?d1d+Ciihr+yIqn;?^tPSV!kS|}rt]bCO=W?ZI0Aoj<!J;@]D XK.{' );
define( 'NONCE_SALT',       'c+2p{gUnEb.*c!8?`3bYFsw1wSG4+DdmY5s!<OS60|Hw]5jyb-y#~)k@VA4Et(74' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and abogados please!
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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', value: true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
