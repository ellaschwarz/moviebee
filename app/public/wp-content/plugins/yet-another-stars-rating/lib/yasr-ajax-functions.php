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

/*************************** Admin ajax functions ***********************/

/********** Functions used while wirting a new post or page ********/

/****** Get Set name from post or page and output the set,
 * used in yasr-metabox-multiple-rating******/

add_action('wp_ajax_yasr_send_id_nameset', 'yasr_output_multiple_set_callback');

function yasr_output_multiple_set_callback() {

    if (isset($_POST['set_id']) && isset($_POST['post_id']) && $_POST['post_id'] != '' && $_POST['set_id'] != '') {
        $set_id  = (int) $_POST['set_id'];
        $post_id = (int) $_POST['post_id'];
    } else {
        exit();
    }

    if (!current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
    }

    yasr_return_multi_set_values_admin($post_id, $set_id);

    die();

}


/****** Create the content for the button shortcode in Tinymce ******/

//Add ajax action that will be called from the .js for button in tinymce
add_action('wp_ajax_yasr_create_shortcode', 'wp_ajax_yasr_create_shortcode_callback');

function wp_ajax_yasr_create_shortcode_callback() {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];
    } else {
        exit();
    }

    global $wpdb;

    $multi_set = yasr_get_multi_set();
    $n_multi_set = $wpdb->num_rows;

    ?>

    <div id="yasr-tinypopup-form">

        <h2 class="nav-tab-wrapper yasr-underline">
            <a href="#" id="yasr-link-tab-main"
               class="nav-tab nav-tab-active yasr-nav-tab"><?php _e("Main", 'yet-another-stars-rating'); ?></a>
            <a href="#" id="yasr-link-tab-charts"
               class="nav-tab yasr-nav-tab"><?php _e("Charts", 'yet-another-stars-rating'); ?></a>
            <?php do_action('yasr_add_tabs_on_tinypopupform'); ?>

            <a href="https://yetanotherstarsrating.com/yasr-basics-shortcode/" target="_blank"
               id="yasr-tinypopup-link-doc"><?php _e("Read the doc", 'yet-another-stars-rating'); ?></a>

        </h2>

        <div id="yasr-content-tab-main" class="yasr-content-tab-tinymce">

            <table id="yasr-table-tiny-popup-main" class="form-table">

                <tr>
                    <th>
                        <label for="yasr-overall"><?php _e("Overall Rating / Review", 'yet-another-stars-rating'); ?></label>
                    </th>
                    <td>
                        <input type="button" class="button-primary" id="yasr-overall" name="yasr-overall"
                               value="<?php _e("Insert Overall Rating", 'yet-another-stars-rating'); ?>"/><br/>
                        <small><?php _e("Insert Overall Rating / Review for this post", 'yet-another-stars-rating'); ?></small>

                        <div id="yasr-overall-choose-size">
                            <small><?php _e("Choose Size", 'yet-another-stars-rating'); ?><small>
                                    <div class="yasr-tinymce-button-size">
                                        <input type="button" class="button-secondary" id="yasr-overall-insert-small"
                                               name="yasr-overall-insert-small"
                                               value="<?php _e("Small", 'yet-another-stars-rating'); ?>"/>
                                        <input type="button" class="button-secondary" id="yasr-overall-insert-medium"
                                               name="yasr-overall-insert-medium"
                                               value="<?php _e("Medium", 'yet-another-stars-rating'); ?>"/>
                                        <input type="button" class="button-secondary" id="yasr-overall-insert-large"
                                               name="yasr-overall-insert-large"
                                               value="<?php _e("Large", 'yet-another-stars-rating'); ?>"/>
                                    </div>
                        </div>

                    </td>
                </tr>

                <tr>
                    <th><label for="yasr-id"><?php _e("Visitor Votes", 'yet-another-stars-rating'); ?></label></th>
                    <td>
                        <input type="button" class="button-primary" name="yasr-visitor-votes" id="yasr-visitor-votes"
                               value="<?php _e("Insert Visitor Votes", 'yet-another-stars-rating'); ?>"/><br/>
                        <small><?php _e("Insert the ability for your visitor to vote", 'yet-another-stars-rating'); ?></small>

                        <div id="yasr-visitor-choose-size">
                            <small><?php _e("Choose Size", 'yet-another-stars-rating'); ?><small>
                                    <div class="yasr-tinymce-button-size">
                                        <input type="button" class="button-secondary" id="yasr-visitor-insert-small"
                                               name="yasr-visitor-insert-small"
                                               value="<?php _e("Small", 'yet-another-stars-rating'); ?>"/>
                                        <input type="button" class="button-secondary" id="yasr-visitor-insert-medium"
                                               name="yasr-visitor-insert-medium"
                                               value="<?php _e("Medium", 'yet-another-stars-rating'); ?>"/>
                                        <input type="button" class="button-secondary" id="yasr-visitor-insert-large"
                                               name="yasr-visitor-insert-large"
                                               value="<?php _e("Large", 'yet-another-stars-rating'); ?>"/>
                                    </div>
                        </div>

                    </td>
                </tr>

                <?php if ($n_multi_set > 1) { //If multiple Set are found ?>

                    <tr>
                        <th>
                            <label for="yasr-size"><?php _e("If you want to insert a Multi Set, pick one:", 'yet-another-stars-rating'); ?></label>
                        </th>
                        <td>
                            <?php foreach ($multi_set as $name) { ?>
                                <input type="radio" value="<?php echo $name->set_id ?>" name="yasr_tinymce_pick_set"
                                       class="yasr_tinymce_select_set"><?php echo $name->set_name ?>
                                <br/>
                            <?php } //End foreach ?>
                            <small><?php _e("Choose wich set you want to insert.", 'yet-another-stars-rating'); ?></small>

                            <p>
                                <input type="checkbox"
                                       id="yasr-allow-vote-multiset"><?php _e("Readonly?", 'yet-another-stars-rating'); ?>
                                <br/>
                            </p>

                            <small><?php _e("If Readonly is checked, only you can insert the votes (in the box above the editor)", 'yet-another-stars-rating'); ?></small>

                            <p>
                                <input type="checkbox"
                                       id="yasr-hide-average-multiset"><?php _e("Hide Average?", 'yet-another-stars-rating'); ?>
                                <br/>
                            </p>

                            <p>
                                <input type="button" class="button-primary" name="yasr-insert-multiset"
                                       id="yasr-insert-multiset-select"
                                       value="<?php _e("Insert Multi Set", 'yet-another-stars-rating') ?>"/><br/>
                            </p>

                        </td>
                    </tr>

                <?php } //End if

                elseif ($n_multi_set == 1) { ?>
                    <tr>
                        <th><label for="yasr-size"><?php _e("Insert Multiset:", 'yet-another-stars-rating'); ?></label>
                        </th>
                        <td>
                            <p>
                                <input type="checkbox"
                                       id="yasr-allow-vote-multiset"><?php _e("Readonly?", 'yet-another-stars-rating'); ?>
                                <br/>
                            </p>

                            <small><?php _e("If Readonly is checked, only you can insert the votes (in the box above the editor)", 'yet-another-stars-rating'); ?></small>

                            <p>
                                <input type="checkbox"
                                       id="yasr-hide-average-multiset"><?php _e("Hide Average?", 'yet-another-stars-rating'); ?>
                                <br/>
                            </p>

                            <?php foreach ($multi_set as $name) { ?>

                                <button type="button" class="button-primary" id="yasr-single-set" name="yasr-single-set"
                                        value="<?php echo $name->set_id ?>"><?php _e("Insert Multiple Set", 'yet-another-stars-rating'); ?></button>

                            <?php } //End foreach ?>
                        </td>
                    </tr>
                    <?php
                }
                //End elseif ?>
            </table>

        </div>

        <div id="yasr-content-tab-charts" class="yasr-content-tab-tinymce" style="display:none">

            <table id="yasr-table-tiny-popup-charts" class="form-table">
                <tr>
                    <th><label for="yasr-10-overall"><?php _e("Ranking reviews", 'yet-another-stars-rating'); ?></label>
                    </th>
                    <td><input type="button" class="button-primary" name="yasr-top-10-overall-rating"
                               id="yasr-top-10-overall-rating"
                               value="<?php _e("Insert Ranking reviews", 'yet-another-stars-rating') ?>"/><br/>
                        <small><?php _e("Insert Top 10 ranking for [yasr_overall_rating] shortcode", 'yet-another-stars-rating'); ?></small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-10-highest-most-rated"><?php _e("Users' ranking", 'yet-another-stars-rating'); ?></label>
                    </th>
                    <td><input type="button" class="button-primary" name="yasr-10-highest-most-rated"
                               id="yasr-10-highest-most-rated"
                               value="<?php _e("Insert Users ranking", 'yet-another-stars-rating') ?>"/><br/>
                        <small><?php _e("Insert Top 10 ranking for [yasr_visitor_votes] shortcode", 'yet-another-stars-rating'); ?></small>
                    </td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-5-active-reviewers"><?php _e("Most active reviewers", 'yet-another-stars-rating'); ?></label>
                    </th>
                    <td><input type="button" class="button-primary" name="yasr-5-active-reviewers"
                               id="yasr-5-active-reviewers"
                               value="<?php _e("Insert Most Active Reviewers", 'yet-another-stars-rating') ?> "/><br/>
                        <small><?php _e("Insert Top 5 active reviewers", 'yet-another-stars-rating'); ?></small></td>
                </tr>

                <tr>
                    <th>
                        <label for="yasr-10-active-users"><?php _e("Most Active Users", 'yet-another-stars-rating'); ?></label>
                    </th>
                    <td><input type="button" class="button-primary" name="yasr-top-10-active-users"
                               id="yasr-top-10-active-users"
                               value="<?php _e("Insert Most Active Users", 'yet-another-stars-rating') ?>"/><br/>
                        <small><?php _e("Insert Top 10 voters [yasr_visitor_votes] shortcode", 'yet-another-stars-rating'); ?></small>
                    </td>
                </tr>

            </table>

        </div>

        <?php do_action('yasr_add_content_on_tinypopupform'); ?>

    </div>

    <script type="text/javascript">

        jQuery(document).ready(function () {

            var nMultiSet = <?php echo(json_encode("$n_multi_set")); ?>

                yasrShortcodeCreator(nMultiSet);


        });

    </script>

    <?php

    die();

} //End callback function

