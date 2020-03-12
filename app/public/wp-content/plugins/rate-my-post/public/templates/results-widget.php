<?php

/**
 * Public template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>

<?php
  $post_id = ( $post_id ) ? $post_id : get_the_id();
  $options = get_option( 'rmp_options' );
  $avg_rating = Rate_My_Post_Common::get_average_rating( $post_id );
  $vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
  $visual_rating = self::get_visual_rating( $post_id, 'js-rmp-results-icon', true );
  $ajax_load = false;

  if ( $options['ajaxLoad'] == 2 ) {
    $ajax_load = true;
  }
?>

<!-- Rate my Post Plugin - Results Widget -->
<div
  class="rmp-results-widget js-rmp-results-widget js-rmp-results-widget--<?php echo $post_id; ?> <?php echo ( $avg_rating ) ? '' : 'rmp-results-widget--not-rated'; ?>"
  data-post-id="<?php echo $post_id; ?>"
>
  <div class="rmp-results-widget__visual-rating">
    <?php echo $visual_rating; ?>
  </div>
  <div class="rmp-results-widget__avg-rating">
    <span class="js-rmp-avg-rating">
      <?php echo ( ! $ajax_load ) ? $avg_rating : ''; ?>
    </span>
  </div>
  <div class="rmp-results-widget__vote-count">
    (<span class="js-rmp-vote-count"><?php echo ( ! $ajax_load ) ? $vote_count : ''; ?></span>)
  </div>
</div>
