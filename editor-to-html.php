<?php

// Change default Editor to text(HTML)
add_filter('wp_default_editor', create_function('', 'return "html";'));
