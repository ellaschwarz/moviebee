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

<?php $rmp_options = get_option( 'rmp_options' ); ?>

<div class="rmp-tab-content rmp-tab-content--visible js-rmp-tab-content js-rmp-tab-content--1">
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Rating Widget Settings', 'rate-my-post' ) ); ?>
  </h2>

  <!-- RATING WIDGET -->
  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <label class="rmp-tab-content__label">
          <?php echo ( esc_html__( 'Type of rating widget', 'rate-my-post' ) ); ?>
        </label>
        <select class="rmp-tab-content__select js-rmp-option" data-key="icon_type">
          <option value="1" <?php echo ($rmp_options['icon_type'] === 1) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Stars', 'rate-my-post' ) ); ?>
          </option>
          <option value="2" <?php echo ($rmp_options['icon_type'] === 2) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Thumbs', 'rate-my-post' ) ); ?>
          </option>
          <option value="3" <?php echo ($rmp_options['icon_type'] === 3) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Hearts', 'rate-my-post' ) ); ?>
          </option>
          <option value="4" <?php echo ($rmp_options['icon_type'] === 4) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Smileys', 'rate-my-post' ) ); ?>
          </option>
          <option value="5" <?php echo ($rmp_options['icon_type'] === 5) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Trophies', 'rate-my-post' ) ); ?>
          </option>
        </select>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Rate my Post supports the following icons: Stars, thumbs and hearts', 'rate-my-post' ) ); ?>.
        </p>
      </td>
      <td>
        <label class="rmp-tab-content__label">
          <?php echo ( esc_html__( 'Rating widget alignment', 'rate-my-post' ) ); ?>
        </label>
        <select class="rmp-tab-content__select js-rmp-option" data-key="widgetAlign">
          <option value="1" <?php echo ($rmp_options['widgetAlign'] === 1) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Center', 'rate-my-post' ) ); ?>
          </option>
          <option value="2" <?php echo ($rmp_options['widgetAlign'] === 2) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Left', 'rate-my-post' ) ); ?>
          </option>
          <option value="3" <?php echo ($rmp_options['widgetAlign'] === 3) ? 'selected="selected"':''; ?>>
            <?php echo ( esc_html__( 'Right', 'rate-my-post' ) ); ?>
          </option>
        </select>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'You can align the widget left, right or center', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>
  </table>

  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <input
          class="rmp-tab-content__input-checkbox js-rmp-option"
          data-key="posts"
          id="rmp-posts"
          type="checkbox"
          <?php echo ($rmp_options['posts'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-posts">
          <?php echo ( esc_html__( 'Add rating widget to all posts', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Automatically adds rating widget to all your posts, after the content.', 'rate-my-post' ) ); ?>
          </p>
      </td>
      <td>
        <input
          class="rmp-tab-content__input-checkbox js-rmp-option"
          data-key="pages"
          id="rmp-pages"
          type="checkbox"
          <?php echo ($rmp_options['pages'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-pages">
          <?php echo ( esc_html__( 'Add rating widget to all pages', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Automatically adds rating widget to all your pages, after the content.', 'rate-my-post' ) ); ?>
        </p>
      </td>
    </tr>

    <tr>
      <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="resultPost"
            id="rmp-post-results"
            type="checkbox"
            <?php echo ($rmp_options['resultPost'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-post-results">
            <?php echo ( esc_html__( 'Add result widget to all posts', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Automatically adds result widget to all your posts, before the content.', 'rate-my-post' ) ); ?>
          </p>
      </td>
      <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="resultPages"
            id="rmp-page-results"
            type="checkbox"
            <?php echo ($rmp_options['resultPages'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-page-results">
            <?php echo ( esc_html__( 'Add result widget to all pages', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Automatically adds result widget to all your pages, before the content.', 'rate-my-post' ) ); ?>
          </p>
        </td>
      </tr>

      <tr>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="rate_email"
            id="rmp-rate-email"
            type="checkbox"
            <?php echo ($rmp_options['rate_email'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-rate-email">
            <?php echo ( esc_html__( 'Send me email whenever post gets rated', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'You will get an email whenever somebody rates your post or page', 'rate-my-post' ) ); ?>.
          </p>
        </td>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="hoverTexts"
            id="rmp-hover-texts"
            type="checkbox"
            <?php echo ($rmp_options['hoverTexts'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-hover-texts">
            <?php echo ( esc_html__( 'Show star hover texts', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'When a visitor hovers over a star, a descriptive rating will be shown under the stars', 'rate-my-post' ) ); ?>.
          </p>
        </td>
      </tr>

      <tr>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="preventAccidental"
            id="rmp-prevent-accidental"
            type="checkbox"
            <?php echo ($rmp_options['preventAccidental'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-prevent-accidental">
            <?php echo ( esc_html__( 'Prevent accidental votes', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Vote has to be confirmed by clicking the rate button', 'rate-my-post' ) ); ?>.
          </p>
        </td>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="notShowRating"
            id="rmp-not-show-rating"
            type="checkbox"
            <?php echo ($rmp_options['notShowRating'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-not-show-rating">
            <?php echo ( esc_html__( 'Do not show average rating', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Removes average rating and vote count information under the rating widget', 'rate-my-post' ) ); ?>.
          </p>
        </td>
      </tr>

      <tr>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="ampCompatibility"
            id="rmp-amp-compatibility"
            type="checkbox"
            <?php echo ($rmp_options['ampCompatibility'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-amp-compatibility">
            <?php echo ( esc_html__( 'AMP compatibility mode (BETA)', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo sprintf( esc_html__('Adds a minimalistic rating and results widget on AMP pages. Check the %sdocumentation%s before enabling', 'rate-my-post'), '<a href="https://blazzdev.com/documentation/rate-my-post-documentation/#amp" target="_blank">', '</a>' ); ?>.
          </p>
        </td>
        <td>
          <input
            class="rmp-tab-content__input-checkbox js-rmp-option"
            data-key="cookieDisable"
            id="rmp-cookie-disable"
            type="checkbox"
            <?php echo ($rmp_options['cookieDisable'] === 2) ? 'checked':""; ?>
          >
          <label class="rmp-tab-content__label" for="rmp-cookie-disable">
            <?php echo ( esc_html__( 'Delete cookie on page load', 'rate-my-post' ) ); ?>
          </label>
          <p class="rmp-tab-content__notice">
            <?php echo ( esc_html__( 'Enable only while you are cutomizing the plugin', 'rate-my-post' ) ); ?>.
          </p>
        </td>
      </tr>

  </table>

  <label class="rmp-tab-content__label">
    <?php echo ( esc_html__( 'Type of structured data for rich snippets', 'rate-my-post' ) ); ?>
  </label>
  <select class="rmp-tab-content__select js-rmp-option" data-key="structuredDataType">
    <option value="none" <?php echo ($rmp_options['structuredDataType'] === 'none') ? 'selected="selected"':""; ?>> <?php echo ( esc_html__( 'Without structured data', 'rate-my-post' ) ); ?></option>
    <option value="Product" <?php echo ($rmp_options['structuredDataType'] === 'Product') ? 'selected="selected"':""; ?>>Product</option>
    <option value="Book" <?php echo ($rmp_options['structuredDataType'] === 'Book') ? 'selected="selected"':""; ?>>Book</option>
    <option value="Course" <?php echo ($rmp_options['structuredDataType'] === 'Course') ? 'selected="selected"':""; ?>>Course</option>
    <option value="CreativeWorkSeason" <?php echo ($rmp_options['structuredDataType'] === 'CreativeWorkSeason') ? 'selected="selected"':""; ?>>CreativeWorkSeason</option>
    <option value="CreativeWorkSeries" <?php echo ($rmp_options['structuredDataType'] === 'CreativeWorkSeries') ? 'selected="selected"':""; ?>>CreativeWorkSeries</option>
    <option value="Episode" <?php echo ($rmp_options['structuredDataType'] === 'Episode') ? 'selected="selected"':""; ?>>Episode</option>
    <option value="Game" <?php echo ($rmp_options['structuredDataType'] === 'Game') ? 'selected="selected"':""; ?>>Game</option>
    <option value="LocalBusiness" <?php echo ($rmp_options['structuredDataType'] === 'LocalBusiness') ? 'selected="selected"':""; ?>>LocalBusiness</option>
    <option value="MediaObject" <?php echo ($rmp_options['structuredDataType'] === 'MediaObject') ? 'selected="selected"':""; ?>>MediaObject</option>
    <option value="Movie" <?php echo ($rmp_options['structuredDataType'] === 'Movie') ? 'selected="selected"':""; ?>>Movie</option>
    <option value="MusicPlaylist" <?php echo ($rmp_options['structuredDataType'] === 'MusicPlaylist') ? 'selected="selected"':""; ?>>MusicPlaylist</option>
    <option value="MusicRecording" <?php echo ($rmp_options['structuredDataType'] === 'MusicRecording') ? 'selected="selected"':""; ?>>MusicRecording</option>
    <option value="Organization" <?php echo ($rmp_options['structuredDataType'] === 'Organization') ? 'selected="selected"':""; ?>>Organization</option>
    <option value="Recipe" <?php echo ($rmp_options['structuredDataType'] === 'Recipe') ? 'selected="selected"':""; ?>>Recipe</option>
  </select>
  <p class="rmp-tab-content__notice">
    <?php echo sprintf( esc_html__('For more information about structured data and rich snippets see the %sdocumentation%s', 'rate-my-post'), '<a href="https://blazzdev.com/documentation/rate-my-post-documentation/#schema" target="_blank">', '</a>' ); ?>.
  </p>

  <label class="rmp-tab-content__label" for="rmp-exclude">
    <?php echo ( esc_html__( 'Exclude rating and result widget from', 'rate-my-post' ) ); ?>:
  </label>
  <input
    type="text"
    class="rmp-tab-content__input js-rmp-option"
    data-key="exclude"
    id="rmp-exclude"
    value="<?php echo esc_html( implode(',', $rmp_options['exclude'] ) ); ?>"
  >
  <p class="rmp-tab-content__notice">
    <?php echo ( esc_html__( 'Insert comma separated post/page IDs', 'rate-my-post' ) ); ?>.
    <a href="https://blazzdev.com/documentation/rate-my-post-documentation/#exclude" target="_blank"><?php echo ( esc_html__( 'Read more', 'rate-my-post' ) ); ?> &raquo;</a>
  </p>

  <label class="rmp-tab-content__label" for="rmp-negative-positive">
    <?php echo ( esc_html__( 'If post or page is rated X/5 stars or less, consider rating negative. X=', 'rate-my-post' ) ); ?>
  </label>
  <input
    type="text"
    class="rmp-tab-content__input js-rmp-option"
    id="rmp-negative-positive"
    data-key="positiveNegative"
    value="<?php echo esc_html( $rmp_options['positiveNegative'] ); ?>"
  >
  <p class="rmp-tab-content__notice">
    <?php echo ( esc_html__( 'Define what is a negative rating to use the feedback and social widget.', 'rate-my-post' ) ); ?>.
  </p>

  <hr class="rmp-tab-content__divider" />

  <!-- FEEDBACK WIDGET -->
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Feedback Widget Settings', 'rate-my-post' ) ); ?>
  </h2>

  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <input
          class="rmp-tab-content__input-checkbox js-rmp-option"
          id="rmp-feedback"
          data-key="feedback"
          type="checkbox"
          <?php echo ($rmp_options['feedback'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-feedback">
          <?php echo ( esc_html__( 'Show feedback widget if rating is negative', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Encourage users to help you improve your posts. Only you can see the feedback. The feedback widget will be shown in case of a negative rating', 'rate-my-post' ) ); ?>.
        </p>
      </td>
      <td>
        <input
          class="rmp-tab-content__input-checkbox js-rmp-option"
          data-key="feedback_email"
          id="rmp-feedback-email"
          type="checkbox"
          <?php echo ($rmp_options['feedback_email'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-feedback-email">
          <?php echo ( esc_html__( 'Send me email whenever feedback is left', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'You will get an email whenever somebody leaves you private feedback', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>
  </table>

  <hr class="rmp-tab-content__divider" />

  <!-- SOCIAL WIDGET -->
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Social Widget Settings', 'rate-my-post' ) ); ?>
  </h2>

  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <input
          id="rmp-social"
          data-key="social"
          class="rmp-tab-content__input-checkbox js-rmp-option js-rmp-social-follow"
          type="checkbox"
          <?php echo ($rmp_options['social'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-social">
          <?php echo ( esc_html__( 'Show social widget if rating is positive', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Encourage users to follow you on social media. Insert links to your social media profiles below. The social widget will be shown in case of a positive rating', 'rate-my-post' ) ); ?>.
        </p>
      </td>
      <td>
        <input
          id="rmp-social-share"
          data-key="socialShare"
          class="rmp-tab-content__input-checkbox js-rmp-option js-rmp-social-share"
          type="checkbox"
          <?php echo ($rmp_options['socialShare'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-social-share">
          <?php echo ( esc_html__( 'Show social share links', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Enable this option if you want to show social share links instead of social follow links. Supported networks: Facebook, Pinterest, Twitter and Reddit', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>
  </table>

  <p class="rmp-tab-content__notice rmp-tab-content__notice--top">
    <?php echo ( esc_html__( 'If you do not want an icon to be displayed, leave the field below empty!', 'rate-my-post' ) ); ?>
  </p>

  <table class="rmp-tab-content__table rmp-tab-content__table--padding rmp-tab-content__table--hidden js-rmp-social-follow-links">
    <tr>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-facebook">
          <?php echo ( esc_html__( 'Your Facebook Page URL', 'rate-my-post' ) ); ?>:
        </label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-facebook"
          data-key="fb"
          value="<?php echo esc_html( $rmp_options['fb'] ); ?>"
        >
      </td>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-pinterest"><?php echo ( esc_html__( 'Your Pinterest Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-pinterest"
          data-key="pinterest"
          value="<?php echo esc_html( $rmp_options['pinterest'] ); ?>"
        >
      </td>
    </tr>
    <tr>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-youtube"><?php echo ( esc_html__( 'Your Youtube Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-youtube"
          data-key="youtube"
          value="<?php echo esc_html( $rmp_options['youtube'] ); ?>"
        >
      </td>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-flickr"><?php echo ( esc_html__( 'Your Flickr Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-flickr"
          data-key="flickr"
          value="<?php echo esc_html( $rmp_options['flickr'] ); ?>"
        >
      </td>
    </tr>
    <tr>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-instagram"><?php echo ( esc_html__( 'Your Instagram Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-instagram"
          data-key="instagram"
          value="<?php echo esc_html( $rmp_options['instagram'] ); ?>"
        >
      </td>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-twitter"><?php echo ( esc_html__( 'Your Twitter Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-twitter"
          data-key="twitter"
          value="<?php echo esc_html( $rmp_options['twitter'] ); ?>"
        >
      </td>
    </tr>
    <tr>
      <td>
        <label class="rmp-tab-content__label rmp-tab-content__label--block" for="rmp-linkedin"><?php echo ( esc_html__( 'Your Linkedin Page URL', 'rate-my-post' ) ); ?>:</label>
        <input
          type="text"
          class="rmp-tab-content__input rmp-tab-content__input--long js-rmp-option"
          id="rmp-linkedin"
          data-key="linkedin"
          value="<?php echo esc_html( $rmp_options['linkedin'] ); ?>"
        >
      </td>
    </tr>
  </table>

  <hr class="rmp-tab-content__divider" />
  <!-- CPT Settings -->
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Custom Post Types', 'rate-my-post' ) ); ?>
  </h2>

  <label class="rmp-tab-content__label" for="rmp-custom-post-types-rating">
    <?php echo ( esc_html__( 'Add rating widget automatically to custom post types', 'rate-my-post' ) ); ?>:
  </label>
  <input
    type="text"
    class="rmp-tab-content__input js-rmp-option js-rmp-cpt-rating-input"
    id="rmp-custom-post-types-rating"
    data-key="cptRating"
    value="<?php echo esc_html( implode(',', $rmp_options['cptRating'] ) ); ?>"
  >

  <p class="rmp-tab-content__notice">
    <?php echo ( esc_html__( 'Insert comma separated post types. Registered custom post types', 'rate-my-post' ) ); ?>:
    <?php if ( $this->custom_post_types() ): ?>
      <span class="rmp-tab-content__notice__selectable js-rmp-cpt-rating">
        <?php echo $this->custom_post_types(); ?>
      </span>
    <?php else: ?>
      <span>/</span>
    <?php endif; ?>
  </p>

  <label class="rmp-tab-content__label" for="rmp-custom-post-types-result">
    <?php echo ( esc_html__( 'Add result widget automatically to custom post types', 'rate-my-post' ) ); ?>:
  </label>
  <input
    type="text"
    class="rmp-tab-content__input js-rmp-option js-rmp-cpt-results-input"
    id="rmp-custom-post-types-result"
    data-key="cptResult"
    value="<?php echo esc_html( implode(',', $rmp_options['cptResult'] ) ); ?>"
  >
  <p class="rmp-tab-content__notice">
    <?php echo ( esc_html__( 'Insert comma separated post types. Registered custom post types', 'rate-my-post' ) ); ?>:
    <?php if ( $this->custom_post_types() ): ?>
      <span class="rmp-tab-content__notice__selectable js-rmp-cpt-results">
        <?php echo $this->custom_post_types(); ?>
      </span>
    <?php else: ?>
      <span>/</span>
    <?php endif; ?>
  </p>

  <hr class="rmp-tab-content__divider" />

  <!-- Archive Pages Settings -->
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Archive Pages', 'rate-my-post' ) ); ?>
  </h2>

  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <input
          id="rmp-archives"
          data-key="archivePages"
          class="rmp-tab-content__input-checkbox js-rmp-option"
          type="checkbox"
          <?php echo ($rmp_options['archivePages'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-archives">
          <?php echo ( esc_html__( 'Show ratings on archive pages', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'Ratings will be shown on archive pages (categories, tags, author pages etc.) next to the title', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>
  </table>

  <hr class="rmp-tab-content__divider" />
  <!-- ADVANCED SETTINGS -->
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Advanced Settings', 'rate-my-post' ) ); ?>
  </h2>

  <table class="rmp-tab-content__table">
    <tr>
      <td>
        <input
          id="rmp-multilingual"
          data-key="multiLingual"
          class="rmp-tab-content__input-checkbox js-rmp-option  js-rmp-multilingual"
          type="checkbox"
          <?php echo ($rmp_options['multiLingual'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-multilingual">
          <?php echo ( esc_html__( 'Multilingual website compatibility mode', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'If your website is available in multiple languages enable this option. You will not be able to customize strings in the customize section. Instead customize strings through your plugin for translations', 'rate-my-post' ) ); ?>.
        </p>
      </td>
      <td>
        <input
          id="rmp-ajax-load"
          type="checkbox"
          data-key="ajaxLoad"
          class="rmp-tab-content__input-checkbox js-rmp-option"
          <?php echo ($rmp_options['ajaxLoad'] === 2) ? 'checked':""; ?>>
        <label class="rmp-tab-content__label" for="rmp-ajax-load">
          <?php echo ( esc_html__( 'AJAX load results', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'If you are utilizing page caching, enable this option. Not required if you use WP Super Cache, LiteSpeed Cache, WP Fastest Cache, WP Rocket or SG Optimizer', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>

    <tr>
      <td>
        <input
          id="rmp-uninstall-wipe"
          data-key="wipeOnUninstall"
          class="rmp-tab-content__input-checkbox js-rmp-option"
          type="checkbox"
          <?php echo ($rmp_options['wipeOnUninstall'] === 2) ? 'checked':""; ?>
        >
        <label class="rmp-tab-content__label" for="rmp-uninstall-wipe">
          <?php echo ( esc_html__( 'Delete all plugin data on uninstall', 'rate-my-post' ) ); ?>
        </label>
        <p class="rmp-tab-content__notice">
          <?php echo ( esc_html__( 'If enabled all plugin data (settings, customization, vote count, rating and feedback) will be deleted when you uninstall the plugin', 'rate-my-post' ) ); ?>.
        </p>
      </td>
    </tr>
  </table>

  <hr class="rmp-tab-content__divider" />

  <!-- SAVE BUTTONS-->

  <button id="js-rmp-options-waypoint" type="button" class="rmp-btn js-rmp-save-options">
    <?php echo ( esc_html__( 'Save Settings', 'rate-my-post' ) ); ?>
  </button>

  <button type="button" class="rmp-btn rmp-btn--danger js-rmp-reset-options">
    <?php echo ( esc_html__( 'Reset Settings', 'rate-my-post' ) ); ?>
  </button>

  <p class="rmp-tab-content__action-msg js-rmp-options-msg"></p>

  <div class="rmp-tab-content__sticky-save js-rmp-options-sticky">
    <button type="button" class="rmp-btn js-rmp-save-options">
      <?php echo ( esc_html__( 'Save Settings', 'rate-my-post' ) ); ?>
    </button>
  </div>

</div>
