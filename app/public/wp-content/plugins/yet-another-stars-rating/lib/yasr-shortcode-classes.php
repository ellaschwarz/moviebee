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

/**
 * Class YasrShortcode
 *
 * @since 2.1.5
 *
 */
class YasrShortcode {
    public $shortcode_html;
    public $post_id; //false
    public $size; //large
    public $readonly; //false
    public $set_id; //1
    public $show_average; //null
    public $shorcode_name;

    public function __construct($atts, $shortcode_name) {
        $this->shorcode_name = $shortcode_name;

        if ($atts !== false) {
            $atts = shortcode_atts(
                array(
                    'size'         => 'large',
                    'postid'       => false,
                    'readonly'     => false,
                    'setid'        => 1,
                    'show_average' => null
                ),
                $atts,
                $shortcode_name
            );

            if ($atts['postid'] === false) {
                $this->post_id = get_the_ID();
            } else {
                $this->post_id = (int) $atts['postid'];
            }
            $this->size          = sanitize_text_field($atts['size']);
            $this->readonly      = sanitize_text_field($atts['readonly']);
            $this->set_id        = (int) $atts['setid'];
            $this->show_average  = sanitize_text_field($atts['show_average']);
        }
    }

    /**
     * Return the stars size according to size attribute in shortcode.
     * If not used, return 32 (default value)
     *
     * @return int
     */
    protected function starSize() {
        if ($this->shorcode_name === 'yasr_top_ten_highest_rated'
           || $this->shorcode_name === 'yasr_most_or_highest_rated_posts') {
            return 24;
        }

        $size = $this->size;
        $px_size = 32; //default value

        if ($size === 'small') {
            $px_size = 16;
        } elseif ($size === 'medium') {
            $px_size = 24;
        }
        return $px_size;
    }
}


/**
 * Class YasrOverallRating
 * Print Yasr Overall Rating
 */
class YasrOverallRating extends YasrShortcode {

    protected $html_stars;
    protected $overall_rating;

    /**
     * Print the visitor votes shortcode
     *
     * @return string|null
     */

    function printOverallRating () {

        //do not run in admin (problem with tinymce)
        if(is_admin()) {
            return false;
        }

        $stars_size = $this->starSize();
        $overall_rating_obj = new YasrDatabaseRatings();
        $this->overall_rating     = $overall_rating_obj->getOverallRating($this->post_id);

        $this->shortcode_html = '<!--Yasr Overall Rating Shortcode-->';

        //generate an unique id to be sure that every element has a different ID
        $unique_id              = str_shuffle(uniqid());
        $overall_rating_html_id = 'yasr-overall-rating-rater-' . $unique_id;

        $this->html_stars = "<div class='yasr-overall-rating'>
                                 <div class='yasr-rater-stars'
                                     id='$overall_rating_html_id'
                                     data-rating='$this->overall_rating'
                                     data-rater-starsize='$stars_size' >
                                 </div>
                             </div>";

        $this->customTextBefore();
        $this->shortcode_html .= '<!--End Yasr Overall Rating Shortcode-->';

        //If overall rating in loop is enabled don't use is_singular && is main_query
        if (YASR_SHOW_OVERALL_IN_LOOP === 'enabled') {
            return $this->shortcode_html;
        } //default
        else {
            if (is_singular() && is_main_query()) {
                return $this->shortcode_html;
            }
            return null;
        }
    }

    /**
     * If enabled in the settings, this function will show the custom text
     * before or after the stars in yasr_visitor_votes
     *
     * @param  void
     * @return void
     *
     */
    protected function customTextBefore() {
        if (YASR_TEXT_BEFORE_STARS == 1 && YASR_TEXT_BEFORE_OVERALL != '') {
            $text_before_star = str_replace('%overall_rating%', $this->overall_rating, YASR_TEXT_BEFORE_OVERALL);
            $this->shortcode_html   = "<div class='yasr-container-custom-text-and-overall'>
                                     <span id='yasr-custom-text-before-overall'>" . $text_before_star . "</span>
                                     $this->html_stars
                                 </div>";
        } else {
            $this->shortcode_html .= $this->html_stars;
        }
    }

}

/**
 * Class YasrVisitorVotes
 * Print Yasr Visitor Votes
 */
class YasrVisitorVotes extends YasrShortcode {

