<?php

/**
 * Define the internationalization functionality for translations 
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/includes
 */

class Rate_My_Post_i18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'rate-my-post',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
