<?php

define( 'FREMEDITI_GUITARS_THEME_VERSION', '1.1.7' );
define( 'FREMEDITI_GUITARS_THEME_PATH', get_stylesheet_directory() );
define( 'FREMEDITI_GUITARS_THEME_URL', get_stylesheet_directory_uri() );

require 'vendor/autoload.php';

require get_template_directory() . '/inc/class-fremediti-guitars-theme.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/class-fremediti-guitars-template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/class-fremediti-guitars-customizer.php';

require get_template_directory() . '/inc/class-fremediti-guitars-nav-walker.php';

require get_template_directory() . '/inc/class-fremediti-guitars-metaboxes.php';

require get_template_directory() . '/inc/class-fremediti-guitars-fg-guitars.php';

require get_template_directory() . '/inc/class-fremediti-guitars-fg-pickups.php';

require get_template_directory() . '/inc/class-fremediti-guitars-available-guitars-post-type.php';

require get_template_directory() . '/inc/class-fremediti-guitars-gallery-post-type.php';

require get_template_directory() . '/inc/class-fremediti-guitars-videos-post-type.php';

require get_template_directory() . '/inc/class-fremediti-guitars-settings.php';

require get_template_directory() . '/inc/class-fremediti-guitars-multilanguage.php';

require get_template_directory() . '/inc/class-fremediti-guitars-multicurrency.php';


Fremediti_Guitars_Theme::instance();