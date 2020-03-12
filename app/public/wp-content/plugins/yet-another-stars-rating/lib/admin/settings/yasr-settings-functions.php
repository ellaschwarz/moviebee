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

/************ Add yasr general options ***********/

add_action('admin_init', 'yasr_general_options_init'); //This is for general options

function yasr_general_options_init() {

    register_setting(
        'yasr_general_options_group', // A settings group name. Must exist prior to the register_setting call. This must match the group name in settings_fields()
        'yasr_general_options', //The name of an option to sanitize and save.
        'yasr_general_options_sanitize'
    );

    $option = get_option('yasr_general_options');

    //This is to avoid undefined offset
    if ($option && $option['auto_insert_enabled'] == 0) {
        $option['auto_insert_what']             = 'visitor_rating';
        $option['auto_insert_where']            = 'bottom';
        $option['auto_insert_align']            = 'center';
        $option['auto_insert_exclude_pages']    = 'yes';
        $option['auto_insert_size']             = 'large';
        $option['auto_insert_custom_post_only'] = 'no';
    }

    //This is to avoid undefined offset
    if ($option && $option['text_before_stars'] == 0) {
        $option['text_before_overall']        = __('Our Score', 'yet-another-stars-rating');
        $option['text_before_visitor_rating'] = __('Click to rate this post!', 'yet-another-stars-rating');
        $option['text_after_visitor_rating']  = sprintf(
                                                    __('[Total: %s  Average: %s]', 'yet-another-stars-rating'),
                                                    '%total_count%', '%average%'
                                                );
        $option['custom_text_user_voted']     = __('You must sign in to vote', 'yet-another-stars-rating');
        $option['custom_text_must_sign_in']   = __('You have already voted for this article', 'yet-another-stars-rating');
    }


    //if it's not blogposting avoid undefined variable
    if (!isset($option['blogposting_organization_name'])) {
        $option['blogposting_organization_name'] = get_bloginfo('name');
    }

    if (!isset($option['blogposting_organization_logo'])) {
        $option['blogposting_organization_logo'] = get_site_icon_url();
    }

    $description_auto_insert = sprintf(__('Auto Insert Options %s
                                          Automatically adds YASR in your post or page. %s
                                          Disable this if you prefer to use shortcodes.',
                                          'yet-another-stars-rating'),
                                '<p>&nbsp;</p>', '<br />');

    add_settings_section('yasr_general_options_section_id', __('General settings', 'yet-another-stars-rating'), 'yasr_section_callback', 'yasr_general_settings_tab');
        add_settings_field('yasr_use_auto_insert_id', $description_auto_insert, 'yasr_auto_insert_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_custom_text', __('Insert custom text to show before / after stars', 'yet-another-stars-rating'), 'yasr_custom_text_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_show_overall_in_loop', __('Show "Overall Rating" in Archive Page?', 'yet-another-stars-rating'), 'yasr_show_overall_in_loop_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_show_visitor_votes_in_loop', __('Show "Visitor Votes" in Archive Page?', 'yet-another-stars-rating'), 'yasr_show_visitor_votes_in_loop_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_visitors_stats', __('Do you want show stats for visitors votes?', 'yet-another-stars-rating'), 'yasr_visitors_stats_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_allow_only_logged_in_id', __('Allow only logged in user to vote?', 'yet-another-stars-rating'), 'yasr_allow_only_logged_in_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_enable_ip', __('Do you want to save ip address?', 'yet-another-stars-rating'), 'yasr_enable_ip_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
        add_settings_field('yasr_choose_snippet_id', __('Rich snippet options', 'yet-another-stars-rating'), 'yasr_choose_snippet_callback', 'yasr_general_settings_tab', 'yasr_general_options_section_id', $option);
}

function yasr_section_callback() {
    //_e('Manage auto insert', 'yet-another-stars-rating');
}

function yasr_auto_insert_callback($option) {

    ?>

    <div>
        <strong><?php _e('Use Auto Insert?', 'yet-another-stars-rating'); ?></strong>
        <div class="yasr-onoffswitch-big">
            <input type="checkbox" name="yasr_general_options[auto_insert_enabled]" class="yasr-onoffswitch-checkbox"
                   value="1" id="yasr_auto_insert_switch" <?php if ($option['auto_insert_enabled'] == 1) {
                echo " checked='checked' ";
            } ?> >
            <label class="yasr-onoffswitch-label" for="yasr_auto_insert_switch">
                <span class="yasr-onoffswitch-inner"></span>
                <span class="yasr-onoffswitch-switch"></span>
            </label>
        </div>

        <p>&nbsp;</p>

        <div>
            <strong><?php _e('What?', 'yet-another-stars-rating'); ?></strong>

            <div class="yasr-indented-answer">

                <input type="radio" name="yasr_general_options[auto_insert_what]" value="visitor_rating"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_what'] === 'visitor_rating') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('Visitor Votes', 'yet-another-stars-rating') ?>
                <br/>

                <input type="radio" name="yasr_general_options[auto_insert_what]" value="overall_rating"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_what'] === 'overall_rating') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('Overall Rating / Author Rating', 'yet-another-stars-rating') ?>
                <br/>

                <input type="radio" name="yasr_general_options[auto_insert_what]" value="both"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_what'] === 'both') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('Both', 'yet-another-stars-rating') ?>
            </div>
        </div>

        <p>&nbsp;</p>

        <div>
            <strong><?php _e('Where?', 'yet-another-stars-rating'); ?></strong>
            <div class="yasr-indented-answer">
                <input type="radio" name="yasr_general_options[auto_insert_where]" value="top"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_where'] === 'top') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('Before the content', 'yet-another-stars-rating') ?>
                <br/>

                <input type="radio" name="yasr_general_options[auto_insert_where]" value="bottom"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_where'] === 'bottom') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('After the content', 'yet-another-stars-rating') ?>
                <br/>
            </div>
        </div>

        <p>&nbsp;</p>

        <div>
            <strong><?php _e('Align', 'yet-another-stars-rating'); ?></strong>

            <div class="yasr-indented-answer">

                <div class="yasr-option-align">
                    <input type="radio" name="yasr_general_options[auto_insert_align]" value="left"
                           class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_align'] === 'left') {
                        echo " checked=\"checked\" ";
                    } ?> >
                    <span class="yasr-text-options-size">
                        <?php _e('Left', 'yet-another-stars-rating') ?>
                    </span>
                </div>

                <div class="yasr-option-align">
                    <input type="radio" name="yasr_general_options[auto_insert_align]" value="center"
                           class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_align'] === 'center') {
                        echo " checked=\"checked\" ";
                    } ?> >
                    <span class="yasr-text-options-size">
                        <?php _e('Center', 'yet-another-stars-rating') ?>
                    </span>
                </div>

                <div class="yasr-option-align">
                    <input type="radio" name="yasr_general_options[auto_insert_align]" value="right"
                           class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_align'] === 'right') {
                        echo " checked=\"checked\" ";
                    } ?> >
                    <span class="yasr-text-options-size">
                        <?php _e('Right', 'yet-another-stars-rating') ?>
                    </span>
                </div>
            </div>
        </div>

        <p>&nbsp;</p>

        <div>
            <strong><?php _e('Size', 'yet-another-stars-rating'); ?></strong>

                <div class="yasr-indented-answer">

                    <div class="yasr-option-size">
                        <input type="radio" name="yasr_general_options[auto_insert_size]" value="small"
                               class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_size'] === 'small') {
                            echo " checked=\"checked\" ";
                        } ?> >
                        <img src="<?php echo YASR_IMG_DIR . "yasr-stars-small.png" ?>" class="yasr-img-option-size"><span
                                class="yasr-text-options-size"><?php _e('Small', 'yet-another-stars-rating') ?></span>
                    </div>

                    <div class="yasr-option-size">
                        <input type="radio" name="yasr_general_options[auto_insert_size]" value="medium"
                               class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_size'] === 'medium') {
                            echo " checked=\"checked\" ";
                        } ?> >
                        <img src="<?php echo YASR_IMG_DIR . "yasr-stars-medium.png" ?>" class="yasr-img-option-size"><span
                                class="yasr-text-options-size"><?php _e('Medium', 'yet-another-stars-rating') ?></span>
                    </div>

                    <div class="yasr-option-size">
                        <input type="radio" name="yasr_general_options[auto_insert_size]" value="large"
                               class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_size'] === 'large') {
                            echo " checked=\"checked\" ";
                        } ?> >
                        <img src="<?php echo YASR_IMG_DIR . "yasr-stars-large.png" ?>" class="yasr-img-option-size"><span
                                class="yasr-text-options-size"><?php _e('Large', 'yet-another-stars-rating') ?></span>
                    </div>
                </div>
        </div>

        <p>&nbsp;</p>
        <strong><?php _e('Exclude Pages?', 'yet-another-stars-rating'); ?></strong>

        <div class="yasr-indented-answer">
            <input type="radio" name="yasr_general_options[auto_insert_exclude_pages]" value="yes"
                   class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_exclude_pages'] === 'yes') {
                echo " checked=\"checked\" ";
            } ?> >
            <?php _e('Yes', 'yet-another-stars-rating'); ?>

            &nbsp;&nbsp;&nbsp;

            <input type="radio" name="yasr_general_options[auto_insert_exclude_pages]" value="no"
                   class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_exclude_pages'] === 'no') {
                echo " checked=\"checked\" ";
            } ?> >
            <?php _e('No', 'yet-another-stars-rating'); ?>
            <br/>

            <p>&nbsp;</p>

        </div>

        <?php

        $custom_post_types = yasr_get_custom_post_type('bool');

        if ($custom_post_types) { ?>
            <strong><?php _e('Use only in custom post types?', 'yet-another-stars-rating'); ?></strong>
            <div class="yasr-indented-answer">
                <input type="radio" name="yasr_general_options[auto_insert_custom_post_only]" value="yes"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_custom_post_only'] === 'yes') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('Yes', 'yet-another-stars-rating'); ?>

                &nbsp;&nbsp;&nbsp;

                <input type="radio" name="yasr_general_options[auto_insert_custom_post_only]" value="no"
                       class="yasr-auto-insert-options-class" <?php if ($option['auto_insert_custom_post_only'] === 'no') {
                    echo " checked=\"checked\" ";
                } ?> >
                <?php _e('No', 'yet-another-stars-rating'); ?>

                <p>

                    <?php _e("You see this because you use custom post types.", 'yet-another-stars-rating'); ?>
                    <br/>
                    <?php _e("If you want to use auto insert only in custom post types, choose Yes", 'yet-another-stars-rating'); ?>

                <p>&nbsp;</p>

            </div>

            <?php
        }

        else {
            ?>
            <input type="hidden" name="yasr_general_options[auto_insert_custom_post_only]" value="no" ?>
            <?php
        }

        ?>

        <?php
            submit_button(YASR_SAVE_All_SETTINGS_TEXT);
        ?>

    </div>

    <hr />

    <?php

} //End yasr_auto_insert_callback

