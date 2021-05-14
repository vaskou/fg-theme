<?php
$template = ! empty( $args['template'] ) ? $args['template'] : '';

Fremediti_Guitars_FG_Pickups::instance();

if ( 'grid' == $template ) {
	get_template_part( 'template-parts/content', 'fg_pickups-grid' );
} else {
	get_template_part( 'template-parts/content', 'custom_post_type' );
}