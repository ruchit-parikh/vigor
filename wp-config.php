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
define('DB_NAME', 'vg_crossfit');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'smile');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/** Enable themes or plugins to be installed without ftp */
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
define('AUTH_KEY', 'A5BQuFARsTtEdt8+`~1]Ixh!^jlGcU.CTjUlk>Xg s[`49!Ss(I=pyhnB^>gM=Xb');
define('SECURE_AUTH_KEY', 'B=rL5oT`,[Ez>>7d[17atDll3>ELlEWC$o2,TvGadD/ocLQ8s> zJne#1kkV}(q|');
define('LOGGED_IN_KEY', 'btX :ce3w<g,^_BnU<zV3O7Kdp]7/n_fBeQv4qnA (f-|SgQgu6~!-ds<VF3ofVC');
define('NONCE_KEY', '1c[-{8T^1PFa4NKaul[V5S;>MPFsoRB]9hAzx94I]#tz/GW)wM_FDF[ mZYBdaIt');
define('AUTH_SALT', '_l$=mW$f|v>O+wnh=KIO^?Y3W),/0S1:YGE@HT)^E2<nqPm;gUaJKBG<icpk//?3');
define('SECURE_AUTH_SALT', '33)tcsY3WebL*Bg5X2f:yi,&2I._YdMS]>ZDwk-Vj96}hW{0y8IRpEk!ld]vM$+,');
define('LOGGED_IN_SALT', 'ap!riu};}2a[BFT){#~^UDEZ.#*}u!,P1Jr!m5D~-b^f:r>i9c+i$/vZT}9&!0>c');
define('NONCE_SALT', 'PO[v|kfY6IkG_&*7@[>a1a@/8/gTK8r[ =fo/=Cf=?y0BMlgQ:Chqex!Q75+MQ_j');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'vg_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
