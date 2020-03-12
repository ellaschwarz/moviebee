<?php


//Dynamic styles
function areview_custom_styles($custom) {
	//Primary color
	$primary_color = esc_html(get_theme_mod( 'primary_color' ));
	if ( isset($primary_color) && ( $primary_color != '#222E38' ) ) {
		$custom = ".main-navigation, .widget-title, .areview_top_rated_posts_widget, .main-navigation ul ul li { background-color: {$primary_color}; }"."\n";
	}
	//Secondary color
	$secondary_color = esc_html(get_theme_mod( 'secondary_color' ));
	if ( isset($secondary_color) && ( $secondary_color != '#00A0B0' ) ) {
		$custom .= ".comment-respond input[type=\"submit\"], .read-more, .comment-reply-link, .post-navigation .nav-previous, .post-navigation .nav-next, .paging-navigation .nav-previous, .paging-navigation .nav-next { background-color: {$secondary_color}; }"."\n";
		$custom .= ".read-more, .comment-respond input[type=\"submit\"], .post-navigation .nav-previous, .post-navigation .nav-next, .paging-navigation .nav-previous, .paging-navigation .nav-next { border-color: {$secondary_color}; }"."\n";
		$custom .= ".author-social a, a.comment-reply-link:hover, .comment-respond input[type=\"submit\"]:hover, .read-more:hover, .areview_recent_posts_widget h4 a:hover, .areview_top_rated_posts_widget h4 a:hover, .entry-title a:hover, .areview_recent_comments a.post-title:hover.author-social a, .post-navigation .nav-previous:hover > a, .post-navigation .nav-next:hover > a, .paging-navigation .nav-previous:hover > a, .paging-navigation .nav-next:hover > a { color: {$secondary_color}; }"."\n";
	}	
	//Site title
	$site_title = esc_html(get_theme_mod( 'site_title_color' ));
	if ( isset($site_title) && ( $site_title != '#fff' )) {
		$custom .= ".site-title a { color: {$site_title}; }"."\n";
	}
	//Site description
	$site_desc = esc_html(get_theme_mod( 'site_desc_color' ));
	if ( isset($site_desc) && ( $site_desc != '#888' )) {
		$custom .= ".site-description { color: {$site_desc}; }"."\n";
	}	
	//Entry title
	$entry_title = esc_html(get_theme_mod( 'entry_title_color' ));
	if ( isset($entry_title) && ( $entry_title != '#444' )) {
		$custom .= ".entry-title, .entry-title a { color: {$entry_title}; }"."\n";
	}
	//Body text
	$body_text = esc_html(get_theme_mod( 'body_text_color' ));
	if ( isset($body_text) && ( $body_text != '#8F8F8F' )) {
		$custom .= "body { color: {$body_text}; }"."\n";
	}
	
	//Decoration bar
	$dec_color_1 = esc_html(get_theme_mod( 'dec_color_1', '#00A0B0' ));
	$dec_color_2 = esc_html(get_theme_mod( 'dec_color_2', '#4ECDC4' ));
	$dec_color_3 = esc_html(get_theme_mod( 'dec_color_3', '#EDC951' ));
	$dec_color_4 = esc_html(get_theme_mod( 'dec_color_4', '#FF6B6B' ));
	$dec_color_5 = esc_html(get_theme_mod( 'dec_color_5', '#C44D58' ));
	$custom .= ".decoration-bar { 
					background: {$dec_color_1};
					background: -moz-linear-gradient(left, {$dec_color_1} 0%, {$dec_color_1} 20%, {$dec_color_2} 20%, {$dec_color_2} 40%, {$dec_color_3} 40%, {$dec_color_3} 60%, {$dec_color_4} 60%, {$dec_color_4} 80%, {$dec_color_5} 80%, {$dec_color_5} 100%);
					background: -webkit-gradient(left top, right top, color-stop(0%, {$dec_color_1}), color-stop(20%, {$dec_color_1}), color-stop(20%, {$dec_color_2}), color-stop(40%, {$dec_color_2}), color-stop(40%, {$dec_color_3}), color-stop(60%, {$dec_color_3}), color-stop(60%, {$dec_color_4}), color-stop(80%, {$dec_color_4}), color-stop(80%, {$dec_color_5}), color-stop(100%, {$dec_color_5}));
					background: -webkit-linear-gradient(left, {$dec_color_1} 0%, {$dec_color_1} 20%, {$dec_color_2} 20%, {$dec_color_2} 40%, {$dec_color_3} 40%, {$dec_color_3} 60%, {$dec_color_4} 60%, {$dec_color_4} 80%, {$dec_color_5} 80%, {$dec_color_5} 100%);
					background: -o-linear-gradient(left, {$dec_color_1} 0%, {$dec_color_1} 20%, {$dec_color_2} 20%, {$dec_color_2} 40%, {$dec_color_3} 40%, {$dec_color_3} 60%, {$dec_color_4} 60%, {$dec_color_4} 80%, {$dec_color_5} 80%, {$dec_color_5} 100%);
					background: -ms-linear-gradient(left, {$dec_color_1} 0%, {$dec_color_1} 20%, {$dec_color_2} 20%, {$dec_color_2} 40%, {$dec_color_3} 40%, {$dec_color_3} 60%, {$dec_color_4} 60%, {$dec_color_4} 80%, {$dec_color_5} 80%, {$dec_color_5} 100%);
					background: linear-gradient(to right, {$dec_color_1} 0%, {$dec_color_1} 20%, {$dec_color_2} 20%, {$dec_color_2} 40%, {$dec_color_3} 40%, {$dec_color_3} 60%, {$dec_color_4} 60%, {$dec_color_4} 80%, {$dec_color_5} 80%, {$dec_color_5} 100%);
					filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$dec_color_1}', endColorstr='{$dec_color_5}', GradientType=1 );					
				}"."\n";

	//Affiliate button
	$affiliate_button = esc_html(get_theme_mod( 'affiliate_button' ));
	if ( isset($affiliate_button) && ( $affiliate_button != '#FF6B6B' )) {
		$custom .= ".buy-button { background-color: {$affiliate_button}; }"."\n";
		$custom .= ".buy-button { border-color: {$affiliate_button}; }"."\n";
		$custom .= ".buy-button:hover { color: {$affiliate_button}; }"."\n";
	}
	//Movies and games tables
	$review_tables = esc_html(get_theme_mod( 'review_tables' ));
	if ( isset($review_tables) && ( $review_tables != '#FF6B6B' )) {
		$custom .= ".movie-table, .game-table { background-color: {$review_tables}; }"."\n";
	}	
	//Carousel background
	$carousel_background = esc_html(get_theme_mod( 'carousel_background' ));
	if ( isset($carousel_background) && ( $carousel_background != '#151E25' )) {
		$custom .= ".carousel-wrapper { background-color: {$carousel_background}; }"."\n";
	}
	
	//Fonts
	$headings_font = esc_html(get_theme_mod('headings_fonts'));	
	$body_font = esc_html(get_theme_mod('body_fonts'));	
	
	if ( $headings_font ) {
		$font_pieces = explode(":", $headings_font);
		$custom .= "h1, h2, h3, h4, h5, h6, .main-navigation li, .post-navigation .nav-previous, .post-navigation .nav-next, .paging-navigation .nav-previous, .paging-navigation .nav-next { font-family: {$font_pieces[0]}; }"."\n";
	}
	if ( $body_font ) {
		$font_pieces = explode(":", $body_font);
		$custom .= "body { font-family: {$font_pieces[0]}; }"."\n";
	}
	
	//Output all the styles
	wp_add_inline_style( 'areview-style', $custom );	
}
add_action( 'wp_enqueue_scripts', 'areview_custom_styles' );