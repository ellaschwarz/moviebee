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


/***** Adding javascript and css *****/

add_action('wp_enqueue_scripts', 'yasr_add_scripts');
add_action('admin_enqueue_scripts', 'yasr_add_admin_scripts');

function yasr_add_scripts() {

    wp_enqueue_style('yasrcss', YASR_CSS_DIR . 'yasr.css', false, YASR_VERSION_NUM, 'all');
    wp_enqueue_style('dashicons'); //dashicons

    //Run after default css are loaded
    do_action('yasr_add_front_script_css');

    $yasr_multiset_theme_handle= 'yasrcsslightscheme';
    $yasr_multiset_theme = 'yasr-table-light.css';

    //default css is the light one
    if (YASR_SCHEME_COLOR === 'dark') {
        $yasr_multiset_theme_handle = 'yasrcssdarkscheme';
        $yasr_multiset_theme = 'yasr-table-dark.css';
    }

    wp_enqueue_style($yasr_multiset_theme_handle, YASR_CSS_DIR . $yasr_multiset_theme, array('yasrcss'),
        YASR_VERSION_NUM, 'all');

    if (YASR_CUSTOM_CSS_RULES) {
        wp_add_inline_style('yasrcss', YASR_CUSTOM_CSS_RULES);
    }

    $rater_file_to_include = 'rater-js.js';

    if (is_rtl()) {
        $rater_file_to_include = 'rater-js-rtl.js';
    }

    wp_enqueue_script('rater', YASR_JS_DIR . $rater_file_to_include, '', YASR_VERSION_NUM, true);
    wp_enqueue_script('yasrfront', YASR_JS_DIR . 'yasr-front.js', array('jquery', 'rater'), YASR_VERSION_NUM, true);
    wp_enqueue_script('tippy', YASR_JS_DIR . 'tippy.all.min.js', '', '3.6.0', true);

    do_action('yasr_add_front_script_js');

    $yasr_visitor_votes_loader = '<div id="loader-visitor-rating" style="display: inline">&nbsp; '.
                                 ' <img src=' . YASR_IMG_DIR . 'loader.gif title="yasr-loader" alt="yasr-loader">
                                 </div>';

    $tooltip_values          = __("bad, poor, ok, good, super", 'yet-another-stars-rating');
    $tooltip_values_exploded = explode(", ", $tooltip_values);

    wp_localize_script('yasrfront', 'yasrCommonData',
        array(
            'postid'              => get_the_ID(),
            'ajaxurl'             => admin_url('admin-ajax.php'),
            'loggedUser'          => is_user_logged_in(),
            'visitorStatsEnabled' => YASR_VISITORS_STATS,
            'tooltipValues'       => $tooltip_values_exploded,
            'loaderHtml'          => $yasr_visitor_votes_loader,
        )
    );

}

//$hook contain the current page in the admin side
function yasr_add_admin_scripts($hook) {

    global $yasr_settings_page;

    if ($hook === 'edit.php' || $hook === 'post.php' || $hook === 'post-new.php') {
        wp_enqueue_script('yasr_shortcode_creator', YASR_JS_DIR . 'yasr-shortcode-creator.js', array('jquery'), YASR_VERSION_NUM, true);
    }

    if ($hook === 'index.php' || $hook === 'edit.php' || $hook === 'post.php' || $hook === 'post-new.php' ||
        $hook === 'edit-comments.php' || $hook === $yasr_settings_page ||
        $hook === 'yet-another-stars-rating_page_yasr_stats_page'
    ) {

        do_action('yasr_add_admin_scripts_begin');

        $rater_file_to_include = 'rater-js.js';

        if (is_rtl()) {
            $rater_file_to_include = 'rater-js-rtl.js';
        }

        wp_enqueue_style('yasrcss', YASR_CSS_DIR . 'yasr-admin.css', false, YASR_VERSION_NUM, 'all');
        wp_enqueue_script('yasradmin', YASR_JS_DIR . 'yasr-admin.js', array('jquery'), YASR_VERSION_NUM, true);
        wp_enqueue_script('rater', YASR_JS_DIR . $rater_file_to_include, '', YASR_VERSION_NUM, true);

        do_action('yasr_add_admin_scripts_end', $hook);

        $yasr_loader = YASR_IMG_DIR . 'loader.gif';

        wp_localize_script(
            'yasradmin', 'yasrCommonDataAdmin',
            array(
                'loaderHtml' => $yasr_loader
            )
        );

    }


}

