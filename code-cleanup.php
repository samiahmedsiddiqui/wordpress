<?php

/**
 * Clean / Remove up unused code / hooks to secure the site.
 */
function wp_cleanup() {
  // Remove wlwmanifest.xml link (needed to support windows live writer)
  remove_action( 'wp_head', 'wlwmanifest_link' );
  // remove wordpress version
  remove_action( 'wp_head', 'wp_generator' );

  // Remove RSD link
  remove_action( 'wp_head', 'rsd_link' );
  // Remove Shortlink
  remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
  remove_action( 'template_redirect', 'wp_shortlink_header', 11 );

  /**
   * Disable Emojis
   */
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
  remove_action( 'wp_print_styles', 'print_emoji_styles' );
  add_filter( 'emoji_svg_url', '__return_false' );

  // remove the Next and previous post links
  remove_action('wp_head', 'adjacent_posts_rel_link_wp_head' );

  // remove rss feed links
  remove_action('wp_head', 'feed_links', 2 );
  // removes all extra rss feed links
  remove_action('wp_head', 'feed_links_extra', 3 );

  // remove the REST API link
  remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
  // remove the REST API link from HTTP Headers
  remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );

  // Remove dns-prefetch
  remove_action( 'wp_head', 'wp_resource_hints', 2 );
}
add_action( 'after_setup_theme', 'wp_cleanup' );

/**
 * Disable RSS Feed
 */
function wp_disable_feed() {
  wp_die();
}

add_action( 'do_feed', 'wp_disable_feed', 1 );
add_action( 'do_feed_rdf', 'wp_disable_feed', 1 );
add_action( 'c', 'wp_disable_feed', 1 );
add_action( 'do_feed_rss2', 'wp_disable_feed', 1 );
add_action( 'do_feed_atom', 'wp_disable_feed', 1 );
add_action( 'do_feed_rss2_comments', 'wp_disable_feed', 1 );
add_action( 'do_feed_atom_comments', 'wp_disable_feed', 1 );

/**
 * Disable Rest API for unauthenticated users. It Returns an authentication
 * error if a user who is not logged in tries to query the REST API.
 *
 * @param $access
 *
 * @return WP_Error
 */
function wp_disable_rest_api( $access ) {
  $error_message = esc_html__( 'DRA: Only authenticated users can access the REST API.', 'disable-json-api' );

  if ( is_wp_error( $access ) ) {
    $access->add( 'rest_cannot_access', $error_message, array( 'status' => rest_authorization_required_code() ) );

    return $access;
  }

  return new WP_Error( 'rest_cannot_access', $error_message, array( 'status' => rest_authorization_required_code() ) );
}

add_filter( 'rest_authentication_errors', 'wp_disable_rest_api' );

/**
 * Remove jQuery Migrate
 */
function wp_remove_jquery_migrate( $scripts ) {
  if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
    $script = $scripts->registered['jquery'];
    if ( $script->deps ) {
      // Check whether the script has any dependencies
      $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
    }
  }
}
add_action( 'wp_default_scripts', 'wp_remove_jquery_migrate' );

/**
 * Disable Embeds
 */
function wp_disable_embeds_code_init() {

  // Remove the REST API endpoint.
  remove_action( 'rest_api_init', 'wp_oembed_register_route' );

  // Turn off oEmbed auto discovery.
  add_filter( 'embed_oembed_discover', '__return_false' );

  // Don't filter oEmbed results.
  remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

  // Remove oEmbed discovery links.
  remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

  // Remove oEmbed-specific JavaScript from the front-end and back-end.
  remove_action( 'wp_head', 'wp_oembed_add_host_js' );

  // Remove filter of the oEmbed result before any HTTP requests are made.
  remove_filter( 'pre_oembed_result', 'wp_filter_pre_oembed_result', 10 );
}
add_action( 'init', 'wp_disable_embeds_code_init', 9999 );

/**
 * Deregister wp-embed
 */
function wp_deregister_scripts() {
  wp_dequeue_script( 'wp-embed' );
}
add_action( 'wp_footer', 'wp_deregister_scripts' );

/**
 * Remove Files Version
 */
function wp_remove_version( $src ) {
  if ( strpos( $src, 'ver=' ) ) {
    $src = remove_query_arg( 'ver', $src );
  }

  return $src;
}

// Remove Style Files Version
add_filter( 'style_loader_src', 'wp_remove_version', 9999 );
// Remove Javascript Files Version
add_filter( 'script_loader_src', 'wp_remove_version', 9999 );

// Disable XML-RPC
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Disable X-Pingback Link
 */
add_filter( 'xmlrpc_methods', function( $methods ) {
  unset( $methods['pingback.ping'] );

  return $methods;
});

/**
 * Remove x-pingback HTTP header
 */
add_filter('wp_headers', function($headers) {
  unset( $headers['X-Pingback'] );

  return $headers;
});
