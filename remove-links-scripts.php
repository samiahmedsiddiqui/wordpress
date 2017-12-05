<?php

/**
 * Remove Unnecessary Files/Links by using the following actions (These can be used as per your requirements)
 */
function remove_links_scripts(){
  // Remove Shortlink
  remove_action('wp_head', 'wp_shortlink_wp_head');
  remove_action( 'template_redirect', 'wp_shortlink_header', 11 );
  // Remove RSD Link
  remove_action( 'wp_head', 'rsd_link' );
  Remove wlwmanifest_link Link
  remove_action( 'wp_head', 'wlwmanifest_link' );
  // Remove Feed Links
  remove_action( 'wp_head', 'feed_links', 2 );
  remove_action('wp_head','feed_links_extra', 3);
  // Remove Emoji Scripts
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
  // Remove Oembed Links
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
  add_action( 'wp_footer', 'remove_links_scripts_deregister' );
  // Remove Emoji Styles
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  remove_action( 'admin_print_styles', 'print_emoji_styles' );
  // Remove Rest Output Link
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  // Remove WordPress Generator
  remove_action('wp_head', 'wp_generator');
  // Remove post rel Link
  remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
}
add_filter( 'init', 'remove_links_scripts');

// Either add this code in your theme functions.php  or use https://wordpress.org/plugins/remove-links-and-scripts/
