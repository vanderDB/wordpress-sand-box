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
define( 'DB_NAME', 'wordpress_sand_box' );

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
define( 'AUTH_KEY',         'f s[8w4htwKQ*G G19J[7^rY@-qf*I/@*q_3OMkvGRG^J9-+2lKp8+$OHy+nb]QJ' );
define( 'SECURE_AUTH_KEY',  'k?WO5$r<0t6m$O<P6puc<X!?-(!J}/SA7qS_fu0cpB]Q&gr<hg2TGJf+doY9HCaR' );
define( 'LOGGED_IN_KEY',    ' #;C>k,WWtvca^;=bU1.ynHwp%@7r@Rt5$rM:Tfn^L7FnOv_GKUYsKw$+n%mpZ>Q' );
define( 'NONCE_KEY',        'k!HaQ7*P_g4>teZjMxvKJ4Wz8G<Mf,>v1_OFpE%}~/8ka:MhP%y4inW8;rd~#J+|' );
define( 'AUTH_SALT',        'rxd6$NR%30#]116zp*OJ _W{..!@zF*Gi ;nro;5P3m)f 1}&Hw3TlYG0&GHoes#' );
define( 'SECURE_AUTH_SALT', '9,(WS)03X-c>g/:^,&z7kRkNZe+TSTCO{@jnlav1x<_2##Ha2BpI xfe;[-MJQy%' );
define( 'LOGGED_IN_SALT',   'gn6{RK2Ub+gUHg,%C_o&9JrcHU1lJ?|2/.Xo?A::6Ao.Tv:J>-hH~<M;;pxZM>z.' );
define( 'NONCE_SALT',       'zodMTww{OyrZ!CX<OVv)EQ&p6fz9tL3[EK04H55E?Na/3Wsy=$igOJsS/Q9Y`E)=' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpsb_';

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
