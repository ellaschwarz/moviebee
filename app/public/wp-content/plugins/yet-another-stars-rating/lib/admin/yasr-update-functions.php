<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit( 'You\'re not allowed to see this page' );
} // Exit if accessed directly

//Update version number and backward compatibility functions
//declared on yasr-update-functions.php
add_action('plugins_loaded', 'yasr_update_version');

function yasr_update_version() {
    //do only in admin

    if (is_admin()) {
        global $yasr_version_installed;
        global $wpdb;
        global $yasr_stored_options;

        function import_multiset_author () {

            global $wpdb;

            $old_yasr_table = $wpdb->prefix . 'yasr_multi_values';

            $sql_import_author_multiset = $wpdb->get_results(
                "SELECT post_id, set_type AS set_id, 
                        CONCAT(
                        '[',
                            GROUP_CONCAT(
                                CONCAT(
                                    '{\"field\":', field_id,', \"rating\":',votes,'}'
                                )
                            ),
                        ']'
                        ) AS fields_and_ratings 
                        FROM $old_yasr_table
                        WHERE post_id IN (
                            SELECT post_id 
                            FROM $old_yasr_table
                            GROUP BY post_id 
                            HAVING SUM(votes)>0
                        )
                        GROUP BY post_id, set_type
                        ORDER BY post_id, set_type", ARRAY_A
            );

            if(!empty($sql_import_author_multiset)) {
                //just the same code used in yasr_save_multiset_editor
                $i = 0;
                foreach ($sql_import_author_multiset as $multiset_ratings) {

                    $post_id = $multiset_ratings['post_id'];
                    $set_id = (int)$multiset_ratings['set_id'];
                    $field_and_vote_array = json_decode($multiset_ratings['fields_and_ratings']);

                    //convert in a object with json_decode
                    $data_to_save[$i] = array(
                        'set_id'             => $set_id,
                        'fields_and_ratings' => $field_and_vote_array
                    );

                    $set_post_meta_values = get_post_meta($post_id, 'yasr_multiset_author_votes',true);

                    if ($set_post_meta_values) {
                        //first, loop saved fields and ratings
                        foreach ($set_post_meta_values as $saved_set) {
                            //if the saved set is different from the one that we're trying to save,
                            //append data to save to the post meta
                            if ($saved_set['set_id'] !== $set_id) {
                                //increment i
                                $i++;
                                $data_to_save[$i]['set_id'] = $saved_set['set_id'];
                                $data_to_save[$i]['fields_and_ratings'] = $saved_set['fields_and_ratings'];

                            }
                        }
                    }
                    update_post_meta($multiset_ratings['post_id'], 'yasr_multiset_author_votes', $data_to_save);
                    //empty array
                    $data_to_save = array();
                }

            }
        }

        if ($yasr_version_installed !== false) {

            if (version_compare($yasr_version_installed, '1.7.3') === -1) {
                $wpdb->query("ALTER TABLE " . YASR_MULTI_SET_FIELDS_TABLE .
                    " CHANGE field_name field_name varchar(40) 
                    COLLATE 'utf8_unicode_ci' NOT NULL 
                    AFTER parent_set_id;
                    ");
            }

            if (version_compare($yasr_version_installed, '2.0.4') === -1) {
                $yasr_stored_options['auto_insert_align'] = 'left';
                update_option('yasr_general_options', $yasr_stored_options);
            }

            //remove end 2020
            if (version_compare($yasr_version_installed, '2.0.9') === -1) {

                //drop useless multi_set_id on yasr log table
                $wpdb->query("ALTER TABLE " . YASR_LOG_TABLE . " DROP multi_set_id");

                //change user_id in bigint 20 (just like the users table do)
                //change vote to useless 11,1 to 2,1
                //format DECIMAL(M, D) where M is the maximum number of digits (the precision) and D is the
                //number of digits to the right of the decimal point (the scale).
                $wpdb->query("ALTER TABLE " . YASR_LOG_TABLE .
                    " CHANGE user_id user_id bigint(20) NOT NULL AFTER post_id,
                               CHANGE vote vote decimal(2,1) NOT NULL AFTER user_id");

                $sql_yasr_log_multi_set_table = "CREATE TABLE " . YASR_LOG_MULTI_SET . " (
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
                ) COLLATE 'utf8_unicode_ci'";

                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

                dbDelta($sql_yasr_log_multi_set_table);

                import_multiset_author();

            }

            //remove end 2020
            if (version_compare($yasr_version_installed, '2.1.0') === -1) {

                $old_yasr_table = $wpdb->prefix . 'yasr_multi_values';

                //delete all transient that use multiset
                $sql_delete_transient = "
                DELETE FROM {$wpdb->options}
                WHERE option_name LIKE '_transient_yasr_visitor_multi_set_%'
                OR option_name LIKE '_transient_timeout_yasr_visitor_multi_set_%'
            ";

                $wpdb->query($sql_delete_transient);

                $sql_import_multiset = $wpdb->get_results(
                    "SELECT 
                    post_id, 
                    set_type, 
                    field_id,
                    number_of_votes, 
                    sum_votes/number_of_votes as average 
                FROM $old_yasr_table 
                WHERE number_of_votes > 0
                AND sum_votes > 0
                ORDER BY post_id, set_type",
                    ARRAY_A
                );

                if (!empty($sql_import_multiset)) {
                    foreach ($sql_import_multiset as $multiset_ratings) {
                        for ($i = 0; $i < $multiset_ratings['number_of_votes']; $i++) {
                            $rating_to_save = array(
                                'field_id' => $multiset_ratings['field_id'],
                                'set_type' => $multiset_ratings['set_type'],
                                'post_id' => $multiset_ratings['post_id'],
                                'vote' => $multiset_ratings['average']
                            );
                            $data_format = array('%d', '%d', '%d', '%f');
                            $wpdb->insert(YASR_LOG_MULTI_SET, $rating_to_save, $data_format);
                        }
                    }
                }
            }

            //remove begin 2021
            //this fix a bug of table not created on version 2.0.9 and 2.1.0
            if (version_compare($yasr_version_installed, '2.1.1') === -1) {
                $multi_set_name_exists = $wpdb->get_var("SELECT COUNT(1) FROM " . YASR_MULTI_SET_NAME_TABLE);

                $multi_set_field_exists = $wpdb->get_var("SELECT COUNT(1) FROM " . YASR_MULTI_SET_FIELDS_TABLE);

                if ($multi_set_name_exists === NULL || $multi_set_field_exists === NULL) {
                    yasr_create_tables();
                }
            }

            /*
             * On version 2.1.0 set_id in YASR_MULTI_SET_NAME_TABLE is set as autoincrement by default
             * In the existing installations, set_id could be = 0
             * Altering set_id to auto_increment will cause a change from 0 to 1
             * Here is the fix
             */

            if ($yasr_version_installed === '2.1.0' || $yasr_version_installed === '2.1.1') {
                //First I've to check if the column set_id is auto increment
                $column_auto_increment = null;
                $sql_check_auto_increment = "
                SELECT EXTRA 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME='" . YASR_MULTI_SET_NAME_TABLE . "'
                AND COLUMN_NAME = 'set_id' 
                AND EXTRA like '%auto_increment%'
                ";

                $column_auto_increment = $wpdb->get_var($sql_check_auto_increment);

                //if the column is not auto increment, return
                if ($column_auto_increment === 'auto_increment')  {

                    $set_with_id_zero_exists = $wpdb->get_results("
                        SELECT parent_set_id FROM "
                        . YASR_MULTI_SET_FIELDS_TABLE .
                        " WHERE parent_set_id = 0
                        LIMIT 1", ARRAY_A
                    );

                    //if array is not empty
                    if (!empty($set_with_id_zero_exists))   {
                        $sql_no_auto_increment = $wpdb->query("ALTER TABLE " . YASR_MULTI_SET_NAME_TABLE .
                            " CHANGE set_id set_id int(2) 
                        NOT NULL FIRST");

                        //if autoincrement has been removed,
                        //change set value from 1 to 0
                        //At this point, we're 100% sure that 1 was 0 before
                        if ($sql_no_auto_increment === true) {
                            $wpdb->update(
                                YASR_MULTI_SET_NAME_TABLE,
                                //new data
                                array(
                                    'set_id' => 0
                                ),
                                //where
                                array(
                                    'set_id' => 1
                                ),
                                '%d',
                                '%d'
                            );
                        }
                    }

                }

            }

            //yasr before 2.1.3 was using JSON_OBJECT to import data.
            //This function doesn't works with all servers, and import can fail
            //Here I check if no meta exists and try to import it again
            if ($yasr_version_installed === '2.0.9' || $yasr_version_installed === '2.1.0'
                || $yasr_version_installed === '2.1.1' || $yasr_version_installed === '2.1.2') {

                $sql_meta_multiset = $wpdb->query('SELECT * FROM ' . $wpdb->postmeta .
                    ' WHERE (meta_key LIKE \'%yasr_multiset_author_votes%\' 
                     OR meta_value LIKE \'%yasr_multiset_author_votes%\')
                ');

                //if no meta are found, try to import data again
                if ($sql_meta_multiset === 0) {
                    import_multiset_author();
                }

            }

        } //Endif yasr_version_installed !== false

        if (version_compare($yasr_version_installed, '2.2.0') === -1) {
            //delete all transient that uses multiset
            $sql_delete_transient_multiset = "
                DELETE FROM {$wpdb->options}
                WHERE option_name LIKE '_transient_yasr_visitor_multi_set_%'
                OR option_name LIKE '_transient_timeout_yasr_visitor_multi_set_%'
                ";
            $wpdb->query($sql_delete_transient_multiset);
        }

        /****** End backward compatibility functions ******/
        if ($yasr_version_installed != YASR_VERSION_NUM) {
            update_option('yasr-version', YASR_VERSION_NUM);
        }

    }

}