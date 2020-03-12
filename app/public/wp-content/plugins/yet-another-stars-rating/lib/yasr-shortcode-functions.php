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

require YASR_ABSOLUTE_PATH . '/lib/yasr-shortcode-classes.php';

/****** Add shortcode for overall rating ******/
add_shortcode('yasr_overall_rating', 'shortcode_overall_rating_callback');

function shortcode_overall_rating_callback ($atts) {

    if (YASR_SHOW_OVERALL_IN_LOOP === 'disabled' && !is_singular() && is_main_query()) {
        return null;
    }

    $shortcode_name = 'yasr_overall_rating';
    $overall_rating = new YasrOverallRating($atts, $shortcode_name);

    return $overall_rating->printOverallRating();

} //end function


/****** Add shortcode for user vote ******/

add_shortcode('yasr_visitor_votes', 'shortcode_visitor_votes_callback');

function shortcode_visitor_votes_callback($atts) {

    if (YASR_SHOW_VISITOR_VOTES_IN_LOOP === 'disabled' && !is_singular() && is_main_query()) {
        return null;
    }

    $shortcode_name = 'yasr_visitor_votes';
    $visitor_votes = new YasrVisitorVotes($atts, $shortcode_name);

    return $visitor_votes->printVisitorVotes();

} //End function shortcode_visitor_votes_callback

/****** Add shortcode for multiple set ******/

add_shortcode ('yasr_multiset',  'yasr_multiset_callback');

function yasr_multiset_callback($atts) {
    $shortcode_name = 'yasr_multiset';
    $multiset = new YasrMultiSet($atts, $shortcode_name);

    return $multiset->printMultiset();
}

/****** Add shortcode for multiset writable by users  ******/

add_shortcode ('yasr_visitor_multiset', 'yasr_visitor_multiset_callback');

function yasr_visitor_multiset_callback($atts) {
    $shortcode_name = 'yasr_visitor_multiset';
    $multiset = new YasrVisitorMultiSet($atts, $shortcode_name);

    return $multiset->printVisitorMultiSet();
}


/****** Add top 10 highest rated post *****/

add_shortcode ('yasr_top_ten_highest_rated', 'yasr_top_ten_highest_rated_callback');

function yasr_top_ten_highest_rated_callback() {
    $top_ten_highest_obj = new YasrRankings(false, 'yasr_top_ten_highest_rated');

    return $top_ten_highest_obj->returnHighestRatedOverall();
} //End function


/****** Add top 10 most rated / highest rated post *****/

add_shortcode ('yasr_most_or_highest_rated_posts', 'yasr_most_or_highest_rated_posts_callback');

function yasr_most_or_highest_rated_posts_callback () {
    $most_highest_obj = new YasrRankings(false, 'yasr_most_or_highest_rated_posts');

    return $most_highest_obj->vvReturnMostHighestRatedPost();
} //End function


/****** Add top 5 most active reviewer ******/

add_shortcode ('yasr_top_5_reviewers', 'yasr_top_5_reviewers_callback');

function yasr_top_5_reviewers_callback () {

    global $wpdb;

    $query_result = $wpdb->get_results("SELECT COUNT( pm.post_id ) AS total_count, p.post_author AS reviewer
                                        FROM $wpdb->posts AS p, $wpdb->postmeta AS pm
                                        WHERE pm.post_id = p.ID
                                        AND pm.meta_key = 'yasr_overall_rating'
                                        AND p.post_status = 'publish'
                                        GROUP BY reviewer
                                        ORDER BY (total_count) DESC
                                        LIMIT 5");


    if ($query_result) {

        $shortcode_html = '
        <!-- Yasr Top 5 Reviewers Shortcode-->
        ';

        $shortcode_html .= "
        <table class=\"yasr-table-chart\">
        <tr>
         <th>Author</th>
         <th>Reviews</th>
        </tr>
        ";

        foreach ($query_result as $result) {

            $user_data = get_userdata($result->reviewer);

            if ($user_data) {
                $user_profile = get_author_posts_url($result->reviewer);
            }

            else {
                $user_profile = '#';
                $user_data = new stdClass;
                $user_data->user_login = 'Anonymous';
            }


            $shortcode_html .= "<tr>
                                    <td><a href=\"$user_profile\">$user_data->user_login</a></td>
                                    <td>$result->total_count</td>
                                </tr>";

        }

        $shortcode_html .= "</table>";

        $shortcode_html .= '
        <!-- End Yasr Top 5 Reviewers Shortcode-->
        ';

        return $shortcode_html;

    }

    else {
        _e("Problem while retrieving the top 5 most active reviewers. Did you publish any review?");
    }

} //End top 5 reviewers function


/****** Add top 10 most active user *****/

add_shortcode ('yasr_top_ten_active_users', 'yasr_top_ten_active_users_callback');

function yasr_top_ten_active_users_callback () {

    global $wpdb;

    $query_result = $wpdb->get_results("SELECT COUNT( user_id ) as total_count, user_id as user
                                        FROM " . YASR_LOG_TABLE . ", $wpdb->posts AS p
                                        WHERE  post_id = p.ID
                                        AND p.post_status = 'publish'
                                        GROUP BY user_id
                                        ORDER BY ( total_count ) DESC
                                        LIMIT 10");

    if ($query_result) {

        $shortcode_html = '<!-- Yasr Top 10 Active Users Shortcode-->';

        $shortcode_html .= "
        <table class=\"yasr-table-chart\">
        <tr>
         <th>UserName</th>
         <th>Number of votes</th>
        </tr>
        ";

        foreach ($query_result as $result) {
            $user_data = get_userdata($result->user);

            if ($user_data) {
                $user_profile = get_author_posts_url($result->user);
            } else {
                $user_profile = '#';
                $user_data = new stdClass;
                $user_data->user_login = 'Anonymous';
            }

            $shortcode_html .= "<tr>
                                    <td><a href=\"$user_profile\">$user_data->user_login</a></td>
                                    <td>$result->total_count</td>
                                </tr>";

        }


        $shortcode_html .= "</table>";
        $shortcode_html .= '<!--End Yasr Top 10 Active Users Shortcode-->';

        return $shortcode_html;

    }

    else {
        _e("Problem while retrieving the top 10 active users chart. Are you sure you have votes to show?");
    }

} //End function

?>
