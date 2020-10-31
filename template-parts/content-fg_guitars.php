<?php

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'uk-flex uk-child-width-1-1' ); ?>>
    <div class="fg-box uk-text-center uk-flex uk-child-width-1-1 uk-flex-right uk-flex-column">
		<?php
		$link     = get_permalink();
		$image_id = FG_Guitars_Images_Fields::instance()->getMenuImageID( get_the_ID() );
		?>
        <a href="<?php echo $link; ?>" class="uk-display-block ">
			<?php
			if ( ! empty( $image_id ) ):
				echo wp_get_attachment_image( $image_id, 'full' );
			endif;
			?>

			<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
        </a>
    </div>
</article>
