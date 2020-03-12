<?php
/**
 * @package aReview
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>

		<div class="entry-meta">
			<?php areview_posted_on(); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->

	<?php if ( has_post_thumbnail() ) : ?>
		<div class="single-thumb no-review-thumb">
			<?php the_post_thumbnail('single-thumb'); ?>
		</div>	
	<?php endif; ?>

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'areview' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
	
	<footer class="entry-footer">
		<?php
			/* translators: used between list items, there is a space after the comma */
			$category_list = get_the_category_list( __( ', ', 'areview' ) );

			/* translators: used between list items, there is a space after the comma */
			$tag_list = get_the_tag_list( '', __( ', ', 'areview' ) );

			if ( ! areview_categorized_blog() ) {
				// This blog only has 1 category so we just need to worry about tags in the meta text
				if ( '' != $tag_list ) {
					$meta_text = '<i class="fa fa-tag"></i> %2$s' . '<i class="fa fa-link"></i>' . __( '<a href="%3$s" rel="bookmark"> permalink</a>.', 'areview' );
				} else {
					$meta_text = '<span><i class="fa fa-link"></i>' . __( '<a href="%3$s" rel="bookmark"> permalink</a>', 'areview' ) . '</span>';
				}

			} else {
				// But this blog has loads of categories so we should probably display them here
				if ( '' != $tag_list ) {
					$meta_text = '<span><i class="fa fa-folder"></i> %1$s</span>' . '<span><i class="fa fa-tag"></i> %2$s</span>' . '<span><i class="fa fa-link"></i>' . __( '<a href="%3$s" rel="bookmark"> permalink</a>', 'areview' ) . '</span>';
				} else {
					$meta_text = '<span><i class="fa fa-folder"></i> %1$s</span>' . '<span><i class="fa fa-link"></i>' . __( '<a href="%3$s" rel="bookmark"> permalink</a>', 'areview' ) . '</span>';
				}

			} // end check for categories on this blog

			printf(
				$meta_text,
				$category_list,
				$tag_list,
				get_permalink()
			);
		?>

		<?php edit_post_link( __( 'Edit', 'areview' ), '<span class="edit-link"><i class="fa fa-edit"></i>&nbsp;', '</span>' ); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-## -->
