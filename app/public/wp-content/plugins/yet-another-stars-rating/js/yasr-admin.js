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


/*** Constant used by yasr

 yasrCommonDataAdmin (loaderHtml)

 ***/

/****** Yasr Metabox overall rating, used for classic editor ******/

function yasrPrintMetaBoxOverall(overallRating) {

    //Convert string to number
    overallRating = parseFloat(overallRating);

    raterJs({
        starSize: 32,
        step: 0.1,
        showToolTip: false,
        rating: overallRating,
        readOnly: false,
        element: document.getElementById("yasr-rater-overall"),
        rateCallback: function rateCallback(rating, done) {

            rating = rating.toFixed(1);
            rating = parseFloat(rating);

            //update hidden field
            document.getElementById('yasr-overall-rating-value').value = rating;

            this.setRating(rating);

            yasrOverallString = 'You\'ve rated';

            document.getElementById('yasr_rateit_overall_value').textContent = yasrOverallString + ' ' + rating;

            done();
        }
    });

}

/****** End Yasr Metabox overall rating ******/


/****** Yasr Metabox Multiple Rating ******/

function yasrAdminMultiSet(nMultiSet, postid, setId) {

    nMultiSet = parseInt(nMultiSet);

    if (nMultiSet === 1) {

        yasrPrintAdminMultiSet(setId, postid, false);

    } else {

        jQuery('#yasr-button-select-set').on("click", function () {

            //get the multi data
            //overwrite setID
            var setId = jQuery('#select_set').val();

            jQuery("#yasr-loader-select-multi-set").show();

            yasrPrintAdminMultiSet(setId, postid, true);

            return false; // prevent default click action from happening!

        });

    }

}

//print the table
function yasrPrintAdminMultiSet(setId, postid, moreThanOneMultiSet) {

    var data_id = {
        action: 'yasr_send_id_nameset',
        set_id: setId,
        post_id: postid
    };

    jQuery.post(ajaxurl, data_id, function (response) {

        //Hide the loader near the select only if more multiset are used
        if (moreThanOneMultiSet === true) {
            document.getElementById('yasr-loader-select-multi-set').style.display = 'none';
        }

        var yasrMultiSetValue = JSON.parse(response);

        var content = '';

        for (var i = 0; i < yasrMultiSetValue.length; i++) {

            var valueName = yasrMultiSetValue[i]['name'];
            var valueRating = yasrMultiSetValue[i]['average_rating'];
            var valueID = yasrMultiSetValue[i]['id'];

            content += '<tr>';
            content += '<td>' + valueName + '</td>';
            content += '<td><div class="yasr-multiset-admin" id="yasr-multiset-admin-' + valueID + '" data-rating="'
                            + valueRating + '" data-multi-idfield="' + valueID + '"></div>';
            content += '<span id="yasr-loader-multi-set-field-' + valueID + '" style="display: none">';
            content += '<img src="' + yasrCommonDataAdmin.loaderHtml + '"></span>';
            content += '</span>';
            content += '</td>';
            content += '</tr>';

            var table = document.getElementById('yasr-table-multi-set-admin');

            table.innerHTML = content;

        }

        //Show the text "Choose a vote"
        document.getElementById('yasr-multi-set-admin-choose-text').style.display = 'block';

        //Set rater for divs
        yasrSetRaterAdminMulti(setId);

        //Show shortcode
        document.getElementById('yasr-multi-set-admin-explain').style.display = 'block';

        document.getElementById('yasr-multi-set-admin-explain-with-id-readonly').innerHTML = '<strong>[yasr_multiset setid=' + setId + ']</strong>';
        document.getElementById('yasr-multi-set-admin-explain-with-id-visitor').innerHTML = '<strong>[yasr_visitor_multiset setid=' + setId + ']</strong>';

    });

    return false; // prevent default click action from happening!

}

