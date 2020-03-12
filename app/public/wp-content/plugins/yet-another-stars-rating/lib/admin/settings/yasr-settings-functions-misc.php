<?php

/**Drow settings tab*/
function yasr_settings_tabs( $active_tab )
{
    ?>

    <h2 class="nav-tab-wrapper yasr-no-underline">

        <a href="?page=yasr_settings_page&tab=general_settings"
           class="nav-tab <?php 
    if ( $active_tab === 'general_settings' ) {
        echo  'nav-tab-active' ;
    }
    ?>">
            <?php 
    _e( "General Settings", 'yet-another-stars-rating' );
    ?>
        </a>

        <a href="?page=yasr_settings_page&tab=manage_multi" class="nav-tab <?php 
    if ( $active_tab === 'manage_multi' ) {
        echo  'nav-tab-active' ;
    }
    ?>">
            <?php 
    _e( "Multi Sets", 'yet-another-stars-rating' );
    ?>
        </a>

        <a href="?page=yasr_settings_page&tab=style_options"
           class="nav-tab <?php 
    if ( $active_tab === 'style_options' ) {
        echo  'nav-tab-active' ;
    }
    ?>">
            <?php 
    _e( "Aspect & Styles", 'yet-another-stars-rating' );
    ?>
        </a>

        <?php 
    do_action( 'yasr_add_settings_tab', $active_tab );
    $rating_plugin_exists = new YasrSearchAndImportRatingPlugin();
    
    if ( $rating_plugin_exists->yasr_search_wppr() || $rating_plugin_exists->yasr_search_rmp() || $rating_plugin_exists->yasr_search_kksr() || $rating_plugin_exists->yasr_search_mr() ) {
        ?>
            <a href="?page=yasr_settings_page&tab=migration_tools" class="nav-tab <?php 
        if ( $active_tab === 'migration_tools' ) {
            echo  'nav-tab-active' ;
        }
        ?>">
                <?php 
        _e( "Migration Tools", 'yet-another-stars-rating' );
        ?>
            </a>
            <?php 
    }
    
    ?>

    </h2>

    <?php 
}

function yasr_upgrade_pro_box( $position = false )
{
    
    if ( yasr_fs()->is_free_plan() ) {
        
        if ( $position && $position == "bottom" ) {
            $yasr_upgrade_class = "yasr-donatedivbottom";
        } else {
            $yasr_upgrade_class = "yasr-donatedivdx";
        }
        
        ?>

        <div class="<?php 
        echo  $yasr_upgrade_class ;
        ?>" style="display: none">

            <h2 class="yasr-donate-title" style="color: #34A7C1">
                <?php 
        _e( 'Upgrade to YASR Pro', 'yet-another-stars-rating' );
        ?>
            </h2>

            <div class="yasr-upgrade-to-pro">
                <ul>
                    <li><strong><?php 
        _e( ' User Reviews', 'yet-another-stars-rating' );
        ?></strong></li>
                    <li><strong><?php 
        _e( ' Custom Rankings', 'yet-another-stars-rating' );
        ?></strong></li>
                    <li><strong><?php 
        _e( ' 20 + ready to use themes', 'yet-another-stars-rating' );
        ?></strong></li>
                    <li><strong><?php 
        _e( ' Upload your own theme', 'yet-another-stars-rating' );
        ?></strong></li>
                    <li><strong><?php 
        _e( ' Dedicate support', 'yet-another-stars-rating' );
        ?></strong></li>
                    <li><strong><?php 
        _e( ' ...And much more!!', 'yet-another-stars-rating' );
        ?></strong></li>
                </ul>
                <a href="<?php 
        echo  yasr_fs()->get_upgrade_url() ;
        ?>">
                    <button class="button button-primary">Upgrade Now</button>
                </a>
            </div>

        </div>

        <?php 
    }

}

