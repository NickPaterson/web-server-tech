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
define( 'DB_NAME', 'wordpress' );

/** Database username */
define( 'DB_USER', 'nick-wp' );

/** Database password */
define( 'DB_PASSWORD', 'erm1@tXj7D' );

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
define( 'AUTH_KEY',         '~YU8ZfyejF=-dLiUPj2uc!B4R5rL(3/~Qya5uQ6zT|Ct%S{2MZM-*,UjnM-paG=w' );
define( 'SECURE_AUTH_KEY',  '2l)MxJ) 2wcEKCdmJ<cX5PhU cl7I_@c&ex6c}j:|/^}@%}uV1K1;5k&f7fSZrc1' );
define( 'LOGGED_IN_KEY',    'oP6YgF8Vd*Wp5,uhs>CT)+:y,r}wyx<*|i@Jy6m{B!U9X;yFc*6ArKNF2i2<#1w0' );
define( 'NONCE_KEY',        '|Pzpb,_zfsZ{{pjDgaYi+e;/e|{J5f7q-8u4e{&YYL%KfaO1zL?I%T[s]X)6%(7)' );
define( 'AUTH_SALT',        '}*(puH[?=h_k68YUUONOk|T07.z/Ta#}3hmoa&vf1o@_9xi8a|m95<)8gcPVsKgr' );
define( 'SECURE_AUTH_SALT', '%/bC`FTzZ4]~#z]E}#qH/i}u> n=/YH&u.mfY1y.,nM0nzICPKq6ZSOI1@u1+@vQ' );
define( 'LOGGED_IN_SALT',   'Y}O8w>Ypq@Y1`c<B9(.+,ktdIn:e+o0o;y.%DQ6h:<acka~.BLGL*:or13_DR(aZ' );
define( 'NONCE_SALT',       '?RBv2Ah$N.aBI.(tQTx1b^>+`m+D@DW!e>@9[:XtJCm8#m4jKXW)={JCLnV4ei#=' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'nickwp_';

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
