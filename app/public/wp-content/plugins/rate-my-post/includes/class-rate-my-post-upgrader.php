<?php

/**
 * Fired on plugins loaded
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/includes
 */

class Rate_My_Post_Upgrader {
    //Runs on plugins loaded
	public static function upgrade() {
		//check if upgrade is required
    if ( RATE_MY_POST_VERSION !== get_option( 'rmp_version' ) ) {
      //OPTIONS
      //required options
      $default_options = Rate_My_Post_Settings::default_options();
      //database options
      $database_options = get_option( 'rmp_options' );

      //compare default and database options
      if( is_array( $default_options ) && is_array( $database_options ) ) {
        foreach ($default_options as $key => $value) {
          if( !array_key_exists( $key,$database_options ) ) {
            $database_options[$key] = $default_options[$key];
          }
        }
        //add new options
        update_option( 'rmp_options', $database_options );
      }

      //CUSTOMIZATION
      //required customization
      $default_customization = Rate_My_Post_Settings::default_customization();
      //database customization
      $database_customization = get_option( 'rmp_customize_strings' );

      //compare default and database customization
      if( is_array( $default_customization ) && is_array( $database_customization ) ) {
        foreach ($default_customization as $key => $value) {
          if( !array_key_exists( $key,$database_customization ) ) {
            $database_customization[$key] = $default_customization[$key];
          }
        }
        //add new customization
        update_option( 'rmp_customize_strings', $database_customization );
      }

			//SECURITY OPTIONS
			//required security options
			$default_security_options = Rate_My_Post_Settings::security_options();
			//database security options
			$database_security_options = get_option( 'rmp_security' );
			//compare default and database security options
			if( is_array( $default_security_options ) && is_array( $database_security_options ) ) { //security options already exist - updating from version higher than 2.4.0
        foreach ($default_security_options as $key => $value) {
          if( !array_key_exists( $key,$database_security_options ) ) {
            $database_security_options[$key] = $default_security_options[$key];
          }
        }
        //add new security options
        update_option( 'rmp_security', $database_security_options );
      } else { //no security options present - adding them for the first time
				update_option( 'rmp_security', $default_security_options );
			}

			//DATABASE TABLE FOR ANALYTICS - SINCE 2.8.0
			//if we are upgrading from version lower than 2.8.0 create the database table
			//Also runs on update
			if ( version_compare( get_option( 'rmp_version' ) ,  '2.8.0') < 0 ) {
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name = $wpdb->prefix . 'rmp_analytics';

				$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					ip tinytext NOT NULL,
					country tinytext NOT NULL,
					user smallint(5) NOT NULL,
					post smallint(5) NOT NULL,
					action smallint(5) NOT NULL,
					duration smallint(5) NOT NULL,
					average decimal(2, 1) NOT NULL,
					votes smallint(5) NOT NULL,
					value smallint(5) NOT NULL,
					UNIQUE KEY id (id)
				) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

			//DATABASE UPGRADE - SINCE 2.10.0
			//if we are upgrading from version lower than 2.10.0 create update the database table to include token
			//Also runs on update
			if ( version_compare( get_option( 'rmp_version' ) ,  '2.10.0') < 0 ) {
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name = $wpdb->prefix . 'rmp_analytics';

				$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					ip tinytext NOT NULL,
					country tinytext NOT NULL,
					user smallint(5) NOT NULL,
					post smallint(5) NOT NULL,
					action smallint(5) NOT NULL,
					duration smallint(5) NOT NULL,
					average decimal(2, 1) NOT NULL,
					votes smallint(5) NOT NULL,
					value smallint(5) NOT NULL,
					token tinytext NOT NULL,
					UNIQUE KEY id (id)
				) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

			//DATABASE UPGRADE - SINCE 2.10.3
			//bug fix - change postid to mediumint for larger websites
			if ( version_compare( get_option( 'rmp_version' ) ,  '2.10.3') < 0 ) {
				global $wpdb;
				$charset_collate = $wpdb->get_charset_collate();
				$table_name = $wpdb->prefix . 'rmp_analytics';

				$sql = "CREATE TABLE $table_name (
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
					ip tinytext NOT NULL,
					country tinytext NOT NULL,
					user smallint(5) NOT NULL,
					post mediumint(9) NOT NULL,
					action smallint(5) NOT NULL,
					duration smallint(5) NOT NULL,
					average decimal(2, 1) NOT NULL,
					votes smallint(5) NOT NULL,
					value smallint(5) NOT NULL,
					token tinytext NOT NULL,
					UNIQUE KEY id (id)
				) $charset_collate;";

				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $sql );
			}

      //UPDATE VERSION
      update_option( 'rmp_version', RATE_MY_POST_VERSION );

    } //end of upgrade required

  }
}