/*
 *   Add a box on with the resouces
 *   Since version 1.9.5
 *
*/
function yasr_resources_box( $position = false )
{
    
    if ( $position && $position == "bottom" ) {
        $yasr_metabox_class = "yasr-donatedivbottom";
    } else {
        $yasr_metabox_class = "yasr-donatedivdx";
    }
    
    $div = "<div class='{$yasr_metabox_class}' id='yasr-resources-box' style='display:none;'>";
    $text = '<div class="yasr-donate-title">Resources</div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-star-filled" style="color: #ccc"></span>
                    <a target="blank" href="https://yetanotherstarsrating.com/">' . __( 'YASR official website', 'yet-another-stars-rating' ) . '</a>
              </div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-edit" style="color: #ccc"></span>
                    <a target="blank" href="https://yetanotherstarsrating.com/docs/">' . __( 'Documentation', 'yet-another-stars-rating' ) . '</a>
              </div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-book-alt" style="color: #ccc"></span>
                    <a target="blank" href="https://yetanotherstarsrating.com/docs/faq/">' . __( 'F.A.Q.', 'yet-another-stars-rating' ) . '</a>
              </div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-video-alt3" style="color: #ccc"></span>
                    <a target="blank" href="https://www.youtube.com/channel/UCU5jbO1PJsUUsCNbME9S-Zw">' . __( 'Youtube channel', 'yet-another-stars-rating' ) . '</a>
              </div>';
    $text .= '<div class="yasr-donate-single-resource">
                <span class="dashicons dashicons-smiley" style="color: #ccc"></span>
                    <a target="blank" href="https://yetanotherstarsrating.com/#yasr-pro">
                        Yasr Pro
                    </a>
              </div>';
    $div_and_text = $div . $text . '</div>';
    echo  $div_and_text ;
}

/** Add a box on the right for asking to rate 5 stars on Wordpress.org
 *   Since version 0.9.0
 */
function yasr_ask_rating( $position = false )
{
    
    if ( $position && $position == "bottom" ) {
        $yasr_metabox_class = "yasr-donatedivbottom";
    } else {
        $yasr_metabox_class = "yasr-donatedivdx";
    }
    
    $div = "<div class='{$yasr_metabox_class}' id='yasr-ask-five-stars' style='display:none;'>";
    $text = '<div class="yasr-donate-title">' . __( 'Can I ask your help?', 'yet-another-stars-rating' ) . '</div>';
    $text .= '<div style="font-size: 32px; color: #F1CB32; text-align:center; margin-bottom: 20px; margin-top: -5px;">
                <span class="dashicons dashicons-star-filled" style="font-size: 26px;"></span>
                <span class="dashicons dashicons-star-filled" style="font-size: 26px;"></span>
                <span class="dashicons dashicons-star-filled" style="font-size: 26px;"></span>
                <span class="dashicons dashicons-star-filled" style="font-size: 26px;"></span>
                <span class="dashicons dashicons-star-filled" style="font-size: 26px;"></span>
            </div>';
    $text .= __( 'Please rate YASR 5 stars on', 'yet-another-stars-rating' );
    $text .= ' <a href="https://wordpress.org/support/view/plugin-reviews/yet-another-stars-rating?filter=5">
        WordPress.org.</a><br />';
    $text .= __( ' It will require just 1 min but it\'s a HUGE help for me. Thank you.', 'yet-another-stars-rating' );
    $text .= "<br /><br />";
    $text .= "<em>> Dario Curvino</em>";
    $div_and_text = $div . $text . '</div>';
    echo  $div_and_text ;
}

/****
Yasr Right settings panel, since version 1.9.5
 ****/
function yasr_right_settings_panel( $position = false )
{
    do_action( 'yasr_right_settings_panel_box', $position );
    yasr_upgrade_pro_box( $position );
    yasr_resources_box( $position );
    yasr_ask_rating( $position );
}

/** Change default admin footer on yasr settings pages
 *       $text is the default wordpress text
 *        Since 0.8.9
 */
add_filter( 'admin_footer_text', 'yasr_custom_admin_footer' );
function yasr_custom_admin_footer( $text )
{
    
    if ( isset( $_GET['page'] ) ) {
        $yasr_page = $_GET['page'];
        
        if ( $yasr_page == 'yasr_settings_page' ) {
            $custom_text = ' | <i>';
            $custom_text .= sprintf(
                __( 'Thank you for using <a href="%s" target="_blank">Yet Another Stars Rating</a>.
                        Please <a href="%s" target="_blank">rate it</a> 5 stars on <a href="%s" target="_blank">WordPress.org</a>', 'yet-another-stars-rating' ),
                'https://yetanotherstarsrating.com',
                'https://wordpress.org/support/view/plugin-reviews/yet-another-stars-rating?filter=5',
                'https://wordpress.org/support/view/plugin-reviews/yet-another-stars-rating?filter=5'
            );
            $custom_text .= '</i>';
            return $text . $custom_text;
        } else {
            return $text;
        }
    
    } else {
        return $text;
    }

}
