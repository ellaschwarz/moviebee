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

<?php $rmp_customization = get_option( 'rmp_customize_strings' ); ?>

<div class="rmp-tab-content js-rmp-tab-content js-rmp-tab-content--2">
  <p class="rmp-tab-content__tip-text">
    <?php echo ( esc_html__( 'Tip: First enable delete cookie on page load in settings. Add shortcode [ratemypost] to one of your posts and open it in the preview mode. Then custmize this plugin to your liking - after saving settings reload the post in the preview mode to see the changes you have made. After testing you can reset the ratings in meta box in edit post.', 'rate-my-post' ) ); ?>
  </p>
  <div class="js-rmp-no-multilingual">

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Strings - Rating Widget', 'rate-my-post' ) ); ?>
    </h2>

    <table class="rmp-tab-content__table rmp-tab-content__table--padding">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-title">
            <?php echo ( esc_html__( 'Title', 'rate-my-post' ) ); ?>:
          </label>
          <p class="rmp-tab-content__notice rmp-tab-content__notice--small-b-margin">
            <?php echo ( esc_html__( 'Leave empty to disable', 'rate-my-post' ) ); ?>.
          </p>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-title"
            data-key="rateTitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['rateTitle'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-subtitle">
            <?php echo ( esc_html__( 'Subtitle', 'rate-my-post' ) ); ?>:
          </label>
          <p class="rmp-tab-content__notice rmp-tab-content__notice--small-b-margin">
            <?php echo ( esc_html__( 'Leave empty to disable', 'rate-my-post' ) ); ?>.
          </p>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-subtitle"
            data-key="rateSubtitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['rateSubtitle'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-result">
            <?php echo ( esc_html__( 'Result text - average rating', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-result"
            data-key="rateResult"
            value="<?php echo stripslashes( esc_html( $rmp_customization['rateResult'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-result2">
            <?php echo ( esc_html__( 'Result text - vote count', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-result2"
            data-key="rateResult2"
            value="<?php echo stripslashes( esc_html( $rmp_customization['rateResult2'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
            <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-cookie">
              <?php echo ( esc_html__( 'Text if somebody tries to vote twice', 'rate-my-post' ) ); ?>:
            </label>
            <input
              type="text"
              class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
              id="rmp-rate-cookie"
              data-key="cookieNotice"
              value="<?php echo stripslashes( esc_html( $rmp_customization['cookieNotice'] ) ); ?>"
            >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-norate">
            <?php echo ( esc_html__( 'Result text if no votes', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-norate"
            data-key="noRating"
            value="<?php echo stripslashes( esc_html( $rmp_customization['noRating'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-aftervote">
            <?php echo ( esc_html__( 'Text after vote', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-aftervote"
            data-key="afterVote"
            value="<?php echo stripslashes( esc_html( $rmp_customization['afterVote'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-star1">
            <?php echo ( esc_html__( 'One star hover text', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-star1"
            data-key="star1"
            value="<?php echo stripslashes( esc_html( $rmp_customization['star1'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-star2"><?php echo ( esc_html__( 'Two stars hover text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-star2"
            data-key="star2"
            value="<?php echo stripslashes( esc_html( $rmp_customization['star2'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-star3"><?php echo ( esc_html__( 'Three stars hover text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-star3"
            data-key="star3"
            value="<?php echo stripslashes( esc_html( $rmp_customization['star3'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-star4"><?php echo ( esc_html__( 'Four stars hover text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-star4"
            data-key="star4"
            value="<?php echo stripslashes( esc_html( $rmp_customization['star4'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-rate-star5"><?php echo ( esc_html__( 'Five stars hover text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-rate-star5"
            data-key="star5"
            value="<?php echo stripslashes( esc_html( $rmp_customization['star5'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-submit-rating-button"><?php echo ( esc_html__( 'Submit rating button text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-submit-rating-button"
            data-key="submitButtonText"
            value="<?php echo stripslashes( esc_html( $rmp_customization['submitButtonText'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-custom-results-text"><?php echo ( esc_html__( 'Custom results text', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-custom-results-text"
            data-key="customResultsText"
            value="<?php echo stripslashes( esc_html( $rmp_customization['customResultsText'] ) ); ?>"
          >
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Replace the generic vote count and average rating text under the stars. Use {{votecount}} for number of votes and {{avgrating}} for average rating. Leave empty for default. This feature does not work in the multilingual website compatibility mode.', 'rate-my-post' ) ); ?>
          </p>
        </td>
      </tr>
    </table>

    <hr class="rmp-tab-content__divider" />

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Strings - Social Widget', 'rate-my-post' ) ); ?>
    </h2>
    <table class="rmp-tab-content__table rmp-tab-content__table--padding">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-social-title">
            <?php echo ( esc_html__( 'Title', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-social-title"
            data-key="socialTitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['socialTitle'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-social-subtitle">
            <?php echo ( esc_html__( 'Subtitle', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-social-subtitle"
            data-key="socialSubtitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['socialSubtitle'] ) ); ?>"
          >
        </td>
      </tr>
    </table>

    <hr class="rmp-tab-content__divider" />

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Strings - Feedback Widget', 'rate-my-post' ) ); ?>
    </h2>
    <table class="rmp-tab-content__table rmp-tab-content__table--padding">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-title">
            <?php echo ( esc_html__( 'Title', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-title"
            data-key="feedbackTitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackTitle'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-subtitle"><?php echo ( esc_html__( 'Subtitle', 'rate-my-post' ) ); ?>:</label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-subtitle"
            data-key="feedbackSubtitle"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackSubtitle'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-text">
            <?php echo ( esc_html__( 'Text', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-text"
            data-key="feedbackText"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackText'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-notice">
            <?php echo ( esc_html__( 'Text after sending feedback', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-notice"
            data-key="feedbackNotice"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackNotice'] ) ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-button">
            <?php echo ( esc_html__( 'Feedback button text', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-button"
            data-key="feedbackButton"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackButton'] ) ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-feedback-empty">
            <?php echo ( esc_html__( 'Text if feedback is empty', 'rate-my-post' ) ); ?>:
          </label>
          <input
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-customization"
            id="rmp-feedback-empty"
            data-key="feedbackAlert"
            value="<?php echo stripslashes( esc_html( $rmp_customization['feedbackAlert'] ) ); ?>"
          >
        </td>
      </tr>
    </table>

  </div> <!-- end of no multilingual div -->

  <hr class="rmp-tab-content__divider" />

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Style - Global', 'rate-my-post' ) ); ?>
    </h2>

    <p class="rmp-tab-content__tip-text">
      <?php echo ( esc_html__( 'Leave empty for default style.', 'rate-my-post' ) ); ?>
    </p>
    <p class="rmp-tab-content__tip-text">
      <?php echo ( esc_html__( 'Insert hexadecimal colors - for example, #ff0000 for red. You can use', 'rate-my-post' ) ); ?> <a href="https://www.w3schools.com/colors/colors_picker.asp" target="_blank">W3 HTML Color Picker</a>.
    </p>

    <table class="rmp-tab-content__table rmp-tab-content__table--cell-3">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-title-font">
            <?php echo ( esc_html__( 'Title Font Size', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-title-font"
            data-key="titleFontSize"
            value="<?php echo $this->numeric_option( $rmp_customization['titleFontSize'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-subtitle-font">
            <?php echo ( esc_html__( 'Subtitle Font Size', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-subtitle-font"
            data-key="subtitleFontSize"
            value="<?php echo $this->numeric_option( $rmp_customization['subtitleFontSize'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-text-font">
            <?php echo ( esc_html__( 'Text Font Size', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-text-font"
            data-key="textFontSize"
            value="<?php echo $this->numeric_option( $rmp_customization['textFontSize'] ); ?>"
          >px
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-title-margin">
            <?php echo ( esc_html__( 'Title Bottom Margin', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-title-margin"
            data-key="titleMarginBottom"
            value="<?php echo $this->numeric_option( $rmp_customization['titleMarginBottom'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-subtitle-margin">
            <?php echo ( esc_html__( 'Subtitle Bottom Margin', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-subtitle-margin"
            data-key="subtitleMarginBottom"
            value="<?php echo $this->numeric_option( $rmp_customization['subtitleMarginBottom'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-border-width">
            <?php echo ( esc_html__( 'Border Width', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-border-width"
            data-key="borderWidth"
            value="<?php echo $this->numeric_option( $rmp_customization['borderWidth'] ); ?>"
          >px
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-border-radius">
            <?php echo ( esc_html__( 'Border Radius', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-border-radius"
            data-key="borderRadius"
            value="<?php echo $this->numeric_option( $rmp_customization['borderRadius'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-border-color">
            <?php echo ( esc_html__( 'Border Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-border-color"
            data-key="borderColor"
            value="<?php echo esc_html( $rmp_customization['borderColor'] ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-background-color">
            <?php echo ( esc_html__( 'Background Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-background-color"
            data-key="backgroundColor"
            value="<?php echo esc_html( $rmp_customization['backgroundColor'] ); ?>"
          >
        </td>
      </tr>
    </table>

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Style - Rating Widget', 'rate-my-post' ) ); ?>
    </h2>

    <table class="rmp-tab-content__table rmp-tab-content__table--cell-3">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-size">
            <?php echo ( esc_html__( 'Icon Size', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-size"
            data-key="iconSize"
            value="<?php echo $this->numeric_option( $rmp_customization['iconSize'] ); ?>"
          >px
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-color-results-widget">
            <?php echo ( esc_html__( 'Results Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-color-results-widget"
            data-key="iconColorResults"
            value="<?php echo esc_html( $rmp_customization['iconColorResults'] ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-color-hover">
            <?php echo ( esc_html__( 'Hover Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-color-hover"
            data-key="iconColorHover"
            value="<?php echo esc_html( $rmp_customization['iconColorHover'] ); ?>"
          >
        </td>
      </tr>
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-color-rated">
            <?php echo ( esc_html__( 'Rated Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-color-rated"
            data-key="iconColorRated"
            value="<?php echo esc_html( $rmp_customization['iconColorRated'] ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-color-avg">
            <?php echo ( esc_html__( 'Highlight Color', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="text"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-color-avg"
            data-key="iconColorAvg"
            value="<?php echo esc_html( $rmp_customization['iconColorAvg'] ); ?>"
          >
        </td>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-icon-margin">
            <?php echo ( esc_html__( 'Icon Margin', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-icon-margin"
            data-key="iconMargin"
            value="<?php echo $this->numeric_option( $rmp_customization['iconMargin'] ); ?>"
          >px
        </td>
      </tr>
    </table>

    <h2 class="rmp-tab-content__title">
      <?php echo ( esc_html__( 'Style - Social Widget', 'rate-my-post' ) ); ?>
    </h2>

    <table class="rmp-tab-content__table rmp-tab-content__table--cell-3">
      <tr>
        <td>
          <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-social-font">
            <?php echo ( esc_html__( 'Social Icon Size', 'rate-my-post' ) ); ?>:
          </label>
          <input
            placeholder="<?php echo ( esc_html__( 'Default', 'rate-my-post' ) ); ?>"
            type="number"
            class="rmp-tab-content__input rmp-tab-content__input--short js-rmp-customization"
            id="rmp-social-font"
            data-key="socialFontSize"
            value="<?php echo $this->numeric_option( $rmp_customization['socialFontSize'] ); ?>"
          >px
        </td>
      </tr>
    </table>

  <hr class="rmp-divider" />

  <p class="rmp-tab-content__tip-text">
    <?php echo ( esc_html__( 'Note: If you are using a cache plugin, clear page cache after saving customization options.', 'rate-my-post' ) ); ?>
  </p>

  <button type="button" class="rmp-btn js-rmp-save-customization">
    <?php echo ( esc_html__( 'Save Customization Options', 'rate-my-post' ) ); ?>
  </button>

  <button id="js-rmp-customization-waypoint" type="button" class="rmp-btn rmp-btn--danger js-rmp-reset-customization">
    <?php echo ( esc_html__( 'Reset Customization Options', 'rate-my-post' ) ); ?>
  </button>

  <p class="rmp-tab-content__action-msg js-rmp-customization-msg"></p>

  <div class="rmp-tab-content__sticky-save js-rmp-customization-sticky">
    <button type="button" class="rmp-btn js-rmp-save-customization">
      <?php echo ( esc_html__( 'Save Customization Options', 'rate-my-post' ) ); ?>
    </button>
  </div>

</div>
