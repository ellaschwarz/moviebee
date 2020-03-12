<?php

/**
 * The public-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public
 */

class Rate_My_Post_Public {

	// Plugin name - string
	private $rate_my_post;

	// Plugin version - string
	private $version;

	// Init
	public function __construct( $rate_my_post, $version ) {
		$this->rate_my_post = $rate_my_post;
		$this->version = $version;
	}

	//---------------------------------------------------
	// PUBLIC CSS
	//---------------------------------------------------

	public function enqueue_styles() {
		// register style
		wp_register_style( $this->rate_my_post, plugin_dir_url( __FILE__ ) . 'css/rate-my-post.css', array(), $this->version, 'all' );
		// enqueue style
		wp_enqueue_style( $this->rate_my_post );
		// internal style for overriding
		wp_add_inline_style( $this->rate_my_post, $this->internal_css() );
	}

	//---------------------------------------------------
	// PUBLIC JS
	//---------------------------------------------------

	public function enqueue_scripts() {
		// plugin options
    $options = get_option( 'rmp_options' );
		$security = get_option( 'rmp_security' );
		$customization = $this->custom_strings();
		// register scripts
		wp_register_script( $this->rate_my_post, plugin_dir_url( __FILE__ ) . 'js/rate-my-post.js', array( 'jquery' ), $this->version, true );
		wp_register_script( 'rmp-recaptcha', 'https://www.google.com/recaptcha/api.js?render=' . $security['siteKey'], array(), null, false );
		// enqueue scripts
		wp_enqueue_script( $this->rate_my_post );
		// localize script
		wp_localize_script(
      $this->rate_my_post,
      'rmp_frontend',
       array(
         'admin_ajax'      			=> admin_url( 'admin-ajax.php' ),
         'postID'          			=> get_the_id(),
         'noVotes'         			=> $customization['noRating'],
         'cookie'          			=> $customization['cookieNotice'],
         'afterVote'       			=> $customization['afterVote'],
				 'notShowRating'       	=> intval($options['notShowRating'] ),
         'social'          			=> $options['social'],
         'feedback'        			=> $options['feedback'],
         'cookieDisable'   			=> $options['cookieDisable'],
         'emptyFeedback'   			=> $customization['feedbackAlert'],
				 'hoverTexts' 					=> intval( $options['hoverTexts'] ),
				 'preventAccidental' 		=> intval( $options['preventAccidental'] ),
				 'grecaptcha' 					=> $this->do_recaptcha(),
				 'siteKey'					 		=> $security['siteKey'],
				 'votingPriv'						=> $security['votingPriv'],
				 'loggedIn'					 		=> is_user_logged_in(),
				 'positiveThreshold'    => intval( $options['positiveNegative'] ),
				 'ajaxLoad' 						=> intval( $options['ajaxLoad'] ),
				 'nonce'       				=> wp_create_nonce( 'rmp_public_nonce' ),
        )
    );

		// enqueue recaptcha if necessary
		if( $this->do_recaptcha() === 2 && ( ( $options['posts'] === 2 && is_singular( 'post' ) ) || ( $options['pages'] === 2 && is_page() ) || ( ! empty( $options['cptRating'] ) && is_singular( $options['cptRating'] ) ) ) ) {
			wp_enqueue_script( 'rmp-recaptcha' );
		};
	}

	//---------------------------------------------------
	// SHORTCODES
	//---------------------------------------------------

	// register shortcodes
	public function register_shortcodes() {
		// rating widget
		add_shortcode( 'ratemypost', array( $this, 'rating_widget_shortcode' ) );
		// result widget
		add_shortcode( 'ratemypost-result', array( $this, 'result_widget_shortcode' ) );
	}

	// rating widget
	public function rating_widget_shortcode( $atts ) {
		// attributes
		$info = shortcode_atts( array(
			'id' => false,
		), $atts );
		// enqueue recaptcha
		if ( $this->do_recaptcha() === 2 ) {
			wp_enqueue_script( 'rmp-recaptcha' );
		}
	  // output the rating widget
		return $this->get_the_rating_widget( $info['id'] );
	}

	// results widget
	public function result_widget_shortcode( $atts ) {
		$info = shortcode_atts( array(
			'id' => false,
		), $atts );
		// output the results widget
		return $this->get_the_results_widget( $info['id'] );
	}

	//---------------------------------------------------
	// AUTOMATICALLY ADD RATING WIDGET TO POSTS, PAGES & CPT
	//---------------------------------------------------

	public function automatically_add_rating_widget( $content ) {
	  $options = get_option( 'rmp_options' );
		// add dummy post type to exclude array
		if ( empty( $options['exclude'] ) ) {
			$options['exclude'] = 'rmp-dummy-post';
		}
    // add rating widget to all posts, except excluded
    if ( $options['posts'] === 2 && is_singular( 'post' ) && ! is_single( $options['exclude'] ) ) {
      $content .= $this->get_the_rating_widget();
      return $content;
    } elseif ( $options['pages'] === 2 && is_page() && ! is_page( $options['exclude'] ) ) { // add rating widget to all pages, except excluded
      $content .= $this->get_the_rating_widget();
      return $content;
    } elseif ( ! empty( $options['cptRating'] ) && is_singular( $options['cptRating'] ) && ! is_single( $options['exclude'] ) ) { // add rating widget to all CPT, except excluded
      $content .= $this->get_the_rating_widget();
      return $content;
		} else { // no rating widget - return content
      return $content;
    }
	}

	//---------------------------------------------------
	// AUTOMATICALLY ADD RESULTS WIDGET TO POSTS, PAGES & CPT
	//---------------------------------------------------

	public function automatically_add_result_widget( $content ) {
	  $options = get_option( 'rmp_options' );
		// add dummy post type to exclude array
		if ( empty( $options['exclude'] ) ) {
			$options['exclude'] = 'rmp-dummy-post';
		}
	  // add result widget to all posts, except excluded
    if ( $options['resultPost'] === 2 && is_singular( 'post' ) && ! is_single( $options['exclude'] ) ) {
			$results_widget = $this->get_the_results_widget();
      $content = $results_widget . $content;
      return $content;
    } elseif ( $options['resultPages'] === 2 && is_page() && !is_page( $options['exclude'] ) ) { // add result widget to all pages, except excluded
			$results_widget = $this->get_the_results_widget();
      $content = $results_widget . $content;
      return $content;
    } elseif ( !empty( $options['cptResult'] ) && is_singular( $options['cptResult'] ) && !is_single( $options['exclude'] ) ) { // add result widget to all CPT, except excluded
			$results_widget = $this->get_the_results_widget();
      $content = $results_widget . $content;
      return $content;
		} else { // no result widget - return content
      return $content;
    }
	}

