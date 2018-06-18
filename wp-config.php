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
define('DB_NAME', 'WP1');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' e@=?2s0VN5u*IK{AASeI 2hc0SIP2$U|>Kk{v*b{GC-nter,G/=m|2`(I7Xij7g');
define('SECURE_AUTH_KEY',  '0?7~o@o}0ZPAl8zza,-vf@Bw3V|._Uh6vt|<GhL;5xjX.kWZ5?_o.&SwIS2 owR`');
define('LOGGED_IN_KEY',    '/s~6pE?H_j~l7^i7d7pkmFm<hP18OftL)/(zdO,v~MMaju |G@[)sFsV5I]:Rfn:');
define('NONCE_KEY',        'd2;&-- Ym=)6OnCY7znhkh37o B#Eq=pa=7p/0PIXMR$6lfQ.z}mbWGHwB]R,FY7');
define('AUTH_SALT',        '(f*C%#$D(jf`d<9$<HPy1qQv9v8i6s?J`<WJBMB[{R$YuAvE}61=/Xc+xAl67.[e');
define('SECURE_AUTH_SALT', 'H6BAiZH2Gx0M|lAPJ^^h]Q-;*92Pl<0JMB|AW,@NQZ](Ri4vb$4aI_)S}XAhZZm5');
define('LOGGED_IN_SALT',   '-m,jlwi)0$()$-I*>zCyA-@@6.eAg:i [* =~R1VC_6E/zjVdnxjcjl*e&>2kp;%');
define('NONCE_SALT',       '*KL!;x)XemD&& A*RUDCQmnWaQ1)Qd:0%_!DfLl65{& hQnY)(>sSl2/;w0Jrs2c');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
