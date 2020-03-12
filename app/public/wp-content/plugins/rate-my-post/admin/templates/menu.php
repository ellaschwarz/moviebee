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

<div class="rmp-menu js-rmp-menu">
  <h1 class="rmp-menu__title">
    Rate my Post
  </h1>
  <!-- tabs -->
  <div class="rmp-menu__tabs">
    <div class="rmp-menu__tabs__tab rmp-menu__tabs__tab--selected js-rmp-tab" data-tab="1">
      <?php echo ( esc_html__( 'Settings', 'rate-my-post' ) ); ?>
    </div>
    <div class="rmp-menu__tabs__tab js-rmp-tab" data-tab="2">
      <?php echo ( esc_html__( 'Customize', 'rate-my-post' ) ); ?>
    </div>
    <div class="rmp-menu__tabs__tab js-rmp-tab" data-tab="3">
      <?php echo ( esc_html__( 'Security', 'rate-my-post' ) ); ?>
    </div>
    <div class="rmp-menu__tabs__tab js-rmp-tab" data-tab="4">
      <?php echo ( esc_html__( 'Migration Tools', 'rate-my-post' ) ); ?>
    </div>
    <div class="rmp-menu__tabs__tab js-rmp-tab" data-tab="5">
      <?php echo ( esc_html__( 'About', 'rate-my-post' ) ); ?>
    </div>
    <div class="rmp-menu__tabs__tab js-rmp-tab" data-tab="6">
      <?php echo ( esc_html__( 'Pro Version', 'rate-my-post' ) ); ?>
    </div>
  </div>

  <!-- alerts -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-alerts.php'; ?>
  <!-- options -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-options.php'; ?>
  <!-- customization -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-customize.php'; ?>
  <!-- security options -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-security.php'; ?>
  <!-- migration tools -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-migration.php'; ?>
  <!-- about -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'menu-about.php'; ?>
  <!-- pro version -->
  <?php include_once plugin_dir_path( __FILE__ ) . 'pro-version.php'; ?>

</div>
