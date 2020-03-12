<?php

/**
 * Admin template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.4.0
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

<div class="rmp-tab-content js-rmp-tab-content js-rmp-tab-content--4">
  <h2 class="rmp-tab-content__title">
    <?php echo ( esc_html__( 'Migration Tools', 'rate-my-post' ) ); ?>
  </h2>

  <p class="rmp-tab-content__tip-text">
    <?php echo sprintf( ( esc_html__( 'The plugin allows you to easily migrate ratings from the following plugins: %s. More rating plugins will be supported in the future. You can submit requests on the %ssupport forum%s.', 'rate-my-post' ) ), 'kk Star Ratings, WP-PostRatings, YASR','<a href="https://wordpress.org/support/plugin/rate-my-post/" target="_blank">', '</a>' ); ?>
  </p>

  <div class="rmp-migration">
    <p class="rmp-migration__title">
      <?php echo ( esc_html__( 'Detected Rating Plugin', 'rate-my-post' ) ); ?> &raquo;
    </p>

    <?php if ( $this->existing_rating_plugin() ): ?>
      <div class="rmp-migration__container">
        <p class="rmp-migration__subtitle">
          <?php echo $this->existing_rating_plugin();  ?>
        </p>
        <p class="rmp-migration__notice">
          <?php echo esc_html__( 'Note: If you already used Rate my Post plugin, the Rate my Post ratings will be overwritten during the migration!', 'rate-my-post' ); ?>
        </p>
        <button type="button" class="rmp-btn rmp-btn--warning js-rmp-migrate">
          <?php echo ( esc_html__( 'Start Migration', 'rate-my-post' ) ); ?>
        </button>
        <p class="rmp-migration__action-msg js-rmp-migration-msg"></p>
      </div>
    <?php else: ?>
      <div class="rmp-migration__container">
        <p class="rmp-migration__subtitle rmp-migration__subtitle--not-found">
          <?php echo esc_html__( 'No rating plugin found. Migration is not possible.', 'rate-my-post' ); ?>
        </p>
      </div>
    <?php endif; ?>
  </div>

</div>
