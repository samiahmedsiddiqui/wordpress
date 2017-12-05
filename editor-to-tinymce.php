<?php

/**
 * Change Editor to tinymce. These lines can be added in functions.php or in a module
 */
function change_editor_to_tinymce() {
	add_filter( 'wp_default_editor', create_function('', 'return "tinymce";') );
}
add_action( 'add_meta_boxes_angular_js', 'change_editor_to_tinymce' );