/********** END Functions used while wirting a new post or page ********/

//add_action('wp_ajax_yasr_change_user_log_page', 'yasr_change_user_log_page_callback');

function yasr_change_user_log_page_callback() {

    if (isset($_POST['pagenum'])) {
        $page_num     = (int)$_POST['pagenum'];
        $num_of_pages = (int)$_POST['totalpages'];
    } else {
        $page_num = 1;
    }

    $limit = 8; //max number of row to echo

    $offset = ($page_num - 1) * $limit;

    $user_id = get_current_user_id();

    global $wpdb;

    $log_result = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM "
            . YASR_LOG_TABLE .
            " WHERE user_id = %d 
            ORDER BY date DESC 
            LIMIT %d, %d ",
            $user_id, $offset, $limit
        )
    );

    if (!$log_result) {
        _e("No Recenet votes yet", 'yet-another-stars-rating');
    } else {

        foreach ($log_result as $column) {

            $avatar = get_avatar($user_id, '32'); //Get avatar from user id

            $title_post = wp_strip_all_tags(get_the_title($column->post_id)); //Get post title from post id
            $link       = get_permalink($column->post_id); //Get post link from post id

            $yasr_log_vote_text = sprintf(__('You rated %s on ', 'yet-another-stars-rating'), '<strong style="color: blue">' . $column->vote . '</strong>');

            echo "

                        <div class='yasr-log-div-child'>

                            <div class='yasr-log-image'>
                                $avatar
                            </div>

                            <div class='yasr-log-child-head'>
                                 <span id='yasr-log-vote'>$yasr_log_vote_text</span><span class='yasr-log-post'><a href='$link'>$title_post</a></span>
                            </div>

                            <div class='yasr-log-ip-date'>
                                <span class='yasr-log-ip'>" . __("Ip address", 'yet-another-stars-rating') . ": <span style='color:blue'>$column->ip</span></span>
                                <span class='yasr-log-date'>$column->date</span>
                            </div>

                        </div>

                    ";

        } //End foreach

        echo "<div id='yasr-log-page-navigation'>";

        //use data attribute instead of value of #yasr-log-total-pages, because, on ajaxresponse,
        //the "last" button could not exists
        //This is required on ajax, not here, but still doing it here to take it simple
        echo "<span id='yasr-user-log-total-pages' data-yasr-user-log-total-pages='$num_of_pages'>";

        _e("Pages", 'yet-another-stars-rating');
        echo ": ($num_of_pages) &nbsp;&nbsp;&nbsp;";

        echo "</span>";

        if ($num_of_pages <= 3) {

            for ($i = 1; $i <= $num_of_pages; $i ++) {
                if ($i == $page_num) {
                    echo "<button class='button-primary' value='$i'>$i</button>&nbsp;&nbsp;";
                } else {
                    echo "<button class='yasr-user-log-page-num' value='$i'>$i</button>&nbsp;&nbsp;";

                }
            }
            echo "<span id='yasr-loader-user-log-metabox' style='display:none;'>&nbsp;<img src='" . YASR_IMG_DIR . "/loader.gif' ></span>";
        } else {
            $start_for = $page_num - 1;

            if ($start_for <= 0) {
                $start_for = 1;
            }

            $end_for = $page_num + 1;

            if ($end_for >= $num_of_pages) {
                $end_for = $num_of_pages;
            }

            if ($page_num >= 3) {
                echo "<button class='yasr-user-log-page-num' value='1'>&laquo; First </button>&nbsp;&nbsp;...&nbsp;&nbsp;";
            }

            for ($i = $start_for; $i <= $end_for; $i ++) {

                if ($i == $page_num) {
                    echo "<button class='button-primary' value='$i'>$i</button>&nbsp;&nbsp;";
                } else {
                    echo "<button class='yasr-user-log-page-num' value='$i'>$i</button>&nbsp;&nbsp;";
                }

            }

            $num_of_page_less_one = $num_of_pages - 1;

            if ($page_num != $num_of_pages && $page_num != $num_of_page_less_one) {
                echo "...&nbsp;&nbsp;<button class='yasr-user-log-page-num' value='$num_of_pages'>Last &raquo;</button>&nbsp;&nbsp;";
            }

            echo "<span id='yasr-user-log-container' style='display:none;' >&nbsp;<img src='" . YASR_IMG_DIR . "/loader.gif' ></span>";

        }

        echo "

            </div>

        </div>";

    } // End else if !$log result

    die();

}

