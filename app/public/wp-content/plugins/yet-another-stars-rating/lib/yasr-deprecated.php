<?php

/*
 * Get overall rating from yasr_votes table used in
 * yasr_add_filter_for_schema(), yasr_get_id_value_callback()
 * and yasr_rest_get_overall_rating
 *
 */

if (!defined('ABSPATH')) {
    exit('You\'re not allowed to see this page');
} // Exit if accessed directly


function yasr_get_overall_rating($post_id = false) {

    //if values it's not passed get the post id, since version 1.6.9 this is just for yasr_add_schema function
    //and for a further check
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id) {
        return null;
    }

    $post_id = (int) $post_id;

    $overall_rating = get_post_meta($post_id, 'yasr_overall_rating', true);

    return $overall_rating;

}

/****** Get visitor votes ******/
function yasr_get_visitor_votes($post_id = false, $create_transient = true) {

    global $wpdb;

    //if values it's not passed get the post id, most of cases and default one
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id) {
        return false;
    }

    $post_id = (int)$post_id;

    $transient_name = 'yasr_visitor_votes_' . $post_id;

    $transient_visitor_votes = get_transient($transient_name);

    if ($transient_visitor_votes) {
        return $transient_visitor_votes;
    } else {
        $result = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT SUM(vote) AS sum_votes, COUNT(vote) as number_of_votes FROM "
                . YASR_LOG_TABLE .
                "  WHERE post_id=%d",
                $post_id
            )
        );

        if ($create_transient == true) {
            set_transient($transient_name, $result, WEEK_IN_SECONDS);
        }

        return $result;

    }

}


/****** Get multi set values and field's name, used in ajax function and shortcode function ******/
function yasr_get_multi_set_fields($set_id) {

    global $wpdb;

    $result = $wpdb->get_results($wpdb->prepare(
        "SELECT f.field_id AS id, f.field_name AS name
                FROM " . YASR_MULTI_SET_FIELDS_TABLE . " AS f
                WHERE f.parent_set_id=%d
                ORDER BY f.field_id
                ", $set_id),
        ARRAY_A);

    if (empty($result)) {
        return false;
    }

    return $result;

}


/*** function that get the star size and return it***/
function yasr_stars_size($size) {

    //$size = sanitize_text_field($size);

    $stars_attribute = array();

    if ($size === 'small') {
        $stars_attribute['px_size'] = '16';
    } elseif ($size === 'medium') {
        $stars_attribute['px_size'] = '24';
    } //default values
    else {
        $stars_attribute['px_size'] = '32';
    }

    return $stars_attribute;

}

/*
 * Show visitor votes average, READ ONLY
 */
add_shortcode ('yasr_visitor_votes_readonly', 'yasr_visitor_votes_readonly_callback');

function yasr_visitor_votes_readonly_callback($atts) {

    $atts['readonly'] = true;

    //Here I call the same function that draw the same function for yasr_visitor_votes,
    //passing the attribute readonly = true
    $shortcode_html = shortcode_visitor_votes_callback($atts);

    return $shortcode_html;

} //End function shortcode_visitor_votes_only_stars_callback


/****** Check if a logged in user has already rated. Return user vote for a post if exists  ******/

function yasr_check_if_user_already_voted($post_id = false) {

    global $wpdb;

    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;

    //just to be safe
    if (!$post_id) {
        $post_id = get_the_ID();
    }

    if (!$post_id || !$user_id) {
        return false;
    }

    $rating = $wpdb->get_var(
        $wpdb->prepare(
            "SELECT vote FROM "
            . YASR_LOG_TABLE .
            " WHERE post_id=%d 
                    AND user_id=%d 
                    LIMIT 1 ",
            $post_id, $user_id
        )
    );

    if ($rating === null) {
        $rating = false;
    }

    return $rating;

}