    protected  $votes = null;
    protected  $votes_number = 0;
    protected  $medium_rating = 0;
    protected  $cookie_value = false; //avoid undefined
    protected  $span_bottom_line;
    protected  $span_text_after_stars;


    /**
     * Print the visitor votes shortcode
     *
     * @return string|null
     */
    public function printVisitorVotes() {

        //do not run in admin (problem with tinymce)
        if(is_admin()) {
            return false;
        }

        $stars_size = $this->starSize();

        $unique_id = str_shuffle(uniqid());
        $htmlid = 'yasr-visitor-votes-rater-' . $unique_id ;
        $span_container_after_stars = "<span id='yasr-visitor-votes-container-after-stars-$unique_id'
                                             class='yasr-visitor-votes-after-stars-class'>";

        $visitor_votes_rating_obj = new YasrDatabaseRatings();
        $this->votes = $visitor_votes_rating_obj->getVisitorVotes($this->post_id); //always reference it

        foreach ($this->votes as $user_votes) {
            $this->votes_number = $user_votes->number_of_votes;
            if ($this->votes_number != 0) {
                $this->medium_rating = ($user_votes->sum_votes/$this->votes_number);
            } else {
                $this->medium_rating = 0;
            }
        }

        $this->medium_rating=round($this->medium_rating, 1);

        if (is_singular()) {
            $is_singular = 'true';
        } else {
            $is_singular = 'false';
        }

        $this->shortcode_html = '<!--Yasr Visitor Votes Shortcode-->';

        //if this come from yasr_visitor_votes_readonly...
        if ($this->readonly === true || $this->readonly === "yes") {
            $htmlid = 'yasr-visitor-votes-readonly-rater-'.$unique_id;

            $this->shortcode_html = "<div class=\"yasr-rater-stars-visitor-votes\" id=\"$htmlid\" data-rating=\"$this->medium_rating\"
            data-rater-starsize=\"$stars_size\" data-rater-postid=\"$this->post_id\" 
            data-rater-readonly=\"true\"></div>";

            return $this->shortcode_html;
        }

        $ajax_nonce_visitor = wp_create_nonce("yasr_nonce_insert_visitor_rating");

        $this->shortcode_html .= "<div id='yasr_visitor_votes_$this->post_id' class='yasr-visitor-votes'>";

        $this->checkCookie();
        $this->allowedUser();
        $this->customTextBeforeAfter();

        $this->shortcode_html  .= "<div id='$htmlid'
                                        class='yasr-rater-stars-visitor-votes'
                                        data-rater-postid='$this->post_id' 
                                        data-rating='$this->medium_rating'
                                        data-rater-starsize='$stars_size' 
                                        data-rater-readonly='$this->readonly'
                                        data-rater-nonce='$ajax_nonce_visitor' 
                                        data-issingular='$is_singular'
                                  ></div>";
        $this->shortcode_html .= $span_container_after_stars;
        $this->shortcode_html .= $this->visitorStats();
        $this->shortcode_html .= $this->span_text_after_stars;
        $this->shortcode_html .= $this->span_bottom_line;
        $this->shortcode_html .= '</span>'; //Close yasr-visitor-votes-after-stars and yasr_visitor_votes
        $this->shortcode_html .= '</div>'; //close all
        $this->shortcode_html .= '<!--End Yasr Visitor Votes Shortcode-->';

        //If visitor_votes in loop is enabled don't use is_singular && is main_query
        if (YASR_SHOW_VISITOR_VOTES_IN_LOOP === 'enabled') {
            return $this->shortcode_html;
        }

        //default value
        else {
            if (is_singular() && is_main_query()) {
                return $this->shortcode_html;
            }
            return null;
        }
    } //end function

    /**
     * Function that checks if cookie exists and set the value
     *
     * @param void
     * @return void
     */
    protected function checkCookie () {
        //name of cookie to check
        $yasr_cookiename = 'yasr_visitor_vote_cookie';

        if (isset($_COOKIE[$yasr_cookiename])) {
            $cookie_data = stripslashes($_COOKIE[$yasr_cookiename]);

            //By default, json_decode return an object, true to return an array
            $cookie_data = json_decode($cookie_data, true);

            if (is_array($cookie_data)) {
                foreach ($cookie_data as $value) {
                    $cookie_post_id = (int)$value['post_id'];
                    if ($cookie_post_id === $this->post_id) {
                        $this->cookie_value = (int)$value['rating'];
                        //Stop doing foreach, here we've found the rating for current post
                        break;
                    }
                }
            }
            if ($this->cookie_value !== false && $this->cookie_value > 5) {
                $this->cookie_value = 5;
            } elseif ($this->cookie_value !== false && $this->cookie_value < 1) {
                $this->cookie_value = 1;
            }
        }
    }