function yasr_custom_text_callback($option) {

    $text_before_overall        = $option['text_before_overall'];
    $text_before_visitor_rating = $option['text_before_visitor_rating'];
    $text_after_visitor_rating  = $option['text_after_visitor_rating'];
    $custom_text_user_votes     = $option['custom_text_user_voted'];
    $custom_text_must_sign_in   = $option['custom_text_must_sign_in'];

    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_general_options[text_before_stars]" class="yasr-onoffswitch-checkbox"
               id="yasr-general-options-text-before-stars-switch" <?php if ($option['text_before_stars'] == 1) {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-general-options-text-before-stars-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/> <br/>

    <input type='text' name='yasr_general_options[text_before_overall]'
           id="yasr-general-options-custom-text-before-overall"
           class='yasr-general-options-text-before' <?php printf('value="%s"', $text_before_overall); ?>
           maxlength="40"/>
    <?php _e('Custom text to display before Overall Rating', 'yet-another-stars-rating') ?>

    <br/> <br/> <br/>

    <input type='text' name='yasr_general_options[text_before_visitor_rating]'
           id="yasr-general-options-custom-text-before-visitor"
           class='yasr-general-options-text-before' <?php printf('value="%s"', $text_before_visitor_rating); ?>
           maxlength="80"/>
    <?php _e('Custom text to display BEFORE Visitor Rating', 'yet-another-stars-rating') ?>

    <br/> <br/>

    <input type='text' name='yasr_general_options[text_after_visitor_rating]'
           id="yasr-general-options-custom-text-after-visitor"
           class='yasr-general-options-text-before' <?php printf('value="%s"', $text_after_visitor_rating); ?>
           maxlength="80"/>
    <?php _e('Custom text to display AFTER Visitor Rating', 'yet-another-stars-rating') ?>

    <br/> <br/> <br/>

    <input type='text' name='yasr_general_options[custom_text_must_sign_in]'
           id="yasr-general-options-custom-text-must-sign-in"
           class='yasr-general-options-text-before' <?php printf('value="%s"', $custom_text_must_sign_in); ?>
           maxlength="60"/>
    <?php _e('Custom text to display when login is required to vote', 'yet-another-stars-rating') ?>

    <br/> <br/>

    <input type='text' name='yasr_general_options[custom_text_user_voted]'
           id="yasr-general-options-custom-text-already-rated"
           class='yasr-general-options-text-before' <?php printf('value="%s"', $custom_text_user_votes); ?>
           maxlength="60"/>
    <?php _e('Custom text to display when a non logged user has already rated', 'yet-another-stars-rating') ?>


    <br/> <br/>

    <a href="#" id="yasr-doc-custom-text-link"><?php _e('Help', 'yet-another-stars-rating'); ?></a>

    <div id="yasr-doc-custom-text-div" class="yasr-help-box-settings">

        <?php
        $string_custom_overall = sprintf(__('In the first field you can use %s pattern to show the overall rating.',
            'yet-another-stars-rating'), '<strong>%overall_rating%</strong>');

        $string_custom_visitor = sprintf(__('In the Second and Third fields you can use %s pattern to show the 
        total count, and %s pattern to show the average', 'yet-another-stars-rating'),
            '<strong>%total_count%</strong>', '<strong>%average%</strong>');

        echo $string_custom_overall;
        echo '<br /><br/>';
        echo $string_custom_visitor;
        echo '<br /><br/>';

        _e('You can use these html tags:', 'yet-another-stars-rating');
        echo ' <strong>' . esc_html('<strong>, <p>') . '.</strong>';

        ?>

    </div>

    <p>&nbsp;</p>

    <?php
        submit_button(YASR_SAVE_All_SETTINGS_TEXT);
        echo '<hr />';

}

function yasr_show_overall_in_loop_callback($option) {

    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_general_options[show_overall_in_loop]" class="yasr-onoffswitch-checkbox"
               id="yasr-show-overall-in-loop-switch" <?php if ($option['show_overall_in_loop'] === 'enabled') {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-show-overall-in-loop-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/> <br/>

    <?php _e('If you enable this, "Overall Rating" will be showed not only in the single article or page, but also in pages like Home Page, category pages or archives.', 'yet-another-stars-rating') ?>

    <p>&nbsp;</p>

    <?php

}

function yasr_show_visitor_votes_in_loop_callback($option) {

    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_general_options[show_visitor_votes_in_loop]" class="yasr-onoffswitch-checkbox"
               id="yasr-show-visitor-votes-in-loop-switch" <?php if ($option['show_visitor_votes_in_loop'] === 'enabled') {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-show-visitor-votes-in-loop-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/> <br/>

    <?php _e('If you enable this, "Visitor Votes" will be showed not only in the single article or page, but also in pages like Home Page, category pages or archives.', 'yet-another-stars-rating') ?>

    <p>&nbsp;</p>

    <?php
        submit_button(YASR_SAVE_All_SETTINGS_TEXT);
    ?>

    <br/>

    <hr>

    <?php

}

function yasr_visitors_stats_callback($option) {

    ?>

    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_general_options[visitors_stats]" class="yasr-onoffswitch-checkbox"
               id="yasr-general-options-visitors-stats-switch" <?php if ($option['visitors_stats'] === 'yes') {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-general-options-visitors-stats-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/> <br/>

    <?php
        _e('Enable or disable the chart bar icon (and tooltip hover it) near the yasr_visitor_votes shortcode.');
    ?>

    <p>&nbsp;</p>

    <a href="#" id="yasr-stats-explained-link"><?php _e("Example", 'yet-another-stars-rating') ?></a>

    <div id="yasr-stats-explained" class="yasr-help-box-settings">
        <?php
        echo '<strong>';
            _e('ENABLED:', 'yet-another-stars-rating');
        echo "</strong>
        <hr />
        <img src=" . YASR_IMG_DIR . "yasr_settings_stats_enabled.png>";

        echo '<br /><br /><strong>';
            _e('DISABLED:', 'yet-another-stars-rating');
        echo "</strong>
        <hr />
        <img src=" . YASR_IMG_DIR . "yasr_settings_stats_disabled.png>";
        ?>
    </div>

    <p>&nbsp;</p>

    <hr>

    <?php

}

function yasr_allow_only_logged_in_callback($option) {

    ?>

    <input type='radio' name='yasr_general_options[allowed_user]' value='logged_only'
           class='yasr_auto_insert_loggedonly' <?php if ($option['allowed_user'] === 'logged_only') {
        echo " checked=\"checked\" ";
    } ?> />
    <?php _e('Allow only logged-in users', 'yet-another-stars-rating') ?>
    <br/>

    <input type='radio' name='yasr_general_options[allowed_user]' value='allow_anonymous'
           class='yasr_auto_insert_loggedonly' <?php if ($option['allowed_user'] === 'allow_anonymous') {
        echo " checked=\"checked\" ";
    } ?> />
    <?php _e('Allow everybody (logged in and anonymous)', 'yet-another-stars-rating') ?>
    <br/>

    <br /><br />
    <?php
        _e(
            'Select who can rate your posts for yasr_visitor_votes shortcode.',
            'yet-another-stars-rating')
    ?>

    <p>&nbsp;</p>

    <hr>

    <?php

} //End function

function yasr_enable_ip_callback($option) {

    ?>


    <div class="yasr-onoffswitch-big">
        <input type="checkbox" name="yasr_general_options[enable_ip]" class="yasr-onoffswitch-checkbox"
               id="yasr-general-options-enable-ip-switch" <?php if ($option['enable_ip'] === 'yes') {
            echo " checked='checked' ";
        } ?> >
        <label class="yasr-onoffswitch-label" for="yasr-general-options-enable-ip-switch">
            <span class="yasr-onoffswitch-inner"></span>
            <span class="yasr-onoffswitch-switch"></span>
        </label>
    </div>

    <br/>

    <?php
        $string = sprintf(
                __('Please note that to comply with the %s EU law, you %s MUST %s warn your users that you\'re storing their ip. %s
                    If in doubt, leave no.',
                   'yet-another-stars-rating'
                ),
                '<a href="https://en.wikipedia.org/wiki/General_Data_Protection_Regulation">GDPR</a>',
                '<strong>', '</strong>', '<br />'
        );
        echo $string;

    ?>

    <br/><p>&nbsp;</p>

    <?php
        submit_button(YASR_SAVE_All_SETTINGS_TEXT);
    ?>

    <hr>

    <?php

} //End function

function yasr_choose_snippet_callback($option) {

    $blogposting_organization_name = $option['blogposting_organization_name'];
    $blogposting_organization_logo = $option['blogposting_organization_logo'];

    ?>

    <strong><?php _e('Select default item type for all post or pages', 'yet-another-stars-rating'); ?></strong>

    <div class="yasr-indented-answer" id="yasr-choose-snippet-type">

        <?php

        $supported_schema_types = json_decode(YASR_SUPPORTED_SCHEMA_TYPES);

        foreach ($supported_schema_types as $type) {

            $type = trim($type);
            $type_visualized = $type;
            if($type === 'Place') {
                $type_visualized = 'LocalBuisness';
            }
            if($type === 'Other') {
                $type_visualized='BlogPosting';
            }

            ?>
            <input type="radio" name="yasr_general_options[snippet_itemtype]" value="<?php echo $type ?>"
                   class="yasr_choose_snippet" <?php if ($option['snippet_itemtype'] === $type) {
                echo " checked=\"checked\" ";
            } ?> >
            <?php
            echo(
                sprintf(
                        __('%s', 'yet-another-stars-rating'),
                        $type_visualized) .
                '<br />'
            );
        }

        ?>

        <div class="yasr-help-box-settings" id="yasr-blogPosting-additional-info" style="display:block">

            <?php _e("Add more info to your rich snippets filling these forms", "yet-another-stars-rating"); ?>

            <br/> <br/>

            <input type='text' name='yasr_general_options[blogposting_organization_name]'
                   id="yasr-general-options-blogposting-organization-name"
                   class="yasr-blogPosting-additional-info-inputs" <?php printf('value="%s"', $blogposting_organization_name); ?>
                   maxlength="180"/>
            <?php _e('Publisher name (e.g. Google)', 'yet-another-stars-rating') ?>

            <br/> <br/>

            <input type='text' name='yasr_general_options[blogposting_organization_logo]'
                   id="yasr-general-options-blogposting-organization-logo"
                   class="yasr-blogPosting-additional-info-inputs" <?php printf('value="%s"', $blogposting_organization_logo); ?>
                   maxlength="300"/>
            <?php _e('Logo Url (if empty siteicon will be used instead)', 'yet-another-stars-rating') ?>

        </div>


        <br/>

        <?php _e('You can always change it in the single post or page.', 'yet-another-stars-rating'); ?>
        <br/>
        <?php _e(
                'This will affect only the post/page where you didn\'t change manually the itemtype yet.',
            'yet-another-stars-rating'); ?>
        <br />
        <?php echo(sprintf(__('Please keep in mind that since since September, 16, 2019 blog posting item type will 
        no show stars in SERP anymore. %sHere%s the announcment by Google.',
            'yet-another-stars-rating'),
            '<a href="https://webmasters.googleblog.com/2019/09/making-review-rich-results-more-helpful.html">',
            '</a>'));
        ?>

        <p>&nbsp;</p>

    </div>


    <p>&nbsp;</p>

    <?php

} //End function yasr_choose_snippet_callback

function yasr_general_options_sanitize($option) {

    //Array to return
    $output = array();

    $tidy_installed = false;

    if (extension_loaded('tidy')) {
        $tidy_release_date = strtotime(tidy_get_release());
        $tidy_working_release_date = strtotime('2017/11/25');

        if ($tidy_release_date >= $tidy_working_release_date) {
            $tidy_installed = true;
        }
    }

    // Loop through each of the incoming options
    foreach ($option as $key => $value) {
        // Check to see if the current option has a value. If so, process it.
        if (isset($option[$key])) {

            //Tags are not allowed for any fields
            $allowed_tags = '';

            //except these ones
            if ($key == 'text_before_overall' || $key == 'text_before_visitor_rating' ||
                $key == 'text_after_visitor_rating' || $key == 'custom_text_must_sign_in' ||
                $key == 'custom_text_user_voted') {

                $allowed_tags = '<strong><p>';

                // handle quoted strings and allow some tags
                $output[$key] = strip_tags(stripslashes($option[$key]), $allowed_tags);

                //if tidy extension is enabled, fix errors in html
                if ($tidy_installed === true) {
                    $tidy         = new Tidy();
                    $output[$key] = $tidy->repairString($output[$key], array('show-body-only' => true));
                }

            }
            else {
                // handle quoted strings and allow no tags
                $output[$key] = strip_tags(stripslashes($option[$key]), $allowed_tags);
            }

            //Always use esc_html
            $output[$key] = esc_html($output[$key]);

            if ($key == 'blogposting_organization_logo') {
                //if is not a valid url get_site_icon_url instead
                if (filter_var($value, FILTER_VALIDATE_URL) === false) {
                    $output[$key] = get_site_icon_url();
                }
            }

        } // end if

    } // end foreach

    //if in array doesn't exists [auto_insert_enabled] key, create it and set to 0
    if (!array_key_exists('auto_insert_enabled', $output)) {
        $output['auto_insert_enabled'] = 0;
    }
    //if exists value must be 1
    else {
        $output['auto_insert_enabled'] = 1;
    }

    //Same as above but for [show_overall_in_loop] key
    if (!array_key_exists('show_overall_in_loop', $output)) {
        $output['show_overall_in_loop'] = 'disabled';
    }
    //if exists must be string 'enabled'
    else {
        $output['show_overall_in_loop'] = 'enabled';
    }

    //Same as above but for [show_visitor_votes_in_loop] key
    if (!array_key_exists('show_visitor_votes_in_loop', $output)) {
        $output['show_visitor_votes_in_loop'] = 'disabled';
    }
    //if exists must be string 'enabled'
    else {
        $output['show_visitor_votes_in_loop'] = 'enabled';
    }

    //Same as above but for text_before_stars key
    if (!array_key_exists('text_before_stars', $output)) {
        $output['text_before_stars'] = 0;
    }
    //if exists must be 1
    else {
        $output['text_before_stars'] = 1;
    }

    //Same as above but for visitors_stats key
    if (!array_key_exists('visitors_stats', $output)) {
        $output['visitors_stats'] = 'no';
    }
    //if exists must be string 'yes'
    else {
        $output['visitors_stats'] = 'yes';
    }

    //Same as above but for enable_ip key
    if (!array_key_exists('enable_ip', $output)) {
        $output['enable_ip'] = 'no';
    }
    //if exists must be string 'yes'
    else {
        $output['enable_ip'] = 'yes';
    }

    return $output;

}


/************ End Yasr General Settings ************/


//include multiset functions
include(YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-functions-multiset.php');

//include style functions
include(YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-functions-style.php');

//Misc
include(YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-functions-misc.php');

//load functions migration
include (YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-migration-functions.php');


?>
