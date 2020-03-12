<?php

/**
 * Public functions for developers
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.5.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/includes
 */

 // returns average rating for any post
 function rmp_get_avg_rating( $post_id = false ) {
   if ( ! $post_id ) {
     $post_id = get_the_id();
   }

   return Rate_My_Post_Common::get_average_rating( $post_id );
 }

 // returns vote count for any post
 function rmp_get_vote_count( $post_id = false ) {
   if ( ! $post_id ) {
     $post_id = get_the_id();
   }

   return Rate_My_Post_Common::get_vote_count( $post_id );
 }

 // returns visual rating for any post
 function rmp_get_visual_rating( $post_id = false ) {
   if ( ! $post_id ) {
     $post_id = get_the_id();
   }

   return Rate_My_Post_Public::get_visual_rating( $post_id );
 }

 // returns an array of top rated posts
 function rmp_get_top_rated_posts( $max_posts = 10, $required_rating = 1,  $required_votes = 1 ) {
   return Rate_My_Post_Public::top_rated_posts( $max_posts, $required_rating, $required_votes );
 }

 // LEGACY METHODS - to be removed in future versions!!!

 class Rate_My_Post_Public_Helper {

 	// Plugin name - string
 	private $rate_my_post;

 	// Plugin version - string
 	private $version;

 	// Init
 	public function __construct( $rate_my_post, $version ) {
 		$this->rate_my_post = $rate_my_post;
 		$this->version = $version;
 	}

  // legacy get visual rating
  public static function get_visual_rating( $post_id = false ) {
    if ( ! $post_id ) {
      $post_id = get_the_id();
    }
    trigger_error("Rate_My_Post_Public::get_visual_rating has been deprecated and will be removed in future versions. Use rmp_get_visual_rating instead.", E_USER_NOTICE);
    return Rate_My_Post_Public::get_visual_rating( $post_id );
  }

  // legacy get top rated posts
  public static function top_rated_posts( $max_posts = 10, $required_rating = 1,  $required_votes = 1 ) {
    trigger_error("Rate_My_Post_Public::top_rated_posts has been deprecated and will be removed in future versions. Use rmp_get_top_rated_posts instead.", E_USER_NOTICE);
    return Rate_My_Post_Public::top_rated_posts( $max_posts, $required_rating, $required_votes );
  }

}