    /**
     * This function checks who can rate to the shortcode
     *
     * @param void
     * @return void
     */
    protected function allowedUser () {
        $visitor_votes = new YasrDatabaseRatings();

        //I've to check a logged in user that has already rated
        if (is_user_logged_in()) {
            $this->readonly = 'false'; //Always false if user is logged in

            //Check if a logged in user has already rated for this post
            $vote_if_user_already_rated = $visitor_votes->visitorVotesHasUserVoted($this->post_id);

            //If user has already rated
            if ($vote_if_user_already_rated) {
                $this->span_bottom_line="<span class='yasr-small-block-bold yasr-already-voted-text' 
                                               id='yasr-user-vote-$this->post_id'
                                               data-yasr-already-voted='$vote_if_user_already_rated'>"
                                                   .__("You've already voted this article with", 'yet-another-stars-rating') .
                                               " $vote_if_user_already_rated
                                         </span>";
            }
        } //End if user is logged

        //if anonymous are allowed to vote
        if (YASR_ALLOWED_USER === 'allow_anonymous') {
            //IF user is not logged in
            if (!is_user_logged_in()) {
                //if cookie exists
                if ($this->cookie_value) {
                    $this->readonly = 'true';
                    $this->span_bottom_line = "<span class='yasr-small-block-bold yasr-already-voted-text'>";
                    if (YASR_TEXT_BEFORE_STARS == 1 && YASR_CUSTOM_TEXT_USER_VOTED != '') {
                        $this->span_bottom_line .= YASR_CUSTOM_TEXT_USER_VOTED;
                    } else {
                        $this->span_bottom_line .= __('You\'ve already voted this article with', 'yet-another-stars-rating') . $this->cookie_value;
                    }
                    $this->span_bottom_line .= '</span>';
                } else {
                    $this->readonly = 'false';
                }
            }
        } //end if  YASR_ALLOWED_USER === 'allow_anonymous' {

        //If only logged in users can vote
        elseif (YASR_ALLOWED_USER === 'logged_only') {
            //IF user is not logged in
            if (!is_user_logged_in()) {
                $this->readonly = 'true'; //readonly is true if user isn't logged

                $this->span_bottom_line = "<span class=\"yasr-visitor-votes-must-sign-in\">";

                //if custom text is defined
                if (defined('YASR_CUSTOM_TEXT_MUST_SIGN_IN') && YASR_CUSTOM_TEXT_MUST_SIGN_IN !== '') {
                    $this->span_bottom_line .= YASR_CUSTOM_TEXT_MUST_SIGN_IN;
                } else {
                    $this->span_bottom_line .= __('You must sign in to vote', 'yet-another-stars-rating');
                }
                $this->span_bottom_line .= "</span>";
            }

        }
    }

    /**
     * If enabled in the settings, this function will show the custom text
     * before or after the stars in yasr_visitor_votes
     *
     * @param  void
     * @return void
     */
    protected function customTextBeforeAfter () {

        $this->span_text_after_stars = "<span class='yasr-total-average-container'
                                              id='yasr-total-average-text_$this->post_id'>";

        if (YASR_TEXT_BEFORE_STARS == 1 && YASR_TEXT_BEFORE_VISITOR_RATING != '') {
            $text_before_star       = str_replace('%total_count%', $this->votes_number, YASR_TEXT_BEFORE_VISITOR_RATING);
            $text_before_star       = str_replace('%average%', $this->medium_rating, $text_before_star);
            $this->shortcode_html   .= "<div class='yasr-container-custom-text-and-visitor-rating'>
                                            <span id='yasr-custom-text-before-visitor-rating'>"
                                            . $text_before_star .
                                            "</span></div>";
        }

        if (YASR_TEXT_BEFORE_STARS == 1 && YASR_TEXT_AFTER_VISITOR_RATING != '') {
            $text_after_star = str_replace('%total_count%', $this->votes_number, YASR_TEXT_AFTER_VISITOR_RATING);
            $text_after_star = str_replace('%average%', $this->medium_rating, $text_after_star);
            $this->span_text_after_stars .= $text_after_star;
        } else {
            $this->span_text_after_stars .= '['
                                            . __('Total:', 'yet-another-stars-rating')
                                            . '&nbsp;' . $this->votes_number  . '&nbsp; &nbsp;'
                                            . __('Average:', 'yet-another-stars-rating')
                                            . '&nbsp;' . $this->medium_rating
                                            . '/5]';
        }

        $this->span_text_after_stars .= '</span>';
    }

