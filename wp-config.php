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
define('DB_NAME', 'wp1');

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
define('AUTH_KEY',         '$HOT;d<zJsSN$)1P;jagJ}x9EdxUxcORk^tQ,8n,X=EDY$`z)3UH!#sU=rKoFU*n');
define('SECURE_AUTH_KEY',  '!jckbHgGr!--7o:O$l^n~jFps}6+N:*S1GZ~sDckgc[eHp59CmGkf_C!^Sg5RipL');
define('LOGGED_IN_KEY',    'a>YS/o|eP^3LS(sQb^d39:<s+BR~R[Nq5MIYPTV= km4td(v}Vq]8^Qn]?#6HOUQ');
define('NONCE_KEY',        'PEmMJh;{9TFD-e+J@g}*`-?G8h@V@r#GEbJ^Mw@(e[Q7N;jiU(ZO`^ m=pjKiTHg');
define('AUTH_SALT',        '&S:RtP9s[F*,C)O.1&6U`51X(/W+FJ 3 o/4*qjjMnr5b0^AoH[&E@uPP,-lKuep');
define('SECURE_AUTH_SALT', 'st.~NeM^N3bNa<%S)~LFmyOs,|]y5PDFN;/Y~>9?grqBF6Q,@,v9P{BoXQB3.:2_');
define('LOGGED_IN_SALT',   'HE{uM rf<$y7D+,?qw|#p!XqbS9bOYX%wy[D[f<RQIM?+*48Si{LPfgP>z)NjG</');
define('NONCE_SALT',       'l~^mu;I}XGsr?!Z]NT!Mc#Pee[lJeoxG{*wy.2hPNs!|1iV(@v8o>Q1w3RizC@=@');

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
define('WP_DEBUG', true);
//Enable Debug logging to the /wp-content/debug.log file
define('WP_DEBUG_LOG', true);
//false == Showing errors ONLY in /wp-content/debug.log file NOT in the site
define('WP_DEBUG_DISPLAY', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');