//Rater for multiset
function yasrSetRaterAdminMulti(setId) {

    //update hidden field
    document.getElementById('yasr-multiset-id').value = setId;

    var yasrMultiSetAdmin = document.getElementsByClassName('yasr-multiset-admin');

    //an array with all the ratings objects
    var ratingArray = [];
    var ratingValue = false;

    for (var i = 0; i < yasrMultiSetAdmin.length; i++) {

        (function (i) {

            var htmlId = yasrMultiSetAdmin.item(i).id;
            var elem = document.getElementById(htmlId);

            raterJs({
                starSize: 32,
                step: 0.5,
                showToolTip: false,
                readOnly: false,
                element: elem,

                rateCallback: function rateCallback(rating, done) {

                    rating = rating.toFixed(1);
                    //Be sure is a number and not a string
                    rating = parseFloat(rating);
                    this.setRating(rating); //Set the rating

                    var setIdField = parseInt(elem.getAttribute('data-multi-idfield'));

                    ratingObject = {
                        field: setIdField,
                        rating: rating
                    };

                    //creating rating array
                    ratingArray.push(ratingObject);

                    ratingValue = JSON.stringify(ratingArray);

                    //update hidden field
                    document.getElementById('yasr-multiset-author-votes').value = ratingValue;

                    done();
                }

            });

        })(i);

    } //End for

}//End function

/****** End Yasr Metabox Multple Rating  ******/


/****** Yasr Settings Page ******/

function YasrSettingsPage(activeTab, nMultiSet, autoInsertEnabled, textBeforeStars) {

    //-------------------General Settings Code---------------------

    if (activeTab === 'general_settings') {

        if (autoInsertEnabled == 0) {
            jQuery('.yasr-auto-insert-options-class').prop('disabled', true);
        }

        //First Div, for auto insert
        jQuery('#yasr_auto_insert_switch').change(function () {
            if (jQuery(this).is(':checked')) {
                jQuery('.yasr-auto-insert-options-class').prop('disabled', false);
            } else {
                jQuery('.yasr-auto-insert-options-class').prop('disabled', true);
            }
        });

        //for text before stars
        if (textBeforeStars == 0) {
            jQuery('.yasr-general-options-text-before').prop('disabled', true);
        }

        jQuery('#yasr-general-options-text-before-stars-switch').change(function () {
            if (jQuery(this).is(':checked')) {

                jQuery('.yasr-general-options-text-before').prop('disabled', false);
                jQuery('#yasr-general-options-custom-text-before-overall').val('Our Score');
                jQuery('#yasr-general-options-custom-text-before-visitor').val('Click to rate this post!');
                jQuery('#yasr-general-options-custom-text-after-visitor').val('[Total: %total_count%  Average: %average%]');
                jQuery('#yasr-general-options-custom-text-must-sign-in').val('You must sign in to vote');
                jQuery('#yasr-general-options-custom-text-already-rated').val('You have already voted for this article');

            } else {
                jQuery('.yasr-general-options-text-before').prop('disabled', true);
            }

        });

        jQuery('#yasr-doc-custom-text-link').on('click', function () {
            jQuery('#yasr-doc-custom-text-div').toggle('slow');
            return false;
        });

        jQuery('#yasr-stats-explained-link').on('click', function () {
            jQuery('#yasr-stats-explained').toggle('slow');
            return false;
        });

    } //End if general settings

    //--------------Multi Sets Page ------------------

    if (activeTab === 'manage_multi') {

        jQuery('#yasr-multi-set-doc-link').on('click', function () {
            jQuery('#yasr-multi-set-doc-box').toggle("slow");
        });

        jQuery('#yasr-multi-set-doc-link-hide').on('click', function () {
            jQuery('#yasr-multi-set-doc-box').toggle("slow");
        });

        if (nMultiSet == 1) {

            var counter = jQuery("#yasr-edit-form-number-elements").attr('value');

            counter++;

            jQuery("#yasr-add-field-edit-multiset").on('click', function () {

                if (counter > 9) {
                    jQuery('#yasr-element-limit').show();
                    jQuery('#yasr-add-field-edit-multiset').hide();
                    return false;
                }

                var newTextBoxDiv = jQuery(document.createElement('tr'));

                newTextBoxDiv.html('<td colspan="2">Element #' + counter + ' <input type="text" name="edit-multi-set-element-' + counter + '" value="" ></td>');

                newTextBoxDiv.appendTo("#yasr-table-form-edit-multi-set");

                counter++;

            });


        } //End if ($n_multi_set == 1)

        if (nMultiSet > 1) {

            //If more then 1 set is used...
            jQuery('#yasr-button-select-set-edit-form').on("click", function () {

                var data = {
                    action: 'yasr_get_multi_set',
                    set_id: jQuery('#yasr_select_edit_set').val()
                }

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#yasr-multi-set-response').show();
                    jQuery('#yasr-multi-set-response').html(response);
                });

                return false; // prevent default click action from happening!

            });

            jQuery(document).ajaxComplete(function () {

                var counter = jQuery("#yasr-edit-form-number-elements").attr('value');

                counter++;

                jQuery("#yasr-add-field-edit-multiset").on('click', function () {

                    if (counter > 9) {
                        jQuery('#yasr-element-limit').show();
                        jQuery('#yasr-add-field-edit-multiset').hide();
                        return false;
                    }

                    var newTextBoxDiv = jQuery(document.createElement('tr'));

                    newTextBoxDiv.html('<td colspan="2">Element #' + counter + ' <input type="text" name="edit-multi-set-element-' + counter + '" value="" ></td>');

                    newTextBoxDiv.appendTo("#yasr-table-form-edit-multi-set");

                    counter++;

                });

            });

        } //End if ($n_multi_set > 1)


    } //end if active_tab=='manage_multi'


    if (activeTab === 'style_options') {

        jQuery('#yasr-color-scheme-preview-link').on('click', function () {
            jQuery('#yasr-color-scheme-preview').toggle('slow');
            return false; // prevent default click action from happening!
        });

    }


}


