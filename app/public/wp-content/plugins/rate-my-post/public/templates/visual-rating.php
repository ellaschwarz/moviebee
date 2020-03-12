<?php

/**
 * Visual rating for results widget, top rated posts widget and archives
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      3.0.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>

<?php
  $icon_classes = self::icons_classes( $post_id, false, $is_results_widget );
  $icon_type = self::icon_type();
  $max_rating = Rate_My_Post_Common::max_rating();
?>

<?php for ( $icons_count = 0; $icons_count < $max_rating; $icons_count++ ): // do not indent icons ?>
<i class="<?php echo $extra_class ? $extra_class : ''; ?> <?php echo $icon_type; ?> <?php echo $icon_classes[$icons_count] ?>"></i>
<?php endfor; ?>
