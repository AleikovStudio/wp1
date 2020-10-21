<?php

/*
Plugin Name: My plugin recent posts shortcode
Plugin URI: https://mypluginrecentpostsshortcode.com
Description: WP plugin for showing recent posts with a shortcode
Author: My plugin recent posts shortcode Ltd.
Version: 1.0
Author URI: https://mypluginrecentpostsshortcode.com
Text Domain: my-plugin-recent-posts-shortcode
Domain Path: /languages/
 */

function my_plugin_recent_posts_shortcode() {
	$buffer = '<h4>Recent Posts:</h4><ul>';

	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 3,
	);

	$q = new WP_Query( $args );

	while ( $q->have_posts() ) {
		$q->the_post();
		$buffer .= '<li><a href=" ' . get_the_permalink() . '">' . get_the_title() . '</a> - ' . get_the_date() . ' </li>';
	}
	wp_reset_postdata();

	$buffer .= '</ul>';
	$buffer .= '<img src="https://picsum.photos/1275/638" alt="random picture">';

	return $buffer;

}

add_shortcode( 'my-plugin-recent-posts', 'my_plugin_recent_posts_shortcode' );