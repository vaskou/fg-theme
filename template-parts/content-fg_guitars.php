<?php

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'uk-flex uk-child-width-1-1' ); ?>>
    <div class="fg-box uk-text-center uk-flex uk-child-width-1-1 uk-flex-right uk-flex-column">
		<?php
		$link     = get_permalink();
		$image_id = FG_Guitars_Images_Fields::getInstance()->getMenuImageID( get_the_ID() );

		if ( ! empty( $image_id ) ):
			?>
            <a href="<?php echo $link; ?>" class="uk-display-block ">
				<?php echo wp_get_attachment_image( $image_id, 'full' ); ?>
            </a>
		<?php
		endif;
		?>
        <a href="<?php echo $link; ?>" class="uk-display-block ">
			<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
        </a>
    </div>
</article>