/*** Css rules for stars set, from version 1.2.7
 * Here I use add_action instead of directly use wp_add_inline_style so I can
 * use remove_action if needed (e.g. Yasr Stylish)
 ***/

add_action('yasr_add_front_script_css', 'yasr_css_stars_set');
add_action('yasr_add_admin_scripts_end', 'yasr_css_stars_set');

function yasr_css_stars_set() {

    //if star selected is "rater", select the images
    if (YASR_STARS_SET === 'rater') {
        $star_grey   = YASR_IMG_DIR . 'star_0.svg';
        $star_yellow = YASR_IMG_DIR . 'star_1.svg';
    } elseif (YASR_STARS_SET === 'rater-oxy') {
        $star_grey   = YASR_IMG_DIR . 'star_oxy_0.svg';
        $star_yellow = YASR_IMG_DIR . 'star_oxy_1.svg';
    } //by default, use the one provided by Yasr
    else {
        $star_grey   = YASR_IMG_DIR . 'star_2.svg';
        $star_yellow = YASR_IMG_DIR . 'star_3.svg';
    }


    $yasr_st_css = "
        .star-rating {
            background-image: url(\"$star_grey\");
        }
        .star-rating .star-value {
            background: url(\"$star_yellow\") ;
        }
    ";

    wp_add_inline_style('yasrcss', $yasr_st_css);

}

add_action('yasr_add_front_script_css', 'yasr_rtl_support');
add_action('yasr_add_admin_scripts_end', 'yasr_rtl_support');

function yasr_rtl_support() {

    if (is_rtl()) {
        $yasr_rtl_css = '.star-rating .star-value {
                        -moz-transform: scaleX(-1);
                        -o-transform: scaleX(-1);
                    
                        -webkit-transform: scaleX(-1);
                        transform: scaleX(-1);
                        filter: FlipH;
                        -ms-filter: "FlipH";
                        right: 0;
                        left: auto;
                    }';

        wp_add_inline_style('yasrcss', $yasr_rtl_css);

    }

}


/****** Translating YASR ******/

add_action('init', 'yasr_translate_option', 110);

function yasr_translate_option() {
    load_plugin_textdomain('yet-another-stars-rating', false, YASR_LANG_DIR);
}


/****** Create a new Page in Administration Menu ******/

/* Hook to admin_menu the yasr_add_pages function above */
add_action('admin_menu', 'yasr_add_pages');

function yasr_add_pages() {

    global $yasr_settings_page;

    //Add Settings Page
    $yasr_settings_page = add_menu_page(
        __('Yet Another Stars Rating: settings', 'yet-another-stars-rating'), //Page Title
        __('Yet Another Stars Rating', 'yet-another-stars-rating'), //Menu Title
        'manage_options', //capability
        'yasr_settings_page', //menu slug
        'yasr_settings_page_callback', //The function to be called to output the content for this page.
        'dashicons-star-half'
    );

    add_submenu_page(
        'yasr_settings_page',
        'Yet Another Stars Rating: settings',
        'Settings',
        'manage_options',
        'yasr_settings_page'
    );

    add_submenu_page(
        'yasr_settings_page',
        'Yet Another Stars Rating: All Rating',
        'Stats',
        'manage_options',
        'yasr_stats_page',
        'yasr_stats_page_callback'
    );

    if (yasr_fs()->is_free_plan() && !yasr_fs()->is_trial()) {
        global $submenu;
        $permalink                       = '#';
        $contact_us_string               = sprintf(
            __('Contact Us %s', 'yet-another-stars-rating'),
            '<span class="dashicons dashicons-lock" />'
        );
        $submenu['yasr_settings_page'][] = array($contact_us_string, 'manage_options', $permalink);
    }

}

// Settings Page Content
function yasr_settings_page_callback() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
    }

    include(YASR_ABSOLUTE_PATH . '/yasr-settings-page.php');

} //End yasr_settings_page_content


