<?php

/*

Copyright 2014 Dario Curvino (email : d.curvino@tiscali.it)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>
*/

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly

/****** Install yasr functions ******/
function yasr_on_activation_install($network_wide) {
    global $wpdb; //Database wordpress object

    // Creating tables for all blogs in a WordPress Multisite installation
    if ( is_multisite() && $network_wide ) {
        // Get all blogs in the network and activate plugin on each one
        $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
        foreach ( $blog_ids as $blog_id ) {
            switch_to_blog( $blog_id );
            yasr_create_tables();
            restore_current_blog();
        }
    } else {
        yasr_create_tables();
    }

    //Write default option settings
    $option = get_option('yasr_general_options');

    if (!$option) {
        $option                                  = array();
        $option['auto_insert_enabled']           = 1;
        $option['auto_insert_what']              = 'visitor_rating';
        $option['auto_insert_where']             = 'bottom';
        $option['auto_insert_size']              = 'large';
        $option['auto_insert_align']             = 'center';
        $option['auto_insert_exclude_pages']     = 'yes';
        $option['auto_insert_custom_post_only']  = 'no';
        $option['show_overall_in_loop']          = 'disabled';
        $option['show_visitor_votes_in_loop']    = 'disabled';
        $option['text_before_stars']             = 1;
        $option['text_before_overall']           = __('Our Score', 'yet-another-stars-rating');
        $option['text_before_visitor_rating']    = __('Click to rate this post!', 'yet-another-stars-rating');
        $option['text_after_visitor_rating']     = sprintf(
                                                    __('[Total: %s  Average: %s]', 'yet-another-stars-rating'),
                                                    '%total_count%', '%average%'
                                                );
        $option['custom_text_user_voted']     = __('You must sign in to vote', 'yet-another-stars-rating');
        $option['custom_text_must_sign_in']   = __('You have already voted for this article', 'yet-another-stars-rating');
        $option['enable_ip']                     = 'no';
        $option['snippet_itemtype']              = 'Product';
        $option['blogposting_organization_name'] = get_bloginfo('name');
        $option['blogposting_organization_logo'] = get_site_icon_url();
        $option['allowed_user']                  = 'allow_anonymous';
        $option['metabox_overall_rating']        = 'stars'; //This is not in settings page but in overall rating metabox
        $option['visitors_stats']                = 'yes';

        add_option("yasr_general_options", $option); //Write here the default value if there is not option

        //Style set options
        $style_options                          = array();
        $style_options['scheme_color_multiset'] = 'light';
        $style_options['stars_set_free']        = 'flat';

        add_option("yasr_style_options", $style_options);

        //multi set options
        $multi_set_options                 = array();
        $multi_set_options['show_average'] = 'yes';

        add_option("yasr_multiset_options", $multi_set_options);

    }

}

function yasr_create_tables () {

    global $wpdb; //Database wordpress object

    $prefix = $wpdb->prefix . 'yasr_';  //Table prefix

    $yasr_multi_set_table    = $prefix . 'multi_set';
    $yasr_multi_set_fields   = $prefix . 'multi_set_fields';
    $yasr_log_multi_set      = $prefix . 'log_multi_set';
    $yasr_log_table          = $prefix . 'log';

    //Do not use IF TABLE EXISTS here
    //see https://wordpress.stackexchange.com/a/302538/48442
    //since this function is called only on plugin activation AND if yasr-version is not found in
    //wp-option, there is no need to check if table exists, unless the user manually remove yasr-version option
    //but not the yasr tables.

    $sql_yasr_multi_set_table = "CREATE TABLE $yasr_multi_set_table (
        set_id int(2) NOT NULL AUTO_INCREMENT,
        set_name varchar(64) COLLATE utf8_unicode_ci NOT NULL,
        UNIQUE KEY set_id (set_id),
        UNIQUE KEY set_name (set_name)
    ) COLLATE 'utf8_unicode_ci';";

    $sql_yasr_multi_set_fields = "CREATE TABLE $yasr_multi_set_fields (
        id int(3) NOT NULL AUTO_INCREMENT,
        parent_set_id int(2) NOT NULL,
        field_name varchar(40) COLLATE utf8_unicode_ci NOT NULL,
        field_id int(2) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY id (id)
    ) COLLATE 'utf8_unicode_ci';";

    //Since version 2.1.0
    $sql_yasr_log_multi_set_table = "CREATE TABLE $yasr_log_multi_set (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        field_id int(2) NOT NULL,
        set_type int(2) NOT NULL,
        post_id bigint(20) NOT NULL,
        vote decimal(2,1) NOT NULL,
        user_id bigint(20) NOT NULL,
        date datetime NOT NULL,
        ip varchar(45) COLLATE 'utf8_unicode_ci' NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY id (id)
    ) COLLATE 'utf8_unicode_ci';";

    //Since version 2.0.9 user_id is bigint 20 and vote decimal 2,1
    //format DECIMAL(M, D) where M is the maximum number of digits (the precision) and D is the
    //number of digits to the right of the decimal point (the scale).
    $sql_yasr_log_table = "CREATE TABLE $yasr_log_table (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        post_id bigint(20) NOT NULL,
        user_id bigint(20) NOT NULL,
        vote decimal(2,1) NOT NULL,
        date datetime NOT NULL,
        ip varchar(45) COLLATE utf8_unicode_ci NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY id (id)
    ) COLLATE 'utf8_unicode_ci';";


    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($sql_yasr_multi_set_table);
    dbDelta($sql_yasr_multi_set_fields);
    dbDelta($sql_yasr_log_multi_set_table);
    dbDelta($sql_yasr_log_table);
}

