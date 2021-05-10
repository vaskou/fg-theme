<?php
$template    = ! empty( $args['template'] ) ? $args['template'] : '';

add_action( 'fremediti_guitars_custom_post_type_after_content', 'fremediti_guitars_add_fg_pickup_specs' );
add_action( 'fremediti_guitars_custom_post_type_after_content_row', 'fremediti_guitars_add_fg_pickup_extra_row' );

if ( 'grid' == $template ) {
	get_template_part( 'template-parts/content', 'fg_pickups-grid' );
} else {
	get_template_part( 'template-parts/content', 'custom_post_type' );
}

function fremediti_guitars_add_fg_pickup_specs() {
	if ( class_exists( 'FG_Pickups_Post_Type' ) && FG_Pickups_Post_Type::POST_TYPE_NAME == get_post_type() ):
		$fg_pickups_image_id = FG_Pickups_Post_Type::instance()->get_pickup_image_id( get_the_ID() );
		$url = wp_get_attachment_url( $fg_pickups_image_id );
		if ( ! empty( $url ) ):
			?>
            <div uk-lightbox>
                <a href="<?php echo esc_url( $url ); ?>" class="uk-button uk-button-primary">
					<?php _e( 'Specs', 'fremediti-guitars' ); ?>
                </a>
            </div>
		<?php
		endif;
	endif;
}

function fremediti_guitars_add_fg_pickup_extra_row() {
	$post_id = get_the_ID();
	?>
    <div class="uk-child-width-1-2@m uk-grid" uk-grid>
        <div>
            1
        </div>
        <div>
			<?php
			if ( class_exists( 'FG_Pickups_Post_Type' ) && FG_Pickups_Post_Type::POST_TYPE_NAME == get_post_type() ):
				$video = FG_Pickups_Post_Type::instance()->get_video( $post_id );
				if ( ! empty( $video ) ):
					?>
                    <div uk-lightbox>
                        <a href="//youtube.com/watch?v=<?php echo $video; ?>" class="video-link" data-attrs="width: 1280; height: 720;">
                            <img src="//img.youtube.com/vi/<?php echo $video; ?>/sddefault.jpg" alt="<?php _e( 'Guitar Video', 'fremediti-guitars' ); ?>"/>
                            <svg height="100%" viewBox="0 0 68 48" width="100%">
                                <path class="ytp-large-play-button-bg"
                                      d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                      fill="#f00"></path>
                                <path d="M 45,24 27,14 27,34" fill="#fff"></path>
                            </svg>
                        </a>
                    </div>
				<?php
				endif;
			endif;
			?>
        </div>
    </div>
	<?php
}