<?php

/****** Check for previous rate my post INSTALLATION *******/
class YasrSearchAndImportRatingPlugin {

    //Search for WP-PostRatings
    function yasr_search_wppr() {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('wp-postratings/wp-postratings.php')) {
            return true;
        } else {
            return false;
        }
    }

    //Search for KK STar Rating
    function yasr_search_kksr () {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('kk-star-ratings/index.php')) {
            return true;
        } else {
            return false;
        }
    }

    //Search for Rate My Post
    function yasr_search_rmp () {
        if (is_plugin_active('rate-my-post/rate-my-post.php')) {
            return true;
        } else {
            global $wpdb;

            $rmp_table = $wpdb->prefix . 'rmp_analytics';

            if ($wpdb->get_var("SHOW TABLES LIKE '$rmp_table'") == $rmp_table) {
                return true;
            } else {
                return false;
            }
        }
    }

    //Search for Multi Rating
    function yasr_search_mr () {
        //only check for active plugin, since import from table will be not used
        if (is_plugin_active('multi-rating/multi-rating.php')) {
            return true;
        } else {
            return false;
        }
    }

    function yasr_count_wppr_query_number () {
        $number_of_query_transient = get_transient('yasr_wppr_import_query_number');

        if($number_of_query_transient !== false) {
            return $number_of_query_transient;
        } else {
            $logs = $this->yasr_return_wppr_data();

            //set counter to 0
            $i = 0;

            if (empty($logs)) {
                return 0;
            } else {
                //count insert queries
                foreach ($logs as $column) {
                    for ($j=1; $j<=$column->ratings_users; $j++) {
                            $i ++;
                    }
                }
            }

            set_transient('yasr_wppr_import_query_number', $i, DAY_IN_SECONDS);
            return $i;
        }

    }

    function yasr_count_kksr_query_number () {
        $number_of_query_transient = get_transient('yasr_kksr_import_query_number');

        if($number_of_query_transient !== false) {
            return $number_of_query_transient;
        } else {
            $logs = $this->yasr_return_kksr_data();

            //set counter to 0
            $i = 0;

            if (empty($logs)) {
                return 0;
            } else {
                //count insert queries
                foreach ($logs as $column) {
                    for ($j=1; $j<=$column->ratings_users; $j++) {
                        $i ++;
                    }
                }
            }

            set_transient('yasr_kksr_import_query_number', $i, DAY_IN_SECONDS);
            return $i;
        }

    }

    function yasr_count_rmp_query_number () {
        global $wpdb;

        $number_of_query_transient = get_transient('yasr_rmp_import_query_number');

        if($number_of_query_transient !== false) {
            return $number_of_query_transient;
        } else {
            $logs = $this->yasr_return_rmp_data();

            if (empty($logs)) {
                return 0;
            } else {
                set_transient('yasr_rmp_import_query_number', $wpdb->num_rows, DAY_IN_SECONDS);
                return $wpdb->num_rows;
            }
        }

    }

    function yasr_count_mr_query_number () {
        $number_of_query_transient = get_transient('yasr_mr_import_query_number');

        if($number_of_query_transient !== false) {
            return $number_of_query_transient;
        } else {
            $logs=$this->yasr_return_mr_data();

            //set counter to 0
            $i = 0;

            if (empty($logs)) {
                return 0;
            } else {
                //count insert queries
                foreach ($logs as $column) {
                    for ($j=1; $j<=$column->ratings_users; $j++) {
                        $i ++;
                    }
                }
            }
            set_transient('yasr_mr_import_query_number', $i, DAY_IN_SECONDS);
            return $i;
        }

    }

    //Import WpPostRating Data
    function yasr_return_wppr_data () {
        global $wpdb;

        $logs = $wpdb->get_results("
                        SELECT pm.post_id, 
                            MAX(CASE WHEN pm.meta_key = 'ratings_average' THEN pm.meta_value END) as ratings_average,
                            MAX(CASE WHEN pm.meta_key = 'ratings_users' THEN pm.meta_value END) as ratings_users
                        FROM $wpdb->postmeta as pm,
                             $wpdb->posts as p
                        WHERE pm.meta_key IN ('ratings_average', 'ratings_users')
                            AND pm.meta_value <> 0
                            AND pm.post_id = p.ID
                        GROUP BY pm.post_id ASC
                        ORDER BY pm.post_id
                        "
        );

        if (empty($logs)) {
            return 0;
        } else {
            return $logs;
        }
    }

    //Import KK Star Rating Data
    function yasr_return_kksr_data() {
        global $wpdb;

        $logs=$wpdb->get_results("
                                SELECT pm.post_id, 
                                    MAX(CASE WHEN pm.meta_key = '_kksr_avg' THEN pm.meta_value END) as ratings_average,
                                    MAX(CASE WHEN pm.meta_key = '_kksr_casts' THEN pm.meta_value END) as ratings_users
                                FROM $wpdb->postmeta as pm,
                                     $wpdb->posts as p
                                WHERE pm.meta_key IN ('_kksr_avg', '_kksr_casts')
                                    AND pm.meta_value <> 0
                                    AND pm.post_id = p.ID
                                GROUP BY pm.post_id ASC
                                ORDER BY pm.post_id
                                "
        );

        if (empty($logs)) {
            return 0;
        } else {
            return $logs;
        }
    }

    function yasr_return_rmp_data() {
        global $wpdb;

        $rmp_table=$wpdb->prefix . 'rmp_analytics';

        //get logs
        $logs=$wpdb->get_results("
                                SELECT rmp.post AS post_id,
                                       rmp.value as vote, 
                                       rmp.time AS date,
                                       p.ID
                                FROM $rmp_table AS rmp, 
                                     $wpdb->posts AS p
                                WHERE rmp.post = p.id"
        );

        if (empty($logs)) {
            return 0;
        } else {
            return $logs;
        }
    }

    //Import Multi Rating Data
    function yasr_return_mr_data() {
        global $wpdb;

        $logs = $wpdb->get_results("
                                    SELECT pm.post_id, 
                                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_star_rating' THEN pm.meta_value END) as ratings_average,
                                        MAX(CASE WHEN pm.meta_key = 'mr_rating_results_count_entries' THEN pm.meta_value END) as ratings_users
                                    FROM $wpdb->postmeta as pm,
                                         $wpdb->posts as p
                                    WHERE pm.meta_key IN ('mr_rating_results_star_rating', 'mr_rating_results_count_entries')
                                        AND pm.meta_value <> 0
                                        AND pm.post_id = p.ID
                                    GROUP BY pm.post_id ASC
                                    ORDER BY pm.post_id
                                    "
        );

        if (empty($logs)) {
            return 0;
        } else {
            return $logs;
        }
    }

}