//action is in the main file
function yasr_on_create_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
    if ( is_plugin_active_for_network( 'yet-another-stars-rating/yet-another-stars-rating.php' ) ) {
        switch_to_blog( $blog_id );
        yasr_create_tables();
        restore_current_blog();
    }
}

// Deleting the table whenever a blog is deleted

function yasr_on_delete_blog($tables) {
    global $wpdb;

    $prefix = $wpdb->prefix . 'yasr_';  //Table prefix

    $yasr_multi_set_table    = $prefix . 'multi_set';
    $yasr_multi_set_fields   = $prefix . 'multi_set_fields';
    $yasr_log_multi_set      = $prefix . 'log_multi_set';
    $yasr_log_table          = $prefix . 'log';

    $tables[] = $yasr_multi_set_table;
    $tables[] = $yasr_multi_set_fields;
    $tables[] = $yasr_log_multi_set;
    $tables[] = $yasr_log_table;

    return $tables;
}


/****** Return the snippet choosen for a post or page ******/
function yasr_get_snippet_type() {

    $review_types = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

    $post_id = get_the_ID();

    if (!$post_id) {
        return false;
    } else {
        $result = get_post_meta($post_id, 'yasr_review_type', true);

        if ($result) {
            $snippet_type = trim($result);

            if (!in_array($snippet_type, $review_types)) {
                $snippet_type = YASR_ITEMTYPE;
            }

        } else {
            $snippet_type = YASR_ITEMTYPE;
        }
        return $snippet_type;
    }

}

/****** Get multi set name ******/
function yasr_get_multi_set() {
    global $wpdb;

    $result = $wpdb->get_results("SELECT * FROM " . YASR_MULTI_SET_NAME_TABLE . " ORDER BY set_id");

    return $result;
}


/** Output the multi set while editing the page, used in
 * yasr-metabox-multiple-rating and yasr-ajax-function
 */

function yasr_return_multi_set_values_admin($post_id, $set_id) {

    if ((!is_int($post_id)) || (!is_int($set_id))) {
        exit(__('Missing post or set id', 'yet-another-stars-rating'));
    }

    $multiset_obj = new YasrMultiSetData();

    //set fields name and ids
    $set_fields = $multiset_obj->multisetFieldsAndID($set_id);

    //set meta values
    $set_post_meta_values = get_post_meta($post_id, 'yasr_multiset_author_votes', true);
    $array_to_return = $multiset_obj->returnArrayFieldsRatings($set_id, $set_fields, $set_post_meta_values);

    echo json_encode($array_to_return);

}




/****** Adding logs widget to the dashboard ******/

add_action('plugins_loaded', 'yasr_add_action_dashboard_widget_log');

function yasr_add_action_dashboard_widget_log() {
    //This is for the admins (show all votes in the site)
    if (current_user_can('manage_options')) {
        add_action('wp_dashboard_setup', 'yasr_add_dashboard_widget_log');
    }

    //This is for all the users to see where they've voted
    add_action('wp_dashboard_setup', 'yasr_add_dashboard_widget_user_log');
}

function yasr_add_dashboard_widget_log() {
    wp_add_dashboard_widget(
        'yasr_widget_log_dashboard', //slug for widget
        'Recent Ratings', //widget name
        'yasr_widget_log_dashboard_callback' //function callback
    );
}

