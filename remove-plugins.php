<?php 

/**
 * Place this under mu-plugins or create a new file under mu-plugins and remove the unnessary plugins on the specific pages/templates
 * 
 * In this example, i have only removed the si-contact-form from the Front Page. This can be remove from multiple pages/templates or multiple Plugins can be removed/disabled
 *
 * All depends on the requirement
 */
function wp_remove_plugins($plugins){
  if ( isset( $_SERVER['REQUEST_URI'] ) ) :
    if( $remove_plugins_url === '/' ) {
      $key = array_search( 'si-contact-form/si-contact-form.php' , $plugins ); // It searches specific plugin whether it's active or not. 
	    if ( false !== $key ) { // If the above condition returns true so, it removes/unset the plugin or disable the plugin
		    unset( $plugins[$key] );
			}
    }  
  endif;
}
add_filter( 'option_active_plugins', 'wp_remove_plugins' );
