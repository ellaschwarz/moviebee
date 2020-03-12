<?php
/**
 * The main template file.
 *
 * @package aReview
 */
get_header(); 
?>



<div id="primary" class="content-areas <?php apply_filters('wpre_primary-width','wpre_primary_class') ?>">
<main id="main" class="site-main <?php apply_filters('wpre_main-class', 'wpre_get_main_class'); ?>" role="main">

<?php

echo do_shortcode('[recent_post_carousel show_read_more="false" show_author=false post_type="movies" design="design-1" limit="9" show_date="false" slides_to_show="3"]');
get_sidebar();
$args = [
    'post_type' => 'movies',
    'posts_per_page' => '10'
 ];

 $movie = new WP_query($args);

        do_shortcode("[yasr_top_ten_highest_rated custom_post=movies]");
?>

<script src="https://kit.fontawesome.com/3a12e18fd4.js" crossorigin="anonymous"></script>

<h1>Recently added</h1>

		<?php if ( $movie -> have_posts() ) : ?>
			<?php while ( $movie -> have_posts() ) : $movie -> the_post(); ?>

 <div class="card">
 <div class="thumbnail">
 <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(array(512, 340)); ?></a>
 </div>
 <div class="info">
 <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> </h3>
 <?php echo get_post_meta(get_the_ID(), 'realease_year', true); ?>

 <?php if (!empty(get_post_meta(get_the_ID(), '_imdb_field', true))) { ?>
    <i class="fab fa-imdb"></i>
 <?php } ?>

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