/********************* END Admin ajax functions ****************/


/********************* NON Admin ajax functions ****************/

/****** Yasr insert visitor votes, called from yasr-shortcode-function ******/

add_action('wp_ajax_yasr_send_visitor_rating', 'yasr_insert_visitor_votes_callback');
add_action('wp_ajax_nopriv_yasr_send_visitor_rating', 'yasr_insert_visitor_votes_callback');

function yasr_insert_visitor_votes_callback() {

    if (isset($_POST['rating']) && isset($_POST['post_id']) && isset($_POST['nonce_visitor'])) {

        $rating        = (int) $_POST['rating'];
        $post_id       = (int) $_POST['post_id'];
        $nonce_visitor = $_POST['nonce_visitor'];
        $is_singular   = $_POST['is_singular'];

        if (!is_int($post_id)) {
            exit();
        }

    } else {
        exit();
    }

    $array_action_visitor_vote = array('post_id' => $post_id, 'is_singular' => $is_singular);

    do_action('yasr_action_on_visitor_vote', $array_action_visitor_vote);

    if (!wp_verify_nonce($nonce_visitor, 'yasr_nonce_insert_visitor_rating')) {
        die('Security check');
    }

    if ($rating < 1) {
        $rating = 1;
    } elseif ($rating > 5) {
        $rating = 5;
    }

    $transient_name = 'yasr_visitor_votes_' . $post_id;

    delete_transient($transient_name);

    global $wpdb;

    $current_user = wp_get_current_user();
    $ip_adress    = yasr_get_ip();

    $result_update_log = null; //avoid undefined
    $result_insert_log = null; //avoid undefined

    if (is_user_logged_in()) {

        //try to update first, if fails the do the insert
        $result_update_log = $wpdb->update(
            YASR_LOG_TABLE,
            array(
                'post_id' => $post_id,
                'user_id' => $current_user->ID,
                'vote'    => $rating,
                'date'    => date('Y-m-d H:i:s'),
                'ip'      => $ip_adress
            ),
            array(
                'post_id' => $post_id,
                'user_id' => $current_user->ID
            ),
            array('%d', '%d', '%d', '%s', '%s', '%s'),
            array('%d', '%d')

        );

        //insert the new row
        //use ! instead of === FALSE
        if (!$result_update_log) {
            $result_insert_log = $wpdb->insert(
                YASR_LOG_TABLE,
                array(
                    'post_id' => $post_id,
                    'user_id' => $current_user->ID,
                    'vote'    => $rating,
                    'date'    => date('Y-m-d H:i:s'),
                    'ip'      => $ip_adress
                ),
                array('%d', '%d', '%d', '%s', '%s', '%s')
            );
        }

    } //if user is not logged in insert
    else {

        //be sure that allow anonymous is on
        if (YASR_ALLOWED_USER === 'allow_anonymous') {
            $result_insert_log = $wpdb->replace(
                YASR_LOG_TABLE,
                array(
                    'post_id' => $post_id,
                    'user_id' => $current_user->ID,
                    'vote'    => $rating,
                    'date'    => date('Y-m-d H:i:s'),
                    'ip'      => $ip_adress
                ),

                array('%d', '%d', '%d', '%s', '%s', '%s')
            );
        }

    }

    if ($result_update_log || $result_insert_log) {

        $row_exists_obj = new YasrDatabaseRatings();
        $row_exists     = $row_exists_obj->getVisitorVotes($post_id, false);

        foreach ($row_exists as $results) {
            $stored_user_votes_sum  = $results->sum_votes;
            $stored_number_of_votes = $results->number_of_votes;
        }

        $user_votes_sum  = $stored_user_votes_sum;
        $number_of_votes = $stored_number_of_votes;

        $cookiename = 'yasr_visitor_vote_cookie';

        $data_to_save = array(
            'post_id' => $post_id,
            'rating'  => $rating
        );

        yasr_setcookie($cookiename, $data_to_save);

        $total_rating  = ($user_votes_sum / $number_of_votes);
        $medium_rating = round($total_rating, 1);

        $html_to_return = '<span class="yasr-total-average-text"> [' . __('Total:', 'yet-another-stars-rating') . " $number_of_votes &nbsp; &nbsp;" . __('Average:', 'yet-another-stars-rating') . " $medium_rating/5 ]</span>";
        $html_to_return .= '<span class="yasr-small-block-bold" id="yasr-vote-saved">' . __('Vote Saved', 'yet-another-stars-rating') . '</span>';

        echo json_encode($html_to_return);

    }

    die(); // this is required to return a proper result

}

