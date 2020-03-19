<?php
/**
 * The template for displaying all single posts.
 *
 * @package aReview
 */

get_header();

?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

    <?php while (have_posts()) :
    the_post(); 
        


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

?>


			<?php if ( get_theme_mod('review_type') =='none' || get_theme_mod('review_type') =='' ) : ?>

			

      </table>
     </div> <!-- info_container -->
    </div> <!-- main_container -->

			<?php else : ?>

				<?php $review_type = get_theme_mod('review_type'); ?>
				<?php get_template_part( 'template-parts/single-template' ); ?>
						

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