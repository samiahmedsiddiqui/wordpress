<?php

/**
 * Add async and defer to javascript file only for Homepage
 */
function wp_defer_javascripts ( $url ) {
	if( !is_front_page() ) return $url; // check the the page is front or not.
	if ( strpos( $url, '.js' ) === false || strpos( $url, 'main.min.js' ) === false ) return $url; // Check file type and specific file. If the file type is something else rather than .js or if this a specified JS File then return the same URL Otherwise add async in it.
	  return "$url' async='async";
}
add_filter( 'clean_url', 'wp_defer_javascripts', 11, 1 );
