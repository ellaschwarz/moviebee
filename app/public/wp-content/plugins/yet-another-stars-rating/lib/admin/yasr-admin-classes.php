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
 * Class YasrLogDashboardWidget
 *
 * Class to print the Dashboard widgets
 *
 */
class YasrLogDashboardWidget {
    private $limit = 8;
    private $offset = 0;
    private $page_num;
    private $num_of_pages;
    private $n_rows;
    private $log_query;
    private $log_result;
    private $is_ajax = false;
    private $html_to_return;
    private $button_class;
    private $span_loader_id;
    private $user_widget = false;
    private $container_id;
    private $span_total_pages;

    public function __construct($widget_user) {
        //If $_POST isset it's in ajax response
        if (isset($_POST['pagenum'])) {
            $this->page_num     = (int)$_POST['pagenum'];
            $this->num_of_pages = (int)$_POST['totalpages'];
            $this->offset = (int)($this->page_num - 1) * $this->limit;
            $this->is_ajax = true;

            if ($widget_user === 'admin') {
                $this->adminWidget();
            }
            if ($widget_user === 'user') {
                $this->userWidget();
            }
        } else {
            $this->page_num = 1;
        }
    }

    /**
     * This function will set the values for print the admin widget logs
     *
     * $this->user_widget
     * $this->n_rows
     * $this->log_query
     * $this->container_id
     * $this->span_total_pages
     * $this->button_class
     * $this->span_loader_id
     *
     */
    public function adminWidget() {
        global $wpdb;

        //query for admin widget
        $this->n_rows = $wpdb->get_var(
            "SELECT COUNT(*) FROM "
            . YASR_LOG_TABLE
        );

        $this->log_query = "SELECT * FROM "
                           . YASR_LOG_TABLE .
                           " ORDER BY date DESC LIMIT %d, %d ";

        $this->container_id     = 'yasr-log-container';
        $this->span_total_pages = 'yasr-log-total-pages';
        $this->button_class     = 'yasr-log-pagenum';
        $this->span_loader_id   = 'yasr-loader-log-metabox';

        $this->returnWidget();
    }

