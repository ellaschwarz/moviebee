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
define( 'DB_NAME', 'local' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'root' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'qJvWEZiXQZGJLjwiutQGqcEjwqUo1VlywPdsQ677HTiN8rOuaUhfUo7Kwk5VymeR5TlYXDIAloqmJ0gQKMpmgw==');
define('SECURE_AUTH_KEY',  '0N0Pbj2FdU0pj9eJt+n41U8o2C9veTybZ8LRkDVB6yIbhbkebRkw2BQgaEdUGOI59q1yWhIl7WL8vz/YkuF/Ag==');
define('LOGGED_IN_KEY',    'C47yyjFh7Wk5NpwL3see1/ByPo6iOrPUlBNk4grkMFBxSRmfAARlxwZAbDMBwhdcf2Mlgygo8AEp+wpURoqY7Q==');
define('NONCE_KEY',        'cldFQOfKoXYI7zISJOLgiHj+03iH2MVEq5ZDq1S3o6b3ZFRsLQC4dC6ZWbdlU2aG8MtYCEQ5HeeQfHRA5diJ1w==');
define('AUTH_SALT',        'PO4iPJnC9++z/rymc+68MS45dmAfjp3QM30+nsWcF8V5AH+wKwh6ljXIIjL4MxMBkwjGEHr/4INWvgS0sBww4A==');
define('SECURE_AUTH_SALT', 'zdYnNASDbEDGSkbeH7sNeWlwfsuUmiKgzsSLZOfYHPAIDUX/SRI38ICAYz35Ycqr93sa5zDfWIJP7kfND30qFA==');
define('LOGGED_IN_SALT',   '8w6NVHjTxwz1vyEQrRv5zyAv8gx5DXF/uBEE1DLzGpQU10SEm8+vqthG0rEPwYem6jjtnNzuMsaLLEzsDqH5pg==');
define('NONCE_SALT',       'iRHKg0ZsnOt78ebOuB9QTEtjjmw+tS8MXn5uzYeiPWV7k7VEBREjt4vrRMxV/XdGdO7hcwSYCUwvXuuAi8kyww==');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';

