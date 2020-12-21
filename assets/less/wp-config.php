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
define( 'DB_NAME', 'dokani' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         'Mr#;n|,8 Us+-g>cx[OAA_gjPa23My+1/c^gkJmt}`61:k3e.fV8 +[{jF,cL_((' );
define( 'SECURE_AUTH_KEY',  '*dU0]SNw{.HT:GE8h{wkk51GVAmcd]j^=Ogn,`bf&|r2Ms7F/I6k]1X}($?}~Vzz' );
define( 'LOGGED_IN_KEY',    '9<+^$-bhACnLyOSn,PllZ5ls&J5ClY~/BuHskwYG0M]VxvIft5U#C^i(V%Y| 6*l' );
define( 'NONCE_KEY',        'eHp3LeU3s(oNBZ6Or[^^T07aICfWAEG)ovijKF?*O/Wc41*DKSJ`go|HUrD6nXT:' );
define( 'AUTH_SALT',        '5CYFH]-u-+?7pyH3w#czm/~fP}G9$9%~/xT.M(MGvuD7MntZdoYKt*{Dq~v7$nEz' );
define( 'SECURE_AUTH_SALT', 'u@7@V^X]iTQ&22}O;ToMr](%bdf>j]WWJYOpmbpSTEPGl-.C8/K6N7lCo4Uw=yRl' );
define( 'LOGGED_IN_SALT',   'T%+Q~L)#+s`gE. S0$y7aPTx|yb8H$,wYCq4G@YJp[ND>|M&j7.BS-JI,w#cPx%F' );
define( 'NONCE_SALT',       'V%6pW`vHC%7?UH18LMs81ur%u/B&]Z{Z=tYFLpSpiz#yY?$y1.g2;1vZR]v|@|]2' );

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
