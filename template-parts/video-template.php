<?php
$videos  = ! empty( $args['videos'] ) ? $args['videos'] : array();
$columns = ! empty( $args['columns'] ) ? $args['columns'] : 1;
$class   = ! empty( $args['class'] ) ? $args['class'] : '';

if ( ! empty( $videos ) ):
	?>

    <div class="<?php echo $class; ?>" uk-lightbox>
		<?php if ( $columns > 1 ): ?>
        <div class="uk-child-width-1-1 uk-child-width-1-2@s uk-child-width-1-<?php echo $columns; ?>@m uk-grid-medium" uk-grid>
			<?php endif; ?>

			<?php foreach ( $videos as $video ): ?>
                <div class="video-wrapper">
                    <a href="//youtube.com/watch?v=<?php echo $video['url']; ?>" class="video-link" data-attrs="width: 1280; height: 720;">
                        <img src="//img.youtube.com/vi/<?php echo $video['url']; ?>/sddefault.jpg" alt="<?php _e( 'Guitar Video', 'fremediti-guitars' ); ?>"/>
                        <svg height="100%" viewBox="0 0 68 48" width="100%">
                            <path class="ytp-large-play-button-bg"
                                  d="M66.52,7.74c-0.78-2.93-2.49-5.41-5.42-6.19C55.79,.13,34,0,34,0S12.21,.13,6.9,1.55 C3.97,2.33,2.27,4.81,1.48,7.74C0.06,13.05,0,24,0,24s0.06,10.95,1.48,16.26c0.78,2.93,2.49,5.41,5.42,6.19 C12.21,47.87,34,48,34,48s21.79-0.13,27.1-1.55c2.93-0.78,4.64-3.26,5.42-6.19C67.94,34.95,68,24,68,24S67.94,13.05,66.52,7.74z"
                                  fill="#f00"></path>
                            <path d="M 45,24 27,14 27,34" fill="#fff"></path>
                        </svg>
                    </a>
					<?php if ( ! empty( $video['title'] ) ): ?>
                        <h4 class="uk-margin-small-top"><?php esc_attr_e( $video['title'] ); ?></h4>
					<?php endif; ?>
                </div>
			<?php endforeach; ?>

			<?php if ( $columns > 1 ): ?>
        </div>
	<?php endif; ?>

    </div>

<?php endif; ?>