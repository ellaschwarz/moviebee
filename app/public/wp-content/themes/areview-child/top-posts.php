<?php
/**
 * The top rated movies template file.
 * Template Name: Top Rated Movies
 *
 * @package aReview
 */
get_header(); 
?>



<div id="primary" class="content-areas <?php apply_filters('wpre_primary-width','wpre_primary_class') ?>">
<main id="main" class="site-main <?php apply_filters('wpre_main-class', 'wpre_get_main_class'); ?>" role="main">

<h1>Top 100 Rated Movies</h1>

<?php 
$max_posts = 250;
$required_rating = 1;
$required_votes = 1;

rmp_get_top_rated_posts( $max_posts, $required_rating, $required_votes ); ?>

<?php

get_sidebar();
$args = [
	'post_type' => 'movies',
	'meta_key' => 'rmp_avg_rating',
	'orderby' => 'meta_value',
	'order' => 'DESC',
	'posts_per_page' => '100'
 ];

 $movie = new WP_query($args);

        
?>

<script src="https://kit.fontawesome.com/3a12e18fd4.js" crossorigin="anonymous"></script>


		<?php if ( $movie -> have_posts() ) : ?>
			<?php while ( $movie -> have_posts() ) : $movie -> the_post(); ?>
 			<?php echo do_shortcode(' [auto-list-number] ') ; ?>
			<?php get_template_part( 'template-parts/card-template' ); ?>


               
			<?php endwhile;
            wp_reset_postdata(); ?>

		<?php else : ?>

			<?php get_template_part( '/modules/content/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>

<?php