function yasr_stats_page_callback() {

    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
    }

    include(YASR_ABSOLUTE_PATH . '/yasr-stats-page.php');
}


/****** Auto insert overall rating and visitor rating  ******/

if (YASR_AUTO_INSERT_ENABLED == 1) {

    if (!is_admin()) {
        add_filter('the_content', 'yasr_auto_insert_shortcode_callback');
    }

    function yasr_auto_insert_shortcode_callback($content) {

        $post_id = get_the_ID();

        //check if for this post or page auto insert is off
        $post_excluded = get_post_meta($post_id, 'yasr_auto_insert_disabled', true);

        if ($post_excluded === 'yes') {
            return $content;
        }

        $shortcode_align = YASR_AUTO_INSERT_ALIGN;

        //if it is not left, or right, default is center
        if ($shortcode_align !== 'left' && $shortcode_align !== 'right') {
            $shortcode_align = 'center';
        }

        $container_div_overall='<div style="text-align:'.$shortcode_align.'" class="yasr-auto-insert-overall">';
        $container_div_visitor='<div style="text-align:'.$shortcode_align.'" class="yasr-auto-insert-visitor">';
        $closing_div = '</div>';

        $auto_insert_shortcode = null; //To avoid undefined variable notice outside the loop (if (is_singular) )
        $overall_rating_code = $container_div_overall. '[yasr_overall_rating size="' . YASR_AUTO_INSERT_SIZE . '"]' . $closing_div;
        $visitor_votes_code = $container_div_visitor. '[yasr_visitor_votes size="' . YASR_AUTO_INSERT_SIZE . '"]' . $closing_div;

        if (YASR_AUTO_INSERT_WHAT === 'overall_rating') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $overall_rating_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $overall_rating_code;
                    break;
            } //End Switch
        } elseif (YASR_AUTO_INSERT_WHAT === 'visitor_rating') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $visitor_votes_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $visitor_votes_code;
                    break;
            } //End Switch
        } elseif (YASR_AUTO_INSERT_WHAT === 'both') {
            switch (YASR_AUTO_INSERT_WHERE) {
                case 'top':
                    $content_and_stars = $overall_rating_code . $visitor_votes_code . $content;
                    break;

                case 'bottom':
                    $content_and_stars = $content . $overall_rating_code . $visitor_votes_code;
                    break;
            } //End Switch
        }

        //IF auto insert must work only in custom post type
        if (YASR_AUTO_INSERT_CUSTOM_POST_ONLY === 'yes') {

            $custom_post_types = yasr_get_custom_post_type();

            //If is a post type return content and stars
            if (is_singular($custom_post_types)) {
                return $content_and_stars;
            } //else return just content
            else {
                return $content;
            }

        }

        //If page are not excluded
        if (YASR_AUTO_INSERT_EXCLUDE_PAGES === 'no') {
            return $content_and_stars;
        } //else return only if it is not a page
        elseif (YASR_AUTO_INSERT_EXCLUDE_PAGES === 'yes') {
            if (is_page()) {
                return $content;
            } //If is a page return the content without stars
            else {
                return $content_and_stars;
            }
        }

    } //End function yasr_auto_insert_shortcode_callback

} //End  if (YASR_AUTO_INSERT_ENABLED

/****** Add review schema data at the end of the post *******/


add_filter('the_content', 'yasr_add_schema');

