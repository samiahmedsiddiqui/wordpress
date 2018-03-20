<?php 
/**
 * Change the default template to the custom template for Search.
 */
function yasglobal_search_template( $template ) {
    global $wp_query;
    if ( ! $wp_query->is_search )
        return $template;

    return dirname( __FILE__ ) . '/search-page.php';
}
add_filter( 'template_include', 'yasglobal_search_template' );
