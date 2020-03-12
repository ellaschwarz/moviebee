<?php
/**
 * The template for displaying all single posts.
 *
 * @package aReview
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php if ( get_theme_mod('review_type') =='none' || get_theme_mod('review_type') =='' ) : ?>

				<?php get_template_part( 'content', 'single' ); ?>

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