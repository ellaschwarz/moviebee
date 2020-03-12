<?php

class aReview_Top_Rated_Posts extends WP_Widget {

// constructor
    function __construct() {
		$widget_ops = array('classname' => 'areview_top_rated_posts_widget', 'description' => __( 'Display your top rated posts/products', 'areview') );
        parent::__construct(false, $name = __('aReview: Top rated', 'areview'), $widget_ops);
		$this->alt_option_name = 'areview_top_rated_posts_widget';
		
		add_action( 'save_post', array($this, 'flush_widget_cache') );
		add_action( 'deleted_post', array($this, 'flush_widget_cache') );
		add_action( 'switch_theme', array($this, 'flush_widget_cache') );		
    }
	
	// widget form creation
	function form($instance) {

	// Check values
		$title     = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$number    = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
	?>

	<p><em><?php echo __('In order for this widget to work, you need to use Yet Another Stars Rating plugin', 'areview'); ?></em></p>
	<p>
	<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'areview'); ?></label>
	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
	</p>

	<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of top rated posts to show:', 'areview' ); ?></label>
	<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>

	
	<?php
	}

	// update widget
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['number'] = strip_tags($new_instance['number']);
		$this->flush_widget_cache();

		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset($alloptions['areview_top_rated_posts']) )
			delete_option('areview_top_rated_posts');		  
		  
		return $instance;
	}
	
	function flush_widget_cache() {
		wp_cache_delete('areview_top_rated_posts', 'widget');
	}
	
	// display widget
	function widget($args, $instance) {
		$cache = array();
		if ( ! $this->is_preview() ) {
			$cache = wp_cache_get( 'areview_top_rated_posts', 'widget' );
		}

		if ( ! is_array( $cache ) ) {
			$cache = array();
		}

		if ( ! isset( $args['widget_id'] ) ) {
			$args['widget_id'] = $this->id;
		}

		if ( isset( $cache[ $args['widget_id'] ] ) ) {
			echo $cache[ $args['widget_id'] ];
			return;
		}

		ob_start();
		extract($args);

		$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : __( 'Top rated', 'areview' );

		/** This filter is documented in wp-includes/default-widgets.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		$number = ( ! empty( $instance['number'] ) ) ? absint( $instance['number'] ) : 5;
		if ( ! $number )
			$number = 3;

?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo $before_title . $title . $after_title; ?>

		<?php if (function_exists('yasr_get_overall_rating')) {

		//Adapted from YASR top ten shortcode
		    global $wpdb;

		    $query_result = $wpdb->get_results("SELECT pm.meta_value AS overall_rating, pm.post_id AS post_id
                                                        FROM $wpdb->postmeta AS pm, $wpdb->posts AS p
                                                        WHERE  pm.post_id = p.ID
                                                        AND p.post_status = 'publish'
                                                        AND pm.meta_key = 'yasr_overall_rating'
                                                        ORDER BY pm.meta_value DESC, pm.post_id ASC LIMIT $number");

		    if ($query_result) {

		    	echo "<ul class=\"clearfix\">";

		        foreach ($query_result as $result) {

		            $post_title = get_the_title($result->post_id);

		            $link = get_permalink($result->post_id);

		            echo "<li class=\"clearfix\">
		            		<div class=\"rateit bigstars col-md-6\" data-rateit-starwidth=\"32\" data-rateit-starheight=\"32\" data-rateit-value=\"$result->overall_rating\" data-rateit-step=\"0.1\" data-rateit-resetable=\"false\" data-rateit-readonly=\"true\"></div>
		            		<h4 class=\"col-md-6\"><a href=\"" . esc_url($link) . "\">" . esc_html($post_title) . "</a></h4>
		            	</li>";

		        }

		        echo "</ul>";

		    }
		} ?>

		<?php echo $after_widget; ?>
	<?php
		wp_reset_postdata();

		if ( ! $this->is_preview() ) {
			$cache[ $args['widget_id'] ] = ob_get_flush();
			wp_cache_set( 'areview_top_rated_posts', $cache, 'widget' );
		} else {
			ob_end_flush();
		}
	}
	
}
