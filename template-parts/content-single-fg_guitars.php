<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::getInstance()->getImageGallery( $post_id );

$featured_image_id = array_key_first( $image_gallery );
$image_gallery     = array_slice( $image_gallery, 1, null, true );

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div uk-lightbox>
        <div uk-grid>
            <div class="uk-width-5-6@m">
                <a href="<?php echo wp_get_attachment_image_url( $featured_image_id, 'full' ); ?>">
					<?php echo wp_get_attachment_image( $featured_image_id, 'full' ); ?>
                </a>
            </div>
            <div class="uk-width-1-6@m">
                <div class="uk-child-width-1-4 uk-child-width-1-1@m uk-grid-small" uk-grid>
					<?php
					foreach ( $image_gallery as $image_id => $image_url ):
						?>
                        <div class="">
                            <a href="<?php echo wp_get_attachment_image_url( $image_id, 'full' ); ?>">
								<?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?>
                            </a>
                        </div>
					<?php
					endforeach;
					?>

                </div>
            </div>
        </div>
    </div>
    <div class="fg-box uk-text-center">
		<?php
		$link     = get_permalink();
		$image_id = FG_Guitars_Images_Fields::getInstance()->getMenuImageID( get_the_ID() );

		?>
        <a href="<?php echo $link; ?>" class="uk-display-block ">
			<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
        </a>
        <a href="<?php echo $link; ?>" class="uk-display-block ">
			<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
        </a>
    </div>
</article>