    /**
     * This function will return the html code for the dashicons
     *
     * @param void
     *
     * @return string
     */
    protected function visitorStats () {
        if (YASR_VISITORS_STATS === 'yes') {
            global $yasr_plugin_imported;

            //default
            $span_dashicon = "<span class='dashicons dashicons-chart-bar yasr-dashicons-visitor-stats'
            data-postid='$this->post_id' id='yasr-total-average-dashicon-$this->post_id'></span>";

            if (is_array($yasr_plugin_imported)) {
                $plugin_import_date = null; //avoid undefined
                if (array_key_exists('wppr', $yasr_plugin_imported)) {
                    $plugin_import_date = $yasr_plugin_imported['wppr']['date'];
                }

                if (array_key_exists('kksr', $yasr_plugin_imported)) {
                    $plugin_import_date = $yasr_plugin_imported['kksr']['date'];
                }

                if (array_key_exists('mr', $yasr_plugin_imported)) {
                    $plugin_import_date = $yasr_plugin_imported['mr']['date'];
                }

                //remove hour from date
                $plugin_import_date=strtok($plugin_import_date,' ');

                $post_date = get_the_date('Y-m-d', $this->post_id);

                //if one of these plugin has been imported and post is older then import,  hide stats
                if ($post_date < $plugin_import_date) {
                    $span_dashicon = "";
                }
            } //End if $yasr_plugin_imported
        } else {
            //Yasr_visitor_stats are disabled
            $span_dashicon = "";
        }
        return $span_dashicon;
    }
}


/**
 * Class YasrMultiSet
 */
class YasrMultiSet extends YasrShortcode {
    /**
     * @return string | bool
     */
    public function printMultiset () {

        //do not run in admin (problem with tinymce)
        if(is_admin()) {
            return false;
        }

        $this->shortcode_html = '<!-- Yasr Multi Set Shortcode-->';

        $multiset_obj  = new YasrMultiSetData();

        //set fields name and ids
        $set_fields = $multiset_obj->multisetFieldsAndID($this->set_id);

        //If there is no set for that id, return
        if (!$set_fields) {
            $string = __('No Set Found with this ID', 'yet-another-stars-rating');
            return $this->shortcode_html . $string;
        }

        //get meta values
        $set_post_meta_values = get_post_meta($this->post_id, 'yasr_multiset_author_votes', true);

        $multiset_content = $multiset_obj->returnArrayFieldsRatings($this->set_id, $set_fields, $set_post_meta_values);

        $this->shortcode_html = '<!-- Yasr Visitor Multi Set Shortcode-->';
        $this->shortcode_html .= '<table class="yasr_table_multi_set_shortcode">';
        $this->star_readonly   = 'true';

        $this->printMultisetRows($multiset_content);

        $this->shortcode_html .= "</table>";
        $this->shortcode_html .= '<!--End Yasr Multi Set Shortcode-->';

        return $this->shortcode_html;
    }