	//---------------------------------------------------
	// LOAD RESULTS - FRONTEND AJAX
	//---------------------------------------------------

	public function load_results() {
		if ( wp_doing_ajax() ) {
			// array with vote count, ratings and errors
			$data = array(
				'voteCount' => false,
				'avgRating' => false,
				'errorMsg' 	=> '',
			);

	    $post_id = intval( $_POST['postID'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// security check
			if( ! $post_id ) {
				$data['errorMsg'] = esc_html__( 'You cannot rate a web page without an id!', 'rate-my-post' );
				echo json_encode( $data );
				die();
			}

			$nonce_check = $this->has_valid_nonce( $nonce );
			if( ! $nonce_check['valid']  ) {
				$data['errorMsg'] = $nonce_check['error'];
				echo json_encode( $data );
				die();
			}

			$vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
			$sum_of_ratings = Rate_My_Post_Common::get_sum_of_ratings( $post_id );
			$average_rating = Rate_My_Post_Common::calculate_average_rating( $sum_of_ratings, $vote_count );

			if ( $average_rating ) { // post has been rated
				$data['voteCount'] = $vote_count;
				$data['avgRating'] = $average_rating;
			} else { // no ratings so far
				$data['voteCount'] = 0;
				$data['avgRating'] = 0;
			}

			echo json_encode( $data );
		};
		die();
	}

	//---------------------------------------------------
	// PROCESS POST RATING - FRONTEND AJAX
	//---------------------------------------------------

	public function process_rating() {
		if ( wp_doing_ajax() ) {
			// array with vote count, ratings, errors, token and post id
			$data = array(
				'voteCount' => false,
				'avgRating' => false,
				'errorMsg' 	=> array(),
				'token'			=> '-1',
				'id'				=> '-1',
			);

			// variables
			$post_id = intval( $_POST['postID'] );
			$security_options = get_option( 'rmp_security' );
			$custom_strings = $this->custom_strings();
			$submitted_rating = intval( $_POST['star_rating'] );
			$duration = intval( $_POST['duration'] );
			$recaptcha_token = isset( $_POST['token'] ) ? $_POST['token'] : false;
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// security checks
			$security_passed = true;
			$recaptcha = $this->is_recaptcha_valid( $recaptcha_token );
			$privilege = $this->has_privileges( $security_options );
			$ip_check = $this->is_not_ip_double_vote( $security_options, $custom_strings, $post_id );
			$required_data = $this->all_rating_data_submitted( $post_id, $submitted_rating );
			$nonce_check = $this->has_valid_nonce( $nonce );
			$user_id_check = $this->is_not_user_id_double_vote( $security_options, $custom_strings, $post_id );

			$security_checks = array(
				$recaptcha,
				$privilege,
				$ip_check,
				$required_data,
				$nonce_check,
				$user_id_check
			);

			foreach ( $security_checks as $security_check ) {
				if( ! $security_check['valid'] ) {
					$data['errorMsg'][] = $security_check['error'];
					$security_passed = false;
				}
			}

			if ( ! $security_passed ) {
				echo json_encode( $data );
				die();
			}

			// security checks passed, continue
	    $options = get_option( 'rmp_options' );
			// insert vote count to post meta
			$new_vote_count = $this->save_vote_count( $post_id );
			// insert rating sum to post meta
			$new_rating = $this->save_rating( $post_id, $submitted_rating );
			// insert avg rating to post meta
			$post_meta_rating = $this->save_avg_rating( $post_id );
			//send email if enabled
			$this->send_email_rating( $post_id, $submitted_rating, $options );

			// get the return data
			if ( $new_vote_count && $new_rating ) { // already has ratings
				$avg_rating = Rate_My_Post_Common::calculate_average_rating( $new_rating, $new_vote_count );
			} else { // rated for the first time
				$avg_rating = $submitted_rating; // average equals to submitted
				$new_vote_count = 1; // vote count equals to 1
			}

			// save details to db for analytics section
			$analytics = $this->save_for_analytics( $post_id, 1, $duration, $avg_rating, $new_vote_count, $submitted_rating, $options, $security_options, false  );
			// token and id are used for feedback
			$data['id'] = $analytics['id'];
			$data['token'] = $analytics['token'];
			// new vote count and average rating are used for live reload
			$data['voteCount'] = $new_vote_count;
			$data['avgRating'] = $avg_rating;

			echo json_encode( $data );

			$this->clear_cache( $post_id, $options );
			do_action( 'rmp_after_vote', $post_id, $avg_rating, $new_vote_count, $submitted_rating );
		};
		die();
	}

	//---------------------------------------------------
	// AMP PROCESS POST RATING
	//---------------------------------------------------

	public function process_rating_amp() {
		$domain_url = ( isset( $_SERVER['HTTPS'] ) ? "https":"http" ) . "://$_SERVER[HTTP_HOST]";
		// headers
    header("Content-type: application/json");
    header("Access-Control-Allow-Credentials: true");
    header("Access-Control-Allow-Origin: *.ampproject.org");
    header("AMP-Access-Control-Allow-Source-Origin: " . $domain_url );

		if ( wp_doing_ajax() ) {
			// return data is an array
			$data = array(
				'voteCount' => false,
				'avgRating' => false,
				'successMsg' => false,
				'errorMsg' 	=> array(),
			);
			// variables
			$options = get_option( 'rmp_options' );
			$post_id = intval( $_POST['postID'] );
			$security_options = get_option( 'rmp_security' );
			$custom_strings = $this->custom_strings();
			$submitted_rating = intval( $_POST['star_rating'] );
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// if amp not enabled, exit
			if ( $options['ampCompatibility'] != 2 ) {
				die();
			}

			// security checks
			$security_passed = true;
			$privilege = $this->has_privileges( $security_options );
			$ip_check = $this->is_not_ip_double_vote( $security_options, $custom_strings, $post_id );
			$required_data = $this->all_rating_data_submitted( $post_id, $submitted_rating );
			$nonce_check = $this->has_valid_nonce( $nonce );
			$user_id_check = $this->is_not_user_id_double_vote( $security_options, $custom_strings, $post_id );

			$security_checks = array(
				$privilege,
				$ip_check,
				$required_data,
				$nonce_check,
				$user_id_check
			);

			foreach ($security_checks as $security_check) {
				if( ! $security_check['valid'] ) {
					$data['errorMsg'][] = $security_check['error'];
					$security_passed = false;
				}
			}

			if ( ! $security_passed ) { // security not passed, abort
				$vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
				$ratings_sum = Rate_My_Post_Common::get_sum_of_ratings( $post_id );
				if ( $vote_count && $ratings_sum ) {
					$avg_rating = Rate_My_Post_Common::calculate_average_rating( $ratings_sum, $vote_count );
				} else {
					$avg_rating = 0;
				}
				// echo current vote count and avg rating
				$data['voteCount'] = $vote_count;
				$data['avgRating'] = $avg_rating;
				$data['successMsg'] = '';
				echo json_encode( $data );
				die();
			}

			// security checks passed, continue
			$new_vote_count = $this->save_vote_count( $post_id );
			$new_rating = $this->save_rating( $post_id, $submitted_rating );
			// insert avg rating to post meta
			$post_meta_rating = $this->save_avg_rating( $post_id );
			// send email if enabled
			$this->send_email_rating( $post_id, $submitted_rating, $options );

			// get the return data
			if ( $new_vote_count && $new_rating ) { // already has ratings
				$avg_rating = Rate_My_Post_Common::calculate_average_rating( $new_rating, $new_vote_count );
			} else { // rated for the first time
				$avg_rating = $submitted_rating; // average equals to submitted
				$new_vote_count = 1; // vote count equals to 1
			}

			// save details for analytics
			$analytics = $this->save_for_analytics( $post_id, 1, -1, $avg_rating, $new_vote_count, $submitted_rating, $options, $security_options, true  );

			// new vote count, average rating and msg are used for live reload
			$data['voteCount'] = $new_vote_count;
			$data['avgRating'] = $avg_rating;
			$data['successMsg'] = $custom_strings['afterVote'];

			echo json_encode( $data );

			$this->clear_cache( $post_id, $options );
			do_action( 'rmp_after_vote_amp', $post_id, $avg_rating, $new_vote_count, $submitted_rating );
		};
		die();
	}

	//---------------------------------------------------
	// PROCESS FEEDBACK - FRONTEND AJAX
	//---------------------------------------------------

	public function process_feedback() {
		if ( wp_doing_ajax() ) {
			$options = get_option( 'rmp_options' );
			$customization = $this->custom_strings();
			// if feedback disabled, die
			if( $options['feedback'] !== 2 ) {
				die();
			}

			$data = array(
				'successMsg' => $customization['feedbackNotice'],
				'errorMsg' 	=> array(),
			);

			// variables
			$post_id = intval( $_POST['postID'] );
			$security_options = get_option( 'rmp_security' );
			$recaptcha_token = isset( $_POST['token'] ) ? $_POST['token'] : false;
			$rmp_token = isset( $_POST['rating_token'] ) ? $_POST['rating_token'] : false;
			$feedback = sanitize_text_field( $_POST['feedback'] );
			$rating_id = isset( $_POST['rating_id'] ) ? intval( $_POST['rating_id'] ) : false;
			$time = date( "d-m-Y H:i:s" );
			$user = $security_options['userTracking'] == 2 ? intval( get_current_user_id() ) : false;
			$nonce = isset( $_POST['nonce'] ) ? $_POST['nonce'] : false;

			// security checks
			$security_passed = true;
			$recaptcha = $this->is_recaptcha_valid( $recaptcha_token );
			$privilege = $this->has_privileges( $security_options );
			$rmp_token_check = $this->feedback_token_verified( $rmp_token, $rating_id );
			$feedback_length_check = $this->is_valid_length( $feedback );
			$nonce_check = $this->has_valid_nonce( $nonce );

			$security_checks = array(
				$recaptcha,
				$privilege,
				$rmp_token_check,
				$feedback_length_check,
				$nonce_check
			);

			foreach ($security_checks as $security_check) {
				if( ! $security_check['valid'] ) {
					$data['errorMsg'][] = $security_check['error'];
					$security_passed = false;
				}
			}

			if ( ! $security_passed ) {
				echo json_encode( $data );
				die();
			}
			// security checks passed, continue

			//fedback array
			$feedback_data = array(
				'feedback' => $feedback,
				'time'     => $time,
				'id'       => uniqid(),
				'user'     => $user,
				'ratingID' => $rating_id,
			);

			//insert feedback to post meta
			if ( ! add_post_meta( $post_id, 'rmp_feedback_val_new', array( $feedback_data ), true ) ) {
				// get the current feedback array
				$existing_feedback = get_post_meta( $post_id, 'rmp_feedback_val_new', true );
				if ( is_array( $existing_feedback ) ) { // feedback must be an array
					$existing_feedback[] = $feedback_data;
					update_post_meta( $post_id, 'rmp_feedback_val_new', $existing_feedback );
				}
			}
			//send email
			$this->send_email_feedback( $post_id, $feedback, $options );
			echo json_encode( $data );
			do_action( 'rmp_after_feedback', $post_id, $feedback );
		};
		die();
	}

	//---------------------------------------------------
	// RATINGS ON ARCHIVE PAGES
	//---------------------------------------------------

	public function ratings_archive_pages( $title ) {
		$options = get_option( 'rmp_options' );

		if ( ( $options['archivePages'] === 2 && is_archive() && in_the_loop() ) || ( $options['archivePages'] === 2 && is_home() && in_the_loop() ) ) { // show ratings
			//variables
			$vote_count = Rate_My_Post_Common::get_vote_count();
			$avg_rating = Rate_My_Post_Common::get_average_rating();
			$visual_rating = self::get_visual_rating();

			$html = '<span class="rmp-archive-results-widget">' . $visual_rating . ' <span>' . $avg_rating . ' (' . $vote_count . ')</span></span>';

			// filter to remove complete output
			if( has_filter('rmp_archive_results') ) {
        $html = apply_filters( 'rmp_archive_results', $html );
      }
			// if filtered to false return only the title
			if( ! $html ) {
				return $title;
			}
			return $title . $html; // return the title with ratings
		} else { // not archive or blog page, return only the title
			return $title;
		}
	}

	//---------------------------------------------------
	// HELPER METHODS FOR PROCESSING NEW RATINGS AND FEEDBACK
	//---------------------------------------------------

	// saves vote count to post meta
	private function save_vote_count( $post_id ) {
		if ( ! add_post_meta( $post_id, 'rmp_vote_count', 1, true ) ) {
			$existing_vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
			$new_vote_count = intval( $existing_vote_count + 1 );
			update_post_meta( $post_id, 'rmp_vote_count', $new_vote_count );
			return $new_vote_count;
		} else {
			return false;
		}
	}

	// saves rating to post meta
	private function save_rating( $post_id, $rating ) {
		if ( ! add_post_meta( $post_id, 'rmp_rating_val_sum', $rating, true ) ) {
			$existing_ratings_sum = Rate_My_Post_Common::get_sum_of_ratings( $post_id );
			$new_ratings_sum = intval( $existing_ratings_sum + $rating );
			update_post_meta( $post_id, 'rmp_rating_val_sum', $new_ratings_sum );
			return $new_ratings_sum;
		} else {
			return false;
		}
	}

	// saves avg rating as post meta
	private function save_avg_rating( $post_id ) {
		$average_rating = Rate_My_Post_Common::get_average_rating( $post_id );
		$post_meta = update_post_meta( $post_id, 'rmp_avg_rating', $average_rating );
		return $post_meta;
	}

	// sends email when post is rated
	private function send_email_rating( $post_id, $rating, $options ) {
		if ( $options['rate_email'] == 1 ) { // email disabled
			return;
		}
		// proceed
		$new_avg_rating = Rate_My_Post_Common::get_average_rating( $post_id );
		$new_vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
		$post_title = get_the_title( $post_id );
		$post_link = get_the_permalink( $post_id );
		$to = get_bloginfo( 'admin_email' );
		// messy translations
		$strSomebodyRated = esc_html__( 'Somebody rated', 'rate-my-post' );
		$subject = '[RMP]' . $strSomebodyRated . ' ' . get_the_title( $post_id );
		$strRated = esc_html__( 'was rated', 'rate-my-post' );
		$strHasRating = esc_html__( 'and now has an average rating of', 'rate-my-post' );
		$strBasedOn = esc_html__( 'based on', 'rate-my-post' );
		$strVotes = esc_html__( 'vote(s)', 'rate-my-post' );
		$strSeePost = esc_html__( 'See the post: ', 'rate-my-post' );

		$message = $post_title . ' ' . $strRated . ' ' . $rating . ' ' . $strHasRating . ' ' . $new_avg_rating . ' ' . $strBasedOn . ' ' . $new_vote_count . ' ' . $strVotes . '. ' . $strSeePost . $post_link;

		// filter for email receiver
		if( has_filter('rmp_mail_address') ) {
			$to = apply_filters( 'rmp_mail_address', $to );
		}
		// filter for email subject
		if( has_filter('rmp_mail_subject') ) {
			$subject = apply_filters( 'rmp_mail_subject', $subject, $post_id, $rating, $new_avg_rating, $new_vote_count, $post_title, $post_link );
		}
		// filter for email body
		if( has_filter('rmp_mail_text') ) {
			$message = apply_filters( 'rmp_mail_text', $message, $post_id, $rating, $new_avg_rating, $new_vote_count, $post_title, $post_link );
		}

		wp_mail( $to, $subject, $message );
	}

	// sends email when new feedback is submitted
	private function send_email_feedback( $post_id, $feedback, $options ) {
		if( $options['feedback_email'] == 1 ) { // feedback emails disabled
			return;
		}

		$to = get_bloginfo( 'admin_email' );
		$post_title = get_the_title( $post_id );
		// messy translations
		$strLeftFeedback = esc_html__( 'Somebody left feedback on', 'rate-my-post' );
		$strFeedbackOn = esc_html__( 'Feedback on', 'rate-my-post' );
		$subject = '[RMP]' . $strLeftFeedback . ' ' . $post_title;
		$message = $strFeedbackOn . ' ' . $post_title . ': ' . $feedback;

		// filter for email receiver
		if( has_filter('rmp_mail_address') ) {
			$to = apply_filters( 'rmp_mail_address', $to );
		}
		// filter for email subject
		if( has_filter('rmp_feedback_mail_subject') ) {
			$subject = apply_filters( 'rmp_feedback_mail_subject', $subject, $post_id, $feedback, 	$post_title );
		}
		// filter for email body
		if( has_filter('rmp_feedback_mail_text') ) {
			$message = apply_filters( 'rmp_feedback_mail_text', $message, $post_id, $feedback, 	$post_title );
		}

		wp_mail( $to, $subject, $message);
	}

	// save to the analytics table
	private function save_for_analytics( $post_id, $action, $duration = 0, $avg_rating, $votes, $rating, $options, $security, $amp ) {

		// declare variables
		$ip = -1;
		$user = -1;
		$token = -1;

		// get rater's username
		if ( $security['userTracking'] == 2 ) {
			$user = intval( get_current_user_id() );
		}
		// get rater's ip
		if ( $security['ipTracking'] == 2 ) {
			$ip = sanitize_text_field( $this->get_user_ip() );
		}
		$ip = $ip ? $ip : false;

		// get country - to be finished
		$country = 0;

		// token for feedback
		if( ( $options['feedback'] === 2 ) && ( $rating <= $options['positiveNegative'] ) && ! $amp ) {
			$token = md5( uniqid( rand(), true ) );
		}
		//insert data in database
		global $wpdb;
		//table name
		$table_name = $wpdb->prefix . 'rmp_analytics';
		//insert into the table
		$wpdb->insert(
			$table_name,
			array(
				'time' => current_time('mysql', false),
				'ip' => $ip,
				'country' => $country,
				'user' => $user,
				'post' => $post_id,
				'action' => $action,
				'duration' => $duration,
				'average' => $avg_rating,
				'votes' => $votes,
				'value' => $rating,
				'token' => $token
			),
			array(
				'%s',
				'%s',
				'%s',
				'%d',
				'%d',
				'%d',
				'%d',
				'%f',
				'%d',
				'%d',
				'%s',
			)
		);

		return array( 'id' => $wpdb->insert_id, 'token' => $token );
	}

	// get rater's ip address
	private function get_user_ip() {
		$ip = false;
		if( array_key_exists( 'HTTP_X_FORWARDED_FOR', $_SERVER ) && !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
        if ( strpos( $_SERVER['HTTP_X_FORWARDED_FOR'], ',' ) > 0 ) {
            $addr = explode( ',',$_SERVER['HTTP_X_FORWARDED_FOR'] );
            $ip = trim( $addr[0] );
        } else {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

		return filter_var( $ip, FILTER_VALIDATE_IP );
	}

	//---------------------------------------------------
	// TEMPLATE METHODS
	//---------------------------------------------------

	// retrieves the rating widget template
	private function get_the_rating_widget( $post_id = false ) {

		if( ! $this->display_widget( 'rmp_display_rating_widget' ) ) {
			return;
		}

		// allow custom templates
		$rating_widget = locate_template( 'rate-my-post/rating-widget.php' );
		if( ! $rating_widget ) {
			$rating_widget = plugin_dir_path( __FILE__ ) . 'templates/rating-widget.php';
		}

		$rating_widget_amp = locate_template( 'rate-my-post/amp-rating-widget.php' );
		if( ! $rating_widget_amp ) {
			$rating_widget_amp = plugin_dir_path( __FILE__ ) . 'templates/amp-rating-widget.php';
		}

		if( $this->is_amp_page() && $this->is_amp_enabled() ) { // amp
			ob_start();
			include $rating_widget_amp;
			return $this->remove_line_breaks( ob_get_clean() );
		} elseif( ! $this->is_amp_page() ) { // no amp
			ob_start();
			include $rating_widget;
			return $this->remove_line_breaks( ob_get_clean() );
		}
	}

	// retrieves the results widget template
	private function get_the_results_widget( $post_id = false ) {
		if( ! $this->display_widget( 'rmp_display_results_widget' ) ) {
			return;
		}
		// allow custom templates
		$results_widget = locate_template( 'rate-my-post/results-widget.php' );
		if( ! $results_widget ) {
			$results_widget = plugin_dir_path( __FILE__ ) . 'templates/results-widget.php';
		}

		$results_widget_amp = locate_template( 'rate-my-post/amp-results-widget.php' );
		if( ! $results_widget_amp ) {
			$results_widget_amp = plugin_dir_path( __FILE__ ) . 'templates/amp-results-widget.php';
		}

		if ( $this->is_amp_page() && $this->is_amp_enabled() ) { // amp
			ob_start();
			include $results_widget_amp;
			return $this->remove_line_breaks( ob_get_clean() );
		} elseif( ! $this->is_amp_page() ) { // non amp
			ob_start();
			include $results_widget;
			return $this->remove_line_breaks( ob_get_clean() );
		}
	}

	// outputs the social widget
	private function social_widget() {
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/social-widget.php';
		return ob_get_clean();
	}

	// outputs the feedback widget
	private function feedback_widget() {
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/feedback-widget.php';
		return ob_get_clean();
	}

	// remove line breaks from a string to avoid issues with wpautop
	private function remove_line_breaks( $string ) {
		$string = str_replace( array( "\r", "\n" ), '', $string);
		return $string;
	}

	// check if on amp page
	private function is_amp_page() {
    if ( function_exists( 'ampforwp_is_amp_endpoint' ) && ampforwp_is_amp_endpoint() ) {
      return true;
    }
    if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
      return true;
    }
    return false;
  }

	// check if amp compatibility mode is enabled
	private function is_amp_enabled() {
		$options = get_option( 'rmp_options' );
    if ( $options['ampCompatibility'] == 2 ) {
      return true;
    }
    return false;
	}

	// check if widget should be displayed

	private function display_widget( $filter ) {
		// if( is_archive() ) {
		// 	return false; // rating widget is not supported on archive pages
		// }
		$display = true;
		if( has_filter( $filter ) ) {
			$display = apply_filters( $filter, $display );
		}
		return $display;
	}

	//---------------------------------------------------
	// SECURITY METHODS
	//---------------------------------------------------

	// get recaptcha score
	private function get_recaptcha_score( $token ) {
		$response = $token;
		$rmp_security = get_option( 'rmp_security' );
		$secret = $rmp_security['secretKey'];
		$recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify';
		// final url
		$url = $recaptchaUrl . '?secret=' . $secret . '&response=' . $response;
		// get the response
		$request = wp_remote_post( $url );
		$recaptcha = wp_remote_retrieve_body( $request );
		$recaptcha = json_decode( $recaptcha );
		// return recaptcha score - 1.0 is very likely a good interaction, 0.0 is very likely a bot
		if ( property_exists( $recaptcha, 'score' ) ) {
			// error_log($recaptcha->score, 0);
			return $recaptcha->score;
		} else {
			// key is probably incorrect
			return 'checkKeys';
		}
	}

	// ip check for double votes
	private function is_ip_double_vote( $post_id ) {
		// get the voter ip address
		$rater_ip = $this->get_user_ip();
		// check for post and ip match
		global $wpdb;
		$analytics_table = $wpdb->prefix . "rmp_analytics";
		$match = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $analytics_table WHERE post = %d AND ip = %s", array( $post_id, $rater_ip ) ) );

		// filter allows us to disable ip check for certain conditions (admin, editor and such)
		if( has_filter('rmp_double_vote') ) {
			$match = apply_filters( 'rmp_double_vote', $match, $post_id );
		}
		// disable for admins
		$is_admin = current_user_can( 'manage_options' );
		// filter for admins
		if( has_filter('rmp_admin_double_vote') ) {
			$is_admin = apply_filters( 'rmp_admin_double_vote', $is_admin );
		}
		if ( count( $match ) && ! $is_admin ) { //this is a double vote
			return true;
		}
		return false;
	}

	// verfies that vote has been made before feedback is submitted
	private function feedback_token_verified( $token, $id ) {
		$data = array(
			'valid' => false,
			'error' => false,
		);

		global $wpdb;
		$analytics_table = $wpdb->prefix . "rmp_analytics";
		$results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $analytics_table WHERE id = %d", array( $id ) ) );

		if( count( $results ) === 1 ) { // matching row found
			$row_object = $results[0];
			$db_token = $row_object->token;
			$acceptableTime = strtotime( $row_object->time ) + 300; // rater has 5 min to leave feedback
			$currentTime = time();
			// if( ( $token === $db_token ) && ( $db_token != '-1' ) && ( $acceptableTime > $currentTime ) ) { on certain server configurations times don't match - to be investigated
			if( ( $token === $db_token ) && ( $db_token != '-1' ) ) {
				$data['valid'] = true;
			} else {
				$data['valid'] = false;
				$data['error'] = esc_html__( 'Invalid Token', 'rate-my-post' );
			}
		} else {
			$data['valid'] = false;
			$data['error'] = esc_html__( 'Invalid Token', 'rate-my-post' );
		}

		// filter allows us to disable verification
		if( has_filter('rmp_verify_feedback') ) {
			$data = apply_filters( 'rmp_verify_feedback', $data );
		}
		return $data;
	}

	// check whether interaction is by a human according to google recaptcha
	private function is_recaptcha_valid( $recaptcha_token ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		if( $this->do_recaptcha() !== 2 ) { // recaptcha disabled
				return $data;
		}

		$score = $this->get_recaptcha_score( $recaptcha_token );

		if ( $score === 'checkKeys' ) {
			$data['error'] = esc_html__( 'Wrong reCAPTCHA keys', 'rate-my-post' );
			$data['valid'] = false;
		}

		if ( $score < 0.5 ) {
			$data['error'] = esc_html__( 'Blocked by reCAPTCHA', 'rate-my-post' );
			$data['valid'] = false;
		}

		return $data;
	}

	// check if user has permission to interact
	private function has_privileges( $security_options ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		if ( $security_options['votingPriv'] == 1 ) { // everybody can vote
			return $data;
		}

		if ( ! is_user_logged_in() ) {
			$data['error'] = esc_html__( 'You need to be logged in to rate!', 'rate-my-post' );
			$data['valid'] = false;
		}

		return $data;
	}

	// check if IP address has permission to interact with this post
	private function is_not_ip_double_vote( $security_options, $custom_strings, $post_id ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		if ( $security_options['ipDoubleVote'] == 1 ) { // ip protection disabled
			return $data;
		}

		if( $this->is_ip_double_vote( $post_id ) ) { // is double vote
			$data['error'] = $custom_strings['cookieNotice'];
			$data['valid'] = false;
		}
		return $data;
	}

	// check if user has permission to interact with this post
	private function is_not_user_id_double_vote( $security_options, $custom_strings, $post_id ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		if( $security_options['userTracking'] == 1 || ! get_current_user_id() ) { // no need for verification - either disabled or user is not logged in
			return $data;
		}

		if( $this->is_user_id_double_vote( $post_id ) ) { // is double vote
			$data['error'] = $custom_strings['cookieNotice'];
			$data['valid'] = false;
		}
		return $data;
	}

	//check if logged in user already rated certain post
	private function is_user_id_double_vote( $post_id ) {
		// get the voter user id
		$user_id = get_current_user_id();
		// check for post and id match
		global $wpdb;
		$analytics_table = $wpdb->prefix . "rmp_analytics";
		$match = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $analytics_table WHERE post = %d AND user = %d", array( $post_id, $user_id ) ) );

		// filter allows us to disable id check
		if( has_filter('rmp_double_vote_by_id') ) {
			$match = apply_filters( 'rmp_double_vote_by_id', $match, $post_id );
		}
		// disable id check for admins
		$is_admin = current_user_can( 'manage_options' );

		if ( count( $match ) && ! $is_admin ) { //this is a double vote
			return true;
		}
		return false;
	}

	// check public nonce
	private function has_valid_nonce( $nonce ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		if( ! wp_verify_nonce( $nonce, 'rmp_public_nonce' ) ) {
			$data['error'] = esc_html__( 'Invalid nonce!', 'rate-my-post' );
			$data['valid'] = false;
		}

		return $data;

	}

	// check if rating and post id were submitted
	private function all_rating_data_submitted( $post_id, $rating ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);

		$max_rating = Rate_My_Post_Common::max_rating();

		if( $post_id && $rating <= $max_rating && $rating > 0 ) {
			return $data;
		}

		if( ! $post_id ) { // post id missing
			$data['error'] = esc_html__( 'You cannot rate a web page without an id!', 'rate-my-post' );
			$data['valid'] = false;
		}

		$data['error'] = esc_html__( 'No rating submitted!', 'rate-my-post' );
		$data['valid'] = false;

		return $data;
	}

	// verify feedback length
	private function is_valid_length( $feedback ) {
		$data = array(
			'valid' => true,
			'error' => false,
		);
		if( ! str_replace( ' ', '', $feedback ) ) {
			$data['error'] = esc_html__( 'Please insert your feedback in the box above!', 'rate-my-post' );
			$data['valid'] = false;
		}
		return $data;
	}

	// check if recaptcha is enabled
  private function do_recaptcha() {
    $security = get_option( 'rmp_security' );
    $recaptcha = intval( $security['recaptcha'] );
    $siteKey = str_replace( ' ', '', $security['siteKey'] );
    $secretKey = str_replace( ' ', '', $security['secretKey'] );
    if ( $recaptcha === 2 && $siteKey && $secretKey ) {
      return 2;
    } else {
      return 1;
    }
  }

	//---------------------------------------------------
	// CACHE COMPATIBILITY METHODS
	//---------------------------------------------------

	// clears cache for the most popular caching plugins
	private function clear_cache( $post_id, $options ) {
		if( $options['ajaxLoad'] == 2 ) { // ajax loading, no need to clear cache
			return;
		}
		// WP Super Cache
		if ( function_exists( 'wp_cache_post_change' ) ) {
			wpsc_delete_post_cache( $post_id );
		}
		// WP Rocket
		if ( function_exists( 'rocket_clean_post' ) ) {
			rocket_clean_post( $post_id );
		}
		// LiteSpeed Cache
		if ( method_exists( 'LiteSpeed_Cache_API', 'purge_post' ) ) {
			LiteSpeed_Cache_API::purge_post( $post_id );
		}
		// WP Fastest Cache
		if ( function_exists( 'wpfc_clear_post_cache_by_id' ) ) {
			wpfc_clear_post_cache_by_id( $post_id );
		}
		// SG Optimizer
		if ( function_exists( 'sg_cachepress_purge_cache' ) ) {
			$url = get_permalink( $post_id );
			sg_cachepress_purge_cache( $url );
		}
		//W3TC
		if ( function_exists(	'w3tc_flush_post'	)	){
			w3tc_flush_post( $post_id );
		}
	}

	//---------------------------------------------------
	// SCHEMA METHODS
	//---------------------------------------------------

	// returns selected schema type
	private function schema_type() {
		$options = get_option( 'rmp_options' );
		$schema_type = $options['structuredDataType'];

		if( has_filter('rmp_schema_type') ) {
			$schema_type = apply_filters( 'rmp_schema_type', $schema_type );
		}

		if( $schema_type === 'none' ) {
			return false;
		}

		return $schema_type;
	}

	// outputs the complete structured data
	private function structured_data() {
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/structured-data.php';
		$structured_data = ob_get_clean();

		if( has_filter('rmp_structured_data') ) {
			$structured_data = apply_filters( 'rmp_structured_data', $structured_data );
		}
		return $structured_data;
	}

	//---------------------------------------------------
	// CUSTOMIZATION METHODS
	//---------------------------------------------------

	// outputs an array of custom strings for templates - takes internationalization into considerations
  private function custom_strings() {
    $options = get_option( 'rmp_options' );

    if ( $options['multiLingual'] != 2 ) { // multilingual website compatibility mode is disabled
      $custom_strings = get_option( 'rmp_customize_strings' );
      // strip backslashes and escape html
      foreach( $custom_strings as $key => $value ) {
        $custom_strings[$key] = stripslashes( esc_html( $value ) );
      }
    } else { // multilingual website compatibility mode is enabled
      $custom_strings = $this->multilingual_strings();
      // strip backslashes and escape html
      foreach( $custom_strings as $key => $value ) {
        $custom_strings[$key] = stripslashes( esc_html( $value ) );
      }
    }
		if( has_filter('rmp_custom_strings') ) { // apply filters
			$custom_strings = apply_filters( 'rmp_custom_strings', $custom_strings );
		}
		return $custom_strings;
  }

	// returns custom results text if inserted
  private function rating_widget_results_text( $options, $avg_rating = false, $vote_count = false ) {
    $customization = get_option( 'rmp_customize_strings' );
    $results_text = stripslashes( esc_html( $customization['customResultsText'] ) );
		$max_rating = Rate_My_Post_Common::max_rating();

		if( $options['notShowRating'] == 2 ) {
			return '';
		}

		if( $options['ajaxLoad'] == 2 && ! $this->is_amp_page() ) {
			$vote_count = '';
			$avg_rating = '';
		}

    if ( str_replace( ' ', '', $results_text ) ) { // custom results text exists
      $results_text = str_replace( '{{votecount}}','<span class="rmp-rating-widget__results__votes js-rmp-vote-count">' . $vote_count .'</span>', $results_text );
      $results_text = str_replace( '{{avgrating}}','<span class="rmp-rating-widget__results__rating js-rmp-avg-rating">' . $avg_rating . '</span>', $results_text );
      }
			else { // generic results text
			$results_text = $customization['rateResult'] . ' <span class="rmp-rating-widget__results__rating js-rmp-avg-rating">' . $avg_rating . '</span> / ' . $max_rating . '. ' . $customization['rateResult2'] . ' <span class="rmp-rating-widget__results__votes js-rmp-vote-count">' . $vote_count . '</span>';
		}
    return $results_text;
  }

	// creates internal style for overriding the default style
	private function internal_css() {
		$basic_options = get_option( 'rmp_options' );
		$customization_options = get_option( 'rmp_customize_strings' );
		foreach ( $customization_options as $key => $value ) {
			$customization_options[$key] = esc_html( str_replace( ' ', '', $value ) );
		}
		ob_start();
		include plugin_dir_path( __FILE__ ) . 'templates/internal-css.php';
		return trim( preg_replace('/\t+/', '', $this->remove_line_breaks( ob_get_clean() ) ) );
	}

	// icon class
  public static function icon_type() {
    $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--star';
    $options = get_option( 'rmp_options' );
    if ( $options['icon_type'] == 3 ) {
      // hearts
      $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--heart';
    } elseif ( $options['icon_type'] == 2 ) {
      // thumbs
      $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--thumbs-up';
    } elseif ( $options['icon_type'] == 4 ) {
      // smileys
      $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--smile-o';
    } elseif ( $options['icon_type'] == 5 ) {
      // thumbs
      $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--trophy';
    } else {
      // stars default
      $icon_type = 'rmp-icon rmp-icon--ratings rmp-icon--star';
    }
    if( has_filter('rmp_rating_icon_class') ) {
      $icon_type = apply_filters( 'rmp_rating_icon_class', $icon_type );
    }
    return $icon_type;
  }

	//---------------------------------------------------
	// SOCIAL WIDGET METHODS
	//---------------------------------------------------

	// generates social sharing links
  private function social_share_links() {
    $social_links = array();
    $title = '';
    $url = '';
    $image = '';
    // get the necessary data
    $title = urlencode( get_the_title() );
    $url = urlencode( get_the_permalink() );
    $image = urlencode( get_the_post_thumbnail_url( get_the_id(), 'full' ) );

    // create social share links
    $social_links['facebook'] = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $social_links['pinterest'] = 'https://pinterest.com/pin/create/bookmarklet/?media=' . $image . '&url=' . $url . '&description=' . $title;
    $social_links['twitter'] = 'http://twitter.com/share?url=' . $url . '&text=' . $title;
    $social_links['reddit'] = 'http://www.reddit.com/submit?url=' . $url . '&title=' . $title;
    $social_links['linkedin'] = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title . '&source=LinkedIn';

    if( has_filter('rmp_social_links') ) {
      $social_links = apply_filters( 'rmp_social_links', $social_links );
    }
    return $social_links;
  }

	//---------------------------------------------------
	// TOP RATED POSTS METHODS
	//---------------------------------------------------

	// returns an array of top rated posts
  public static function top_rated_posts( $max_posts = 10, $required_rating = 1,  $required_votes = 1 ) {
    $rated_posts = array();
    $top_rated_posts = array();

    // get post types for the query
    $registered_post_types = get_post_types( array( 'public' => true ) );
    if ( array_search('attachment', $registered_post_types ) ) {
      unset( $registered_post_types['attachment'] );
    }

    $post_types = array_values( $registered_post_types );
    // args for the loop
    $args = array(
       'fields'          => 'ids',
       'post_type'       => $post_types,
       'posts_per_page'  => -1
    );

    $the_query = new WP_Query( $args );

    if ( $the_query->have_posts() ) {
      while ( $the_query->have_posts() ) {
        $the_query->the_post();
        //data we'll need
        $post_id = get_the_id();
        $avg_rating = intval( Rate_My_Post_Common::get_average_rating() * 10 ); //floats are hassle
        $vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
        //save post ids and average rating
        if ( $avg_rating && $avg_rating >= ( $required_rating * 10 ) && $vote_count && $vote_count >= $required_votes ) {
          $rated_posts[$post_id] = $avg_rating;
        }
      }
      // sort by averatge rating
      arsort( $rated_posts );
      // reorganize to output the top rated posts
      $count = 0;
      foreach ( $rated_posts as $key => $value ) {
        $count++;

        $post_id = $key;
        $avg_rating = $value / 10;
        $title = get_the_title( $post_id );
        $link = get_the_permalink( $post_id );
        $thumb = get_the_post_thumbnail_url( $post_id );
        $votes = Rate_My_Post_Common::get_vote_count( $post_id );

        $top_rated_posts[] = array(
          'postID'    => $key,
          'avgRating' => $avg_rating,
          'title'     => $title,
          'postLink'  => $link,
          'thumb'     => $thumb,
          'votes'     => $votes,
        );
        // only output the defined number
        if( $count >= $max_posts ) {
          break;
        }
      } // end foreach

      // restore original post data
      wp_reset_postdata();
    }
    return $top_rated_posts;
  }

	//---------------------------------------------------
	// VISUAL RATING METHODS
	//---------------------------------------------------

	// returns visual rating for post
  public static function get_visual_rating ( $post_id = false, $extra_class = false, $is_results_widget = false ) {
   if ( ! $post_id ) {
     $post_id = get_the_id();
   }
	 // allow custom templates
	 $template = locate_template( 'rate-my-post/visual-rating.php' );
	 if( ! $template ) {
		 $template = plugin_dir_path( __FILE__ ) . 'templates/visual-rating.php';
	 }

	 ob_start();
	 include $template;
	 $visual_rating = str_replace( array( "\r", "\n" ), '', ob_get_clean() );
	 $visual_rating = trim( preg_replace('/\t+/', '', $visual_rating ) );
	 return $visual_rating;
  }

	// how many stars to highlight
	public static function icons_type_count( $post_id ) {
		$average_rating = Rate_My_Post_Common::get_average_rating( $post_id );
		$max_rating = Rate_My_Post_Common::max_rating();
		if( ! $average_rating ) { // not yet rated - return false
			return false;
		}

		$icons_highlighted = floor( $average_rating ); // these are for sure highlighted
		$icons_half_highlighted = 0;
		$icons_empty = 0;

		// let's not compare floats
		$decimals = intval( ( $average_rating * 10 ) - ( $icons_highlighted * 10 ) );

		// determine whether we need a half highlighted icon
		if ( $decimals > 7 ) {
			$icons_highlighted = $icons_highlighted + 1;
		} elseif ( $decimals <= 7 && $decimals >= 3 ) {
			$icons_half_highlighted = 1;
		};

		// calculate how many empty icons we need
		$icons_empty = $max_rating - $icons_highlighted - $icons_half_highlighted;

		$count = array(
			'fullIcons'     => intval( $icons_highlighted ),
			'halfIcons' 		=> intval( $icons_half_highlighted ),
			'emptyIcons'    => intval( $icons_empty ),
			'avgRating'			=> $average_rating
		);
		return $count;
	}

	// returns an organized array of icon classes for highlighting
  public static function icons_classes( $post_id = false, $is_rating_widget = false, $is_results_widget = false) {
		$options = get_option( 'rmp_options' );
		$max_rating = Rate_My_Post_Common::max_rating();
    if ( ! $post_id ) {
      $post_id = get_the_id();
    }

    $icons_type = self::icons_type_count( $post_id );
		$classes = array();

		// No highlight - if "Do not show average rating" is enabled
		if( $is_rating_widget && $options['notShowRating'] == 2 ) {
			$classes = array_fill( 0, $max_rating, '' );
			return $classes;
		}

		// No highlight - if "Ajax load" is enabled
		if( $options['ajaxLoad'] == 2 && ( $is_rating_widget || $is_results_widget ) ) {
			$classes = array_fill( 0, $max_rating, '' );
			return $classes;
		}

		if( ! $icons_type ) { // post hasn't been rated yet - all icons are empty
			$classes = array_fill( 0, $max_rating, '' );
			return $classes;
		}

    $icons_highlighted = $icons_type['fullIcons']; // number of highlighted icons
    $icons_half_highlighted = $icons_type['halfIcons']; // number of half highlighted icons
    $icons_empty = $icons_type['emptyIcons']; // number of empty icons

		// Classes for browser compatibility
		$average_rating = $icons_type['avgRating'];
		$browser_compatibility_class = '';
		if( (substr( $average_rating * 10 , 1) ) >= $max_rating ) {
			$browser_compatibility_class = 'js-rmp-replace-half-star';
		} else {
			$browser_compatibility_class = 'js-rmp-remove-half-star';
		}

    $icons_highlighted_array = array();
    $icons_half_highlighted_array = array();
    $icons_empty_array = array();

    if( $icons_highlighted ) {
      $icons_highlighted_array = array_fill( 0, $icons_highlighted, 'rmp-icon--full-highlight' );
    }
    if( $icons_half_highlighted ) {
      $icons_half_highlighted_array = array_fill( $icons_highlighted, $icons_half_highlighted, 'rmp-icon--half-highlight ' . $browser_compatibility_class );
    }
    if( $icons_empty ) {
      $icons_empty_array = array_fill( $icons_highlighted + $icons_half_highlighted, $icons_empty, '' );
    }

    $classes = array_merge( $icons_highlighted_array, $icons_half_highlighted_array, $icons_empty_array );

    return $classes;
  }

	//---------------------------------------------------
	// INTERNATIONALIZATION METHODS
	//---------------------------------------------------

	// makes all strings translatable in multilingual compatibility mode
  private function multilingual_strings() {
    $translatable_strings = array(
      'rateTitle'             => esc_html__( 'How useful was this post?', 'rate-my-post' ),
      'rateSubtitle'          => esc_html__( 'Click on a star to rate it!', 'rate-my-post' ),
      'rateResult'            => esc_html__( 'Average rating', 'rate-my-post' ),
      'rateResult2'           => esc_html__( 'Vote count:', 'rate-my-post' ),
      'cookieNotice'          => esc_html__( 'You already voted! This vote will not be counted!', 'rate-my-post' ),
      'noRating'              => esc_html__( 'No votes so far! Be the first to rate this post.', 'rate-my-post' ),
      'afterVote'             => esc_html__( 'Thank you for rating this post!', 'rate-my-post' ),
      'star1'                 => esc_html__( 'Not at all useful', 'rate-my-post' ),
      'star2'                 => esc_html__( 'Somewhat useful', 'rate-my-post' ),
      'star3'                 => esc_html__( 'Useful', 'rate-my-post' ),
      'star4'                 => esc_html__( 'Fairly useful', 'rate-my-post' ),
      'star5'                 => esc_html__( 'Very useful', 'rate-my-post' ),
      'socialTitle'           => esc_html__( 'As you found this post useful...', 'rate-my-post' ),
      'socialSubtitle'        => esc_html__( 'Follow us on social media!', 'rate-my-post' ),
      'feedbackTitle'         => esc_html__( 'We are sorry that this post was not useful for you!', 'rate-my-post' ),
      'feedbackSubtitle'      => esc_html__( 'Let us improve this post!', 'rate-my-post' ),
      'feedbackText'          => esc_html__( 'Tell us how we can improve this post?', 'rate-my-post' ),
      'feedbackNotice'        => esc_html__( 'Thanks for your feedback!', 'rate-my-post' ),
      'feedbackButton'        => esc_html__( 'Submit Feedback', 'rate-my-post' ),
      'feedbackAlert'         => esc_html__( 'Please insert your feedback in the box above!', 'rate-my-post' ),
      'submitButtonText'      => esc_html__( 'Submit Rating', 'rate-my-post' ),
    );
    return $translatable_strings;
  }

} //end of class
