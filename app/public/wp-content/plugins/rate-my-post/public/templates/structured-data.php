<?php

/**
 * Public template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.9.2
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/public/partials
 */
?>

<?php
  $average_rating = Rate_My_Post_Common::get_average_rating();
  $vote_count = Rate_My_Post_Common::get_vote_count();
  $schema_type = $this->schema_type();
  $max_rating = Rate_My_Post_Common::max_rating();
?>

<script type="application/ld+json">
{
  "@context": "http://schema.org",
  "@type": "<?php echo $schema_type; ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "bestRating": "<?php echo $max_rating; ?>",
    "ratingCount": "<?php echo intval( $vote_count ); ?>",
    "ratingValue": "<?php echo $average_rating; ?>"
  },
  "image": "<?php echo get_the_post_thumbnail_url(); ?>",
  "name": "<?php echo wp_strip_all_tags( get_the_title() ); ?>",
  "description": "<?php echo wp_strip_all_tags( get_the_title() ); ?>"
}
</script>