    protected function printMultisetRows($multiset_content, $visitor_multiset=false) {

        $multiset_string  = 'yasr-average-multiset-';
        if ($visitor_multiset === true) {
            $multiset_string = 'yasr-visitor-multi-set-average-';
        }

        foreach ($multiset_content as $set_content) {
            $unique_id_identifier = 'yasr-multiset-' . str_shuffle(uniqid());

            $average_rating = round($set_content['average_rating'], 1);

            $html_stars = "<div class='yasr-multiset-visitors-rater'
                                id='$unique_id_identifier' 
                                data-rater-postid='$this->post_id'
                                data-rater-setid='$this->set_id'
                                data-rater-set-field-id='$set_content[id]' 
                                data-rating='$average_rating'
                                data-rater-readonly='$this->star_readonly'>
                            </div>";

            $span_container_number_of_votes = '';
            if ($visitor_multiset === true) {
                $span_container_number_of_votes = '<span class="yasr-visitor-multiset-vote-count">'
                                                    . $set_content['number_of_votes'] .
                                                  '</span>';
            }

            $this->shortcode_html .='<tr>
                                         <td>
                                             <span class="yasr-multi-set-name-field">' . $set_content['name'] . '</span>
                                         </td>
                                         <td>'
                                              . $html_stars . $span_container_number_of_votes .
                                         '</td>
                                     </tr>';

        } //End foreach

        $multiset_obj = new YasrMultiSetData();
        //get the average of the multiset
        $multiset_average = $multiset_obj->returnMultiSetAverage($multiset_content);

        //print it
        $this->shortcode_html .= $this->printAverageRowMultiSet($this->show_average, $multiset_average, $multiset_string);

    }

    /**
     * @since 2.1.0
     *
     * @param $show_average
     * @param $multiset_average
     * @param $multiset_string
     *
     * @return string
     */
    protected function printAverageRowMultiSet($show_average, $multiset_average, $multiset_string) {
        $average_txt = __("Average", "yet-another-stars-rating");
        $html_average = null;

        //Show average row
        if ($show_average === '' && YASR_MULTI_SHOW_AVERAGE !== 'no' || $show_average !== '' && $show_average !== 'no') {
            $unique_id_identifier = $multiset_string . str_shuffle(uniqid());

            $html_average = "<tr>
                                <td colspan='2' class='yasr-multiset-average'>
                                    <div class='yasr-multiset-average'>
                                        <span class='yasr-multiset-average-text'>$average_txt</span>
                                        <div class='yasr-rater-stars' id='$unique_id_identifier'
                                        data-rating='$multiset_average' data-rater-readonly='true'
                                        data-rater-starsize='24'></div>
                                    </div>
                                </td>
                            </tr>";
        }

        return $html_average;
    }

}

/**
 * Class YasrVisitorMultiSet
 */
class YasrVisitorMultiSet extends YasrMultiSet {

    protected $loader_html;
    protected $button_html;
    protected $button_html_disabled;
    protected $button;
    protected $star_readonly;
    protected $span_message_content;


    /**
     * Print Yasr Visitor MultiSet
     *
     * @param void
     * @return string
     */
    public function printVisitorMultiSet () {

        //do not run in admin (problem with tinymce)
        if(is_admin()) {
            return false;
        }

        $multiset_obj  = new YasrMultiSetData();
        $ajax_nonce_visitor_multiset = wp_create_nonce("yasr_nonce_insert_visitor_rating_multiset");

        $this->shortcode_html = '<!-- Yasr Visitor Multi Set Shortcode-->';

        $image = YASR_IMG_DIR . "/loader.gif";
        $this->loader_html = "<span class='yasr-loader-multiset-visitor' 
                                  id='yasr-loader-multiset-visitor-$this->post_id-$this->set_id'>
                                  &nbsp;<img src='$image' title='yasr-loader' alt='yasr-loader'>
                              </span>";

        $this->button_html = "<input type='submit'
                                  name='submit'
                                  id='yasr-send-visitor-multiset-$this->post_id-$this->set_id'
                                  class='button button-primary yasr-send-visitor-multiset'
                                  data-postid='$this->post_id'
                                  data-setid='$this->set_id'
                                  value='" . __('Submit!', 'yet-another-stars-rating') . "' 
                              />";

        $this->button_html_disabled = "<input type='submit'
                                           disabled='disabled'
                                           class='button button-primary' 
                                           id='yasr-send-visitor-multiset-disabled'
                                           disabled='disabled' 
                                           value='" . __('Submit!', 'yet-another-stars-rating') . "'
                                        />";

        //check cookie and assign default values
        $this->multisetAttributes();

        $set_name_content = $multiset_obj->returnVisitorMultiSet($this->post_id, $this->set_id);

        if (!$set_name_content) {
            $this->shortcode_html .= __('No MultiSet found with this ID', 'yet-another-stars-rating');
            return $this->shortcode_html;
        }

        $this->shortcode_html .= "<table class='yasr_table_multi_set_shortcode'>";

        $this->printMultisetRows($set_name_content, true);

        //Submit row and button
        $this->shortcode_html .="<tr>
                                    <td colspan='2'>
                                        $this->button
                                        $this->loader_html
                                        <span class='yasr-visitor-multiset-message'>$this->span_message_content</span>
                                    </td>
                                </tr>
                                ";

        $this->shortcode_html .= "</table>";
        $this->shortcode_html .= '<!-- End Yasr Multi Set Visitor Shortcode-->';

        wp_localize_script(
            'yasrfront',
            "yasrMultiSetData",
            array(
                'nonceVisitor' => $ajax_nonce_visitor_multiset,
                'setType'      => $this->set_id
            )
        );

        return $this->shortcode_html;
    }

