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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u342960691_finalwills' );

//u342960691_finalwills
//u342960691_root
//Sandbox@123


/** Database username */
define( 'DB_USER', 'u342960691_root' );

/** Database password */
define( 'DB_PASSWORD', 'Sandbox@123' );

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
define( 'AUTH_KEY',         '/sOc(CY>__VY1uF~jtK3O#<KRQ.U_=bmp*_Q>GVJp@0YV-dv[0#@(!r0T1}#Xmz,' );
define( 'SECURE_AUTH_KEY',  '1LzS1<dU~.k45[@PYtP1+Z?l]ed)/b~n8#U{jnX-.908S2ah6w?~3V_iU~}8ip%g' );
define( 'LOGGED_IN_KEY',    'qX+(Gh(d>N`mhoq%1%L[,T+BYi}jQ-_Fj?5RFoxOB#n@(=9WsWPn/t?@hw(oY=/H' );
define( 'NONCE_KEY',        'hk-$`ES6HUS_yXe<n,xyrgqNT@Dkc5bFg1P|CsnyZUgjmY)XmY$DxJ{,-End?,!B' );
define( 'AUTH_SALT',        '$9:i0S*7k?PFpTEQRsn*g}DZ/2&>Gat|}SCX4>[d0$z|HHhcY;V*&]K}-oLf5Ur+' );
define( 'SECURE_AUTH_SALT', '=e`qg>*9A@d3BWI1L$9ClmN=^.6l3s84{Ab~Fc6{M`T]`;b:K>^y@:GZ1/*RyY~R' );
define( 'LOGGED_IN_SALT',   'zL$BbRd.mX}}1_&`q+]vz,~(O|;d.Q=HW%%U4[$XCKBTH!7E=W]Ky@pu_NXF5]LW' );
define( 'NONCE_SALT',       '*Dx|WvW#mmDIg!O -n!r>XreBB9i:>b#ac7N`jK*O2~@D>py~>V*(1M/DnkL{s6m' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
