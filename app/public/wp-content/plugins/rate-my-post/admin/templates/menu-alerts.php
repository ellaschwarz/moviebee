<?php

/**
 * Admin template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.1.0
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
  global $wp_version;
  $options = get_option( 'rmp_options' );
  $ajax_load = $options['ajaxLoad'];
  $security = get_option( 'rmp_security' );
?>

<?php if ( ! current_user_can( 'manage_options' ) ): ?>
<div class="rmp-alert">
  <p class="rmp-alert__text">
    <?php echo ( esc_html__( 'You need to be logged in as administrator to save settings for Rate my Post plugin', 'rate-my-post' ) ); ?>.
  </p>
</div>
<?php endif; ?>

<?php if ( version_compare( $wp_version, '4.7.0' ) < 0 ): ?>
  <div class="rmp-alert">
    <p class="rmp-alert__text">
      <?php echo ( esc_html__( 'Rate my Post requires WordPress version 4.7.0 or higher. Please update your WordPress', 'rate-my-post' ) ); ?>.
    </p>
  </div>
<?php endif; ?>

<?php if ( $ajax_load != 2 && $this->has_incompatible_caching() ): ?>
  <div class="rmp-alert">
    <p class="rmp-alert__text">
      <?php echo ( esc_html__( 'We detected a caching system. It is recommended that you enable Ajax Load in the advanced settings for better user experience.', 'rate-my-post' ) ); ?>.
    </p>
  </div>
<?php endif; ?>

<?php if ( $security['recaptcha'] == 2 && ( ! str_replace( ' ', '', $security['secretKey'] ) || ! str_replace( ' ', '', $security['siteKey'] ) ) ): ?>
  <div class="rmp-alert">
    <p class="rmp-alert__text">
      <?php echo ( esc_html__( 'reCaptcha is not configured correctly. Make sure you have inserted secret and site key in the security options', 'rate-my-post' ) ); ?>.
    </p>
  </div>
<?php endif; ?>
