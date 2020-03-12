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
  $rated_posts = $this->stats_rows();
?>
<div class="rmp-stats js-rmp-orderly-tables">
  <h1 class="rmp-stats__title">
    <?php echo ( esc_html__( 'Rate my Post Stats', 'rate-my-post' ) ); ?>
  </h1>

  <p class="rmp-stats__notice">
    <?php echo ( esc_html__( 'Displaying only rated posts and pages! To see feedback or change ratings click on a post/page title below and find the Rate my Post meta box at the bottom.', 'rate-my-post' ) ); ?>
  </p>

  <table class="rmp-stats__table rmp-data-table js-rmp-stats-table">
    <thead>
      <tr>
        <th><?php echo ( esc_html__( 'Title', 'rate-my-post' ) ); ?></th>
        <th><?php echo ( esc_html__( 'Votes', 'rate-my-post' ) ); ?></th>
        <th><?php echo ( esc_html__( 'Average Rating', 'rate-my-post' ) ); ?></th>
        <th><?php echo ( esc_html__( 'Feedback', 'rate-my-post' ) ); ?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($rated_posts as $rated_post): ?>
        <tr>
          <td><a href="<?php echo $rated_post['edit_link']; ?>" target="_blank"><?php echo $rated_post['title']; ?></a></td>
          <td><?php echo $rated_post['vote_count']; ?></td>
          <td><?php echo $rated_post['avg_rating']; ?></td>
          <td><?php echo $rated_post['feedback_count']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
