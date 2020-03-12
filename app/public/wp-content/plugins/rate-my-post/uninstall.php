<?php

// Exit if this file is not called from wordpress
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$rmp_options = get_option( 'rmp_options' );

if ( $rmp_options['wipeOnUninstall'] === 2 ) { //delete data on unistall is enabled
	//delete plugin settings
	delete_option('rmp_options');
	delete_option('rmp_customize_strings');
	delete_option('rmp_version');
	delete_option('rmp_security');
	//delete vote count, ratings and feedbacks
	delete_post_meta_by_key( 'rmp_vote_count' );
	delete_post_meta_by_key( 'rmp_rating_val_sum' );
	delete_post_meta_by_key( 'rmp_feedback_val' );
	delete_post_meta_by_key( 'rmp_feedback_val_new' );
	//delete table
	global $wpdb;
	$table_name = $wpdb->prefix . 'rmp_analytics';
	$wpdb->query( "DROP TABLE IF EXISTS $table_name" );
} else {
	//do nothing
}
