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
define( 'DB_NAME', 'superfk' );

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
define( 'AUTH_KEY',         '|iBGfEMzLah< HDW~O;@=%u(EZo[:XK/owy4]8j*lsH*FJ0r?u7@3`gF?qrt=-Rk' );
define( 'SECURE_AUTH_KEY',  '#bxrZ/2cC1hu;rWwv]H`PoU~9NHX9cM/MpBCfI`]nDQ`.d/>mHY}GA UV%;:`Tq>' );
define( 'LOGGED_IN_KEY',    'knS_nU**>U8wz5jQ/F%@`B/BEl%st$8GacQ+E}&$lLtAFC0_?3O(l1}C_0Evz{I`' );
define( 'NONCE_KEY',        'SNE~@]9Y|2YE%AoH;B*2)g{KT.!_7$E |hnru{ktotd7O8pxZ+Q-s+Pe4l@m^XHt' );
define( 'AUTH_SALT',        '{&p1>_!LIChCP~K@;gGGiv#UFBO@0(c[lJRkt1u=Twv1G<[K=sWc(Hq/A<}PpJ5<' );
define( 'SECURE_AUTH_SALT', 'x`]:i8gO;Vw%V!UUI[xC8BI: saeQNVc8/Z w:7Pyg7x>32PS$,h3?&NZN=}k&U}' );
define( 'LOGGED_IN_SALT',   ')$[i4T3]5n5kNQN=wZyq.yhh:%F/,VVB}/0h#6({sorq`{Q3yMO#xo2._g:|5nNY' );
define( 'NONCE_SALT',       ';-3#u!0&Gfv88yJZO&j$*}vv /I@< <,qu}f~~Oy-r5+,i&C{Q6kaG5KM 5y}~SK' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