/****** Get Multiple value from visitor and insert into db, used in yasr-shortcode-functions ******/

add_action('wp_ajax_yasr_visitor_multiset_field_vote', 'yasr_visitor_multiset_field_vote_callback');
add_action('wp_ajax_nopriv_yasr_visitor_multiset_field_vote', 'yasr_visitor_multiset_field_vote_callback');

function yasr_visitor_multiset_field_vote_callback() {

    if (isset($_POST['post_id']) && isset($_POST['rating']) && isset($_POST['set_type'])) {
        $post_id  = (int) $_POST['post_id'];
        $rating   = $_POST['rating'];
        $set_type = (int) $_POST['set_type'];
        $nonce    = $_POST['nonce'];

        if (!is_int($post_id) || !is_int($set_type)) {
            exit("Missing post id or set type");
        }

        if ($rating == "") {
            exit("You must insert at least a rating");
        }

    } else {
        exit();
    }

    if (!wp_verify_nonce($nonce, 'yasr_nonce_insert_visitor_rating_multiset')) {
        die('Security Check');
    }

    $current_user = wp_get_current_user();
    $ip_adress    = yasr_get_ip();

    delete_transient('yasr_visitor_multi_set_' . $post_id . '_' . $set_type);

    $array_action_visitor_multiset_vote = array('post_id' => $post_id);

    do_action('yasr_action_on_visitor_multiset_vote', $array_action_visitor_multiset_vote);

    global $wpdb;

    $array_error = array();

    //clean array, so if an user rate same field twice, take only the last rating
    $cleaned_array = yasr_unique_multidim_array($rating, 'field');

    //this is a counter: if at the end of the foreach it still 0, means that an user rated in a set
    //and then submit another one
    $counter_matched_fields = 0;

    foreach ($cleaned_array as $rating_values) {

        //check if the set id in the array is the same of the clicked
        if ($rating_values['postid'] == $post_id && $rating_values['setid'] == $set_type) {

            //increase the counter
            $counter_matched_fields = $counter_matched_fields + 1;

            $id_field = $rating_values['field'];
            $rating   = $rating_values['rating'];

            $query_success = $wpdb->insert(
                YASR_LOG_MULTI_SET,
                array(
                    'field_id' => $id_field,
                    'set_type' => $set_type,
                    'post_id'  => $post_id,
                    'vote'     => $rating,
                    'user_id'  => $current_user->ID,
                    'date'     => date('Y-m-d H:i:s'),
                    'ip'       => $ip_adress

                ),
                array("%d", "%d", "%d", "%d", "%d", "%s", "%s")
            );

            if ($query_success) {
                $array_error[] = 0;
            } else {
                $array_error[] = 1;
            }

        } //End if $rating_values['postid'] == $post_id

    } //End foreach ($rating as $rating_values)

    if ($counter_matched_fields === 0) {
        $array_error[] = 1;
    }

    $error_found = false;

    foreach ($array_error as $error) {
        if ($error === 1) {
            $error_found = true;
        }
    }

    if (!$error_found) {
        $cookiename = 'yasr_multi_visitor_cookie';

        $data_to_save = array(
            'post_id' => $post_id,
            'set_id'  => $set_type
        );

        yasr_setcookie($cookiename, $data_to_save);

        _e('Rating saved!', 'yet-another-stars-rating');

    } else {
        _e('Rating not saved. Please Try again', 'yet-another-stars-rating');
    }

    die();

} //End callback function


