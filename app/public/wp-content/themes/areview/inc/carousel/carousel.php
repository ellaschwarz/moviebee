<?php

	
	function areview_carousel_scripts() {
		if ( is_home() && get_theme_mod('carousel_display') ) {
			
		wp_enqueue_script( 'areview-carousel', get_template_directory_uri() .  '/inc/carousel/owl.carousel.min.js', array( 'jquery' ), true );
					
		wp_enqueue_script( 'areview-carousel-init', get_template_directory_uri() .  '/js/carousel-init.js', array(), true );

		wp_enqueue_style( 'areview-carousel-style', get_template_directory_uri() . '/inc/carousel/owl.carousel.css' );
			
			//carousel speed options
			if ( get_theme_mod('slideshowspeed') != '') {
				$slideshowspeed = 500;
			} else {
				$slideshowspeed = absint(get_theme_mod('slideshowspeed'));
			}					
			$carousel_options = array(
				'slideshowspeed' => $slideshowspeed,
			);			
			wp_localize_script('areview-carousel-init', 'carouselOptions', $carousel_options);			
		}		
	}
	add_action( 'wp_enqueue_scripts', 'areview_carousel_scripts' );
	
	function areview_carousel_template() {
		$cat = absint(get_theme_mod('carousel_cat'));
		if (get_theme_mod('carousel_number')) {	
			$number = absint(get_theme_mod('carousel_number'));
		} else {
			$number = 12;
		}
		$args = array(
			'posts_per_page' => $number,
			'cat' => $cat
		);
		$query = new WP_Query( $args );
		
		if ( $query->have_posts() ) {
		
		?>
		<?php global $cfs; ?>
		<div class="carousel-wrapper">
			<div class="carousel container">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="carousel-block">
							<?php if(function_exists('yasr_get_overall_rating') && function_exists('cfs') && (CFS()->get('show_stars') == 1)) { 
								echo do_shortcode('[yasr_overall_rating]');
							} ?>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="carousel-thumb">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail('carousel-thumb'); ?>
									</a>
								</div>
							<?php endif; ?>
					</div>
				<?php endwhile; ?>
			</div>
			<div class="decoration-bar"></div>	
		</div>
		<?php }
		
		wp_reset_postdata();
	}