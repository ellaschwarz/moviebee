<?php

/**
 * Social widget
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      3.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>

<?php
  //retrive necessary data for the template
  $rmp_options = get_option( 'rmp_options' );
  $rmp_custom_strings = $this->custom_strings();
  $social_links = $this->social_share_links();
?>

<div class="rmp-social-widget js-rmp-social-widget">

  <p class="rmp-heading rmp-heading--title">
    <?php echo $rmp_custom_strings['socialTitle']; ?>
  </p>

  <p class="rmp-heading rmp-heading--subtitle">
    <?php echo $rmp_custom_strings['socialSubtitle']; ?>
  </p>

  <?php do_action( 'rmp_before_social_icons' ); ?>

  <div class="rmp-social-widget__icons-container">
    <?php if ( $rmp_options['socialShare'] != 2 ): ?>
      <!-- Social follow widget -->
      <?php if ( $rmp_options['fb'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['fb']); ?>" class="rmp-icon rmp-icon--facebook rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['pinterest'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['pinterest']); ?>" class="rmp-icon rmp-icon--pinterest rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['youtube'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['youtube']); ?>" class="rmp-icon rmp-icon--youtube-square rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['flickr'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['flickr']); ?>" class="rmp-icon rmp-icon--flickr rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['instagram'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['instagram']); ?>" class="rmp-icon rmp-icon--instagram rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['twitter'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['twitter']); ?>" class="rmp-icon rmp-icon--twitter rmp-icon--social"></a>
      <?php endif; ?>

      <?php if ( $rmp_options['linkedin'] ): ?>
        <a target="_blank" href="<?php echo esc_url($rmp_options['linkedin']); ?>" class="rmp-icon rmp-icon--linkedin rmp-icon--social"></a>
      <?php endif; ?>

  <?php else: ?>
    <!-- Social share widget -->
    <?php if ( array_key_exists( 'facebook', $social_links ) ): ?>
      <a target="_blank" href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="rmp-icon rmp-icon--facebook rmp-icon--social"></a>
    <?php endif; ?>

    <?php if ( array_key_exists( 'pinterest', $social_links ) ): ?>
      <a target="_blank" href="<?php echo esc_url( $social_links['pinterest'] ); ?>" class="rmp-icon rmp-icon--pinterest rmp-icon--social"></a>
    <?php endif; ?>

    <?php if ( array_key_exists( 'twitter', $social_links ) ): ?>
      <a target="_blank" href="<?php echo esc_url( $social_links['twitter'] ); ?>" class="rmp-icon rmp-icon--twitter rmp-icon--social"></a>
    <?php endif; ?>

    <?php if ( array_key_exists( 'reddit', $social_links ) ): ?>
      <a target="_blank" href="<?php echo esc_url( $social_links['reddit'] ); ?>" class="rmp-icon rmp-icon--reddit rmp-icon--social"></a>
    <?php endif; ?>

    <?php if ( array_key_exists( 'linkedin', $social_links ) ): ?>
      <a target="_blank" href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="rmp-icon rmp-icon--linkedin rmp-icon--social"></a>
    <?php endif; ?>

  <?php endif; ?>

  <?php do_action( 'rmp_after_social_icons' ); ?>

</div> <!--  .rmp-social-widget__icons-container -->

  <?php do_action( 'rmp_after_social_widget' ); ?>

</div> <!--  .rmp-social-widget -->
