<?php
/**
 * aReview functions and definitions
 *
 * @package aReview
 */

if ( ! function_exists( 'areview_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function areview_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on aReview, use a find and replace
	 * to change 'areview' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'areview', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	// Set the content width based on the theme's design and stylesheet.
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 640; /* pixels */
	}	

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size('home-thumb', 650, 400, true);
	add_image_size('single-thumb', 650);
	add_image_size('carousel-thumb', 255, 160, true);

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'areview' ),
		'social' => __( 'Social', 'areview' ),
	) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link'
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'areview_custom_background_args', array(
		'default-color' => '222e38',
		'default-image' => get_template_directory_uri() . '/pattern.png',
	) ) );
	
	add_theme_support( 'title-tag' );
}
endif; // areview_setup
add_action( 'after_setup_theme', 'areview_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function areview_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'areview' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3><div class="decoration-bar"></div>',
	) );
	register_sidebar( array(
		'name'          => __( 'Footer A', 'areview' ),
		'id'            => 'sidebar-4',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer B', 'areview' ),
		'id'            => 'sidebar-5',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	
	register_sidebar( array(
		'name'          => __( 'Footer C', 'areview' ),
		'id'            => 'sidebar-6',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );	

	//Register the widgets
	register_widget( 'aReview_Recent_Posts' );
	register_widget( 'aReview_Recent_Comments' );
	register_widget( 'aReview_Social_Profile' );
	register_widget( 'aReview_Video_Widget' );
	register_widget( 'aReview_Top_Rated_Posts' );
}
add_action( 'widgets_init', 'areview_widgets_init' );

/**
 * Load the widgets.
 */
require get_template_directory() . "/widgets/recent-posts.php";
require get_template_directory() . "/widgets/recent-comments.php";
require get_template_directory() . "/widgets/video-widget.php";
require get_template_directory() . "/widgets/social-profile.php";
require get_template_directory() . "/widgets/top-rated-posts.php";


/**
 * Enqueue scripts and styles.
 */
function areview_scripts() {

	wp_enqueue_style( 'areview-bootstrap', get_template_directory_uri() . '/bootstrap/bootstrap.min.css', array(), true );

	wp_enqueue_style( 'areview-style', get_stylesheet_uri() );

	wp_enqueue_style( 'areview-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css' );

	//Load the fonts
	$headings_font = esc_html(get_theme_mod('headings_fonts'));
	$body_font = esc_html(get_theme_mod('body_fonts'));
	if( $headings_font ) {
		wp_enqueue_style( 'areview-headings-fonts', '//fonts.googleapis.com/css?family='. $headings_font );	
	} else {
		wp_enqueue_style( 'areview-headings-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:700');
	}	
	if( $body_font ) {
		wp_enqueue_style( 'areview-body-fonts', '//fonts.googleapis.com/css?family='. $body_font );	
	} else {
		wp_enqueue_style( 'areview-body-fonts', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic');
	}

	if ( get_theme_mod('areview_scroller') != 1 )  {
		
		wp_enqueue_script( 'areview-nicescroll', get_template_directory_uri() . '/js/jquery.nicescroll.min.js', array('jquery'), true );	

		wp_enqueue_script( 'areview-nicescroll-init', get_template_directory_uri() . '/js/nicescroll-init.js', array('jquery'), true );

	}

	wp_enqueue_script( 'areview-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), true );

	wp_enqueue_script( 'areview-fitvids', get_template_directory_uri() . '/js/jquery.fitvids.js', array('jquery'), true );		

	wp_enqueue_script( 'areview-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20120206', true );

	wp_enqueue_script( 'areview-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20130115', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'areview_scripts' );

function areview_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'areview_excerpt_length', 999 );

/**
 * Adds more contact methods in the User profile screen (links used for the author bio).
 */
function areview_contactmethods( $contactmethods ) {
	
	$contactmethods['areview_facebook'] = __( 'Author Bio: Facebook', 'areview' );
	$contactmethods['areview_twitter'] = __( 'Author Bio: Twitter', 'areview' );	
	$contactmethods['areview_googleplus'] = __( 'Author Bio: Google Plus', 'areview' );
	$contactmethods['areview_linkedin'] = __( 'Author Bio: Linkedin', 'areview' );
	
	return $contactmethods;
}
add_filter( 'user_contactmethods', 'areview_contactmethods', 10, 1);

/**
 * Load html5shiv
 */
function areview_html5shiv() {
    echo '<!--[if lt IE 9]>' . "\n";
    echo '<script src="' . esc_url( get_template_directory_uri() . '/js/html5shiv.js' ) . '"></script>' . "\n";
    echo '<![endif]-->' . "\n";
}
add_action( 'wp_head', 'areview_html5shiv' );

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';
/**
 * Load the carousel.
 */
require get_template_directory() . '/inc/carousel/carousel.php';
/**
 * Load the dynamic styles
 */
require get_template_directory() . '/styles.php';
/**
 *TGM Plugin activation.
 */
require_once get_template_directory() . '/plugins/class-tgm-plugin-activation.php';
 
add_action( 'tgmpa_register', 'areview_register_required_plugins' );

function areview_register_required_plugins() {
    $plugins = array(
        array(
            'name'               => 'Custom Field Suite',
            'slug'               => 'custom-field-suite',
            'required'           => false,
        ),
        array(
            'name'               => 'Yasr - Yet Another Stars Rating',
            'slug'               => 'yet-another-stars-rating',
            'required'           => false,
        ),          
    );
 
    $config = array(
        'id'           => 'areview',               // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
    );

    tgmpa( $plugins, $config );
}
