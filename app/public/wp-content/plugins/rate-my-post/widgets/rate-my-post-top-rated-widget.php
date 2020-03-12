<?php

/**
 * Widget - top rated posts
 *
 * @link       http://wordpress.org/plugins/rate-my-post/
 * @since      2.6.0
 *
 * @package    Rate_My_Post
 * @subpackage Rate_My_Post/widgets
 */

 class Rate_My_Post_Top_Rated_Widget extends WP_Widget {
   public function __construct() {
     parent::__construct(
       'rate-my-post-top-rated-widget', // Base ID
       'Top Rated Posts by Rate my Post', // Name
       array(
         'description' => __( 'Displays top rated posts from Rate my Post plugin.', 'rate-my-post' ),
       ) // Args
     );
   }

   //frontend output content
   public function widget( $args, $instance ) {
      extract( $args );

      $title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );

      $postsNumber = empty( $instance['postsNumber'] ) ? 5 : $instance['postsNumber'];
      $minRating = empty( $instance['minRating'] ) ? 1 : $instance['minRating'];
      $minVotes = empty( $instance['minVotes'] ) ? 1 : $instance['minVotes'];
      $showThumb = empty( $instance['showThumb'] ) ? false : $instance['showThumb'];
      $showStars = empty( $instance['showStars'] ) ? false : $instance['showStars'];

      $topRatedPosts = Rate_My_Post_Public::top_rated_posts( $postsNumber, $minRating, $minVotes );

      echo $before_widget;

      if ( ! empty( $title ) ) {
        echo $before_title . $title . $after_title;
      }

      ?>
        <!-- Rate my Post - Top Rated Posts Widget -->
        <div class="rmp-tr-posts-widget">
          <?php foreach ( $topRatedPosts as $post ): ?>
            <div class="rmp-tr-posts-widget__post">
              <?php if ( $post['thumb'] && $showThumb ): ?>
                <div class="rmp-tr-posts-widget__img-container">
                  <a href="<?php echo $post['postLink']; ?>">
                    <img class="rmp-tr-posts-widget__img" src="<?php echo $post['thumb']; ?>" alt="<?php echo $post['title'] ?>" />
                  </a>
                </div>
              <?php endif; ?>
              <?php if ( $showStars ): ?>
                <div class="rmp-tr-posts-widget__star-rating">
                  <?php echo Rate_My_Post_Public::get_visual_rating( $post['postID'] ); ?>
                  <span class="rmp-tr-posts-widget__avg-rating"><?php echo $post['avgRating']; ?></span>
                  <span class="rmp-tr-posts-widget__num-votes">(<?php echo $post['votes']; ?>)</span>
                </div>
              <?php endif; ?>
              <?php do_action( 'rmp_before_widget_title', $post['postID'] ); ?>
              <p>
                <a class="rmp-tr-posts-widget__link" href="<?php echo $post['postLink']; ?>"><?php echo $post['title']; ?></a>
              </p>
            </div>
          <?php endforeach; ?>
        </div>
        <!-- End Rate my Post - Top Rated Posts Widget -->
      <?php

      echo $after_widget;
    }

    //backend form
    public function form( $instance ) {
      $title = ( isset( $instance['title'] ) ) ? strip_tags( $instance['title'] ) : '';
      $postsNumber = ( isset( $instance['postsNumber'] ) ) ? intval( $instance['postsNumber'] ) : '';
      $minRating = ( isset( $instance['minRating'] ) ) ? floatval( $instance['minRating'] ) : '';
      $minVotes = ( isset( $instance['minVotes'] ) ) ? intval( $instance['minVotes'] ) : '';
      $showThumb = ( isset( $instance['showThumb'] ) && $instance['showThumb'] ) ? true : false;
      $showStars = ( isset( $instance['showStars'] ) && $instance['showStars'] ) ? true : false;
  		?>
  		<p>
  			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
  		</p>

      <p>
  			<label for="<?php echo $this->get_field_id( 'postsNumber' ); ?>"><?php echo esc_html__( 'Number of posts to show:', 'rate-my-post'); ?></label>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'postsNumber' ); ?>" name="<?php echo $this->get_field_name( 'postsNumber' ); ?>" type="number" value="<?php echo esc_attr( $postsNumber ); ?>" />
  		</p>

      <p>
  			<label for="<?php echo $this->get_field_id( 'minRating' ); ?>"><?php echo esc_html__( 'Minimum average rating required:', 'rate-my-post'); ?></label>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'minRating' ); ?>" name="<?php echo $this->get_field_name( 'minRating' ); ?>" type="text" value="<?php echo esc_attr( $minRating ); ?>" />
  		</p>

      <p>
  			<label for="<?php echo $this->get_field_id( 'minVotes' ); ?>"><?php echo esc_html__( 'Minimum votes required:', 'rate-my-post'); ?></label>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'minVotes' ); ?>" name="<?php echo $this->get_field_name( 'minVotes' ); ?>" type="text" value="<?php echo esc_attr( $minVotes ); ?>" />
  		</p>

      <p>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'showThumb' ); ?>" name="<?php echo $this->get_field_name( 'showThumb' ); ?>" type="checkbox" <?php echo ($showThumb) ? 'checked':""; ?> />
        <label for="<?php echo $this->get_field_id( 'showThumb' ); ?>"><?php echo esc_html__( 'Show featured image', 'rate-my-post'); ?></label>
  		</p>

      <p>
  			<input class="rmp-widget-input" id="<?php echo $this->get_field_id( 'showStars' ); ?>" name="<?php echo $this->get_field_name( 'showStars' ); ?>" type="checkbox" <?php echo ($showStars) ? 'checked':""; ?> />
        <label for="<?php echo $this->get_field_id( 'showStars' ); ?>"><?php echo esc_html__( 'Show star rating', 'rate-my-post'); ?></label>
  		</p>

  		<?php
  	 }

     //backend update
     public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
      $showThumb = ( isset( $new_instance['showThumb'] ) && $new_instance['showThumb'] ) ? true : false;
      $showStars = ( isset( $new_instance['showStars'] ) && $new_instance['showStars'] ) ? true : false;

  		$instance = $old_instance;
  		$instance['title'] = sanitize_text_field( $new_instance['title'] );
      $instance['postsNumber'] = intval( $new_instance['postsNumber'] );
      $instance['minRating'] = floatval( $new_instance['minRating'] );
      $instance['minVotes'] = intval( $new_instance['minVotes'] );
      $instance['showThumb'] = sanitize_text_field( $showThumb );
      $instance['showStars'] = sanitize_text_field( $showStars );
  		return $instance;
  	}

 }