add_action('wp_ajax_yasr_stats_visitors_votes', 'yasr_stats_visitors_votes_callback');
add_action('wp_ajax_nopriv_yasr_stats_visitors_votes', 'yasr_stats_visitors_votes_callback');

function yasr_stats_visitors_votes_callback() {

    if (isset($_POST['post_id']) && $_POST['post_id'] !== '') {
        $post_id = $_POST['post_id'];
    } else {
        return;
    }

    $visitor_votes_obj = new YasrDatabaseRatings();
    $votes_array       = $visitor_votes_obj->getVisitorVotes($post_id);

    $medium_rating = 0;   //Avoid undefined variable

    if (is_array($votes_array) && !empty($votes_array)) {
        foreach ($votes_array as $user_votes) {
            $votes_number = $user_votes->number_of_votes;
            if ($votes_number != 0) {
                $medium_rating = ($user_votes->sum_votes / $votes_number);
            } else {
                $medium_rating = 0;
            }
        }
    }

    $medium_rating = round($medium_rating, 1);
    $missing_vote  = null; //avoid undefined variable

    global $wpdb;

    $stats = $wpdb->get_results($wpdb->prepare("SELECT ROUND( vote, 0 ) as vote, COUNT( vote ) AS n_of_votes
                        FROM " . YASR_LOG_TABLE . "
                        WHERE post_id=%d
                        GROUP BY vote
                        ORDER BY vote DESC
                        ",
        $post_id),
        ARRAY_A);

    $total_votes = 0; //Avoid undefined variable if stats exists. Necessary if $stats not exists

    //if query return 0 write an empty array $existing_votes
    if (!$stats) {
        $existing_votes = array();
    } else {
        //Write a new array with only existing votes, and count all the number of votes
        foreach ($stats as $votes_array) {
            $existing_votes[] = $votes_array['vote'];//Create an array with only existing votes
            $total_votes      = $total_votes + $votes_array['n_of_votes'];
        }

    }

    for ($i = 1; $i <= 5; $i ++) {
        //If query return 0 write a new $stats array with index
        if (!$stats) {
            $stats[$i]               = array();
            $stats[$i]['vote']       = $i;
            $stats[$i]['n_of_votes'] = 0;
        } else {
            //If in the new array there are some vote missing create a new array
            if (!in_array($i, $existing_votes)) {
                $missing_vote[$i]               = array();
                $missing_vote[$i]['vote']       = $i;
                $missing_vote[$i]['n_of_votes'] = 0;
            }
        }
    }

    //If missing_vote exists merge it
    if ($missing_vote) {
        $stats = array_merge($stats, $missing_vote);
    }

    arsort($stats); //sort it by $votes[n_of_votes]

    $html_to_return = '<div class="yasr-visitors-stats-tooltip">';
    $html_to_return .= '<span id="yasr-medium-rating-tooltip">' . $medium_rating . ' '
                       . __('out of 5 stars', 'yet-another-stars-rating') .
                       '</span>';
    $html_to_return .= '<div class="yasr-progress-bars-container">';

    if ($total_votes == 0) {
        $increase_bar_value = 0;
    } else {
        $increase_bar_value = 100 / $total_votes; //Find how much all the bars should increase per vote
    }

    $i = 5;

    $stars_text = __("stars", 'yet-another-stars-rating');

    foreach ($stats as $logged_votes) {

        if ($i == 1) {
            $stars_text = __("star", 'yet-another-stars-rating');
        }

        $value_progressbar = $increase_bar_value * $logged_votes['n_of_votes']; //value of the single bar
        $value_progressbar = round($value_progressbar, 2) . '%'; //use only 2 decimal

        $html_to_return .= "<div class='yasr-progress-bar-row-container yasr-w3-container'>
                                <div class='yasr-progress-bar-name'>$i $stars_text</div>
                                <div class='yasr-single-progress-bar-container'>
                                    <div class='yasr-w3-border '>
                                        <div class='yasr-w3-amber' style='height:17px;width:$value_progressbar'></div>
                                    </div></div>
                                <div class='yasr-progress-bar-votes-count'>" . $logged_votes['n_of_votes'] . "</div><br />
                                </div>";

        $i --;

    } //End foreach

    $html_to_return .= '</div></div>';
    echo json_encode($html_to_return);

    die();

}

?>
