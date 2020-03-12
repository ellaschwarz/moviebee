<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/admin
 */

class Rate_My_Post_Admin {

	// plugin name - string
	private $rate_my_post;

	// plugin version - string
	private $version;

	// init
	public function __construct( $rate_my_post, $version ) {
		$this->rate_my_post = $rate_my_post;
		$this->version = $version;
	}

	//---------------------------------------------------
	// REGISTER AND ENQUEUE ADMIN CSS
	//---------------------------------------------------

	public function enqueue_styles() {
		// register style
		wp_register_style( $this->rate_my_post, plugin_dir_url( __FILE__ ) . 'css/rate-my-post-admin.css', array(), $this->version, 'all' );
		// enqueue style
		wp_enqueue_style( $this->rate_my_post );
	}

	//---------------------------------------------------
	// REGISTER AND ENQUEUE ADMIN JS
	//---------------------------------------------------

	public function enqueue_scripts() {
		// register JS
		wp_register_script( $this->rate_my_post, plugin_dir_url( __FILE__ ) . 'js/rate-my-post-admin.js', array( 'jquery' ), $this->version, false );
		// enqueue JS
		wp_enqueue_script( $this->rate_my_post );
		// localize script
		wp_localize_script(
      $this->rate_my_post,
      'rmp_options',
       array(
         'admin_ajax' 				=> admin_url( 'admin-ajax.php' ),
         'postID'     				=> get_the_id(),
				 'save'       				=> ( esc_html__( 'Saving', 'rate-my-post' ) ),
         'resetConfirm'       => ( esc_html__( 'Are your sure you want to reset the settings?', 'rate-my-post' ) ),
				 'nonce'       				=> wp_create_nonce( 'rmp_admin_save' ),
        )
      );
	}

	//---------------------------------------------------
	// META BOX - RATINGS UPDATE AND FEEDBACK DISPLAY
	//---------------------------------------------------
	public function manipulate_rating_meta_box() {
		if ( ! $this->has_required_capability() ) {
			return;
		}
		add_meta_box( 'rmp-rate-id', 'Rate my Post', array( $this, 'display_metabox' ), $this->define_post_types() );
	}

	public function display_metabox() {
		ob_start();
    include_once plugin_dir_path( __FILE__ ) . 'templates/meta-box.php';
		echo ob_get_clean();
	}

	//---------------------------------------------------
	// ADMIN OR AUTHOR UPDATE RESULTS - AJAX
	//---------------------------------------------------
	public function backend_results_update() {
	  if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Successfully Saved!', 'rate-my-post'),
				'errorMsg' 	=> array()
			);
			// variables
	    $vote_count = intval( $_POST['votes'] );
	    $avg_rating = floatval( $_POST['avg'] );
	    $post_id = intval( $_POST['postID'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;
			$max_rating = Rate_My_Post_Common::max_rating();

			$rating_sum = intval( round( $vote_count * $avg_rating ) );

			// recalculate avg rating - for example if user inserts vote count 1 and rating 4.5 we want to avg rating to be 4 as the former is not possible
			if( $rating_sum && $vote_count ) {
				$avg_rating = $rating_sum / $vote_count;
			} else {
				$rating_sum = 0;
				$vote_count = 0;
				$avg_rating = 0;
			}

			// security checks
			if( ! $this->has_required_capability() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $vote_count || $vote_count < 1 || $avg_rating < 1 || $avg_rating > $max_rating ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid vote count or average rating!', 'rate-my-post' );
			}

			// die if failed a security check
			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// update vote count, sum of ratings and average rating
			if ( ! add_post_meta( $post_id, 'rmp_vote_count', $vote_count, true ) ) {
				update_post_meta( $post_id, 'rmp_vote_count', $vote_count );
			}
			if ( ! add_post_meta( $post_id, 'rmp_rating_val_sum', $rating_sum, true ) ) {
				update_post_meta( $post_id, 'rmp_rating_val_sum', $rating_sum );
			}
			if ( ! add_post_meta( $post_id, 'rmp_avg_rating', $avg_rating, true ) ) {
				update_post_meta( $post_id, 'rmp_avg_rating', $avg_rating );
			}
			echo json_encode( $data );
		}
		die();
	}

	//---------------------------------------------------
	// ADMIN OR AUTHOR RESET RATINGS
	//---------------------------------------------------

