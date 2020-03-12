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

class YasrDatabaseRatings {

    /**
     * @param $post_id
     *
     * @return mixed|null
     */
    public function getOverallRating ($post_id=false) {
        //if values it's not passed get the post id, since version 1.6.9 this is just for yasr_add_schema function
        //and for a further check
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }
        if (!is_int($post_id)) {
            return null;
        }
        $post_id = (int)$post_id;

        $overall_rating = get_post_meta($post_id, 'yasr_overall_rating', true);
        if (!$overall_rating) {
            $overall_rating = 0;
        }
        return $overall_rating;
    }

    /**
     * @param bool|integer $post_id
     * @param bool $create_transient
     *
     * @return array|bool|mixed|object|null
     */
    public function getVisitorVotes ($post_id = false, $create_transient = true) {
        global $wpdb;

        //if values it's not passed get the post id, most of cases and default one
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        if (!is_int($post_id)) {
            return false;
        }

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
            if ($create_transient === true) {
                set_transient($transient_name, $result, WEEK_IN_SECONDS);
            }
            return $result;
        }

    }

    /**
     * Check if an user has already rated, and if so, return the rating, or false otherwise
     *
     * @param int | bool $post_id
     *
     * @return bool|string
     */
    public function visitorVotesHasUserVoted($post_id = false) {
        global $wpdb;

        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;

        //just to be safe
        if (!is_int($post_id)) {
            $post_id = get_the_ID();
        }

        if (!is_int($user_id)) {
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

}

class YasrMultiSetData {
    /**
     * @var array $array_to_return
     */
    protected $array_to_return = array();

    /**
     * This function returns an multidimensional array of multiset ID and Fields
     *    array (
     *        array (
     *            'id' => '0',
     *            'name' => 'Field1',
     *        ),
     *        array (
     *            'id' => '1',
     *            'name' => 'Field2',
     *        ),
     *    )
     *
     * @param int $set_id
     * @return array|bool|object|null
     */

    public function multisetFieldsAndID($set_id) {
        if (!is_int($set_id)) {
            return false;
        }

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

    /** This functions returns an array with all the value to print the multiset
     *
     * array (
     *     array (
     *         'value_id' => 0,
     *         'value_name' => 'Field 1',
     *         'value_rating' => 3.5,
     *     ),
     *     array (
     *         'value_id' => 1,
     *         'value_name' => 'Field 2',
     *         'value_rating' => 3,
     *     )
     *
     * @param integer $set_id the set id
     * @param array $set_fields an array with fields names and id
     * @param array $set_post_meta_values an array with fields id and rating, can be empty
     *
     * @return bool | array
     */

    public function returnArrayFieldsRatings($set_id, $set_fields, $set_post_meta_values) {

        if (!is_int($set_id) || !$set_fields) {
            return false;
        }

        //index
        $i = 0;
        //always returns field id and name
        foreach ($set_fields as $fields_ids_and_names) {
            $this->array_to_return[$i]['id']     = (int) $fields_ids_and_names['id'];
            $this->array_to_return[$i]['name']   = $fields_ids_and_names['name'];
            $this->array_to_return[$i]['average_rating'] = 0;

            //if there is post meta
            if ($set_post_meta_values) {
                //first, loop saved fields and ratings
                foreach ($set_post_meta_values as $saved_set_id) {
                    //if the saved set is the same selected
                    if ($saved_set_id['set_id'] === $set_id) {
                        //loop the saved arrays
                        foreach ($saved_set_id['fields_and_ratings'] as $single_value) {
                            //if field id is the same, add the rating
                            if ($this->array_to_return[$i]['id'] === $single_value->field) {
                                //save the rating
                                $this->array_to_return[$i]['average_rating'] = $single_value->rating;
                            }
                        }
                    }
                }
            }
            //this is for list the set names
            $i ++;
        }
        return $this->array_to_return;
    }

    /**
     * @param array $multiset_content
     *
     * This get the multiset content, and returns the average
     *
     * @return int|false|float
     */
    public function returnMultiSetAverage($multiset_content) {
        if (!is_array($multiset_content)) {
            return 0;
        }
        //default value
        $multiset_vote_sum = 0;
        $multiset_rows_number = 0;

        foreach ($multiset_content as $set_content) {
            $multiset_vote_sum = $multiset_vote_sum + $set_content['average_rating'];
            $multiset_rows_number = $multiset_rows_number+1;
        }
        $multiset_average = round($multiset_vote_sum/$multiset_rows_number, 1);

        return $multiset_average;
    }


    /**
     * @param $post_id
     * @param $set_type
     *
     * @return array|mixed|object|null
     */
    public function returnVisitorMultiSet($post_id, $set_type) {
        $post_id = (int)$post_id;
        $set_type = (int)($set_type);

        $result = get_transient('yasr_visitor_multi_set_' . $post_id . '_' . $set_type);

        if ($result !== false) {
            return $result;
        }

        global $wpdb;

        $result = $wpdb->get_results($wpdb->prepare("SELECT f.field_name AS name, 
                        f.field_id AS id, 
                        (SUM(l.vote)/COUNT(l.vote)) AS average_rating,
                        COUNT(l.vote) AS number_of_votes
                        FROM " . YASR_MULTI_SET_FIELDS_TABLE . " AS f LEFT JOIN " . YASR_LOG_MULTI_SET . " AS l
                        ON l.post_id = %d
                        AND f.field_id = l.field_id
                        WHERE f.parent_set_id=%d
                        GROUP BY f.field_name, f.field_id
                        ORDER BY f.field_id", $post_id, $set_type), ARRAY_A);

        if (!empty($result)) {
            set_transient('yasr_visitor_multi_set_' . $post_id . '_' . $set_type, $result, WEEK_IN_SECONDS);
        }

        return $result;

    }

}