//This add a dashboard log for every users
function yasr_add_dashboard_widget_user_log() {
    wp_add_dashboard_widget(
        'yasr_users_dashboard_widget', //slug for widget
        'Your Ratings', //widget name
        'yasr_users_dashboard_widget_callback' //function callback
    );
}

//ajax action
add_action('wp_ajax_yasr_change_log_page', 'yasr_widget_log_dashboard_callback');
function yasr_widget_log_dashboard_callback() {
    $log_widget = new YasrLogDashboardWidget('admin');
    $log_widget->adminWidget();
} //End callback function


//ajax action
add_action('wp_ajax_yasr_change_user_log_page', 'yasr_users_dashboard_widget_callback');
function yasr_users_dashboard_widget_callback() {
    $log_widget = new YasrLogDashboardWidget('user');
    $log_widget->userWidget();
} //End callback function


/****** Delete data value from yasr tabs when a post or page is deleted
 * Added since yasr 0.3.3
 ******/

add_action('admin_init', 'admin_init_delete_data_on_post_callback');

function admin_init_delete_data_on_post_callback() {

    if (current_user_can('delete_posts')) {
        add_action('delete_post', 'yasr_erase_data_on_post_page_remove_callback');
    }

}

function yasr_erase_data_on_post_page_remove_callback($post_id) {
    global $wpdb;

    delete_metadata('post', $post_id, 'yasr_overall_rating');
    delete_metadata('post', $post_id, 'yasr_review_type');
    delete_metadata('post', $post_id, 'yasr_multiset_author_votes');
    
    //Delete multi value
    $wpdb->delete(
        YASR_LOG_MULTI_SET,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );

    $wpdb->delete(
        YASR_LOG_TABLE,
        array(
            'post_id' => $post_id
        ),
        array(
            '%d'
        )
    );


}


/****** Function to get always the last id in the log table ******/

function yasr_count_logged_vote() {

    global $wpdb;

    $result = $wpdb->get_var("SELECT COUNT(id) FROM " . YASR_LOG_TABLE);

    if ($result) {
        return $result;
    } else {
        return '0';
    }

}


/********** Save Post actions **********/

add_action('save_post', 'yasr_insert_overall_rating_callback');

function yasr_insert_overall_rating_callback($post_id) {

    //this mean there we're not in the classic editor
    if (!isset($_POST['yasr_nonce_overall_rating'])) {
        return;
    }

    $update_result = null;

    if (isset($_POST['yasr_overall_rating'])) {
        $rating = $_POST['yasr_overall_rating'];
        $nonce  = $_POST['yasr_nonce_overall_rating'];
    } else {
        return;
    }

    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        return;
    }

    if (!wp_verify_nonce($nonce, 'yasr_nonce_overall_rating_action')) {
        return;
    }

    $rating = (float) $rating;

    if ($rating > 5) {
        $rating = 5;
    }

    //Put an action to hook into
    do_action('yasr_action_on_overall_rating', $post_id, $rating);

    $update_result = update_post_meta($post_id, 'yasr_overall_rating', $rating);

    //if update_post_meta returns an integer means this is a new post
    //so we're going to insert the default YASR_ITEMTYPE
    if (is_int($update_result)) {
        add_post_meta($post_id, 'yasr_review_type', YASR_ITEMTYPE);
    }

    //this will not work on error or
    //if the value is the same in the db
    //comment out from version 1.9.6
    //Have to find a way to save transient with gutenberg without save_post or ajax action
    /*if ($update_result) {

        $transient_name = 'yasr_overall_rating_' . $post_id;

        set_transient($transient_name, $rating, WEEK_IN_SECONDS);

    }*/

}

add_action('save_post', 'yasr_insert_review_type_callback');

function yasr_insert_review_type_callback($post_id) {
    //if user can not publish posts
    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        return;
    }

    //this mean there we're not in the classic editor
    if(!isset($_POST['yasr_nonce_review_type'])) {
        return;
    } else {
        $nonce = $_POST['yasr_nonce_review_type'];
    }

    //check nonce
    if (!wp_verify_nonce($nonce, 'yasr_nonce_review_type_action')) {
        return;
    }

    $post_id = (int)$post_id;

    //check if $_POST isset
    if (isset($_POST['yasr-review-type'])) {
        $snippet_type = $_POST['yasr-review-type'];

        //check if $snippet_type is a supported itemType
        if (yasr_is_supported_schema($snippet_type)===true) {
            //if the selected item type, is the same of the default one, delete the saved postmeta
            if ($snippet_type === YASR_ITEMTYPE) {
                delete_post_meta($post_id, 'yasr_review_type');
            } else {
                update_post_meta($post_id, 'yasr_review_type', $snippet_type);
            }
        } else {
            return;
        }
    } else {
        return;
    }

}