    /**
     * This function first check if a cookie is set,
     * Then who can rate and set attributes to:
     * $this->button
     * $this->star_readonly
     * $this->span_message_content
     *
     * @param void
     * @return void
     *
     */
    protected function multisetAttributes() {
        $yasr_cookiename = 'yasr_multi_visitor_cookie';

        //Check cookie and if voting is allowed only to logged in users
        if (isset($_COOKIE[$yasr_cookiename])) {
            $cookie_data = stripslashes($_COOKIE[ $yasr_cookiename ]);

            //By default, json_decode return an object, true to return an array
            $cookie_data = json_decode($cookie_data, true);

            if (is_array($cookie_data)) {
                foreach ($cookie_data as $value) {
                    $cookie_post_id = (int)$value['post_id'];
                    $cookie_set_id = (int)$value['set_id'];

                    if ($cookie_post_id === $this->post_id && $cookie_set_id === $this->set_id) {
                        $this->button = "";
                        $this->star_readonly = 'true';
                        $this->span_message_content = __('Thank you for voting!', 'yet-another-stars-rating');

                        //Stop doing foreach, here we've found the rating for current post
                        break;
                    } else {
                        $this->button = $this->button_html;
                        $this->star_readonly = 'false';
                        $this->span_message_content = "";
                    }
                }
            }
        } else {
            //If user is not logged in
            if (!is_user_logged_in()) {
                if (YASR_ALLOWED_USER === 'allow_anonymous') {
                    $this->button = $this->button_html;
                    $this->star_readonly = 'false';
                    $this->span_message_content = "";
                } elseif (YASR_ALLOWED_USER === 'logged_only') {
                    $this->button = $this->button_html_disabled;
                    $this->star_readonly = 'true';
                    $this->span_message_content = '<span class="yasr-visitor-votes-must-sign-in">';

                    if (defined('YASR_CUSTOM_TEXT_MUST_SIGN_IN') && YASR_CUSTOM_TEXT_MUST_SIGN_IN !== '') {
                        $this->span_message_content .= YASR_CUSTOM_TEXT_MUST_SIGN_IN;
                    } else {
                        $this->span_message_content .= __('You must sign in to vote', 'yet-another-stars-rating');
                    }
                    $this->span_message_content .= '</span>';
                }
            } //End if user logged in

            //User is logged in
            else {
                $this->button = $this->button_html;
                $this->star_readonly = 'false';
                $this->span_message_content = "";
            }
        }
    }
}

/**
 * Class YasrRankings
 */
class YasrRankings extends YasrShortcode {

    protected $query_highest_rated_overall;
    protected $query_result_most_rated_visitor;
    protected $query_result_highest_rated_visitor;
    protected $vv_highest_rated_table;
    protected $vv_most_rated_table;

    /*
     *
     * */
    public function returnHighestRatedOverall () {
        $this->shortcode_html = '<!-- Yasr Most Or Highest Rated Shortcode-->';

        global $wpdb;

        $this->query_highest_rated_overall = $wpdb->get_results("
            SELECT pm.meta_value AS overall_rating, 
                pm.post_id AS post_id
            FROM $wpdb->postmeta AS pm, 
                 $wpdb->posts AS p
            WHERE  pm.post_id = p.ID
                AND p.post_status = 'publish'
                AND pm.meta_key = 'yasr_overall_rating'
            ORDER BY pm.meta_value DESC, 
                     pm.post_id 
            LIMIT 10"
        );

        $this->loopHighestRatedOverall();

        $this->shortcode_html .= '<!--End Yasr Top 10 highest Rated Shortcode-->';
        return $this->shortcode_html;

    }

