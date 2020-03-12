<?php

/**
 * Methods for both public and admin side
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      3.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/common
 */

class Rate_My_Post_Common {

  // Plugin name - string
	private $rate_my_post;

	// Plugin version - string
	private $version;

	// Init
	public function __construct( $rate_my_post, $version ) {
		$this->rate_my_post = $rate_my_post;
		$this->version = $version;
	}

  // post's vote count
  public static function get_vote_count( $post_id = false ) {
    if( ! $post_id ) {
      $post_id = get_the_id();
    }
    return intval( get_post_meta( $post_id, 'rmp_vote_count', true ) );
  }

  // post's sum of ratings
  public static function get_sum_of_ratings( $post_id = false ) {
    if( ! $post_id ) {
      $post_id = get_the_id();
    }
    return intval( get_post_meta( $post_id, 'rmp_rating_val_sum', true ) );
  }

  // calculate average rating
  public static function calculate_average_rating( $sum_of_ratings, $vote_count ) {
    if( ! $sum_of_ratings || ! $vote_count ) {
      return 0;
    }

    return round( ( $sum_of_ratings / $vote_count ), 1 );
  }

	// returns average rating
	public static function get_average_rating( $post_id = false ) {
    if( ! $post_id ) {
      $post_id = get_the_id();
    }
    $ratings_sum = self::get_sum_of_ratings( $post_id );
    $vote_count = self::get_vote_count( $post_id );

    if( $ratings_sum && $vote_count ) {
      return round( ( $ratings_sum / $vote_count ), 1 );
    } else {
      return 0;
    }
  }

	// returns max rating
	public static function max_rating() {
		$max_rating = 5;
		if( has_filter('rmp_max_rating') ) {
			$max_rating = apply_filters( 'rmp_max_rating', $max_rating );
		}
		return intval($max_rating);
	}

} //end of class
