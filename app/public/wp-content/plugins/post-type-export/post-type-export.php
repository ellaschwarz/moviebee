<?php 

/**
 *  Plugin name:    Custom Post Type & Metabox Export 
 *  Description:    This is a plugin for the custom post type Movies and its metaboxes.
 *  Author:         Ella Schwarz
 *  Version:        1.0
 *  Text Domain:    post-type-export
 */

function cptui_register_my_cpts_movies() {

	/**
	 * Post Type: movies.
	 */

	$labels = [
		"name" => __( "movies", "custom-post-type-ui" ),
		"singular_name" => __( "movie", "custom-post-type-ui" ),
	];

	$args = [
		"label" => __( "movies", "custom-post-type-ui" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "movies", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"taxonomies" => [ "category", "post_tag" ],
	];

	register_post_type( "movies", $args );
}

add_action( 'init', 'cptui_register_my_cpts_movies' );


function wporg_custom_box_html( $post ) {
    $value_imdb = get_post_meta( $post->ID, '_imdb_field', true );
    $value_year = get_post_meta( $post->ID, 'realease_year', true );
    $value_actor = get_post_meta( $post->ID, 'actors', true );
	?>

	<label for="imdb_field">Type the IMDb-id</label><br>
    <input type="text" name="_imdb_field" id="imdb_field" class="postbox" value="<?php print_r($value_imdb) ?>" placeholder="Type here..."></input><br>
    <label for="year">Enter the release year</label><br>
    <input type="text" name="_year" id="year" class="postbox" value="<?php print_r($value_year) ?>" placeholder="Type here..."></input><br>
    <label for="actor">Type the star actors</label><br>
    <input type="text" name="_actor" id="actor" class="postbox" value="<?php print_r($value_actor) ?>" placeholder="Type here..."></input><br>

	<?php
}
function wporg_add_custom_box() {
	$screens = [ 'post', 'movies' ];
	foreach ( $screens as $screen ) {
		add_meta_box(
			'wporg_box_id',                 // Unique ID
			__( 'IMDb-information' ),  // Box title
			'wporg_custom_box_html',  		// Content callback, must be of type callable
			$screen                         // Post type
        );
	}
}
add_action( 'add_meta_boxes', 'wporg_add_custom_box' );

function wporg_save_postdata( $post_id ) {
        if ( isset( $_REQUEST['_imdb_field'] ) ) {
		update_post_meta(
			$post_id,
			'_imdb_field',
			sanitize_text_field( $_POST['_imdb_field'] )
        );
    }
    if ( isset( $_REQUEST['_year'] ) ) {
		update_post_meta(
			$post_id,
			'realease_year',
			sanitize_text_field( $_POST['_year'] )
        );
    }

        if ( isset( $_REQUEST['_actor'] ) ) {
		update_post_meta(
			$post_id,
			'actors',
			sanitize_text_field( $_POST['_actor'] )
        );
    }
	//}
}
add_action( 'save_post', 'wporg_save_postdata' );


// function get_imdb_data(){
//     if (!empty(get_post_meta(get_the_ID(), '_imdb_field', true))) {
//         $request = wp_remote_get('http://www.omdbapi.com/?i=tt3794354&plot=full');
//     }

// }