add_action('save_post', 'yasr_post_a_review');

function yasr_post_a_review($post_id) {

    //this mean there we're not in the classic editor
    if(!isset($_POST['yasr_nonce_is_post_review'])) {
        return;
    } else {
        $nonce      = $_POST['yasr_nonce_is_post_review'];
    }

    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        return;
    }

    if (!wp_verify_nonce($nonce, 'yasr_nonce_is_post_review_action')) {
        return;
    }

    if (isset($_POST['yasr_is_post_review'])) {
        update_post_meta($post_id, 'yasr_post_is_review', 'yes');
    }
    else {
        delete_post_meta($post_id, 'yasr_post_is_review');
    }
}


/****** Get multiple value and insert into database, used in yasr-metabox-multiple-rating ******/

add_action('save_post', 'yasr_save_multiset_editor');

function yasr_save_multiset_editor($post_id) {

    if (isset($_POST['yasr_multiset_author_votes']) && isset($_POST['yasr_multiset_id'])) {
        $field_and_vote_array = json_decode(sanitize_text_field(stripslashes($_POST['yasr_multiset_author_votes'])));
        $set_id   = (int) $_POST['yasr_multiset_id'];
        $nonce    = $_POST['yasr_nonce_save_multi_values'];

        if (!is_int($set_id) || $field_and_vote_array == '') {
            return;
        }

    } else {
        return;
    }

    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
       return;
    }

    if (!wp_verify_nonce($nonce, 'yasr_nonce_save_multi_values_action')) {
        die('Security check');
    }

    $i = 0;

    $data_to_save[$i] = array(
        'set_id' => (int)$set_id,
        'fields_and_ratings' => $field_and_vote_array
    );

    $i++;

    $set_post_meta_values = get_post_meta($post_id, 'yasr_multiset_author_votes',true);

    //If data for this post already exists
    if ($set_post_meta_values) {
        //first, loop saved fields and ratings
        foreach ($set_post_meta_values as $saved_set_id) {
            //if the saved set is different from the one that we're trying to save,
            //append data to save to the post meta
            if ($saved_set_id['set_id'] !== $set_id) {

                $data_to_save[$i]['set_id'] = $saved_set_id['set_id'];
                $data_to_save[$i]['fields_and_ratings'] = $saved_set_id['fields_and_ratings'];

                $i++;
                //Append data to save to the post meta

            } //if the set is not stored
        }
    }

    // Write new data
    update_post_meta($post_id, 'yasr_multiset_author_votes', $data_to_save);

} //End callback function

/******* Add post_meta on save_post if this post is excluded for auto insert *******/

if (YASR_AUTO_INSERT_ENABLED == 1) {

    add_action('save_post', 'yasr_exclude_auto_insert_callback');

    function yasr_exclude_auto_insert_callback($post_id) {

        //this mean there we're not in the classic editor
        if (!isset($_POST['yasr_nonce_auto_insert'])) {
            return;
        } else {
            $nonce = $_POST['yasr_nonce_auto_insert'];
        }

        if (!wp_verify_nonce($nonce, 'yasr_nonce_auto_insert_action')) {
            return;
        }

        if (isset($_POST['yasr_auto_insert_disabled'])) {
            update_post_meta($post_id, 'yasr_auto_insert_disabled', 'yes');
        } else {
            delete_post_meta($post_id, 'yasr_auto_insert_disabled');
        }

    }

}


//This will add to the REST yasr data
//must be public and not in the admin files
add_action('init', 'yasr_gutenberg_show_in_rest_overall_meta');

function yasr_gutenberg_show_in_rest_overall_meta() {
    register_meta('post', 'yasr_overall_rating',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'number',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );

    register_meta('post', 'yasr_post_is_review',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );

    register_meta('post', 'yasr_auto_insert_disabled',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );

    register_meta('post', 'yasr_review_type',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'auth_callback' => function() {
                return current_user_can('edit_posts');
            }
        )
    );

}


?>
