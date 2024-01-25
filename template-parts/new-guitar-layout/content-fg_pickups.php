<?php
$template = ! empty( $args['template'] ) ? $args['template'] : '';

if ( 'grid' == $template ) {
	get_template_part( 'template-parts/new-guitar-layout/content', 'fg_pickups-grid' );
} else {
	get_template_part( 'template-parts/new-guitar-layout/content', 'custom_post_type' );
}