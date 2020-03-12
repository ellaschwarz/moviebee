<?php

/**
 * Admin template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/admin/partials
 */
?>

<?php
  if ( ! defined( 'WPINC' ) ) {
  	die;
  }
?>

<?php
  $feedback = $this->feedbacks();
  $max_rating = Rate_My_Post_Common::max_rating();
?>

<!-- Meta Box Template-->

<div class="rmp-meta-box js-rmp-meta-box">

  <div class="rmp-meta-box__vote-count">
    <label class="rmp-meta-box__vote-count__label" for="rmp-votes">
      <?php echo ( esc_html__( 'Number of Votes', 'rate-my-post' ) ); ?>
    </label>
    <input
      type="number"
      class="rmp-meta-box__vote-count__input js-rmp-mb-vote-count"
      id="rmp-votes"
      value="<?php echo intval( get_post_meta( get_the_id(), 'rmp_vote_count', true ) ); ?>"
    >
  </div>

  <hr class="rmp-meta-box__divider" />

  <div class="rmp-meta-box__avg-rating">
    <label class="rmp-meta-box__avg-rating__label" for="rmp-avg">
      <?php echo ( esc_html__( 'Average Rating', 'rate-my-post' ) ); ?>
    </label>
    <input
      type="number"
      step="0.1"
      class="rmp-meta-box__avg-rating__input js-rmp-mb-avg"
      id="rmp-avg"
      value="<?php echo Rate_My_Post_Common::get_average_rating(); ?>"
    >
  </div>

  <button type="button" class="rmp-btn rmp-btn--small js-rmp-mb-update">
    <?php echo ( esc_html__( 'Update Rating', 'rate-my-post' ) ); ?>
  </button>

  <button type="button" class="rmp-btn rmp-btn--small rmp-btn--danger js-rmp-mb-reset">
    <?php echo ( esc_html__( 'Reset Rating', 'rate-my-post' ) ); ?>
  </button>

  <p class="rmp-meta-box__action-msg js-rmp-mb-msg"></p>

  <p class="rmp-meta-box__notice">
      <?php echo ( esc_html__( 'Average rating will be rounded to the nearest mathematically valid average rating!', 'rate-my-post' ) ); ?>
  </p>

  <hr class="rmp-meta-box__divider" />

  <div class="rmp-meta-box__feedback">

    <p class="rmp-meta-box__feedback__title">
      <?php echo ( esc_html__( 'Feedback', 'rate-my-post' ) ); ?>
    </p>

    <?php if( $feedback ): ?>
      <table class="rmp-meta-box__feedback__table js-rmp-feedback-table">
        <tr>
          <th>
            <?php echo ( esc_html__( 'Time', 'rate-my-post' ) ); ?>
          </th>
          <th>
            <span class="rmp-tooltip">
              <span class="rmp-tooltip__title">
                <?php echo ( esc_html__( 'ID', 'rate-my-post' ) ); ?>
              </span>
              <span class="rmp-tooltip__text">
                <?php echo ( esc_html__( 'Rating ID to which the feedback is associated. You can find details about the action in the analytics section.', 'rate-my-post' ) ); ?>
              </span>
            </span>
          </th>
          <th>
            <span class="rmp-tooltip">
              <span class="rmp-tooltip__title">
                <?php echo ( esc_html__( 'User', 'rate-my-post' ) ); ?>
              </span>
              <span class="rmp-tooltip__text">
                <?php echo ( esc_html__( 'Shows username if visitor was logged in and tracking is enabled in the security options.', 'rate-my-post' ) ); ?>
              </span>
            </span>
          </th>
          <th>
            <?php echo ( esc_html__( 'Feedback', 'rate-my-post' ) ); ?>
          </th>
          <th>
            <?php echo ( esc_html__( 'Delete', 'rate-my-post' ) ); ?>
          </th>
        </tr>

        <?php foreach( $feedback as $feedback_single ): ?>
          <tr class="<?php echo ( $feedback_single['id'] ) ? 'js-rmp-feedback--' .  $feedback_single['id'] : ''; ?>">
            <td>
              <?php echo ( ! $feedback_single['time'] ) ? 'n/a':  $feedback_single['time']; ?>
            </td>
            <td>
              <?php echo ( ! isset( $feedback_single['ratingID'] ) || ! $feedback_single['ratingID'] ) ? 'n/a':  $feedback_single['ratingID']; ?>
            </td>
            <td>
              <?php echo ( ! $this->feedback_user( $feedback_single['user'] ) ) ? 'n/a': $this->feedback_user( $feedback_single['user'] ); ?>
            </td>
            <td>
              <?php echo $feedback_single['feedback']; ?>
            </td>
            <td>
              <span
                class="<?php echo ( ! $feedback_single['id'] ) ? 'rmp-meta-box__feedback__table__delete rmp-meta-box__feedback__table__delete--disabled rmp-js-ind-delete-feedback': 'rmp-meta-box__feedback__table__delete rmp-js-ind-delete-feedback'; ?>"
                data-id="<?php echo $feedback_single['id']; ?>"
              >
                <?php echo ( esc_html__( 'Delete', 'rate-my-post' ) ); ?>
              </span>
            </td>
          </tr>
        <?php endforeach; ?>

      </table>
      <button type="button" class="rmp-btn rmp-btn--small rmp-btn--danger js-rmp-mb-delete-feedback">
        <?php echo ( esc_html__( 'Delete Feedback', 'rate-my-post' ) ); ?>
      </button>
      <p class="rmp-meta-box__feedback__msg js-rmp-mb-feedback-msg"></p>
    <?php else: ?>
      <p class="rmp-meta-box__feedback__text">
        <?php echo ( esc_html__( 'No feedback so far!', 'rate-my-post' ) ); ?>
      </p>
    <?php endif; ?>

  </div>

</div>
