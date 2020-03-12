<?php

/**
 * Fired during plugin activation
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/includes
 */

class Rate_My_Post_Activator {
    //Runs on activation
	public static function activate() {
    //save options for the plugin
    add_option( 'rmp_options', Rate_My_Post_Settings::default_options() );
    //save customization for the plugin
    add_option( 'rmp_customize_strings', Rate_My_Post_Settings::default_customization() );
		//save security option for the plugin
    add_option( 'rmp_security', Rate_My_Post_Settings::security_options() );

		//Version and table are inserted in upgrader - it runs on activation.
	}

}
