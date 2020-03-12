<?php

/**
 * The core plugin class
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/includes
 */


// Defines internationalization, admin-specific hooks and public hooks
class Rate_My_Post {

	// Registers all hooks for the plugin
	protected $loader;

	// Rate My Post - string
	protected $rate_my_post;

	//Plugin current version - string
	protected $version;

    // Set the Rate My Post and the plugin version, and fire the methods!
	public function __construct() {
		if ( defined( 'RATE_MY_POST_VERSION' ) ) {
			$this->version = RATE_MY_POST_VERSION;
		} else {
			$this->version = '3.1.0';
		}
		$this->rate_my_post = 'rate-my-post';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	//Load the required dependencies for this plugin.

	private function load_dependencies() {
		// Manages hooks
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rate-my-post-loader.php';

		// Manages internationalization
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-rate-my-post-i18n.php';

		// Manages admin side
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-rate-my-post-admin.php';

		// Manages public side
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-rate-my-post-public.php';

		// Common methods for public and admin side
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-rate-my-post-common.php';

		// Plugin default settings
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-rate-my-post-settings.php';

		// Top Rated Posts Widget
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'widgets/rate-my-post-top-rated-widget.php';

    //Fire the loader
		$this->loader = new Rate_My_Post_Loader();
	}

	// Register internationalization
	private function set_locale() {
		$plugin_i18n = new Rate_My_Post_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	// Register admin hooks
	private function define_admin_hooks() {
		$plugin_admin = new Rate_My_Post_Admin( $this->get_rate_my_post(), $this->get_version() );
		//CSS
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		//JS
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		//META BOX
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'manipulate_rating_meta_box' );
		//UPDATE RESULTS AJAX
		$this->loader->add_action( 'wp_ajax_update_results', $plugin_admin, 'backend_results_update' );
		//RESET RESULTS AJAX
		$this->loader->add_action( 'wp_ajax_reset_results', $plugin_admin, 'backend_results_reset' );
		//DELETE FEEDBACK AJAX
		$this->loader->add_action( 'wp_ajax_delete_feedback', $plugin_admin, 'delete_feedback' );
		//INDIVIDUALLY DELETE FEEDBACK AJAX
		$this->loader->add_action( 'wp_ajax_individually_delete_feedback', $plugin_admin, 'individually_delete_feedback' );
		//MENU SECTION
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'menu_section' );
		//OPTIONS UPDATE AJAX
		$this->loader->add_action( 'wp_ajax_update_options', $plugin_admin, 'options_update' );
		//OPTIONS RESET AJAX
		$this->loader->add_action( 'wp_ajax_reset_options', $plugin_admin, 'options_reset' );
		//CUSTOMIZATION UPDATE AJAX
		$this->loader->add_action( 'wp_ajax_update_customization', $plugin_admin, 'customization_update' );
		//CUSTOMIZATION RESET AJAX
		$this->loader->add_action( 'wp_ajax_reset_customization', $plugin_admin, 'customization_reset' );
		//SECURITY UPDATE AJAX
		$this->loader->add_action( 'wp_ajax_update_security', $plugin_admin, 'security_update' );
		//HIDE RMP FIELDS IN EDIT POST
		$this->loader->add_filter( 'is_protected_meta', $plugin_admin, 'hide_custom_fields', 10, 2 );
		//MIGRATION TOOLS
		$this->loader->add_action( 'wp_ajax_migrate_data', $plugin_admin, 'migration_tools' );
		//WIDGETS
		$this->loader->add_action( 'widgets_init', $plugin_admin, 'register_widgets' );
	}

	// Register public hooks
	private function define_public_hooks() {

		$plugin_public = new Rate_My_Post_Public( $this->get_rate_my_post(), $this->get_version() );
		//CSS
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		//JS
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		//SHORTCODES
		$this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
		//INCLUDE RATING WIDGET AUTOMATICALLY - FILTER CONTENT
		$this->loader->add_filter( 'the_content', $plugin_public, 'automatically_add_rating_widget' );
		//INCLUDE RESULT WIDGET AUTOMATICALLY - FILTER CONTENT
		$this->loader->add_filter( 'the_content', $plugin_public, 'automatically_add_result_widget' );
		//LOAD RESULTS AJAX PRIV
		$this->loader->add_action( 'wp_ajax_load_results', $plugin_public, 'load_results' );
		//LOAD RESULTS AJAX NOPRIV
		$this->loader->add_action( 'wp_ajax_nopriv_load_results', $plugin_public, 'load_results' );
		//PROCESS RATING AJAX PRIV
		$this->loader->add_action( 'wp_ajax_process_rating', $plugin_public, 'process_rating' );
		//PROCESS RATING AJAX NOPRIV
		$this->loader->add_action( 'wp_ajax_nopriv_process_rating', $plugin_public, 'process_rating' );
		//PROCESS FEEDBACK AJAX PRIV
		$this->loader->add_action( 'wp_ajax_process_feedback', $plugin_public, 'process_feedback' );
		//PROCESS FEEDBACK AJAX NOPRIV
		$this->loader->add_action( 'wp_ajax_nopriv_process_feedback', $plugin_public, 'process_feedback' );
		//PROCESS AMP RATING PRIV
		$this->loader->add_action( 'wp_ajax_process_rating_amp', $plugin_public, 'process_rating_amp' );
		//PROCESS AMP RATING NO PRIV
		$this->loader->add_action( 'wp_ajax_nopriv_process_rating_amp', $plugin_public, 'process_rating_amp' );
		//RATINGS ON ARCHIVE PAGES
		$this->loader->add_filter( 'the_title', $plugin_public, 'ratings_archive_pages', 10, 2 );
	}

	// Run the loader to execute all hooks
	public function run() {
		$this->loader->run();
	}

	// Rate My Post for identification
	public function get_rate_my_post() {
		return $this->rate_my_post;
	}

	// The reference to the class that orchestrates the hooks with the plugin
	public function get_loader() {
		return $this->loader;
	}

	// Retrive the plugin version
	public function get_version() {
		return $this->version;
	}

}
