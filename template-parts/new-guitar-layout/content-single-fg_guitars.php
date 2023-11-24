<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::instance()->getImageGallery( $post_id );

$image_gallery = ! is_array( $image_gallery ) ? array( $image_gallery ) : $image_gallery;

$featured_image    = array_slice( $image_gallery, 0, 1, true );
$featured_image_id = key( $featured_image );
$image_gallery     = array_slice( $image_gallery, 1, null, true );

$single_guitar = Fremediti_Guitars_FG_Guitars::instance();

$features = FG_Guitars_Features_Fields::instance();
$sounds   = FG_Guitars_Sounds_Fields::instance();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <div uk-slideshow class="fg-guitar-slideshow">
        <div class="uk-grid" uk-grid>
            <div class="uk-width-5-6@m">
                <ul class="uk-slideshow-items" uk-lightbox>
                    <li>
                        <a href="<?php echo wp_get_attachment_image_url( $featured_image_id, 'full' ); ?>" class="uk-display-block uk-height-1-1">
							<?php echo wp_get_attachment_image( $featured_image_id, 'full', false, [ 'class' => 'uk-object-contain uk-height-1-1 uk-width-1-1' ] ); ?>
                        </a>
                    </li>
					<?php foreach ( $image_gallery as $image_id => $image_url ): ?>
                        <li>
                            <a href="<?php echo wp_get_attachment_image_url( $image_id, 'full' ); ?>" class="uk-display-block uk-height-1-1">
								<?php echo wp_get_attachment_image( $image_id, 'full', false, [ 'class' => 'uk-object-contain uk-height-1-1 uk-width-1-1' ] ); ?>
                            </a>
                        </li>
					<?php endforeach; ?>
                </ul>
            </div>


            <div class="uk-width-1-6@m">
                <div class="uk-thumbnav uk-flex-center uk-child-width-1-6 uk-child-width-1-1@m uk-grid-small" uk-grid>
					<?php $index = 1; ?>
                    <div uk-slideshow-item="0" class="fg-guitar-thumbs">
                        <a href="#"><?php echo wp_get_attachment_image( $featured_image_id, 'thumbnail' ); ?></a>
                    </div>
					<?php foreach ( $image_gallery as $image_id => $image_url ): ?>

                        <div uk-slideshow-item="<?php echo $index; ?>" class="fg-guitar-thumbs">
                            <a href="#"><?php echo wp_get_attachment_image( $image_id, 'thumbnail' ); ?></a>
                        </div>

						<?php $index ++; ?>
					<?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>


	<?php echo $single_guitar->get_description_html( $post_id ); ?>

	<?php
	if ( $features->isEnabled() ):
		?>
        <!-- Features -->
        <div class="fg-features uk-margin-top fg-read-more__block">
            <h3><?php echo $features->getMetaboxTitle(); ?></h3>
            <div class="uk-child-width-1-3@m uk-grid" uk-grid>
				<?php echo $single_guitar->get_features_html( $post_id ); ?>
            </div>
        </div>
        <div class="fg-read-more__button__container">
            <span class="fg-read-more__button__outside" uk-toggle="target: .fg-features; cls: fg-read-more__block;">
                <span class="fg-read-more__button__inside"><?php echo __( 'Read more', 'fg-guitars' ); ?></span>
            </span>
        </div>
        <!-- Features End -->
	<?php
	endif;
	?>

    <!-- Sounds -->
    <div class="fg-sounds uk-margin-top fg-read-more__block">
        <h3><?php echo $sounds->getMetaboxTitle(); ?></h3>
		<?php echo $single_guitar->get_sounds_html( $post_id, 3 ); ?>
    </div>
    <div class="fg-read-more__button__container">
        <span class="fg-read-more__button__outside" uk-toggle="target: .fg-sounds; cls: fg-read-more__block;">
            <span class="fg-read-more__button__inside"><?php echo __( 'Read more', 'fg-guitars' ); ?></span>
        </span>
    </div>
    <!-- Sounds End -->

    <!-- Specifications -->
    <div class="fg-specifications uk-margin-top fg-read-more__block">
        <h3><?php echo __( 'Specifications', 'fg-guitars' ); ?></h3>
		<?php echo $single_guitar->get_custom_specs_html( $post_id ); ?>
    </div>
    <div class="fg-read-more__button__container">
        <span class="fg-read-more__button__outside" uk-toggle="target: .fg-specifications; cls: fg-read-more__block;">
            <span class="fg-read-more__button__inside"><?php echo __( 'Read more', 'fg-guitars' ); ?></span>
        </span>
    </div>
    <!-- Specifications End -->

</article>