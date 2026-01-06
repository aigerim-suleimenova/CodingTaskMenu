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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

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
define( 'AUTH_KEY',         '8oAR&Vtf.*,}`817SUvsXwdSG90K.VeSJ~Ro`|cn.SMWa;Umliu?,qH%}9dEvK1n' );
define( 'SECURE_AUTH_KEY',  '*1-yUml3nfbcndS`(7zhYwD}h8H0m19NB^D||~WQifUn}CwwAs>2)=0co~ [R@[m' );
define( 'LOGGED_IN_KEY',    'fyyk%N;X&BhzKxci#meD)xJ_!C-9fZq-U.M,1fK`iYNA64Y5[]7Nf#v$B_ScyT &' );
define( 'NONCE_KEY',        '%!%]mHlO}/+xJPbeJ&1Jwd7E^h*qd?D/LpR|DRZ997bsToMO=*f[-t+F8*PI|(ZU' );
define( 'AUTH_SALT',        'V%|3^VzEjU9B.^K,fLU0fpfV$%-gh[7^xiWXtpZM|,sY_2n}f^,8l+N*fR/@{`G{' );
define( 'SECURE_AUTH_SALT', 'u1vX=wI4<T5c*o9-zS6y_df?.vePf!UPT>eIf:znI#=fiB@4 XJCXVa-6Q@nwTIZ' );
define( 'LOGGED_IN_SALT',   '70n+|N.-j*58!9y~qzL,?[QLhrkT2;j(AHGxRSas+>Cl4%}[b?5N@5@sM^.a*g9P' );
define( 'NONCE_SALT',       '(yo2dX#5xhbE6(s>~6zO%-8R{l3IU~>Y8|4b#*4l4N-M]~_Ibh-7us,)-F7pm?Nu' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
