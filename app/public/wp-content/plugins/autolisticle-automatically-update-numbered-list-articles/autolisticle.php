<?php

/**
	 * Plugin Name: AutoListicle: Automatically Update Numbered List Articles
	 * Plugin URI: https://smartwp.com/autolisticle
	 * Description: Automatically keep your numbered lists in articles displaying the correct number by using this shortcode [auto-list-number].
	 * Version: 1.1.1
	 * Text Domain: autolisticle
	 * Author: Andy Feliciotti
	 * Author URI: https://smartwp.com
*/

//Shortcode function to display numbers in articles that automatically increment
function auto_list_number_shortcode_func( $atts ) {
	global $auto_list_numbers;
	global $post;

	if(empty($auto_list_numbers)){
		$auto_list_numbers = array();
	}

	$output = '';
	$atts = shortcode_atts( array(
		'name' => 'default',
		'wrapper' => null,
		'after' => '.',
		'display' => 'increase',
	), $atts );

	if($atts['display'] == 'increase'){
		if(!$auto_list_numbers[$atts['name']]){
			$auto_list_numbers[$atts['name']] = '1';
		}else{
			$auto_list_numbers[$atts['name']]++;
		}
	}

	if($atts['wrapper']){
		$auto_list_numbers_display = '<'.$atts['wrapper'].' class="auto-list-number auto-list-number-'.$auto_list_numbers[$atts['name']].'">'.$auto_list_numbers[$atts['name']].''.$atts['after'].'</'.$atts['wrapper'].'>';
	}else{
		$auto_list_numbers_display = $auto_list_numbers[$atts['name']].''.$atts['after'];
	}

	if($atts['display'] == 'total'){
		if($atts['name'] == 'default'){ $atts['name'] = null; }
		$pattern = get_shortcode_regex();
		preg_match_all('/'.$pattern.'/s', $post->post_content, $matches);
		if(is_array($matches[2])){
			$i = 0;
			$total = 0;
			foreach($matches[2] as $match){
				if($match == 'auto-list-number' && (empty(shortcode_parse_atts($matches[3][$i])['display']) || shortcode_parse_atts($matches[3][$i])['display'] != 'total') && (empty(shortcode_parse_atts($matches[3][$i])['name']) || shortcode_parse_atts($matches[3][$i])['name'] == $atts['name'])){
					$total++;
				}
				$i++;
			}
		}
		$auto_list_numbers_display = $total;
	}

	$output .= $auto_list_numbers_display;

	return $output;
}
add_shortcode( 'auto-list-number', 'auto_list_number_shortcode_func' );