function yasr_add_schema($content) {

    //Add buddypress compatibility
    if (function_exists('bp_is_active')) {
        //If this is a page, return $content without adding schema.
        //If I try to return $content after if (YASR_SNIPPET == 'overall_rating')
        //or (YASR_SNIPPET == 'visitor_rating') $content will have only wp content, losing the buddypress one
        if (is_page()) {
            return $content;
        }
    }

    if (!is_singular() && is_main_query() || is_404() || did_action('get_footer')) {
        return $content;
    }

    $database_ratings_obj = new YasrDatabaseRatings();
    $overall_rating = $database_ratings_obj->getOverallRating();
    $visitor_votes = $database_ratings_obj->getVisitorVotes(false, false);

    $is_post_a_review = get_post_meta(get_the_ID(), 'yasr_post_is_review', true);

    $visitor_rating = array(); //avoid undefined

    if($visitor_votes != false && is_array($visitor_votes)) {
        foreach ($visitor_votes as $rating) {
            $visitor_rating['votes_number'] = $rating->number_of_votes;
            $visitor_rating['sum']          = $rating->sum_votes;
        }
    } else {
        $visitor_rating['votes_number'] = false;
        $visitor_rating['sum']          = false;
    }

    if (!$overall_rating && !$visitor_rating['votes_number'] && !$visitor_rating['sum']) {
        return $content;
    }

    $script_type = '<script type="application/ld+json">';
    $end_script_type = '</script>';

    $review_choosen = yasr_get_snippet_type();

    //if doesn't exists a filter for yasr_filter_schema_jsonld $review_chosen value is assigned to $filtered_schema...
    $filtered_schema = apply_filters('yasr_filter_schema_jsonld', $review_choosen);

    //So check here if $schema != $review_choosen
    if ($filtered_schema !== $review_choosen) {
        return $content . $script_type . $filtered_schema . $end_script_type;
    }

    $rich_snippet["@context"] = "http://schema.org/";

    $author = get_the_author();
    $review_name = wp_strip_all_tags(get_the_title());

    $date = get_the_date('c');
    $date_modified = get_the_modified_date('c');

    $post_image_url = ''; //avoid undefined
    $logo_image_url = ''; //avoid undefined

    if (defined('YASR_BLOGPOSTING_ORGANIZATION_LOGO')) {
        $logo_image_url = YASR_BLOGPOSTING_ORGANIZATION_LOGO;
        $post_image_url = $logo_image_url; //this will be overwritten if has_post_thumbnail is true

        $logo_image_url_absolute = $_SERVER['DOCUMENT_ROOT'] . parse_url(YASR_BLOGPOSTING_ORGANIZATION_LOGO, PHP_URL_PATH);

        $post_image_size = @getimagesize($logo_image_url_absolute);  //the @ should be useless, just to be safe
        $logo_image_size = @getimagesize($logo_image_url_absolute);  //the @ should be useless, just to be safe
    } else {
        $post_image_size[0] = 0;
        $post_image_size[1] = 0;
        $logo_image_size[0] = 0;
        $logo_image_size[1] = 0;
    }

    //if exists featuread image get the url and overwrite the variable
    if (has_post_thumbnail()) {
        $post_image_url = wp_get_attachment_url(get_post_thumbnail_id());
        $post_image_url_absolute = $_SERVER['DOCUMENT_ROOT'] . parse_url($post_image_url, PHP_URL_PATH);
        $post_image_size = @getimagesize($post_image_url_absolute);  //the @ should be useless, just to be safe
    }

    if ($review_choosen === "Product") {
        $rich_snippet["@type"] = "Product";
    }
    elseif($review_choosen === 'Book') {
        $rich_snippet["@type"] = "Book";
    }
    elseif($review_choosen === 'Movie') {
        $rich_snippet["@type"] = "Movie";
    }
    elseif($review_choosen === 'Game') {
        $rich_snippet["@type"] = "Game";
    }
    elseif ($review_choosen == "Place") {
        $rich_snippet["@type"] = "LocalBusiness";
    }
    elseif ($review_choosen === "Recipe") {
        $rich_snippet["@type"] = "Recipe";
    }
    elseif ($review_choosen == "Other") {
        $rich_snippet["@type"] = "BlogPosting";
        $rich_snippet["datePublished"] = $date;
        $rich_snippet["headline"] = $review_name;
        $rich_snippet["mainEntityOfPage"] = array(
            "@type" => "WebPage",
            "@id"   => get_permalink()
        );
        $rich_snippet["author"] = array(
            "@type" => "Person",
            "name" => "$author"
        );
        $rich_snippet["publisher"] = array(
            "@type" => "Organization",
            "name"  => wp_strip_all_tags(YASR_BLOGPOSTING_ORGANIZATION_NAME), //already sanitized in the settings, just to be safe
            "logo"  => array(
                "@type"  => "ImageObject",
                "url"    => $logo_image_url,
                "width"  => $logo_image_size[0],
                "height" => $logo_image_size[1]
            ),
        );

        $rich_snippet["dateModified"] = $date_modified;

        $rich_snippet["image"] = array(
            "@type"  => "ImageObject",
            "url"    => $post_image_url,
            "width"  => $post_image_size[0],
            "height" => $post_image_size[1]
        );

    }

    $rich_snippet["name"] = $review_name;
    $rich_snippet["image"] = array(
        "@type"  => "ImageObject",
        "url"    => $post_image_url,
        "width"  => $post_image_size[0],
        "height" => $post_image_size[1]
    );

    //Do not add rating if blogposting
    if ($review_choosen !== 'Other') {

        if ($overall_rating && $overall_rating != '-1' && $overall_rating != '0.0') {
            $rich_snippet["Review"] = array(
                "@type"         => "Review",
                "name"          => "$review_name",
                "author"        => array(
                    "@type" => "Person",
                    "name"  => "$author"
                ),
                "datePublished" => "$date",
                "dateModified"  => $date_modified,
                "reviewRating"  => array(
                    "@type"       => "Rating",
                    "ratingValue" => "$overall_rating",
                ),
            );
        }

        //if both are included, google will index AggregateRating instead of Review.
        //So, is post is selected as review, exclude AggregateRating
        if($is_post_a_review !== 'yes') {
            if ($visitor_votes) {
                if ($visitor_rating['sum'] != 0 && $visitor_rating['votes_number'] != 0) {
                    $average_rating = $visitor_rating['sum'] / $visitor_rating['votes_number'];
                    $average_rating = round($average_rating, 1);

                    $rich_snippet["aggregateRating"] = array(
                        "@type"       => "AggregateRating",
                        "ratingValue" => "$average_rating",
                        "ratingCount" => $visitor_rating['votes_number'],
                    );
                }
            }
        }

    }

    //if doesn't exists a filter for yasr_filter_existing_schema, put $rich_snippet into $more_rich_snippet
    $more_rich_snippet = apply_filters('yasr_filter_existing_schema', $rich_snippet);

    if ($more_rich_snippet !== $rich_snippet && is_array($more_rich_snippet)) {
        $rich_snippet = $more_rich_snippet;
    }

    if (is_singular() && is_main_query() && !is_404()) {
        return $content . $script_type . json_encode($rich_snippet) . $end_script_type;
    } else {
        return $content;
    }

} //End function

