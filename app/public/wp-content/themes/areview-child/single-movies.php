<?php
/**
 * The template for displaying all single posts.
 *
 * @package aReview
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

        <?php while ( have_posts() ) : the_post(); 
        
        $post_id = get_post_meta(get_the_ID(), 'post_id', true);
        $realease_year = get_post_meta(get_the_ID(), 'realease_year', true);
        $actor = get_post_meta(get_the_ID(), 'actors', true);

$args = [
    'post__in' => $post_id,
    'post_type' => 'movies',
];

$movie = new WP_query($args);

if ($movie -> have_posts()) {
    while ($movie -> have_posts()) {
        $movie->the_post();
    }

    wp_reset_postdata();
} 

echo '<h1 class="movie_title"> ' . get_the_title() . '</h1>';
echo '<h2 class="realease_year"> ' . $realease_year . '</h2>';
?>
<div class="main_container">
<div class="content_container">
<?php
echo '<h5 class="object_content"> ' . $actor . '</h5>';
?>


			<?php if ( get_theme_mod('review_type') =='none' || get_theme_mod('review_type') =='' ) : ?>

				<?php get_template_part( 'content', 'single' ); ?>

      </table>
     </div> <!-- info_container -->
    </div> <!-- main_container -->

			<?php else : ?>

				<?php $review_type = get_theme_mod('review_type'); ?>

				<?php get_template_part( 'reviews/content', $review_type ); ?>				

			<?php endif; ?>

			<?php if (get_theme_mod('author_bio') != '') : ?>
				<?php get_template_part( 'author-bio' ); ?>
			<?php endif; ?>		

			<?php areview_post_nav(); ?>	

			<?php
				// If comments are open or we have at least one comment, load up the comment template
				if ( comments_open() || '0' != get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // end of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>