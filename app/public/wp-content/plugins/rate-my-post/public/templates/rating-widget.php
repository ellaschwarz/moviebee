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
  // variables
  $post_id = ( $post_id ) ? $post_id : get_the_id();
  $rmp_options = get_option( 'rmp_options' );
  $rmp_custom_strings = $this->custom_strings();
  $rating_icon_type = self::icon_type();
  $avg_rating = Rate_My_Post_Common::get_average_rating( $post_id );
  $vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
  $icon_classes = self::icons_classes( $post_id, true );
  $results_text = $this->rating_widget_results_text( $rmp_options, $avg_rating, $vote_count );
  $max_rating = Rate_My_Post_Common::max_rating();
?>

<!-- Rate my Post Plugin -->
<div
  class="rmp-widgets-container rmp-wp-plugin rmp-main-container js-rmp-widgets-container js-rmp-widgets-container--<?php echo $post_id; ?>"
  data-post-id="<?php echo $post_id; ?>"
>
  <?php do_action( 'rmp_before_all_widgets' ); ?>
  <!-- Rating widget -->
  <div class="rmp-rating-widget js-rmp-rating-widget">

    <?php if( str_replace( ' ', '', $rmp_custom_strings['rateTitle'] ) ): ?>
      <p class="rmp-heading rmp-heading--title">
        <?php echo $rmp_custom_strings['rateTitle']; ?>
      </p>
    <?php endif; ?>

    <?php if( str_replace( ' ', '', $rmp_custom_strings['rateSubtitle'] ) ): ?>
      <p class="rmp-heading rmp-heading--subtitle">
        <?php echo $rmp_custom_strings['rateSubtitle']; ?>
      </p>
    <?php endif; ?>

    <div class="rmp-rating-widget__icons">
      <ul class="rmp-rating-widget__icons-list js-rmp-rating-icons-list">
        <?php for ( $icons_count = 0; $icons_count < $max_rating; $icons_count++ ): ?>
          <li class="rmp-rating-widget__icons-list__icon js-rmp-rating-item" data-descriptive-rating="<?php echo $rmp_custom_strings['star' . ($icons_count + 1)]; ?>" data-value="<?php echo $icons_count + 1 ?>">
            <i class="js-rmp-rating-icon <?php echo $rating_icon_type; ?> <?php echo $icon_classes[$icons_count]; ?>"></i>
          </li>
        <?php endfor; ?>
      </ul>
    </div>

    <p class="rmp-rating-widget__hover-text js-rmp-hover-text"></p>

    <button class="rmp-rating-widget__submit-btn rmp-btn js-submit-rating-btn">
      <?php echo $rmp_custom_strings['submitButtonText']; ?>
    </button>

    <p class="rmp-rating-widget__results js-rmp-results <?php echo ! $avg_rating ? 'rmp-rating-widget__results--hidden':''?>">
      <?php echo $results_text; ?>
    </p>

    <p class="rmp-rating-widget__not-rated js-rmp-not-rated <?php echo $avg_rating ? 'rmp-rating-widget__not-rated--hidden':''?>">
      <?php echo $rmp_options['notShowRating'] == 1 ? $rmp_custom_strings['noRating']: ''; ?>
    </p>

    <p class="rmp-rating-widget__msg js-rmp-msg"></p>

  </div>

  <?php if ( $this->schema_type() && $vote_count ): ?>
    <!--Structured data -->
    <?php echo $this->structured_data(); ?>
  <?php endif; ?>

  <?php if ( $rmp_options['social'] === 2 ): ?>
    <!-- Social widget -->
    <?php echo $this->social_widget(); ?>
  <?php endif; ?>

  <?php if ( $rmp_options['feedback'] === 2 ): ?>
    <!-- Feedback widget -->
    <?php echo $this->feedback_widget(); ?>
  <?php endif; ?>
  <?php do_action( 'rmp_after_all_widgets' ); ?>
</div>
