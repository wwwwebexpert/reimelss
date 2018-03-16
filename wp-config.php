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
define('DB_NAME', 'outletcorner');

/** MySQL database username */
define('DB_USER', 'outletcorner');

/** MySQL database password */
define('DB_PASSWORD', 'PbXms7elhYGohr6K');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

define('FS_METHOD', 'direct');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '3E,- W9aJY:-@Jnx3o)LJXky?VM9F*;>FVb)pQ`=m1[j<@Fj~E;KeBX{t,eX/`mR');
define('SECURE_AUTH_KEY',  'Lw8#zDMqz*WYh:~nwq[DpvPq~=bW)@_[-+XiKn/|moRr#..}h39nix@b->Qg,4}o');
define('LOGGED_IN_KEY',    '!.?1X B_b N<M&`x{Q~pZimvj~vHr9fl<L3?69xw#xX$gI@E)(cOws!F)c;$g9,f');
define('NONCE_KEY',        '>J$n]r/L*eN4:En Jw[)]#xWI$)c<YG6.).I *9]2K!!;|S?|.x;{{Q@eQS1kKZ4');
define('AUTH_SALT',        ':t=w_DwZ.UMC)pZn*lMm% $(UmG BCGunjFpJHrElRC@n`j[+bbpwqqiO7}fuBS_');
define('SECURE_AUTH_SALT', 'wi-EjiQed`$Z-:M >}ikvha9b^=l6W/17K.~SW5q^^N.w|idh;LkV7DPslZh)ggE');
define('LOGGED_IN_SALT',   'Gp/dKr0R:ZS3-,utJ0T*>1,HQb<8&kPN&!Cj2uNW}6{VO[n2Lu@13~CdRq5`[>g#');
define('NONCE_SALT',       '5k0sw4l*5HY1WHI$M? #{KI1Doq7[LW.AnZy:bf9z,|&g,>u)a%<~w=U|*fB$7f;');

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

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
