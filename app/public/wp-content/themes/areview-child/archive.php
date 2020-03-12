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

<h1>ARCHIVE</h1>

<?php

get_sidebar();
$args = [
    'post_type' => 'movies',
    'order' => 'ASC',
    'orderby' => 'title',

 ];

 $movie = new WP_query($args);

        
?>

<script src="https://kit.fontawesome.com/3a12e18fd4.js" crossorigin="anonymous"></script>


		<?php if ( $movie -> have_posts() ) : ?>
			<?php while ( $movie -> have_posts() ) : $movie -> the_post(); ?>

 <div class="card">
 <div class="thumbnail">
 <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(512, 340)); ?></a>
 </div>
 <div class="info">
<div class="imdb_icon">
<?php if (!empty(get_post_meta(get_the_ID(), '_imdb_field', true))) { ?>
    <i class="fab fa-imdb"></i>
 <?php } ?>
</div>
 <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h3>
 <h3> <?php echo get_post_meta(get_the_ID(), 'release_year', true) ?> </h3>

 <?php echo get_post_meta(get_the_ID(), 'realease_year', true); ?>
 <div class="rating_result"> <?php echo do_shortcode('[ratemypost-result]'); ?> </div>  


 </div>
 </div>

               
			<?php endwhile;
            wp_reset_postdata(); ?>

		<?php else : ?>

			<?php get_template_part( '/modules/content/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_footer(); ?>

<?php

