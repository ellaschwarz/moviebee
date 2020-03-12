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
  //retrive necessary data for the template
  $post_id = ( $post_id ) ? $post_id : get_the_id();
  $options = get_option( 'rmp_options' );
  $rmp_custom_strings = $this->custom_strings();
  $average_rating = Rate_My_Post_Common::get_average_rating( $post_id );
  $vote_count = Rate_My_Post_Common::get_vote_count( $post_id );
  $results_text = $this->rating_widget_results_text( $options, $average_rating, $vote_count );
?>

<!-- Rate my Post Plugin - Rating Widget -->
<!-- Inspired by AMP by Example - https://ampbyexample.com/advanced/star_rating/ -->

<div class="rmp-amp-rating-widget">
  <?php do_action( 'rmp_before_widget_amp' ); ?>
  <p id="rmp-amp-rating-widget__title">
    <?php echo $rmp_custom_strings['rateTitle']; ?>
  </p>

  <form
    id="rmp-amp-rating-form"
    class="p2 rmp-amp-rating-widget__form"
    method="post"
    action-xhr="<?php echo admin_url( 'admin-ajax.php' ); ?>"
    target="_top">

    <p class="rmp-amp-rating-widget__subtitle" id="rmp-amp-rating-widget-subtitle">
      <?php echo $rmp_custom_strings['rateSubtitle']; ?>
    </p>
    <div class="rmp-amp-centered-fieldset">

      <fieldset id="rmp-amp-rating-fieldset" class="rmp-amp-rating-widget__fieldset">

        <label id="rmp-amp-action" style="display: none;">
          <span>Action:</span>
          <input type="text"
            name="action"
            required value="process_rating_amp">
        </label>

        <label id="rmp-amp-post-id" style="display: none;">
          <span>Post ID:</span>
          <input type="text"
            name="postID"
            required value="<?php echo $post_id ?>">
        </label>

        <label id="rmp-amp-post-nonce" style="display: none;">
          <span>Post Nonce:</span>
          <input type="text"
            name="nonce"
            required value="<?php echo wp_create_nonce( 'rmp_public_nonce' ); ?>">
        </label>

        <input name="star_rating"
         type="radio"
         id="rating5"
         value="5"
         on="change:rmp-amp-rating-form.submit,rmp-amp-rating-fieldset.hide,rmp-amp-rating-widget-subtitle.hide,rmp-amp-result.hide,rmp-amp-not-rated.hide" />
       <label for="rating5"
         title="5 stars">☆</label>

       <input name="star_rating"
         type="radio"
         id="rating4"
         value="4"
         on="change:rmp-amp-rating-form.submit,rmp-amp-rating-fieldset.hide,rmp-amp-rating-widget-subtitle.hide,rmp-amp-result.hide,rmp-amp-not-rated.hide" />
       <label for="rating4"
         title="4 stars">☆</label>

       <input name="star_rating"
         type="radio"
         id="rating3"
         value="3"
         on="change:rmp-amp-rating-form.submit,rmp-amp-rating-fieldset.hide,rmp-amp-rating-widget-subtitle.hide,rmp-amp-result.hide,rmp-amp-not-rated.hide" />
       <label for="rating3"
         title="3 stars">☆</label>

       <input name="star_rating"
         type="radio"
         id="rating2"
         value="2"
         on="change:rmp-amp-rating-form.submit,rmp-amp-rating-fieldset.hide,rmp-amp-rating-widget-subtitle.hide,rmp-amp-result.hide,rmp-amp-not-rated.hide" />
       <label for="rating2"
         title="2 stars">☆</label>

       <input name="star_rating"
         type="radio"
         id="rating1"
         value="1"
         on="change:rmp-amp-rating-form.submit,rmp-amp-rating-fieldset.hide,rmp-amp-rating-widget-subtitle.hide,rmp-amp-result.hide,rmp-amp-not-rated.hide" />
       <label for="rating1"
         title="1 stars">☆</label>

      </fieldset>

    </div>

    <div submitting>
    <template type="amp-mustache">
      <?php echo esc_html__( 'Processing your rating...', 'rate-my-post' ); ?>
    </template>
    </div>

    <div submit-success>
      <template type="amp-mustache">
        <?php echo $rmp_custom_strings['rateResult']; ?>
        {{avgRating}} / 5.
        <?php echo $rmp_custom_strings['rateResult2']; ?>
        {{voteCount}}
        <br />
        {{successMsg}}
        {{#errorMsg}} <span> {{.}} </span> {{/errorMsg}}
      </template>
    </div>

    <div submit-error>
      <template type="amp-mustache">
        <?php echo esc_html__( 'There was an error rating this post!', 'rate-my-post' ); ?>
      </template>
    </div>

    <p
      class="rmp-amp-rating-widget__results <?php echo ! $average_rating ? 'rmp-amp-rating-widget__results--hidden':''?>"
      id="rmp-amp-result"
    >
      <?php echo $results_text; ?>
    </p>

    <p
      class="rmp-amp-rating-widget__not-rated <?php echo $average_rating ? 'rmp-amp-rating-widget__not-rated--hidden':''?>"
      id="rmp-amp-not-rated"
    >
      <?php echo $rmp_custom_strings['noRating']; ?>
    </p>

  </form>

  <?php if ( $this->schema_type() && $average_rating ): ?>
    <?php echo $this->structured_data(); ?>
  <?php endif; ?>

  <?php do_action( 'rmp_after_widget_amp' ); ?>
</div>
