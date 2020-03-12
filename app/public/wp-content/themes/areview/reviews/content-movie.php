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

	<div class="col-md-12">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="single-thumb poster col-md-6 col-sm-6 col-xs-6">
				<?php the_post_thumbnail(); ?>
			</div>	
		<?php endif; ?>

		<?php if ( function_exists('cfs') && CFS()->get('display_movie_info') == 1 ) : ?>
			
			<?php
				echo '<table class="col-md-6 col-sm-6 col-xs-6 movie-table">';
				if (CFS()->get('movie_genre') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Genre: ', 'areview') . '</td><td>' . esc_html(CFS()->get('movie_genre')) . '</td></tr>';
				}
				if (CFS()->get('movie_director') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Director: ', 'areview') . '</td><td>' . esc_html(CFS()->get('movie_director')) . '</td></tr>';
				}
				if (CFS()->get('movie_writer') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Writer: ', 'areview') . '</td><td>' . esc_html(CFS()->get('movie_writer')) . '</td></tr>';
				}
				if (CFS()->get('movie_release_date') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Release date: ', 'areview') . '</td><td>' . esc_attr(CFS()->get('movie_release_date')) . '</td></tr>';
				}
				if (CFS()->get('movie_runtime') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Runtime: ', 'areview') . '</td><td>' . esc_attr(CFS()->get('movie_runtime')) . '</td></tr>';
				}
				if (CFS()->get('movie_language') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Language: ', 'areview') . '</td><td>' . esc_html(CFS()->get('movie_language')) . '</td></tr>';
				}
				if (CFS()->get('movie_plot') !='') {
					echo '<tr><td class="movie-left-cell">' . __('Plot: ', 'areview') . '</td><td>' . esc_textarea(CFS()->get('movie_plot')) . '</td></tr>';
				}
				if (CFS()->get('movie_cast') !='') {
					$actors = CFS()->get('movie_cast');
					echo '<tr class="actors"><td colspan="2">' . __('Cast', 'areview') . '</td></tr>';
					foreach ($actors as $actor) {
						echo '<tr><td class="actor-name" colspan="2">' . esc_html($actor['actor_name']) . '</td></tr>';
					}
				}											
				echo '</table>';	
			?>
		<?php endif; ?>
	</div>

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
