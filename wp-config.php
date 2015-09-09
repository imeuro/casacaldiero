<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wp_ccaldiero');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'ricorda');

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
define('AUTH_KEY',         'lIBLeV^@:#D*hukXUvgL#ShO!yc8[(^2`}aMejYiuT23)d--Hgy^qbX]qIa`ntB_');
define('SECURE_AUTH_KEY',  '_sE|2)?}$=b5Y,d|LzwF(Oh+gbudbv}J<`L)={%|Ymnp6)*enee)o$MuQ8A{wDYG');
define('LOGGED_IN_KEY',    'G`=-2_,=i6krMlg/Qh4UHj5XR+nhUYOUTS1N|OU?Qpd`eM{Q]5b;7A^%y`^FehqO');
define('NONCE_KEY',        'p[_3^VT+&%a;yOj?*$7KessTNd0mFA~ESKRk|g${!X4DQ%@XcAS[A+@5+&e{=KU7');
define('AUTH_SALT',        '};].B`$,dQpRWY5&tkh!b^tGQ-bq5w+z@;+Ou?RAIBng I}>:y~RuTj,aj!Fh%+v');
define('SECURE_AUTH_SALT', 'fc;0OET7dly!O%R)]|URN(l<*t`JpLHRE7vA[KD}*=|u*mM>!aF-,,P37n LC~))');
define('LOGGED_IN_SALT',   '83:=N:wo}Kjuz=RJ0zKlX[FM2:%#1]FCvR,^t7!&0O|pUY-*.25$e2bn+w@B!lc~');
define('NONCE_SALT',       'NgPucR:?PPig8tBz>[T@xM:eTU.|g#SOX[r/|>Q-oA/D5`GPo5zD6rX74HZy<}Ts');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'cc_wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
