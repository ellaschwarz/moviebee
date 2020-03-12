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

//this load guten-block.js, only in admin side
add_action('enqueue_block_editor_assets', 'yasr_gutenberg_scripts');

function yasr_gutenberg_scripts() {

    //Script
    wp_enqueue_script(
        'yasr_blocks',
        YASR_JS_DIR . 'yasr-guten-blocks.js',
        array(
            'wp-i18n',
            'wp-blocks',
            'wp-components',
            'wp-element',
            'wp-editor'
        )
    );

    wp_enqueue_script(
        'yasr_guten_panel',
        YASR_JS_DIR . 'yasr-guten-panel.js',
        array(
            'wp-plugins',
            'wp-edit-post',
            'wp-element',
            'wp-editor'
        )
    );

}

//This filter is used to add a new category in gutenberg
add_filter('block_categories', 'yasr_add_gutenberg_category', 10, 2);

function yasr_add_gutenberg_category($categories) {
    return array_merge(
        $categories,
        array(
            array(
                'slug'  => 'yet-another-stars-rating',
                'title' => 'Yasr: Yet Another Stars Rating',
            ),
        )
    );
}

add_action('yasr_add_admin_scripts_end', 'yasr_add_js_constant_gutenberg');

function yasr_add_js_constant_gutenberg($hook) {

    if ($hook === 'post.php' || $hook === 'post-new.php') {

        if (YASR_AUTO_INSERT_ENABLED == 1) {
            $auto_insert = YASR_AUTO_INSERT_WHAT;
        } else {
            $auto_insert = 'disabled';
        }

        wp_localize_script(
            'yasradmin',
            'yasrConstantGutenberg',
            array(
                'autoInsert'   => $auto_insert,
                'defaultItemType' => YASR_ITEMTYPE
            )
        );

    }

}


/****** Create 2 metaboxes in post and pages ******/

add_action('add_meta_boxes', 'yasr_add_metaboxes');

function yasr_add_metaboxes() {

    //Default post type where display metabox
    $post_type_where_display_metabox = array('post', 'page');

    //get the custom post type
    $custom_post_types = yasr_get_custom_post_type();

    if ($custom_post_types) {
        //First merge array then changes keys to int
        $post_type_where_display_metabox = array_values(array_merge($post_type_where_display_metabox, $custom_post_types));
    }

    //For classic editor, add this metabox
    foreach ($post_type_where_display_metabox as $post_type) {
        add_meta_box(
            'yasr_metabox_overall_rating',
            'YASR',
            'yasr_metabox_overall_rating_content',
            $post_type,
            'side',
            'high',
            //Set this to true, so this metabox will be only loaded to classic editor
            array(
                '__back_compat_meta_box' => true,
            )
        );
    }

    $multi_set = yasr_get_multi_set();
    //If multiset are used then add the second metabox
    if ($multi_set) {
        foreach ($post_type_where_display_metabox as $post_type) {
            add_meta_box(
                'yasr_metabox_multiple_rating',
                __('Yet Another Stars Rating: Multiple set', 'yet-another-stars-rating'),
                'yasr_metabox_multiple_rating_content',
                $post_type,
                'normal',
                'high'
            );
        }
    }

} //End function

function yasr_metabox_overall_rating_content() {

    if (current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        include(YASR_ABSOLUTE_PATH . '/lib/admin/editor/yasr-metabox-top-right.php');
    } else {
        _e("You don't have enought privileges to insert Overall Rating");
    }

}

function yasr_metabox_multiple_rating_content() {

    if (current_user_can(YASR_USER_CAPABILITY_EDIT_POST)) {
        include(YASR_ABSOLUTE_PATH . '/lib/admin/editor/yasr-metabox-multiple-rating.php');
    } else {
        _e("You don't have enough privileges to insert a Multi Set");
    }

}

?>