<?php

/**
 * Admin template
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.8.0
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

<!-- Analytics Section Template -->
<?php
  $analyticsData = $this->retrieve_analytics_table_data();
?>
<div class="rmp-analytics js-rmp-orderly-tables">
  <h1 class="rmp-analytics__title">
    <?php echo ( esc_html__( 'Rate my Post Analytics', 'rate-my-post' ) ); ?>
  </h1>
  <p class="rmp-analytics__notice">
    <?php echo ( esc_html__( 'Here you can see the details about the last 100 votes on your website.', 'rate-my-post' ) ); ?>
  </p>

  <table class="rmp-analytics__table rmp-data-table js-rmp-analytics-table">
    <thead>
      <tr>
        <th>
          <?php echo ( esc_html__( 'ID', 'rate-my-post' ) ); ?>
        </th>
        <th>
          <?php echo ( esc_html__( 'Time', 'rate-my-post' ) ); ?>
        </th>
        <th>
          <div class="rmp-tooltip">
            <span class="rmp-tooltip__title">
              <?php echo ( esc_html__( 'IP', 'rate-my-post' ) ); ?>
            </span>
            <span class="rmp-tooltip__text">
              <?php echo ( esc_html__( 'Shows IP address if enabled in the security options.', 'rate-my-post' ) ); ?>
            </span>
          </div>
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
        <th><?php echo ( esc_html__( 'Post', 'rate-my-post' ) ); ?></th>
        <th>
          <div class="rmp-tooltip">
            <span class="rmp-tooltip__title">
              <?php echo ( esc_html__( 'Duration', 'rate-my-post' ) ); ?>
            </span>
            <span class="rmp-tooltip__text">
              <?php echo ( esc_html__( 'Shows time spent on page before the rating was submitted.', 'rate-my-post' ) ); ?>
            </span>
          </div>
        </th>
        <th>
          <?php echo ( esc_html__( 'Average Rating', 'rate-my-post' ) ); ?>
        </th>
        <th>
          <?php echo ( esc_html__( 'Total Votes', 'rate-my-post' ) ); ?>
        </th>
        <th>
          <?php echo ( esc_html__( 'Rated', 'rate-my-post' ) ); ?>
        </th>
      </tr>
    </thead>
    <tbody>
      <?php foreach( $analyticsData as $row ): ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><?php echo $row['time']; ?></td>
          <td><?php echo $row['ip']; ?></td>
          <td><?php echo $row['user']; ?></td>
          <td><a href="<?php echo $row['postLink']; ?>"><?php echo $row['postTitle']; ?></a></td>
          <td><?php echo $row['duration']; ?></td>
          <td><?php echo $row['newRating']; ?></td>
          <td><?php echo $row['newVotes']; ?></td>
          <td><?php echo $row['value']; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>
