<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::instance()->getImageGallery( $post_id );

$image_gallery = ! empty( $image_gallery ) && ! is_array( $image_gallery ) ? array( $image_gallery ) : $image_gallery;

$single_guitar = Fremediti_Guitars_FG_Guitars::instance();

$available_guitars = FG_Guitars_Available_Guitars_Fields::instance();
$features          = FG_Guitars_Features_Fields::instance();
$sounds            = FG_Guitars_Sounds_Fields::instance();
$reviews           = FG_Guitars_Reviews_Fields::instance();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( ! empty( $image_gallery ) ): ?>
        <div uk-slideshow class="fg-guitar-slideshow">
            <div class="uk-grid" uk-grid>
                <div class="uk-width-5-6@m">
                    <ul class="uk-slideshow-items" uk-lightbox>
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
						<?php $index = 0; ?>
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

	<?php endif; ?>


	<?php $description_html = $single_guitar->get_description_html( $post_id ); ?>

	<?php $available_guitars_html = $single_guitar->get_available_guitars_html( $post_id ); ?>

	<?php $has_available_guitars_class = ! empty( $available_guitars_html ) ? 'uk-width-2-5@m uk-width-1-2@xl' : ''; ?>

    <div class="uk-grid uk-margin-top" uk-grid>
        <div class="fg-guitar-description-wrapper <?php echo $has_available_guitars_class; ?>">
			<?php echo $description_html; ?>
        </div>

		<?php if ( ! empty( $available_guitars_html ) ): ?>
            <div class="fg-available-guitars-wrapper uk-width-3-5@m uk-width-1-2@xl">
				<?php echo $available_guitars_html; ?>
            </div>
		<?php endif; ?>
    </div>


	<?php if ( $features->isEnabled() ): ?>
        <!-- Features -->
		<?php $features_html = $single_guitar->get_features_html( $post_id ); ?>
		<?php if ( ! empty( $features_html ) ): ?>
            <div class="fg-features uk-margin-top fg-read-more__block">
                <h3><?php echo $features->getMetaboxTitle(); ?></h3>
                <div class="uk-child-width-1-3@m uk-grid" uk-grid>
					<?php echo $features_html; ?>
                </div>
            </div>
			<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-features' ); ?>
		<?php endif; ?>
        <!-- Features End -->
	<?php endif; ?>

    <!-- Sounds -->
	<?php $sounds_html = $single_guitar->get_sounds_html( $post_id, 3, true ); ?>
	<?php if ( ! empty( $sounds_html ) ): ?>
        <div class="fg-sounds uk-margin-top fg-read-more__block">
            <h3><?php echo $sounds->getMetaboxTitle(); ?></h3>
			<?php echo $sounds_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-sounds' ); ?>
	<?php endif; ?>
    <!-- Sounds End -->

    <!-- Specifications -->
	<?php $custom_specs_html = $single_guitar->get_custom_specs_html( $post_id ); ?>

	<?php if ( ! empty( $custom_specs_html ) ): ?>
        <div class="fg-specifications uk-margin-top fg-read-more__block">
            <h3><?php echo __( 'Specifications', 'fg-guitars' ); ?></h3>
			<?php echo $custom_specs_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-specifications' ); ?>
	<?php endif; ?>
    <!-- Specifications End -->

    <!-- Reviews -->
	<?php $reviews_html = $single_guitar->get_reviews_html( $post_id ); ?>

	<?php if ( ! empty( $reviews_html ) ): ?>
        <div class="fg-reviews uk-margin-top fg-read-more__block">
            <h3><?php echo __( 'Reviews', 'fg-guitars' ); ?></h3>
			<?php echo $reviews_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-reviews' ); ?>
	<?php endif; ?>
    <!-- Reviews End -->

</article>