/****** Create a select menu to choose the rich snippet itemtype ******/

function yasr_select_itemtype() {

    $review_types   = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);
    $review_type_choosen = yasr_get_snippet_type();

    ?>

    <select name="yasr-review-type" id="yasr-choose-reviews-types-list">

        <?php

        foreach ($review_types as $type) {

            $type = trim($type);
            $type_option = $type;
            if($type === 'Place') {
                $type_option='LocalBusiness';
            }
            if($type === 'Other') {
                $type_option='BlogPosting';
            }

            if ($type === $review_type_choosen) {
                echo "<option value=\"$type\" selected>$type_option</option>";
            } else {
                echo "<option value=\"$type\">$type_option</option>";
            }

        }

        ?>

    </select>

    <?php

} //End function yasr_select_itemtype()


/******* Add a media content button ******/

add_action('media_buttons', 'yasr_shortcode_button_media', 99);

function yasr_shortcode_button_media() {

    if (is_admin()) {

        add_thickbox();

        echo '<a href="#TB_inline?width=530&height=600&inlineId=yasr-tinypopup-form" 
                 id="yasr-shortcode-creator" 
                 class="button thickbox">
                 <span class="dashicons dashicons-star-half" style="vertical-align: middle;"></span> Yasr Shortcode
              </a>';

    }

}


