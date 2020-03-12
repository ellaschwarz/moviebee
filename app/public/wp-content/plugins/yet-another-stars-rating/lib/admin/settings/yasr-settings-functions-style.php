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

add_action('admin_init', 'yasr_style_options_init'); //This is for auto insert options

function yasr_style_options_init() {

    register_setting(
        'yasr_style_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
        'yasr_style_options', //The name of an option to sanitize and save.
        'yasr_style_options_sanitize'
    );

    global $style_options;

    if (!$style_options || !isset($style_options['scheme_color_multiset']) || !isset($style_options['textarea'])) {

        $style_options                          = array();
        $style_options['scheme_color_multiset'] = 'light';
        $style_options['textarea']              = null;

    }

    //filter $style_options
    $style_options = apply_filters('yasr_filter_style_options', $style_options);

    add_settings_section('yasr_style_options_section_id', __('Style Options', 'yet-another-stars-rating'), 'yasr_style_section_callback', 'yasr_style_tab');
    do_action('yasr_style_options_add_settings_field', $style_options);
    add_settings_field('yasr_color_scheme_multiset', __('Which color scheme do you want to use?', 'yet-another-stars-rating'), 'yasr_color_scheme_multiset_callback', 'yasr_style_tab', 'yasr_style_options_section_id', $style_options);
    add_settings_field('yasr_style_options_textarea', __('Custom CSS Styles', 'yet-another-stars-rating'), 'yasr_style_options_textarea_callback', 'yasr_style_tab', 'yasr_style_options_section_id', $style_options);


}

function yasr_style_section_callback() {

}


function yasr_color_scheme_multiset_callback($style_options) {

    ?>

    <input type='radio' name='yasr_style_options[scheme_color_multiset]' value='light'
           class='yasr-general-options-scheme-color' <?php if ($style_options['scheme_color_multiset'] === 'light') {
        echo " checked=\"checked\" ";
    } ?> />
    <?php _e('Light', 'yet-another-stars-rating') ?>

    &nbsp;&nbsp;&nbsp;

    <input type='radio' name='yasr_style_options[scheme_color_multiset]' value='dark'
           class='yasr-general-options-scheme-color' <?php if ($style_options['scheme_color_multiset'] === 'dark') {
        echo " checked=\"checked\" ";
    } ?> />
    <?php _e('Dark', 'yet-another-stars-rating') ?>
    <br/>

    <br/>

    <a href="#" id="yasr-color-scheme-preview-link"><?php _e("Preview", 'yet-another-stars-rating') ?></a>

    <div id="yasr-color-scheme-preview" style="display:none">
        <?php

        _e("Light theme", 'yet-another-stars-rating');
        echo "<br /><br /><img src=" . YASR_IMG_DIR . "yasr-multi-set.png>";

        echo "<br /> <br />";

        _e("Dark theme", 'yet-another-stars-rating');
        echo "<br /><br /><img src=" . YASR_IMG_DIR . "dark-multi-set.png>";
        ?>
    </div>

    <p>

    <?php
}

function yasr_style_options_textarea_callback($style_options) {

    _e("Please use text area below to write your own CSS styles to override the default ones.", 'yet-another-stars-rating');
    echo "<br /><strong>";
    _e("Leave it blank if you don't know what you're doing.", 'yet-another-stars-rating');
    echo "</strong><p>";

    echo("
			<textarea rows=\"17\" cols=\"40\" name=\"yasr_style_options[textarea]\" id=\"yasr_style_options_textarea\">$style_options[textarea]</textarea>
			");


}

//sanitize
function yasr_style_options_sanitize($style_options) {

    $style_options = apply_filters('yasr_sanitize_style_options', $style_options);

    foreach ($style_options as $key => $value) {
        $output[$key] = strip_tags(stripslashes($style_options[$key]));

        //Always use esc_html
        $output[$key] = esc_html($output[$key]);
    }

    return $output;

}

?>