    protected function loopHighestRatedOverall($text_position=false, $text=false) {
        if ($this->query_highest_rated_overall) {
            $this->shortcode_html .= "<table class='yasr-table-chart'>";

            foreach ($this->query_highest_rated_overall as $result) {
                $post_title = wp_strip_all_tags(get_the_title($result->post_id));
                $link       = get_permalink($result->post_id); //Get permalink from post id
                $yasr_top_ten_html_id = 'yasr-highest_rated-' . str_shuffle(uniqid());

                $this->returnTableRows($result->post_id,
                    $result->overall_rating,
                    null,
                    $post_title,
                    $link, $yasr_top_ten_html_id);


            } //End foreach
            $this->shortcode_html .= "</table>";
        }
        else {
            _e("You don't have any votes stored", 'yet-another-stars-rating');
        }

    }

    /**
     * Create the queries for the rankings
     *
     * Return the full html for the shortcode
     *
     * @return string $this->shortcode_html;
     */
    public function vvReturnMostHighestRatedPost() {
        $this->shortcode_html = '<!-- Yasr Most Or Highest Rated Shortcode-->';

        global $wpdb;

        $this->query_result_most_rated_visitor = $wpdb->get_results(
            "SELECT post_id, 
                        COUNT(post_id) AS number_of_votes,
                        SUM(vote) AS sum_votes
                    FROM " . YASR_LOG_TABLE . ",
                        $wpdb->posts AS p
                    WHERE post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY post_id
                        HAVING number_of_votes > 1
                    ORDER BY number_of_votes DESC, 
                        post_id DESC
                    LIMIT 10"
            );

        //count run twice but access data only once: tested with query monitor and asked
        //here http://stackoverflow.com/questions/39201235/does-count-run-twice/39201492
        $this->query_result_highest_rated_visitor = $wpdb->get_results(
            "SELECT post_id, 
                        COUNT(post_id) AS number_of_votes, 
                        (SUM(vote) / COUNT(post_id)) AS result
                    FROM " . YASR_LOG_TABLE . " , 
                        $wpdb->posts AS p
                    WHERE post_id = p.ID
                        AND p.post_status = 'publish'
                    GROUP BY post_id
                        HAVING COUNT(post_id) >= 2
                    ORDER BY result DESC, 
                        number_of_votes DESC
                    LIMIT 10"
            );

        $this->vv_highest_rated_table = "<table class='yasr-table-chart' id='yasr-highest-rated-posts'>
                                              <tr class='yasr-visitor-votes-title'>
                                              <th>" . __('Post / Page', 'yet-another-stars-rating') . "</th>
                                              <th>" . __('Order By', 'yet-another-stars-rating') . ":&nbsp;&nbsp; 
                                                  <span id='link-yasr-most-rated-posts'>
                                                  <a href='' onclick='yasrShowMost(); return false'>"
                                        . __("Most Rated", 'yet-another-stars-rating') .
                                        "</a> | 
                                                  <span id='yasr_multi_chart_link_to_nothing'>"
                                        . __("Highest Rated", 'yet-another-stars-rating') .
                                        "</span>
                                              </th>
                                          </tr>";

        $this->vv_most_rated_table = "<table class='yasr-table-chart' id='yasr-most-rated-posts'>
                                              <tr class='yasr-visitor-votes-title'>
                                                  <th>" . __('Post / Page', 'yet-another-stars-rating') . " </th>
                                                  <th>" . __('Order By', 'yet-another-stars-rating') . ":&nbsp;&nbsp;
                                                      <span id='yasr_multi_chart_link_to_nothing'>"
                                     . __('Most Rated', 'yet-another-stars-rating') .
                                     "</span> | 
                                                      <span id='link-yasr-highest-rated-posts'>
                                                          <a href='' onclick='yasrShowHighest(); return false'>"
                                     . __('Highest Rated', 'yet-another-stars-rating') .
                                     "</a>
                                                      </span>
                                                  </th>
                                              </tr>";

        $this->vvMostRated();
        $this->vvHighestRated();

        $this->shortcode_html .= '<!-- End Yasr Most Or Highest Rated Shortcode-->';

        wp_localize_script( 'yasrfront', "yasrMostHighestRanking", array(
                'enable' => 'yes'
            )
        );

        return $this->shortcode_html;

    }

