<?php
/**
 * aReview Theme Customizer
 *
 * @package aReview
 */

function areview_customize_register( $wp_customize ) {
	/**
	 * Add postMessage support for site title and description for the Theme Customizer.
	 *
	 */	
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

    //Extends the customizer with a categories dropdown control.
    class aReview_Categories_Dropdown extends WP_Customize_Control {
        public function render_content() {
            $dropdown = wp_dropdown_categories(
                array(
                    'name'              => '_customize-dropdown-categories-' . $this->id,
                    'echo'              => 0,
                    'show_option_none'  => __( '&mdash; Select &mdash;', 'areview' ),
                    'option_none_value' => '0',
                    'selected'          => $this->value(),
                )
            );
 
            $dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );
 
            printf(
                '<label class="customize-control-select"><span class="customize-control-title">%s</span> %s</label>',
                $this->label,
                $dropdown
            );
        }
    }    


	//___General___//
    $wp_customize->add_section(
        'areview_general',
        array(
            'title' => __('General', 'areview'),
            'priority' => 9,
        )
    );
	//Logo Upload
    $wp_customize->add_setting(	'site_logo', array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_logo',
            array(
               'label'          => __( 'Upload your logo', 'areview' ),
			   'type' 			=> 'image',
               'section'        => 'areview_general',
               'settings'       => 'site_logo',
               'priority' => 9,
            )
        )
    );
	//Favicon Upload
    $wp_customize->add_setting(	'site_favicon',	array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'site_favicon',
            array(
               'label'          => __( 'Upload your favicon', 'areview' ),
			   'type' 			=> 'image',
               'section'        => 'areview_general',
               'settings'       => 'site_favicon',
               'priority' => 10,
            )
        )
    );
    //Apple touch icon 144
    $wp_customize->add_setting( 'apple_touch_144', array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_144',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (144x144 pixels)', 'areview' ),
               'type'           => 'image',
               'section'        => 'areview_general',
               'settings'       => 'apple_touch_144',
               'priority'       => 11,
            )
        )
    );
    //Apple touch icon 114
    $wp_customize->add_setting( 'apple_touch_114', array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_114',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (114x114 pixels)', 'areview' ),
               'type'           => 'image',
               'section'        => 'areview_general',
               'settings'       => 'apple_touch_114',
               'priority'       => 12,
            )
        )
    );
    //Apple touch icon 72
    $wp_customize->add_setting( 'apple_touch_72', array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_72',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (72x72 pixels)', 'areview' ),
               'type'           => 'image',
               'section'        => 'areview_general',
               'settings'       => 'apple_touch_72',
               'priority'       => 13,
            )
        )
    );
    //Apple touch icon 57
    $wp_customize->add_setting( 'apple_touch_57', array(
        'default-image'     => '',
        'sanitize_callback' => 'areview_sanitize_image',
    ) );

    $wp_customize->add_control(
        new WP_Customize_Image_Control(
            $wp_customize,
            'apple_touch_57',
            array(
               'label'          => __( 'Upload your Apple Touch Icon (57x57 pixels)', 'areview' ),
               'type'           => 'image',
               'section'        => 'areview_general',
               'settings'       => 'apple_touch_57',
               'priority'       => 14,
            )
        )
    );
    //SCroller
	$wp_customize->add_setting(
		'areview_scroller',
		array(
			'sanitize_callback' => 'areview_sanitize_checkbox',
			'default' => 0,			
		)		
	);
	$wp_customize->add_control(
		'areview_scroller',
		array(
			'type' => 'checkbox',
			'label' => __('Check this box if you want to disable the custom scroller.', 'areview'),
			'section' => 'areview_general',
            'priority' => 15,			
		)
	); 
    //Home icon
    $wp_customize->add_setting(
        'areview_home_icon',
        array(
            'sanitize_callback' => 'areview_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'areview_home_icon',
        array(
            'type' => 'checkbox',
            'label' => __('Check this box to hide the Home icon from your menu', 'areview'),
            'section' => 'areview_general',
            'priority' => 16,
        )
    );
    //___Review Mode___//
    $wp_customize->add_section(
        'areview_review',
        array(
            'title' => __('Review Mode', 'areview'),
            'priority' => 9,
            'description' => __('Select the review type you will do on your website. Make sure you download the appropiate settings for your review type.', 'areview')
        )
    );
    $wp_customize->add_setting(
        'review_type',
        array(
            'default' => 'none',
            'sanitize_callback' => 'areview_sanitize_review',
        )
    );
     
    $wp_customize->add_control(
        'review_type',
        array(
            'type' => 'radio',
            'section' => 'areview_review',
            'choices' => array(
                'none' => 'None',
                'product' => 'Products',
                'movie' => 'Movies',
                'game' => 'Games',
            ),
        )
    );      
    //___Carousel___//
    $wp_customize->add_section(
        'areview_carousel',
        array(
            'title' => __('Carousel', 'areview'),
            'priority' => 10,
        )
    );
    //Display
    $wp_customize->add_setting(
        'carousel_display',
        array(
            'sanitize_callback' => 'areview_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'carousel_display',
        array(
            'type' => 'checkbox',
            'label' => __('Check this box to display the carousel (posts should have Featured Images).', 'areview'),
            'section' => 'areview_carousel',
        )
    );
    
    //Category
    $wp_customize->add_setting( 'carousel_cat', array(
        'default'        => '',
        'sanitize_callback' => 'areview_sanitize_int',
    ) );
    
    $wp_customize->add_control( new aReview_Categories_Dropdown( $wp_customize, 'carousel_cat', array(
        'label'   => __('Select which category to show in the carousel', 'areview'),
        'section' => 'areview_carousel',
        'settings'   => 'carousel_cat',
    ) ) );
    
    //Number of posts
    $wp_customize->add_setting(
        'carousel_number',
        array(
            'default' => '6',
            'sanitize_callback' => 'areview_sanitize_int',
        )
    );
        
    $wp_customize->add_control(
        'carousel_number',
        array(
            'label' => __('Enter the number of posts you want to show in the carousel.', 'areview'),
            'section' => 'areview_carousel',
            'type' => 'text',
        )
    );
    //Slideshow speed
    $wp_customize->add_setting(
        'slideshowspeed',
        array(
            'default' => '500',
            'sanitize_callback' => 'areview_sanitize_int',
        )
    );
        
    $wp_customize->add_control(
        'slideshowspeed',
        array(
            'label' => __('Enter your desired slideshow speed, in miliseconds.', 'areview'),
            'section' => 'areview_carousel',
            'type' => 'text',
        )
    ); 
    //___Single posts___//
    $wp_customize->add_section(
        'areview_singles',
        array(
            'title' => __('Single posts', 'areview'),
            'priority' => 13,
        )
    );
    //Author bio
    $wp_customize->add_setting(
        'author_bio',
        array(
            'sanitize_callback' => 'areview_sanitize_checkbox',
        )       
    );
    $wp_customize->add_control(
        'author_bio',
        array(
            'type' => 'checkbox',
            'label' => __('Check this box to display the author bio on single posts. You can add your author bio and social links on the Users screen in the Your Profile section.', 'areview'),
            'section' => 'areview_singles',
        )
    );
    //___Colors___//
    //Primary color
    $wp_customize->add_setting(
        'primary_color',
        array(
            'default'           => '#222E38',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'primary_color',
            array(
                'label' => __('Primary color', 'areview'),
                'section' => 'colors',
                'settings' => 'primary_color',
                'priority' => 12
            )
        )
    );
    //Secondary color
    $wp_customize->add_setting(
        'secondary_color',
        array(
            'default'           => '#00A0B0',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'secondary',
            array(
                'label' => __('Secondary color', 'areview'),
                'section' => 'colors',
                'settings' => 'secondary_color',
                'priority' => 13
            )
        )
    );     
    //Site title
    $wp_customize->add_setting(
        'site_title_color',
        array(
            'default'           => '#ffffff',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_title_color',
            array(
                'label' => __('Site title', 'areview'),
                'section' => 'colors',
                'settings' => 'site_title_color',
                'priority' => 14
            )
        )
    );
    //Site description
    $wp_customize->add_setting(
        'site_desc_color',
        array(
            'default'           => '#888',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'site_desc_color',
            array(
                'label' => __('Site description', 'areview'),
                'section' => 'colors',
                'settings' => 'site_desc_color',
                'priority' => 15
            )
        )
    );
    //Entry title
    $wp_customize->add_setting(
        'entry_title_color',
        array(
            'default'           => '#505050',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'entry_title_color',
            array(
                'label' => __('Entry title', 'areview'),
                'section' => 'colors',
                'settings' => 'entry_title_color',
                'priority' => 16
            )
        )
    );  
    //Body
    $wp_customize->add_setting(
        'body_text_color',
        array(
            'default'           => '#8F8F8F',
            'sanitize_callback' => 'sanitize_hex_color',
            'transport'         => 'postMessage'
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'body_text_color',
            array(
                'label' => __('Text', 'areview'),
                'section' => 'colors',
                'settings' => 'body_text_color',
                'priority' => 17
            )
        )
    );
    //Decoration bar
    $wp_customize->add_setting(
        'dec_color_1',
        array(
            'default'           => '#00A0B0',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dec_color_1',
            array(
                'label' => __('Decoration bar - color 1', 'areview'),
                'section' => 'colors',
                'settings' => 'dec_color_1',
                'priority' => 18
            )
        )
    );
    $wp_customize->add_setting(
        'dec_color_2',
        array(
            'default'           => '#4ECDC4',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dec_color_2',
            array(
                'label' => __('Decoration bar - color 2', 'areview'),
                'section' => 'colors',
                'settings' => 'dec_color_2',
                'priority' => 19
            )
        )
    );
    $wp_customize->add_setting(
        'dec_color_3',
        array(
            'default'           => '#EDC951',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dec_color_3',
            array(
                'label' => __('Decoration bar - color 3', 'areview'),
                'section' => 'colors',
                'settings' => 'dec_color_3',
                'priority' => 20
            )
        )
    );
    $wp_customize->add_setting(
        'dec_color_4',
        array(
            'default'           => '#FF6B6B',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dec_color_4',
            array(
                'label' => __('Decoration bar - color 4', 'areview'),
                'section' => 'colors',
                'settings' => 'dec_color_4',
                'priority' => 21
            )
        )
    );
    $wp_customize->add_setting(
        'dec_color_5',
        array(
            'default'           => '#C44D58',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'dec_color_5',
            array(
                'label' => __('Decoration bar - color 5', 'areview'),
                'section' => 'colors',
                'settings' => 'dec_color_5',
                'priority' => 22
            )
        )
    );
    //Affiliate button
    $wp_customize->add_setting(
        'affiliate_button',
        array(
            'default'           => '#FF6B6B',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'affiliate_button',
            array(
                'label' => __('Affiliate button', 'areview'),
                'section' => 'colors',
                'settings' => 'affiliate_button',
                'priority' => 23
            )
        )
    );
    //Movie and game tables
    $wp_customize->add_setting(
        'review_tables',
        array(
            'default'           => '#FF6B6B',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'review_tables',
            array(
                'label' => __('Movies&amp;Games tables', 'areview'),
                'section' => 'colors',
                'settings' => 'review_tables',
                'priority' => 24
            )
        )
    );
    //Carousel background
    $wp_customize->add_setting(
        'carousel_background',
        array(
            'default'           => '#151E25',
            'sanitize_callback' => 'sanitize_hex_color',
        )
    );
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            'carousel_background',
            array(
                'label' => __('Carousel background', 'areview'),
                'section' => 'colors',
                'settings' => 'carousel_background',
                'priority' => 25
            )
        )
    );                            
    //___Fonts___//
    $wp_customize->add_section(
        'areview_typography',
        array(
            'title' => __('Fonts', 'areview' ),
            'priority' => 15,
        )
    );
    $font_choices = 
        array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
        );
    
    $wp_customize->add_setting(
        'headings_fonts',
        array(
            'sanitize_callback' => 'areview_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'headings_fonts',
        array(
            'type' => 'select',
            'label' => __('Select your desired font for the headings.', 'areview'),
            'section' => 'areview_typography',
            'choices' => $font_choices
        )
    );
    
    $wp_customize->add_setting(
        'body_fonts',
        array(
            'sanitize_callback' => 'areview_sanitize_fonts',
        )
    );
    
    $wp_customize->add_control(
        'body_fonts',
        array(
            'type' => 'select',
            'label' => __('Select your desired font for the body.', 'areview'),
            'section' => 'areview_typography',
            'choices' => $font_choices
        )
    );  
}
add_action( 'customize_register', 'areview_customize_register' );

