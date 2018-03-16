<?php
/**
 * Add all the below mentioned code in your theme's functions.php
 * to fix the W3Validator Warnings of `type` attribute in
 * script and style elements.
 */

/**
 * Enable Output buffering and attach the function on WordPress init
 */
function yasglobal_init_html() {
	ob_start( 'yasglobal_page_html' );
}
add_action( 'init', 'yasglobal_init_html', 1 );

/**
 * This function works at the end and removed the type attribute
 * from the style and script tags.
 */
function yasglobal_page_html($buffer) {

	$patterns    = array();
	$patterns[0] = 'type="text/javascript"';
	$patterns[1] = "type='text/javascript'";
	$patterns[2] = 'type="text/css"';
	$patterns[3] = "type='text/css'";

	$replacements    = array();
	$replacements[0] = 'type="text/javascript"#yasglobal_break_script#';
	$replacements[1] = "type='text/javascript'#yasglobal_break_script#";
	$replacements[2] = 'type="text/css"#yasglobal_break_style#';
	$replacements[3] = "type='text/css'#yasglobal_break_style#";

	$buffer = str_replace( $patterns, $replacements, $buffer );
	$output = $buffer;
	$buffer = '';

	$break_point_patterns    = array();
	$break_point_patterns[0] = ' type="text/javascript"';
	$break_point_patterns[1] = " type='text/javascript'";
	$output_script = explode( '#yasglobal_break_script#', $output );

	foreach ( $output_script as $row ) {
		$type_attribute_script   = explode( '<script', $row );
		$type_attribute_noscript = explode( '<noscript', $row );
		if ( isset( $type_attribute_script[1] ) 
			|| isset( $type_attribute_noscript[1] ) ) {
			$replaced_break_point = str_replace( $break_point_patterns, '', $row );
		} else {
			$replaced_break_point = $row;
		}

		$buffer .= $replaced_break_point;
	}

	$output = $buffer;

	$break_point_patterns[0] = ' type="text/css"';
	$break_point_patterns[1] = " type='text/css'";
	$output_style = explode( '#yasglobal_break_style#', $output );
	$buffer = '';

	foreach ( $output_style as $row ) {
		$type_attribute = explode( '<style', $row );		
		if ( isset($type_attribute[1]) ) {
			$replaced_break_point = str_replace( $break_point_patterns, '', $row );
		} else {
			$replaced_break_point = $row;
		}
		$buffer .= $replaced_break_point;
	}

	return $buffer;
}

/**
 * Send and turn off the buffering on WordPress shutdown hook
 */
function yasglobal_shutdown_html() {
	ob_end_flush();
}
add_action( 'shutdown', 'yasglobal_shutdown_html', 1000 );