/****** Return the custom post type if exists
 * Argument is to set what to return, if array or boolean value.
 * Default: array******/

add_action('admin_init', 'yasr_get_custom_post_type');

//Creating a rest response
add_action('rest_api_init', function () {
    register_rest_route(
        'yet-another-stars-rating/v1/',
        '/list-posts-types',
        array(
            'methods'  => 'GET',
            'callback' => 'yasr_get_custom_post_type',
        )
    );
});

function yasr_get_custom_post_type($return_value = 'array') {

    //if this is a rest request $return_value is an object called WP_REST_Request
    //overwrite content to 'array'
    if (is_object($return_value)) {
        if (get_class($return_value) === 'WP_REST_Request') {
            $return_value = 'array';
        }
    }

    $args = array(
        'public'   => true,
        '_builtin' => false
    );

    $output   = 'names'; // names or objects, note names is the default
    $operator = 'and'; // 'and' or 'or'

    $post_types = get_post_types($args, $output, $operator);

    if ($post_types) {
        if ($return_value === 'array') {
            return ($post_types);
        } else {
            return true;
        }
    } else {
        //no external post type founds
        return false;
    }

}


/*** Add support for wp super cache ***/
function yasr_wp_super_cache_support($post_id) {
    if (function_exists('wp_cache_post_change')) {
        wp_cache_post_change($post_id);
    }
}

/*** Add support for wp rocket, thanks to GeekPress
 * https://wordpress.org/support/topic/compatibility-with-wp-rocket-2
 ***/
function yasr_wp_rocket_support($post_id) {
    if (function_exists('rocket_clean_post')) {
        rocket_clean_post($post_id);
    }
}

/*** Add support for LiteSpeed Cache plugin, thanks to Pako69
 * https://wordpress.org/support/topic/yasr-is-litespeed-cache-plugin-compatible/
 ***/
function yasr_litespeed_cache_support($post_id) {
    if (method_exists('LiteSpeed_Cache_API', 'purge_post') == true) {
        LiteSpeed_Cache_API::purge_post($post_id);
    }
}

/*** Add support for cache enabler ***/
function yasr_cache_enabler_support($post_id, $is_singular) {

    if (has_action('ce_clear_cache') || has_action('ce_clear_post_cache')) {
        //IF is in the single post or page delete only that cache
        if ($is_singular === 'true') {
            do_action('ce_clear_post_cache', $post_id);
        } //otherwise, delete everything
        else {
            do_action('ce_clear_cache');
        }


    }
}


/**** Add support for Wp Fastest Cache ****/
function yasr_wp_fastest_cache($post_id, $is_singular) {

    if ($is_singular === 'true') {
        if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'singleDeleteCache')) {
            $GLOBALS['wp_fastest_cache']->singleDeleteCache(false, $post_id);
        }
    } else {
        if (isset($GLOBALS['wp_fastest_cache']) && method_exists($GLOBALS['wp_fastest_cache'], 'deleteCache')) {
            $GLOBALS['wp_fastest_cache']->deleteCache();
        }
    }


}

/*** Function to set cookie, since version 0.8.3 ***/
function yasr_setcookie($cookiename, $data_to_save) {

    if (!$data_to_save || !$cookiename) {
        exit('Error setting yasr cookie');
    }

    $existing_data = array(); //avoid undefined index

    if (isset($_COOKIE[$cookiename])) {

        //setcookie add \ , so I need to stripslahes
        $existing_data = stripslashes($_COOKIE[$cookiename]);

        //By default, json_decode return an object, TRUE to return an array
        $existing_data = json_decode($existing_data, true);

    }

    //whetever exists or not, push into at the end of array
    $existing_data[] = $data_to_save;

    $encoded_data = json_encode($existing_data);

    setcookie($cookiename, $encoded_data, time() + 31536000, COOKIEPATH, COOKIE_DOMAIN, true);

}

/*** Function to get ip, since version 0.8.8
 * This code can be found on http://codex.wordpress.org/Plugin_API/Filter_Reference/pre_comment_user_ip
 ***/