/**
 * Sanitization
 */
//Checkboxes
function areview_sanitize_checkbox( $input ) {
	if ( $input == 1 ) {
		return 1;
	} else {
		return '';
	}
}
//Integers
function areview_sanitize_int( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}
//Fonts
function areview_sanitize_fonts( $input ) {
    $valid = array(
            'Source Sans Pro:400,700,400italic,700italic' => 'Source Sans Pro',     
            'Droid Sans:400,700' => 'Droid Sans',
            'Lato:400,700,400italic,700italic' => 'Lato',
            'Arvo:400,700,400italic,700italic' => 'Arvo',
            'Lora:400,700,400italic,700italic' => 'Lora',
            'PT Sans:400,700,400italic,700italic' => 'PT Sans',
            'PT+Sans+Narrow:400,700' => 'PT Sans Narrow',
            'Arimo:400,700,400italic,700italic' => 'Arimo',
            'Ubuntu:400,700,400italic,700italic' => 'Ubuntu',
            'Bitter:400,700,400italic' => 'Bitter',
            'Droid Serif:400,700,400italic,700italic' => 'Droid Serif',
            'Open+Sans:400italic,700italic,400,700' => 'Open Sans',
            'Roboto:400,400italic,700,700italic' => 'Roboto',
            'Oswald:400,700' => 'Oswald',
            'Open Sans Condensed:700,300italic,300' => 'Open Sans Condensed',
            'Roboto Condensed:400italic,700italic,400,700' => 'Roboto Condensed',
            'Raleway:400,700' => 'Raleway',
            'Roboto Slab:400,700' => 'Roboto Slab',
            'Yanone Kaffeesatz:400,700' => 'Yanone Kaffeesatz',
            'Rokkitt:400' => 'Rokkitt',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}
//Blog layout
function areview_sanitize_review( $input ) {
    $valid = array(
                'none' => 'None',
                'product' => 'Products',
                'movie' => 'Movies',
                'game' => 'Games',
    );
 
    if ( array_key_exists( $input, $valid ) ) {
        return $input;
    } else {
        return '';
    }
}
//Images
function areview_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'gif'          => 'image/gif',
        'png'          => 'image/png',
        'bmp'          => 'image/bmp',
        'tif|tiff'     => 'image/tiff',
        'ico'          => 'image/x-icon'
    );
	// Return an array with file extension and mime_type.
    $file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
    return ( $file['ext'] ? $image : $setting->default );
}
/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function areview_customize_preview_js() {
	wp_enqueue_script( 'areview_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), true );
}
add_action( 'customize_preview_init', 'areview_customize_preview_js' );
