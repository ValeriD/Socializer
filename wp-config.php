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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'socializer' );

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
define( 'AUTH_KEY',         'd`dpUoCTRZ(9CZ~.)i#S^p5MMI/a_,i0!c<sIh`#q8m@ZtUQ-R`}O:>uNman`R5B' );
define( 'SECURE_AUTH_KEY',  '4]f6dezNcIj5nvYv(7%dlajTLvaN?21@)y=|%urS_ParT)_u6Ml*Uf&LNMY[3*3E' );
define( 'LOGGED_IN_KEY',    'Q5%zq[_)j (IE#-Py%cY9Z1}E<pW,#Mw0io*e*GU0%,2,Shy.1exPJah.kr}-O(Z' );
define( 'NONCE_KEY',        '~I_l|LQtT#;r<I~?+.ARstd^yi4xrYtCM/1xSw=O5Y]<Cw C%$WVG !#PZb _j(X' );
define( 'AUTH_SALT',        '%51:=fphFV#0#01%$h6e5vtaf~_P#,`0ke]u2FCNLp_Y$:LiNLJK3-=PiVL-7n(o' );
define( 'SECURE_AUTH_SALT', 'g_e*#3ym::+`k/!Val.w;3?kykk30UIpsoU;rfO]m mfCcLtJYY_cMZ9*(.r<[)j' );
define( 'LOGGED_IN_SALT',   'rMU0tKjL8&~@% 6SxAr,RbUP8lU_+Pqs9zACT!j)z9 SAVQZ95Ti3Wr(j-/g:d6f' );
define( 'NONCE_SALT',       '~Nh9*Iq%%~2*sc&8R+|{HK$./q,(v|dn?Ji#vek~z&cr?&v tNyP53A45ZT{+022' );

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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', true );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
