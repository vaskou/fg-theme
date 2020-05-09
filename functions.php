<?php

define( 'FREMEDITI_GUITARS_THEME_VERSION', '1.0.0' );
define( 'FREMEDITI_GUITARS_THEME_PATH', get_stylesheet_directory() );
define( 'FREMEDITI_GUITARS_THEME_URL', get_stylesheet_directory_uri() );

require get_template_directory() . '/inc/class-fremediti-guitars-theme.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/class-fremediti-guitars-customizer.php';

require get_template_directory() . '/inc/class-fremediti-guitars-nav-walker.php';

require get_template_directory() . '/inc/class-fremediti-guitars-metaboxes.php';

require get_template_directory() . '/inc/class-fremediti-guitars-fg-guitars.php';

require get_template_directory() . '/inc/class-fremediti-guitars-gallery-post-type.php';

require get_template_directory() . '/inc/class-fremediti-guitars-videos-post-type.php';


Fremediti_Guitars_Theme::getInstance()->init();