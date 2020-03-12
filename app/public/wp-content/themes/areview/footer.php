<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package aReview
 */
?>

	</div><!-- #content -->
	<?php if ( is_active_sidebar( 'sidebar-4' ) || is_active_sidebar( 'sidebar-5' ) || is_active_sidebar( 'sidebar-6' ) ) : ?>
		<?php get_sidebar('footer'); ?>
	<?php endif; ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="decoration-bar"></div>
		<div class="site-info container">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'areview' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'areview' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %2$s by %1$s', 'areview' ), 'aThemes', '<a href="http://athemes.com/theme/areview" rel="nofollow">aReview</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