add_action( 'wp_ajax_yasr_import_wppr', 'yasr_import_wppr_callback' );

function yasr_import_wppr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-wppr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    //get logs
    //With Wp Post Rating I need to import postmeta.
    //It has his own table too, but can be disabled in the settings.
    //The only way to be sure is get the postmeta

    $wppr = new YasrSearchAndImportRatingPlugin();

    $logs = $wppr->yasr_return_wppr_data();

    if(empty($logs)) {
        echo json_encode(__('No WP Post Rating data found'));
    } else {
        /****** Insert logs ******/
        foreach ($logs as $column) {

            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {

                //check if rating_average is not null.
                //I found out that sometimes Wp Post Rating can save value with null data (sigh!!)
                if ($column->ratings_average !== null) {

                    $result = $wpdb->replace(
                        YASR_LOG_TABLE,
                        array(
                            'post_id'      => $column->post_id,
                            'user_id'      => 0, //not stored on wp post rating
                            'vote'         => $column->ratings_average,
                            'date'         => 'wppostrating', //not stored on wp post rating
                            'ip'           => 'wppostrating'//not stored on wp post rating
                        ),
                        array('%d', '%d', '%f', '%s', '%s')
                    );
                }
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('wppr');

            $string_to_return = __('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }
    die();
}

add_action( 'wp_ajax_yasr_import_kksr', 'yasr_import_kksr_callback' );

function yasr_import_kksr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-kksr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    //get logs
    //With KK star rating I need to import postmeta.
    $kksr = new YasrSearchAndImportRatingPlugin();

    $logs= $kksr->yasr_return_kksr_data();

    if(empty($logs)) {
        echo json_encode(__('No KK Star Ratings data found'));
    } else {
        /****** Insert logs ******/
        foreach ($logs as $column) {
            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {
                $result = $wpdb->replace(
                    YASR_LOG_TABLE,
                    array(
                        'post_id'      => $column->post_id,
                        'user_id'      => 0, //not stored on KK star rating
                        'vote'         => $column->ratings_average,
                        'date'         => 'kkstarratings', //not stored KK star rating
                        'ip'           => 'kkstarratings'//not stored KK star rating
                    ),
                    array('%d', '%d', '%f', '%s', '%s')
                );
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('kksr');

            $string_to_return = __('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }
    die();
}

add_action( 'wp_ajax_yasr_import_ratemypost', 'yasr_import_ratemypost_callback' );

function yasr_import_ratemypost_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-ratemypost-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    $rmp = new YasrSearchAndImportRatingPlugin();

    //get logs
    $logs=$rmp->yasr_return_rmp_data();

    if(empty($logs)) {
        echo json_encode(__('No Rate My Post data found'));
    } else {
        /****** Insert logs ******/
        foreach ($logs as $column) {
            $result = $wpdb->replace(
                YASR_LOG_TABLE,
                array(
                    'post_id'      => $column->post_id,
                    'user_id'      => 0, //seems like rate my post store all users like -1, so I cant import the user_id
                    'vote'         => $column->vote,
                    'date'         => $column->date,
                    'ip'           => 'ratemypost'
                ),
                array('%d', '%d', '%f', '%s', '%s')
            );
        }

        if ($result) {
            yasr_save_option_imported_plugin('rmp');

            $string_to_return = __('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }
    }
    die();
}

add_action( 'wp_ajax_yasr_import_mr', 'yasr_import_mr_callback' );

function yasr_import_mr_callback() {

    if($_POST['nonce']) {
        $nonce = $_POST['nonce'];
    } else {
        exit();
    }

    if (!wp_verify_nonce( $nonce, 'yasr-import-mr-action' ) ) {
        die('Error while checking nonce');
    }

    if (!current_user_can( 'manage_options' ) ) {
        die(__( 'You do not have sufficient permissions to access this page.', 'yet-another-stars-rating' ));
    }

    global $wpdb;

    $mr_exists = new YasrSearchAndImportRatingPlugin;

    //get logs
    //With Multi Rating I need to import postmeta.
    $logs=$mr_exists->yasr_return_mr_data();

    if(empty($logs)) {
        echo json_encode(__('No Multi Rating data found'));
    } else {
        /****** Insert logs ******/
        foreach ($logs as $column) {

            if($column->ratings_average > 5) {
                $column->ratings_average = 5;
            }

            for ($i=1; $i<=$column->ratings_users; $i++) {
                $result = $wpdb->replace(
                    YASR_LOG_TABLE,
                    array(
                        'post_id'      => $column->post_id,
                        'user_id'      => 0, //not stored on KK star rating
                        'vote'         => $column->ratings_average,
                        'date'         => 'multirating', //not stored KK star rating
                        'ip'           => 'multirating'//not stored KK star rating
                    ),
                    array('%d', '%d', '%f', '%s', '%s')
                );
            }
        }

        if ($result) {
            yasr_save_option_imported_plugin('mr');

            $string_to_return = __('Woot! All data have been imported!', 'yet-another-stars-rating');
            echo json_encode($string_to_return);
        }

    }

    die();
}

function yasr_save_option_imported_plugin($plugin) {

    global $wpdb;

    //delete all transient related to yasr_visitor_votes
    $sql_delete_transient = "
    DELETE FROM {$wpdb->options}
        WHERE option_name LIKE '_transient_yasr_visitor_votes_%'
        OR option_name LIKE  '_transient_timeout_yasr_visitor_votes_%'
    ";

    $wpdb->query($sql_delete_transient);

    //get actual data
    $plugin_imported = get_option('yasr_plugin_imported');
    //Add plugin just imported as a key
    $plugin_imported[$plugin] = array('date' => date('Y-m-d H:i:s'));
    //update option
    update_option('yasr_plugin_imported', $plugin_imported, false);
}

function yasr_import_plugin_alert_box($plugin, $number_of_queries) {

    echo '<div class="yasr-alert-box">';
        echo sprintf(__(
            'To import %s seems like %s %d %s INSERT queries are necessary. %s
                There is nothing wrong with that, but some hosting provider can have a query limit/hour. %s
                I strongly suggest to contact your hosting and ask about your plan limit',
            'yet-another-stars-rating'
        ),$plugin, '<strong>', $number_of_queries, '</strong>', '<br />','<br />');
    echo '</div>';

}

?>