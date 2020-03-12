<?php

/**
 * Rating Widget for AMP
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.3.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>
<?php
  $post_id = ( $post_id ) ? $post_id : get_the_id();
  $vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
  $avg_rating = Rate_My_Post_Common::get_average_rating( $post_id );
  $visual_rating = self::get_visual_rating( $post_id );
?>
<!-- Rate my Post Plugin - Results Widget -->
<div class="rmp-amp-results-widget">
  <div class="rmp-amp-results-widget__stars">
    <?php echo $visual_rating; ?>
  </div>
  <div class="rmp-amp-results-widget__average-rating">
    <span><?php echo $avg_rating; ?></span>
  </div>
  <div class="rmp-amp-results-widget__vote-count">
    <span><?php echo '(' . $vote_count . ')'; ?></span>
  </div>
</div>