	public function backend_results_reset() {
	  if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Successfully Saved!', 'rate-my-post'),
				'errorMsg' 	=> array()
			);
			// variables
	    $post_id = intval( $_POST['postID'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// security checks
			if( ! $this->has_required_capability() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			// die if failed a security check
			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			delete_post_meta( $post_id, 'rmp_avg_rating' );
			delete_post_meta( $post_id, 'rmp_vote_count' );
			delete_post_meta( $post_id, 'rmp_rating_val_sum' );
			echo json_encode( $data );
		}
		die();
	}


	//---------------------------------------------------
	// DELETE FEEDBACK - AJAX
	//---------------------------------------------------
	public function delete_feedback() {
	  if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Feedback successfully deleted!', 'rate-my-post'),
				'errorMsg' 	=> array()
			);
			// variables
	    $post_id = intval( $_POST['postID'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;
			// security checks
			if( ! $this->has_required_capability() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}
			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}
			// die if failed a security check
			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}
	    //delete feedback
			delete_post_meta( $post_id, 'rmp_feedback_val' );
			delete_post_meta( $post_id, 'rmp_feedback_val_new' );
	    echo json_encode( $data );
	  }
	  die();
	}

	//---------------------------------------------------
	// INDIVIDUALLY DELETE FEEDBACK - AJAX
	//---------------------------------------------------
	public function individually_delete_feedback() {
	  if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'feedbackID' => false,
				'errorMsg' 	=> array()
			);

			// variables
	    $post_id = intval( $_POST['postID'] );
			$feedback_id = sanitize_text_field( $_POST['feedbackID'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// security checks
			if( ! $this->has_required_capability() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			// die if failed a security check
			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
			$feedback = get_post_meta( $post_id, 'rmp_feedback_val_new', true );
			if( is_array( $feedback ) && ! empty( $feedback ) ) {
				foreach ( $feedback as $key => $value ) {
					if( $value['id'] == $feedback_id ) {
						unset( $feedback[$key] ); //remove this feedback from array
					}
				}
			}
			update_post_meta( $post_id, 'rmp_feedback_val_new', $feedback );
			$data['feedbackID'] = $feedback_id;
	    echo json_encode( $data );
	  }
	  die();
	}

  //---------------------------------------------------
  // MENU SECTION
  //---------------------------------------------------
  public function menu_section() {
		// main item
    add_menu_page( 'Rate my Post', 'Rate my Post', 'edit_others_posts', 'rate-my-post', array( $this, 'menu_section_display' ), 'dashicons-thumbs-up', 24 );
		// settings item
		add_submenu_page('rate-my-post', 'Rate my Post Settings', esc_html__( 'Settings', 'rate-my-post' ), 'edit_others_posts', 'rate-my-post' );
		// stats item
		add_submenu_page( 'rate-my-post', 'Rate my Post Stats', esc_html__( 'Stats', 'rate-my-post' ), 'edit_others_posts', 'rate-my-post-stats', array( $this, 'submenu_stats_display' ) );
		// analytics item
		add_submenu_page( 'rate-my-post', 'Rate my Post Analytics', esc_html__( 'Analytics', 'rate-my-post' ), 'edit_others_posts', 'rate-my-post-analytics', array( $this, 'submenu_analytics_display' ) );
  }

  public function menu_section_display(){
		if ( current_user_can( 'manage_options' ) ) {
	    ob_start();
	    include_once plugin_dir_path( __FILE__ ) . 'templates/menu.php';
			echo ob_get_clean();
		} else {
			echo '<p>' . esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' ) . '</p>';
		}
  }

	public function submenu_stats_display() {
		if ( current_user_can( 'edit_others_posts' ) ) {
	    ob_start();
	    include_once plugin_dir_path( __FILE__ ) . 'templates/stats.php';
			echo ob_get_clean();
		} else {
			echo 'Access Denied';
		}
	}

	public function submenu_analytics_display() {
		if ( current_user_can( 'edit_others_posts' ) ) {
	    ob_start();
	    include_once plugin_dir_path( __FILE__ ) . 'templates/analytics.php';
			echo ob_get_clean();
		} else {
			echo 'Access Denied';
		}
	}

  //---------------------------------------------------
  // UPDATE OPTIONS
  //---------------------------------------------------
  public function options_update() {
    if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Successfully Saved!', 'rate-my-post'),
				'errorMsg' 	=> array()
			);

			// variables
			$default_options = Rate_My_Post_Settings::default_options();
			$updated_options = array();
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if( ! $this->is_administrator() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
			foreach( $_POST as $key => $value ) {
        if ( array_key_exists( $key, $default_options ) ) {
          //sanitize options before saving them
          $value = $this->sanitize_options( $key, $value );
          //push new options to array
          $updated_options[$key] = $value;
        }
      }

			// verify that all options were provided
			$errored_options = $this->verify_options( $default_options, $updated_options );

			// fix if not all options were provided
			if( $errored_options ) {
				$updated_options = $this->fix_options( $default_options, $updated_options, $errored_options );
				$errored_options_string = implode( ', ', $errored_options );
				$data['successMsg'] = esc_html__( 'Settings Partially Saved. Unable to save: ', 'rate-my-post' ) . $errored_options_string . '. ' . esc_html__( 'Try clearing all caches, especially your browser cache!', 'rate-my-post' );
			}
			// update options
			update_option( 'rmp_options', $updated_options );
			echo json_encode( $data );
    }
    die();
  }

  //---------------------------------------------------
  // RESET OPTIONS
  //---------------------------------------------------
  public function options_reset() {
    if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Settings Reset Done', 'rate-my-post' ),
				'errorMsg' 	=> array()
			);

			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if( ! $this->is_administrator() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
      delete_option( 'rmp_options' );
      $default_options = Rate_My_Post_Settings::default_options();
      add_option( 'rmp_options', $default_options );
      echo json_encode( $data );
    }
    die();
  }

  //---------------------------------------------------
  // UPDATE CUSTOMIZATION
  //---------------------------------------------------
  public function customization_update() {
      if ( wp_doing_ajax() ) {
				$data = array(
					'valid'     => true,
					'successMsg' => esc_html__( 'Successfully Saved!', 'rate-my-post'),
					'errorMsg' 	=> array()
				);

        // variables
        $default_customization = Rate_My_Post_Settings::default_customization();
        $updated_customization = array();
				$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

				if( ! $this->is_administrator() ) {
					$data['valid'] = false;
					$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
				}

				if( ! $this->has_valid_nonce( $nonce ) ) {
					$data['valid'] = false;
					$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
				}

				if( ! $data['valid'] ) {
					echo json_encode( $data );
					die();
				}
				// proceed
        foreach( $_POST as $key => $value ) {
          if ( array_key_exists( $key, $default_customization ) ) {
            $value = $this->sanitize_customization( $key, $value );
            //push new customization to array
            $updated_customization[$key] = $value;
          }
        }
				// verify that all customization was provided
				$errored_customization = $this->verify_options( $default_customization, $updated_customization );
				// fix if not all customization was provided
				if( $errored_customization ) {
					$updated_customization = $this->fix_options( $default_customization, $updated_customization, $errored_customization );
					$errored_options_string = implode( ', ', $errored_customization );
					$data['successMsg'] = esc_html__( 'Settings Partially Saved. Unable to save: ', 'rate-my-post' ) . $errored_options_string . '. ' . esc_html__( 'Try clearing all caches, especially your browser cache!', 'rate-my-post' );
				}
	      // update customization
        update_option( 'rmp_customize_strings', $updated_customization );
				echo json_encode( $data );
      }
    die();
  }

  //---------------------------------------------------
  // RESET CUSTOMIZATION
  //---------------------------------------------------
  public function customization_reset() {
    //only admin can update customization options
    if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Settings Reset Done', 'rate-my-post' ),
				'errorMsg' 	=> array()
			);

			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if( ! $this->is_administrator() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
      delete_option('rmp_customize_strings');
      $default_customization = Rate_My_Post_Settings::default_customization();
      add_option( 'rmp_customize_strings', $default_customization );
      echo json_encode( $data );
    }
    die();
  }

	//---------------------------------------------------
  // UPDATE SECURITY OPTIONS
  //---------------------------------------------------
	public function security_update() {
		if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => esc_html__( 'Successfully Saved!', 'rate-my-post'),
				'errorMsg' 	=> array()
			);

			// variables
			$default_security = Rate_My_Post_Settings::security_options();
			$updated_security = array();
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if( ! $this->is_administrator() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
			foreach( $_POST as $key => $value ) {
				if ( array_key_exists( $key, $default_security ) ) {
					$value = $this->sanitize_security( $key, $value );
					$updated_security[$key] = $value;
				}
			}
			// verify that all options were provided
			$errored_security = $this->verify_options( $default_security, $updated_security );
			// fix if not all options were provided
			if( $errored_security ) {
				$updated_security = $this->fix_options( $default_security, $updated_security, $errored_security );
				$errored_security_string = implode( ', ', $errored_security );
				$data['successMsg'] = esc_html__( 'Settings Partially Saved. Unable to save: ', 'rate-my-post' ) . $errored_security_string . '. ' . esc_html__( 'Try clearing all caches, especially your browser cache!', 'rate-my-post' );
			}
			// update security options
			update_option( 'rmp_security', $updated_security );
			echo json_encode( $data );
		}
		die();
	}

	//---------------------------------------------------
  // HIDE RATE MY POST CUSTOM FIELDS IN EDIT POST SECTION
  //---------------------------------------------------

	// prevents custom fields from getting reverted on post update
	public function hide_custom_fields( $protected, $meta_key ) {
		if ( $meta_key === 'rmp_feedback_val' || $meta_key === 'rmp_rating_val_sum' || $meta_key === 'rmp_vote_count' || $meta_key === 'rmp_avg_rating' ) {
			return true;
		} else {
			return $protected;
		}
	}

	//---------------------------------------------------
  // REGISTER WIDGETS
  //---------------------------------------------------
	public function register_widgets() {
		// top rated posts widget
		register_widget( 'Rate_My_Post_Top_Rated_Widget' );
	}

	//---------------------------------------------------
  // MIGRATION TOOLS - AJAX
  //---------------------------------------------------
	public function migration_tools() {
		if ( wp_doing_ajax() ) {
			$data = array(
				'valid'     => true,
				'successMsg' => false,
				'errorMsg' 	=> array()
			);

			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			if( ! $this->is_administrator() ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'You do not have adequate permissions for this action!', 'rate-my-post' );
			}

			if( ! $this->has_valid_nonce( $nonce ) ) {
				$data['valid'] = false;
				$data['errorMsg'][] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			}

			if( ! $data['valid'] ) {
				echo json_encode( $data );
				die();
			}

			// proceed
			if ( function_exists( 'kk_star_ratings' ) ) { // kk star ratings
				$migrated_posts = $this->migrate_ratings( '_kksr_ratings', '_kksr_casts' );
				$data['successMsg'] = $this->verify_migration( $migrated_posts );
			} elseif ( function_exists('the_ratings') ) { // wp post ratings
				$migrated_posts = $this->migrate_ratings( 'ratings_score', 'ratings_users' );
				$data['successMsg'] = $this->verify_migration( $migrated_posts );
			} elseif ( function_exists('yasr_get_visitor_votes') ) { // yasr
				$migrated_posts = $this->migrate_ratings('', '', 'yasr');
				$data['successMsg'] = $this->verify_migration( $migrated_posts );
			} else {
				$data['successMsg'] = esc_html__( 'No rating plugin found! Migration is not possible!', 'rate-my-post' );
			}
			echo json_encode( $data );
		}
		die();
	}

	// strings for completed migration
	private function verify_migration( $migrated_posts ) {
		if( $migrated_posts ) {
			return esc_html__( 'Migration successful! Number of posts affected', 'rate-my-post' ) . ': ' . $migrated_posts;
		} else {
			return esc_html__( 'No existing ratings found! Nothing to migrate!', 'rate-my-post' );
		}
	}

	// migrate from other plugins to rate my post
	private function migrate_ratings( $ratings_sum_field, $vote_count_field, $specific_plugin = false ) {
		$count = 0;
		$merge = false;
		if( has_filter('rmp_migrate_merge') ) {
			$merge = apply_filters( 'rmp_migrate_merge', $merge );
		}

	  $args = array(
			'fields'    		=> 'ids',
	    'post_type' 		=> array(
	            'post',
	            'page',
	            ),
	    'posts_per_page' => -1,
	  );
	  $the_query = new WP_Query( $args );
	  if ( $the_query->have_posts() ) {
	  	while ( $the_query->have_posts() ) {

	      $the_query->the_post();
	      $post_id = get_the_id();

				if ( ! $specific_plugin ) { // migration based on post meta
		      $ratings_sum = intval( get_post_meta( $post_id, $ratings_sum_field, true ) );
		      $vote_count = intval( get_post_meta( $post_id, $vote_count_field, true ) );

		      if ( $ratings_sum && $vote_count ) { // post is rated in another plugin
		        $count++;
						if ( $merge ) { // merge the ratings if enabled via filter
							$ratings_sum = get_post_meta( $post_id, 'rmp_rating_val_sum', true ) + $ratings_sum;
							$vote_count = get_post_meta( $post_id, 'rmp_vote_count', true ) + $vote_count;
						}
						//update ratings
		        update_post_meta( $post_id, 'rmp_rating_val_sum', $ratings_sum );
		        update_post_meta( $post_id, 'rmp_vote_count', $vote_count );
		      }
				} // end migration based on post meta

				// migration for yasr plugin
				if ( $specific_plugin === 'yasr' ) {
					$yasr_info = yasr_get_visitor_votes( $post_id );
					if ( is_array($yasr_info) && !empty($yasr_info ) ) {
						$yasr_rating_object = $yasr_info[0];
						$ratings_sum = intval( $yasr_rating_object->sum_votes );
						$vote_count = intval( $yasr_rating_object->number_of_votes );

						if ( $ratings_sum && $vote_count ) { // post is rated in yasr
			        $count++;
			        update_post_meta( $post_id, 'rmp_rating_val_sum', $ratings_sum );
			        update_post_meta( $post_id, 'rmp_vote_count', $vote_count );
			      }
					}
				} // end yasr

	  	} // end while
	  } // endif

	  wp_reset_postdata();
	  return $count;
	}

	// check for an existing rating plugin
	private function existing_rating_plugin() {
		if ( function_exists( 'kk_star_ratings' ) ) {
			return 'KK StarRatings';
		} elseif ( function_exists('the_ratings') ) {
			return 'WP-PostRatings';
		} elseif ( function_exists('yasr_get_visitor_votes') ) {
			return 'Yasr – Yet Another Stars Rating';
		} else {
			return false;
		}
	}

	//---------------------------------------------------
	// ANALYTICS SECTION
	//---------------------------------------------------

	// retrieve data for analytics table
	private function retrieve_analytics_table_data() {
		// get the data from plugin's table
		global $wpdb;
		$analytics_table = $wpdb->prefix . "rmp_analytics";
		$analytics_data = $wpdb->get_results( "SELECT * FROM $analytics_table ORDER BY id DESC LIMIT 100" );
		// reverse array - we want latest actions first
		$analytics_data = array_reverse( $analytics_data );
		// for storing
		$complete_data = array();

		// populate complete_data array
		foreach ( $analytics_data as $row) {
			$analytics_row = array();

			$analytics_row['id'] = intval( $row->id );
			$analytics_row['postTitle'] = get_the_title( $row->post );
			$analytics_row['postLink'] = get_the_permalink( $row->post );
			$analytics_row['action'] = intval( $row->action );
			$analytics_row['newRating'] = floatval( $row->average );
			$analytics_row['newVotes'] = intval( $row->votes );
			$analytics_row['value'] = intval( $row->value );

			// not yet functional
			$analytics_row['country'] = $row->country;
			// reformat time
			$time = strtotime( $row->time );
			$analytics_row['time'] = date( 'd-m-Y H:i:s', $time );
			// user if available
			if( $row->user == -1 ) {
				$analytics_row['user'] = esc_html__( 'Tracking Disabled', 'rate-my-post' );
			} elseif ( $row->user ) {
				$user_info = get_userdata( $row->user );
				$username = $user_info->user_login;
				// allow hiding username in admin panel
				if( has_filter('rmp_rater_username') ) {
					$username = apply_filters( 'rmp_rater_username', $username );
				}
				$analytics_row['user'] = $username;
			} else {
				$analytics_row['user'] = esc_html__( 'Not logged in', 'rate-my-post' );
			}

			// ip if enabled
			if ( $row->ip == -1 ) {
				$analytics_row['ip'] = esc_html__( 'Tracking Disabled', 'rate-my-post' );
			} elseif ( $row->ip ) {
				$analytics_row['ip'] = sanitize_text_field( $row->ip );
			} else {
				$analytics_row['ip'] = 'n/a';
			}

			// duration
			if( $row->duration == -1 ) {
					$analytics_row['duration'] = 'AMP - n/a';
			} else {
				$analytics_row['duration'] = intval( $row->duration ) . ' seconds';
			}

			//push $analyticsRow to $completeAnalyticsData
			$complete_data[] = $analytics_row;
		}

		return $complete_data;
	}

	//---------------------------------------------------
	// OPTIONS VERIFICATION AND SANITIZATION
	//---------------------------------------------------

	// return empty string rather than 0 - for options
	private function numeric_option( $number ) {
		if( intval( $number ) ) {
			return intval( $number );
		}
		return '';
	}

	// verfiy that all option were sent to the server (prevent cache issues)
	private function verify_options( $deafult_options, $updated_options ) {
		$diff = array_diff_key( $deafult_options, $updated_options );
		$errored_options = array();
		foreach ( $diff as $key => $value ) {
			$errored_options[] = $key;
		}
		return $errored_options;
	}

	// fallback in case that not all options are sent (cache)
	private function fix_options( $deafult_options, $updated_options, $missing_keys ) {
		foreach ( $missing_keys as $missing_key ) {
			$updated_options[$missing_key] = $deafult_options[$missing_key];
		}
		return $updated_options;
	}

	// sanitize options
	private function sanitize_options( $key, $value ) {
		// numeric options
    if ( in_array( $key, Rate_My_Post_Settings::$numeric_options ) ) {
      return intval($value);
    };
		// url options
		if ( in_array( $key, Rate_My_Post_Settings::$url_options ) ) {
        return esc_url( str_replace(' ', '', $value ) );
    }

		// string options
		if ( in_array( $key, Rate_My_Post_Settings::$string_options ) ) {
        return sanitize_text_field( $value );
    }
		// exclude option
		if ( $key === 'exclude' ) { // exclude is an array
        $new_array = array();
        $input_numbers = explode( ',',( $value ) );
        foreach ( $input_numbers as $input_number ) {
          $input_number = intval( $input_number );
          if ( $input_number ) {
            $new_array[] = $input_number;
          }
        }
        return $new_array;
    }
		// cpt options
		if ( $key === 'cptRating' || $key === 'cptResult' ) { // cpt rating and cpt result are arrays
      $sanitized_array = array();
      $cpt_array = explode( ',',( $value ) );
      foreach ( $cpt_array as $cpt ) {
        $cpt = sanitize_text_field( $cpt );
        if ( $cpt ) {
          $sanitized_array[] = $cpt;
        }
      }
      return $sanitized_array;
    }
		return '';
  }

	// sanitize customization
	private function sanitize_customization( $key, $value ) {
    if ( in_array( $key, Rate_My_Post_Settings::$customization_numeric ) ) {
      return intval( $value );
    } else {
      return sanitize_text_field( $value );
    }
  }

	// sanitize security options
	private function sanitize_security( $key, $value ) {
    if ( in_array( $key, Rate_My_Post_Settings::$security_numeric ) ) {
      return intval( $value );
    } else {
      return sanitize_text_field( $value );
    }
  }

	//---------------------------------------------------
	// SECURITY METHODS
	//---------------------------------------------------

	// checks required capability for manipulating votes and feedback
	private function has_required_capability() {
		$security = get_option( 'rmp_security' );
		if ( $security['privileges'] == 1 ) {
			return current_user_can( 'publish_posts' );
		} elseif ( $security['privileges'] == 2 ) {
			return current_user_can( 'edit_others_posts' );
		} else {
			return current_user_can( 'manage_options' );
		}
	}

	// checks nonce for admin ajax
	private function has_valid_nonce( $nonce ) {
		return wp_verify_nonce( $nonce, 'rmp_admin_save' );
	}

	// check if current user is admin
	private function is_administrator() {
		return current_user_can( 'manage_options' );
	}

	//---------------------------------------------------
	// POST TYPES
	//---------------------------------------------------

	// post type to which the ratings apply
	private function define_post_types() {
		$args = array(
      'public'       => true,
    );
    $registered_post_types = get_post_types( $args );
    if ( array_search( 'attachment', $registered_post_types ) ) {
      unset( $registered_post_types['attachment'] );
    }
    $applicable_post_types = array_values($registered_post_types);
    return $applicable_post_types;
	}

	// returns a string of custom post types on the website
	private function custom_post_types() {
		$args = array(
			'public'       => true,
		);

		$all_post_types = get_post_types( $args );
		//remove default wordpress post types
		if ( array_search( 'attachment', $all_post_types ) ) {
			unset( $all_post_types['attachment'] );
		}
		if ( array_search( 'page', $all_post_types ) ) {
			unset( $all_post_types['page'] );
		}
		if ( array_search( 'post', $all_post_types ) ) {
			unset( $all_post_types['post'] );
		}
		// array of custom post types
		$cpt = array_values($all_post_types);
		if ( !empty( $cpt ) ) {
			return substr( implode(', ', $cpt), 0, 100 );
		}
		return false;
	}

	//---------------------------------------------------
	// FEEDBACK
	//---------------------------------------------------

	// returns an array of feedback for the post
	private function feedbacks() {
		// get feedback before version 2.7.0
		$legacy_feedback = get_post_meta( get_the_id(), 'rmp_feedback_val', true );
		// get feedback after version 2.7.0
		$feedback = get_post_meta( get_the_id(), 'rmp_feedback_val_new', true );

		// restructure legacy feedback
		if ( $legacy_feedback && !is_array( $legacy_feedback ) ) {
			$legacy_feedback = str_replace( '<b>Feedback:</b> ','', $legacy_feedback );
			$legacy_feedback = explode( '<br />', $legacy_feedback );

			foreach ( $legacy_feedback as $key => $value ) {
				if ( $value ) {
					$legacy_feedback[$key] = array(
						'feedback' => $value,
						'time'     => false,
						'id'       => false,
						'user'     => false,
						'ratingID' => false,
					);
				} else {
					unset( $legacy_feedback[$key] );
				}
			}
		}

		// merge legacy feedback and new feedback
		$feedback_array = array();
		// populate legacy feedback
		if( is_array( $legacy_feedback ) && !empty( $legacy_feedback ) ) {
			foreach( $legacy_feedback as $single_legacy_feedback ) {
				$feedback_array[] = $single_legacy_feedback;
			}
		}
		// populate new feedback
		if( is_array( $feedback ) && ! empty( $feedback ) ) {
			foreach ( $feedback as $single_feedback ) {
				$feedback_array[] = $single_feedback;
			}
		}

		if ( !empty( $feedback_array ) ) {
			return $feedback_array;
		}

		return false;
	}

	// returns user who left feedback
	private function feedback_user( $user_id ) {
		if ( $user_id ) {
			$user = get_userdata( $user_id );
			$username = $user->user_login;
			// allow hiding username in admin panel
			if( has_filter('rmp_rater_username') ) {
				$username = apply_filters( 'rmp_rater_username', $username );
			}
			return $username;
		}
		return false;
	}

	//---------------------------------------------------
	// STATS SECTION
	//---------------------------------------------------

	// returns rows for stats table
	private function stats_rows() {
		$rated_posts = array();
		$args = array(
			 'fields'          => 'ids',
			 'post_type'       => $this->define_post_types(),
			 'posts_per_page'  => -1
		);
		$the_query = new WP_Query( $args );
		// The Loop
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				//data we'll need
				$vote_count = intval( get_post_meta( get_the_ID(), 'rmp_vote_count', true ) );
				$rating_sum = intval( get_post_meta( get_the_ID(), 'rmp_rating_val_sum', true ) );

				if ( $vote_count && $rating_sum ) { // post is rated
					$post_id = get_the_ID();
					$feedback_count = 0;
					if( $this->feedbacks() ) {
						$feedback_count = count( $this->feedbacks() );
					}

					$rated_posts[$post_id]['title'] = get_the_title();
					$rated_posts[$post_id]['edit_link'] = get_edit_post_link();
					$rated_posts[$post_id]['vote_count'] = $vote_count;
					$rated_posts[$post_id]['feedback_count'] = $feedback_count;
					$rated_posts[$post_id]['avg_rating'] = Rate_My_Post_Common::get_average_rating();
				}
			}
			wp_reset_postdata();
		}
		return $rated_posts;
	}

	//---------------------------------------------------
	// OTHER METHODS
	//---------------------------------------------------

	// check if uses caching plugin that requires ajax load
	private function has_incompatible_caching() {
		if ( class_exists( 'HyperCache' ) ) {
			return true;
		}
		if ( class_exists( 'Cache_Enabler' ) ) {
			return true;
		}
		return false;
	}

}