function yasr_get_ip() {

    if (YASR_ENABLE_IP === 'yes') {

        $ip = null;
        $ip = apply_filters('yasr_filter_ip', $ip);

        if (isset($ip)) {
            return $ip;
        }


        $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];

        if (!empty($_SERVER['X_FORWARDED_FOR'])) {

            $X_FORWARDED_FOR = explode(',', $_SERVER['X_FORWARDED_FOR']);

            if (!empty($X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($X_FORWARDED_FOR[0]);
            }

        } /*
        * Some php environments will use the $_SERVER['HTTP_X_FORWARDED_FOR']
        * variable to capture visitor address information.
        */

        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

            $HTTP_X_FORWARDED_FOR = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

            if (!empty($HTTP_X_FORWARDED_FOR)) {
                $REMOTE_ADDR = trim($HTTP_X_FORWARDED_FOR[0]);
            }

        }

        return preg_replace('/[^0-9a-f:\., ]/si', '', $REMOTE_ADDR);

    } else {
        return ('X.X.X.X');
    }


}


/*function to remove duplicate in an array for a specific key
Taken value: array to search, key
*/

function yasr_unique_multidim_array($array, $key) {

    $temp_array = array();
    $i          = 0;

    //creo un array vuoto che conterrà solo gli indici
    $key_array = array();

    foreach ($array as $val) {

        $result_search_array = array_search($val[$key], $key_array);

        $key_array[$i]  = $val[$key];
        $temp_array[$i] = $val;

        //if result is found
        if ($result_search_array !== false) {

            unset($key_array[$result_search_array], $temp_array[$result_search_array]);

        }

        $i ++;

    }

    sort($temp_array);

    return $temp_array;

}


//Delete caches for supported plugins
add_action('yasr_action_on_visitor_vote', 'yasr_delete_cache');
add_action('yasr_action_on_visitor_multiset_vote', 'yasr_delete_cache');

function yasr_delete_cache($array_action_visitor_vote) {

    if (isset($array_action_visitor_vote['post_id'])) {
        $post_id = $array_action_visitor_vote['post_id'];
    } else {
        return;
    }

    if (isset($array_action_visitor_vote['is_singular'])) {
        $is_singular = $array_action_visitor_vote['is_singular'];
    } else {
        return;
    }

    yasr_wp_super_cache_support($post_id);
    yasr_wp_rocket_support($post_id);
    yasr_litespeed_cache_support($post_id);
    yasr_cache_enabler_support($post_id, $is_singular);
    yasr_wp_fastest_cache($post_id, $is_singular);

}


function yasr_check_valid_url($url) {

    $timeout = 5;

    //Check if url is valid
    if (filter_var($url, FILTER_VALIDATE_URL) !== false) {

        //Check if curl is installed
        if (function_exists('curl_version')) {

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

            //execute curl
            $http_respond = trim(strip_tags(curl_exec($ch)));
            $http_code    = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            //Check the response
            if (($http_code == "200") || ($http_code == "302")) {
                return true;
            } else {
                return false;
            }
            //close curl
            curl_close($ch);

        } //if curl is not installed, use file_get_contents
        else {

            //...but only if enabled on the server!
            if (ini_get('allow_url_fopen')) {

                //Change timeout for file_get_contents
                ini_set('default_socket_timeout', $timeout); //5 seconds

                $headers         = get_headers($url, 1);
                $string_to_check = '200 OK';

                //check if in the first heade we've 200 OK
                if (strpos($headers[0], $string_to_check) !== false) {
                    return true;
                } else {
                    return false;
                }
            } //if url_fopen is not enabled
            else {
                return false;
            }

        }
    } else {
        return false;
    }

}


/**
 * Check if the given string is a supported itemType
 *
 * @param string $item_type
 * @return bool
 *
 * @since 2.1.5
 */

function yasr_is_supported_schema ($item_type) {
    $supported_schema_array = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

    if (in_array($item_type, $supported_schema_array)) {
        return true;
    }

    return false;
}

?>
