<?php 

/**
 *  Plugin name:    Top Rating Widget
 *  Description:    This is a widget for displaying the top rated movies.
 *  Author:         Ella Schwarz
 *  Version:        1.0
 *  Text Domain:    top_rating_widget_domain
 */

// Register and load the widget
function top_rating_load_widget() {
    register_widget( 'top_rating_widget' );
}
add_action( 'widgets_init', 'top_rating_load_widget' );
 
// Creating the widget 
class top_rating_widget extends WP_Widget {
 
function __construct() {
parent::__construct(
 
// Base ID of your widget
'top_rating_widget', 
 
// Widget name will appear in UI
__('Top Rating Widget', 'top_rating_widget_domain'),
 
// Widget description
array( 'description' => __( 'A widget that displays the top rated movies', 'top_rating_widget_domain' ), ) 
);
}
 
// Creating widget front-end
 
public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );
 
// before and after widget arguments are defined by themes
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];
 
// This is where you run the code and display the output
echo __( 'Hello, World!', 'top_rating_widget_domain' );

$args = array(
    'post_status' => 'publish',
    'post_type' => 'movies',
    'meta_key' => '',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
);

echo $args['after_widget'];
}
         
// Widget Backend 
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'New title', 'top_rating_widget_domain' );
}
// Widget admin form
?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php 
}
     
// Updating widget replacing old instances with new
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // Class wpb_widget ends here