    /**
     * Loop the query for the Most Rated chart
     */
    protected function vvMostRated() {
        if ($this->query_result_most_rated_visitor) {

            $this->shortcode_html .= $this->vv_most_rated_table;

            foreach ($this->query_result_most_rated_visitor as $result) {
                $rating = round($result->sum_votes / $result->number_of_votes, 1);
                $post_title = wp_strip_all_tags(get_the_title($result->post_id));
                $link = get_permalink($result->post_id); //Get permalink from post id
                $yasr_top_ten_html_id = 'yasr-10-most-rated-' . str_shuffle(uniqid());

                //print the rows
                $this->returnTableRows($result->post_id,
                    $rating,
                    $result->number_of_votes,
                    $post_title,
                    $link,
                    $yasr_top_ten_html_id
                );

            } //End foreach
            $this->shortcode_html .= "</table>" ;

        } //End if $query_result_most_rated)

        else {
            $this->shortcode_html = __("You've not enough data",'yet-another-stars-rating') . "<br />";
        }
    }

    /**
     * Loop the query for the Highest Rated chart
     */
    protected function vvHighestRated () {
        if ($this->query_result_highest_rated_visitor) {

            $this->shortcode_html .= $this->vv_highest_rated_table;

            foreach ($this->query_result_highest_rated_visitor as $result) {
                $rating = round($result->result, 1);
                $post_title = wp_strip_all_tags(get_the_title($result->post_id));
                $link = get_permalink($result->post_id); //Get permalink from post id
                $yasr_top_ten_html_id = 'yasr-10-highest-rater-' . str_shuffle(uniqid());

                //print the rows
                $this->returnTableRows($result->post_id,
                    $rating,
                    $result->number_of_votes,
                    $post_title,
                    $link,
                    $yasr_top_ten_html_id
                );

            } //End foreach

            $this->shortcode_html .= "</table>";

        } //end if $query_result

        else {
            $this->shortcode_html = __("You've not enought data",'yet-another-stars-rating') . "<br />";
        }
    }

    /**
     * @param $post_id
     * @param $rating
     * @param $number_of_votes
     * @param $post_title
     * @param $link
     * @param $yasr_top_ten_html_id
     */
    protected function returnTableRows ($post_id, $rating, $number_of_votes, $post_title, $link, $yasr_top_ten_html_id) {
        $star_size = $this->starSize();

        $html_stars = "<div 
                           class='yasr-rater-stars'
                           id='$yasr_top_ten_html_id'
                           data-rater-postid='$post_id'
                           data-rater-starsize=$star_size
                           data-rating='$rating'>
                       </div>";

        //if number of votes === null means that the caller is loopHighestRatedOverall
        if ($number_of_votes === null) {

            $div_html_stars=apply_filters('yasr_filter_highest_rated_stars', $html_stars, $rating);

            if ($div_html_stars === $html_stars) {
                $div_html_stars .= "<span class='yasr-highest-rated-text'>"
                                       . __('Rating:', 'yet-another-stars-rating') . " $rating
                                    </span>";
            }

            $this->shortcode_html .= "<tr>
                                          <td class='yasr-top-10-overall-left'>
                                              <a href='$link'>$post_title</a>
                                          </td>
                                          <td class='yasr-top-10-overall-right'>
                                              $div_html_stars
                                          </td>
                                      </tr>";

        }

        //otherwise is vvMostRated or vvHighestRated
        else {
            $this->shortcode_html .= "<tr>
                                          <td class='yasr-top-10-most-highest-left'>
                                              <a href='$link'>$post_title</a>
                                          </td>
                                          <td class='yasr-top-10-most-highest-right'>
                                              $html_stars
                                              <br /> 
                                              ["
                                              . __('Total:', 'yet-another-stars-rating') .
                                              "$number_of_votes &nbsp;&nbsp;&nbsp;" .
                                               __('Average', 'yet-another-stars-rating') .
                                              " $rating]
                                          </td>
                                       </tr>";

        }
    } //end function returnTableRows

}