/****** Migration tools page ******/
document.addEventListener('DOMContentLoaded', function(event) {

    jQuery('#yasr-import-ratemypost-submit').on('click', function() {

        //show loader on click
        document.getElementById('yasr-import-ratemypost-answer').innerHTML = '<img src="'
            +yasrCommonDataAdmin.loaderHtml+'"</img>';

        var nonce = document.getElementById('yasr-import-rmp-nonce').value;

        var data = {
            action: 'yasr_import_ratemypost',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            response = JSON.parse(response);
            document.getElementById('yasr-import-ratemypost-answer').innerHTML = response;
        });

    });

    jQuery('#yasr-import-wppr-submit').on('click', function() {

        //show loader on click
        document.getElementById('yasr-import-wppr-answer').innerHTML = '<img src="'
            +yasrCommonDataAdmin.loaderHtml+'"</img>';

        var nonce = document.getElementById('yasr-import-wppr-nonce').value;

        var data = {
            action: 'yasr_import_wppr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-wppr-answer').innerHTML = response;
        });

    });

    jQuery('#yasr-import-kksr-submit').on('click', function() {

        //show loader on click
        document.getElementById('yasr-import-kksr-answer').innerHTML = '<img src="'
            +yasrCommonDataAdmin.loaderHtml+'"</img>';

        var nonce = document.getElementById('yasr-import-kksr-nonce').value;

        var data = {
            action: 'yasr_import_kksr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-kksr-answer').innerHTML = response;
        });

    });

    //import multi rating
    jQuery('#yasr-import-mr-submit').on('click', function() {

        //show loader on click
        document.getElementById('yasr-import-mr-answer').innerHTML = '<img src="'
            +yasrCommonDataAdmin.loaderHtml+'"</img>';

        var nonce = document.getElementById('yasr-import-mr-nonce').value;

        var data = {
            action: 'yasr_import_mr',
            nonce: nonce
        };

        jQuery.post(ajaxurl, data, function (response) {
            //response = JSON.parse(response);
            document.getElementById('yasr-import-mr-answer').innerHTML = response;
        });

    });

});

