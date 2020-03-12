<?php
/**
 * @package aReview
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="entry-thumb col-md-4 col-sm-4 col-xs-12">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" >
				<?php the_post_thumbnail('home-thumb'); ?>
			</a>			
		</div>
		<?php $has_thumb = "col-md-8 col-sm-8 col-xs-12"; ?>
	<?php else : ?>
		<?php $has_thumb = ""; ?>
	<?php endif; ?>

	<div class="entry-summary <?php echo $has_thumb; ?>">
		<header class="entry-header">
			<?php the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
		</header><!-- .entry-header -->		
		<div class="post-info">
			<?php if ( 'post' == get_post_type() ) : ?>
				<?php areview_posted_on(); ?>
			<?php endif; ?>
			<span class="cat-link">
				<?php 
					$category = get_the_category(); 
					if($category[0]){
						echo '<i class="fa fa-folder"></i><a href="' . esc_url(get_category_link($category[0]->term_id )) . '">' . esc_attr($category[0]->cat_name) . '</a>';
					}
				?>
			</span>				
			<?php if(function_exists('yasr_get_overall_rating') && function_exists('cfs') && (CFS()->get('show_stars') == 1)) { 
				echo do_shortcode('[yasr_overall_rating]');
			} ?>
		</div>		
		<?php the_excerpt(); ?>
	</div><!-- .entry-content -->

	<div class="buttons-area">
		<?php if ( function_exists('cfs') && (CFS()->get('button_link') !='' ) && (CFS()->get('button_title') !='') && (CFS()->get('button_index') == 1) ) : ?>
			<a href="<?php echo esc_url(CFS()->get('button_link')); ?>" class="buy-button" target="_blank"><?php echo esc_html(CFS()->get('button_title')); ?></a>
		<?php endif; ?>
		<a href="<?php the_permalink(); ?>" class="read-more"><?php echo __('Read more', 'areview'); ?></a>
	</div>
</article><!-- #post-## -->