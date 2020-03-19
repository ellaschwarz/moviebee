<?php
/**
 * The main template file.
 * Template Name: Archive
 *
 * @package aReview
 */
get_header(); 
?>



<div id="primary" class="content-areas <?php apply_filters('wpre_primary-width','wpre_primary_class') ?>">
<main id="main" class="site-main <?php apply_filters('wpre_main-class', 'wpre_get_main_class'); ?>" role="main">

<h1>ARCHIVE (A-Z) </h1>

<?php

get_sidebar();
$args = [
    'post_type' => 'movies',
    'order' => 'ASC',
	'orderby' => 'title',
	'posts_per_page' => 1000

 ];

 $movie = new WP_query($args);

        
?>

<script src="https://kit.fontawesome.com/3a12e18fd4.js" crossorigin="anonymous"></script>


		<?php if ( $movie -> have_posts() ) : ?>
			<?php while ( $movie -> have_posts() ) : $movie -> the_post(); ?>

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

