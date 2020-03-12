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

if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.', 'yet-another-stars-rating'));
}

$n_multi_set = null; //Avoid undefined variable when printed outside multiset tab

?>

<div class="wrap">

    <h2>Yet Another Stars Rating: <?php _e("Settings", 'yet-another-stars-rating'); ?></h2>

    <?php

    if (isset($_GET['tab'])) {
        $active_tab = $_GET['tab'];
    } else {
        $active_tab = 'general_settings';
    }

    //Do the settings tab
    yasr_settings_tabs($active_tab);

    if ($active_tab == 'general_settings') {
        ?>

        <div class="yasr-settingsdiv">
            <form action="options.php" method="post" id="yasr_settings_form">
                <?php
                settings_fields('yasr_general_options_group');
                do_settings_sections('yasr_general_settings_tab');
                submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                ?>
            </form>
        </div>

        <?php
            yasr_right_settings_panel();
        ?>

        <div class="yasr-space-settings-div">
        </div>

        <?php

    } //End if tab 'general_settings'


    if ($active_tab === 'manage_multi') {
        include (YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-functions-multiset-page.php');
        yasr_right_settings_panel();
    } //End if ($active_tab=='manage_multi')


    if ($active_tab === 'style_options') {
        ?>
        <?php do_action('yasr_add_content_top_style_options_tab', $active_tab); ?>

        <div class="yasr-settingsdiv">
            <form action="options.php" method="post" enctype='multipart/form-data' id="yasr_settings_form">
                <?php
                settings_fields('yasr_style_options_group');
                do_settings_sections('yasr_style_tab');
                submit_button(YASR_SAVE_All_SETTINGS_TEXT);
                ?>
            </form>
        </div>

        <?php
            yasr_right_settings_panel();
        ?>

        <div class="yasr-space-settings-div">
        </div>

        <?php do_action('yasr_add_content_bottom_style_options_tab', $active_tab);

    } //End tab style

    if ($active_tab === 'migration_tools') {
        ?>
        <div class="yasr-settingsdiv">
            <?php
                //include migration functions
                include(YASR_ABSOLUTE_PATH . '/lib/admin/settings/yasr-settings-migration-page.php');
            ?>
            <div class="yasr-space-settings-div">
            </div>
        </div>

        <?php
            yasr_right_settings_panel();
        ?>

        <div class="yasr-space-settings-div">
        </div>

        <?php

    } //End tab migration

    do_action('yasr_settings_check_active_tab', $active_tab);

    yasr_right_settings_panel('bottom');

    ?>

    <!--End div wrap-->
</div>


<script type="text/javascript">
    jQuery(document).ready(function () {
        var activeTab = <?php echo(json_encode("$active_tab")); ?>;
        var nMultiSet = <?php echo(json_encode("$n_multi_set")); ?> ;//Null in php is different from javascript NULL
        var autoInsertEnabled = <?php echo(json_encode(YASR_AUTO_INSERT_ENABLED)); ?>;
        var textBeforeStars = <?php echo(json_encode(YASR_TEXT_BEFORE_STARS)); ?>;

        YasrSettingsPage(activeTab, nMultiSet, autoInsertEnabled, textBeforeStars);

    }); //End jquery document ready
</script>