/****** End Yasr Settings Page ******/


/****** Yasr Ajax Page ******/


// When click on chart hide tab-main and show tab-charts

function yasrShortcodeCreator(nMultiSet) {

    // When click on main tab hide tab-main and show tab-charts
    jQuery('#yasr-link-tab-main').on("click", function () {

        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-link-tab-main').addClass('nav-tab-active');

        jQuery('.yasr-content-tab-tinymce').hide();
        jQuery('#yasr-content-tab-main').show();

    });

    jQuery('#yasr-link-tab-charts').on("click", function () {

        jQuery('.yasr-nav-tab').removeClass('nav-tab-active');
        jQuery('#yasr-link-tab-charts').addClass('nav-tab-active');

        jQuery('.yasr-content-tab-tinymce').hide();
        jQuery('#yasr-content-tab-charts').show();

    });

    // Add shortcode for overall rating
    jQuery('#yasr-overall').on("click", function () {
        jQuery('#yasr-overall-choose-size').toggle('slow');
    });

    jQuery('#yasr-overall-insert-small').on("click", function () {
        var shortcode = '[yasr_overall_rating size="small"]';

        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');

    });

    jQuery('#yasr-overall-insert-medium').on("click", function () {
        var shortcode = '[yasr_overall_rating size="medium"]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    jQuery('#yasr-overall-insert-large').on("click", function () {
        var shortcode = '[yasr_overall_rating size="large"]';

        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    //Add shortcode for visitors rating
    jQuery('#yasr-visitor-votes').on("click", function () {
        jQuery('#yasr-visitor-choose-size').toggle('slow');
    });

    jQuery('#yasr-visitor-insert-small').on("click", function () {
        var shortcode = '[yasr_visitor_votes size="small"]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {
            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    jQuery('#yasr-visitor-insert-medium').on("click", function () {
        var shortcode = '[yasr_visitor_votes size="medium"]';

        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    jQuery('#yasr-visitor-insert-large').on("click", function () {
        var shortcode = '[yasr_visitor_votes size="large"]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    if (nMultiSet > 1) {

        //Add shortcode for multiple set
        jQuery('#yasr-insert-multiset-select').on("click", function () {
            var setType = jQuery("input:radio[name=yasr_tinymce_pick_set]:checked").val();
            var visitorSet = jQuery("#yasr-allow-vote-multiset").is(':checked');
            var showAverage = jQuery("#yasr-hide-average-multiset").is(':checked');

            if (!visitorSet) {

                var shortcode = '[yasr_visitor_multiset setid=';

            } else {

                var shortcode = '[yasr_multiset setid=';

            }

            shortcode += setType;

            if (showAverage) {

                shortcode += ' show_average=\'no\'';

            }


            shortcode += ']';

            // inserts the shortcode into the active editor
            if (tinyMCE.activeEditor == null) {

                //this is for tinymce used in text mode
                jQuery("#content").append(shortcode);

            } else {

                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

            }

            // close
            tb_remove();
            //jQuery('#yasr-tinypopup-form').dialog('close');
        });

    } //End if

    else if (nMultiSet == 1) {

        //Add shortcode for single set (if only 1 are found)
        jQuery('#yasr-single-set').on("click", function () {
            var setType = jQuery('#yasr-single-set').val();
            var showAverage = jQuery("#yasr-hide-average-multiset").is(':checked');

            var visitorSet = jQuery("#yasr-allow-vote-multiset").is(':checked');

            if (!visitorSet) {

                var shortcode = '[yasr_visitor_multiset setid=';

            } else {

                var shortcode = '[yasr_multiset setid=';

            }

            shortcode += setType;

            if (showAverage) {

                shortcode += ' show_average=\'no\'';

            }

            shortcode += ']';

            // inserts the shortcode into the active editor
            if (tinyMCE.activeEditor == null) {

                //this is for tinymce used in text mode
                jQuery("#content").append(shortcode);

            } else {

                // inserts the shortcode into the active editor
                tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

            }

            // close
            tb_remove();
            //jQuery('#yasr-tinypopup-form').dialog('close');
        });

    } //End elseif

    // Add shortcode for top 10 by overall ratings
    jQuery('#yasr-top-10-overall-rating').on("click", function () {
        var shortcode = '[yasr_top_ten_highest_rated]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    // Add shortcode for 10 highest most rated
    jQuery('#yasr-10-highest-most-rated').on("click", function () {
        var shortcode = '[yasr_most_or_highest_rated_posts]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    // Add shortcode for top 5 active reviewer
    jQuery('#yasr-5-active-reviewers').on("click", function () {
        var shortcode = '[yasr_top_5_reviewers]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

    // Add shortcode for top 10 active users
    jQuery('#yasr-top-10-active-users').on("click", function () {
        var shortcode = '[yasr_top_ten_active_users]';

        // inserts the shortcode into the active editor
        if (tinyMCE.activeEditor == null) {

            //this is for tinymce used in text mode
            jQuery("#content").append(shortcode);

        } else {

            // inserts the shortcode into the active editor
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);

        }

        // close
        tb_remove();
        //jQuery('#yasr-tinypopup-form').dialog('close');
    });

} //End function

/****** End YAsr Ajax page ******/

/****** Yasr db functions ******/

//Vote log
jQuery(document).ready(function () {

    //Log
    jQuery('.yasr-log-pagenum').on('click', function () {

        jQuery('#yasr-loader-log-metabox').show();

        var data = {
            action: 'yasr_change_log_page',
            pagenum: jQuery(this).val(),
            totalpages: jQuery('#yasr-log-total-pages').data('yasr-log-total-pages')

        };

        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#yasr-loader-log-metabox').hide();
            jQuery('#yasr-log-container').html(response);
        });

    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {

        //check if the ajax call is done by yasr with action yasr_change_log_page
        var isYasrAjaxCall = settings.data.search("action=yasr_change_log_page");
        if (isYasrAjaxCall !== -1) {

            jQuery('.yasr-log-pagenum').on('click', function () {
                jQuery('#yasr-loader-log-metabox').show();

                var data = {
                    action: 'yasr_change_log_page',
                    pagenum: jQuery(this).val(),
                    totalpages: jQuery('#yasr-log-total-pages').data('yasr-log-total-pages')
                };

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#yasr-log-container').html(response); //This will hide the loader gif too
                });
            });
        }

    });

});


//Vote user log
jQuery(document).ready(function () {

    //Log
    jQuery('.yasr-user-log-page-num').on('click', function () {
        jQuery('#yasr-loader-user-log-metabox').show();
        var data = {
            action: 'yasr_change_user_log_page',
            pagenum: jQuery(this).val(),
            totalpages: jQuery('#yasr-user-log-total-pages').data('yasr-log-total-pages')

        };
        jQuery.post(ajaxurl, data, function (response) {
            jQuery('#yasr-loader-log-metabox').hide();
            jQuery('#yasr-user-log-container').html(response);
        });
    });

    jQuery(document).ajaxComplete(function (event, xhr, settings) {

        //check if the ajax call is done by yasr with action yasr_change_log_page
        var isYasrAjaxCall = settings.data.search("action=yasr_change_user_log_page");
        if (isYasrAjaxCall !== -1) {

            jQuery('.yasr-user-log-page-num').on('click', function () {
                jQuery('#yasr-loader-user-log-metabox').show();

                var data = {
                    action: 'yasr_change_user_log_page',
                    pagenum: jQuery(this).val(),
                    totalpages: jQuery('#yasr-user-log-total-pages').data('yasr-log-total-pages')
                };

                jQuery.post(ajaxurl, data, function (response) {
                    jQuery('#yasr-user-log-container').html(response); //This will hide the loader gif too
                });
            });

        }

    });

});

/****** End yasr db functions ******/