    /**
     * This function will set the values for print the user widget logs
     *
     * $this->user_widget
     * $this->n_rows
     * $this->log_query
     * $this->container_id
     * $this->span_total_pages
     * $this->button_class
     * $this->span_loader_id
     *
     */
    public function userWidget() {
        $user_id = get_current_user_id();

        //set true to user widget
        $this->user_widget = true;

        global $wpdb;

        $this->n_rows = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM "
                . YASR_LOG_TABLE . " WHERE user_id = %d ",
                $user_id));

        $this->log_query = "SELECT * FROM "
                           . YASR_LOG_TABLE .
                           " WHERE user_id = $user_id 
                             ORDER BY date 
                             DESC LIMIT %d, %d ";

        $this->container_id     = 'yasr-user-log-container';
        $this->span_total_pages = 'yasr-user-log-total-pages';
        $this->button_class     = 'yasr-user-log-page-num';
        $this->span_loader_id   = 'yasr-loader-user-log-metabox';

        $this->returnWidget();
    }

    /**
     * Print the widget
     */
    private function returnWidget() {
        global $wpdb;

        if($this->n_rows > 0) {
            $this->num_of_pages = ceil($this->n_rows / $this->limit);
        } else {
            $this->num_of_pages = 1;
        }

        //do the query
        $this->log_result = $wpdb->get_results(
            $wpdb->prepare(
                $this->log_query,
                $this->offset, $this->limit)
        );

        if (!$this->log_result) {
            _e("No Recent votes yet", 'yet-another-stars-rating');
        } else {
            $this->html_to_return = "<div class='yasr-log-container' id='$this->container_id'>";

            foreach ($this->log_result as $column) {

                $user = get_user_by('id', $column->user_id); //Get info user from user id

                //If ! user means that the vote are anonymous
                if ($user == false) {
                    $user             = (object) array('user_login');
                    $user->user_login = __('anonymous', 'yet-another-stars-rating');
                }

                $avatar = get_avatar($column->user_id, '32'); //Get avatar from user id

                $post_title = wp_strip_all_tags(get_the_title($column->post_id)); //Get post title from post id
                $link       = get_permalink($column->post_id); //Get post link from post id

                if ($this->user_widget !== true) {
                    $yasr_log_vote_text = ' ' . sprintf(
                            __('Vote %d from %s on', 'yet-another-stars-rating'),
                            $column->vote,
                            '<strong style="color: blue">' . $user->user_login . '</strong>'
                        );
                } else {
                    $yasr_log_vote_text = ' ' . sprintf(
                            __('You rated %s on', 'yet-another-stars-rating'),
                            '<strong style="color: blue">' . $column->vote . '</strong>'
                        );
                }

                //Default values (for admin widget)
                $ip_span = ''; //default value

                //Set value depending if we're on user or admin widget
                if ($this->user_widget !== true) {
                    if (YASR_ENABLE_IP === 'yes') {
                        $ip_span = '<span class="yasr-log-ip">' . __("Ip address", 'yet-another-stars-rating') . ': 
                                   <span style="color:blue">' . $column->ip . '</span>
                               </span>';
                    }
                } else {
                    $ip_span = '';
                }

                $rows_content = '<div class="yasr-log-div-child">
                                      <div class="yasr-log-image">'
                                .$avatar.
                                '</div>
                                      <div class="yasr-log-child-head">
                                          <span id="yasr-log-vote">'.$yasr_log_vote_text.'</span>
                                          <span id="yasr-log-post"><a href=&quot;'.$link.'&quot;>'.$post_title.'</a></span>
                                      </div>
                                      <div class="yasr-log-ip-date">'
                                .$ip_span.
                                '<span class="yasr-log-date">'.$column->date.'</span>
                                      </div>
                                </div>';

                $this->html_to_return .= $rows_content;

            } //End foreach

            $this->html_to_return .= "<div id='yasr-log-page-navigation'>";

            //use data attribute instead of value of #yasr-log-total-pages, because, on ajaxresponse,
            //the "last" button could not exists
            $this->html_to_return .= "<span id='$this->span_total_pages' data-yasr-log-total-pages='$this->num_of_pages'>";
            $this->html_to_return .= __("Pages", 'yet-another-stars-rating') . ": ($this->num_of_pages) &nbsp;&nbsp;&nbsp;";
            $this->html_to_return .= '</span>';

            $this->pagination();

            $this->html_to_return .= '</div>'; //End yasr-log-page-navigation
            $this->html_to_return .= '</div>'; //End Yasr Log Container

            echo $this->html_to_return;

        } // End else if !$log result

        if ($this->is_ajax === true) {
            die();
        }

    }

    /**
     * This function will print the row with pagination
     */
    private function pagination() {

        if ($this->num_of_pages <= 3) {
            for ($i = 1; $i <= $this->num_of_pages; $i++) {
                if ($i == $this->page_num) {
                    $this->html_to_return .= "<button class='button-primary' value='$i'>$i</button>&nbsp;&nbsp;";
                } else {
                    $this->html_to_return .= "<button class=$this->button_class value='$i'>$i</button>&nbsp;&nbsp;";
                }
            }
            $this->html_to_return .= "<span id='yasr-loader-log-metabox' style='display:none;'>&nbsp;
                                        <img alt='loader' src='" . YASR_IMG_DIR . "/loader.gif' >
                                    </span>";
        }
        else {
            $start_for = $this->page_num - 1;

            if ($start_for <= 0) {
                $start_for = 1;
            }

            $end_for = $this->page_num + 1;

            if ($end_for >= $this->num_of_pages) {
                $end_for = $this->num_of_pages;
            }

            if ($this->page_num >= 3) {
                $this->html_to_return .= "<button class=$this->button_class value='1'>
                                            &laquo; First </button>&nbsp;&nbsp;...&nbsp;&nbsp;";
            }

            for ($i = $start_for; $i <= $end_for; $i ++) {
                if ($i == $this->page_num) {
                    $this->html_to_return .= "<button class='button-primary' value='$i'>$i</button>&nbsp;&nbsp;";
                } else {
                    $this->html_to_return .= "<button class=$this->button_class value='$i'>$i</button>&nbsp;&nbsp;";
                }
            }

            $num_of_page_less_one = $this->num_of_pages - 1;

            if ($this->page_num != $this->num_of_pages && $this->page_num != $num_of_page_less_one) {
                $this->html_to_return .= "...&nbsp;&nbsp;
                                        <button class=$this->button_class 
                                            value='$this->num_of_pages'>
                                            Last &raquo;</button>
                                            &nbsp;&nbsp;";
            }

            $this->html_to_return .= "<span id='$this->span_loader_id' style='display:none;' >&nbsp;
                                        <img alt='loader' src='" . YASR_IMG_DIR . "/loader.gif' >
                                    </span>";

        }

    }
}