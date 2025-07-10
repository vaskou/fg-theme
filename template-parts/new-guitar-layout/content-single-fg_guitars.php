<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::instance()->getImageGallery( $post_id );

$image_gallery = ! empty( $image_gallery ) && ! is_array( $image_gallery ) ? array( $image_gallery ) : $image_gallery;

$single_guitar = Fremediti_Guitars_FG_Guitars::instance();

$available_guitars = FG_Guitars_Available_Guitars_Fields::instance();
$features          = FG_Guitars_Features_Fields::instance();
$sounds            = FG_Guitars_Sounds_Fields::instance();
$reviews           = FG_Guitars_Reviews_Fields::instance();

$show_new_images = Fremediti_Guitars_Helpers::show_new_images();

$not_full_width_class = $show_new_images ? 'uk-container' : '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if ( $show_new_images ): ?>
        <div class="uk-cover-container uk-text-center fg-guitar-main-image">
			<?php echo wp_get_attachment_image( array_key_first( $image_gallery ), 'full', false, [ 'class' => 'uk-position-center' ] ); ?>
        </div>
	<?php else: ?>
		<?php echo $single_guitar->get_images_slideshow( $image_gallery ); ?>
	<?php endif; ?>

	<?php $description_html = $single_guitar->get_description_html( $post_id ); ?>

	<?php $available_guitars_html = $single_guitar->get_available_guitars_html( $post_id ); ?>

	<?php $has_available_guitars_class = ! empty( $available_guitars_html ) ? 'uk-width-2-5@m uk-width-1-2@xl' : ''; ?>

    <div class="fg-guitar-description-available-guitars <?php echo $not_full_width_class; ?>">
        <div class="uk-grid uk-margin-top" uk-grid>
            <div class="fg-guitar-description-wrapper <?php echo $has_available_guitars_class; ?>">
				<?php echo $description_html; ?>
            </div>

			<?php if ( ! empty( $available_guitars_html ) ): ?>
                <div class="fg-available-guitars-wrapper uk-width-3-5@m uk-width-1-2@xl">
					<?php echo $available_guitars_html; ?>

					<?php do_action( 'fremediti_guitars_single_fg_guitars_available_guitars_after', get_the_ID() ); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>

	<?php if ( $show_new_images ): ?>
		<?php $new_images_html = $single_guitar->get_new_images( $image_gallery ); ?>
		<?php if ( ! empty( $new_images_html ) ): ?>
            <div class="fg-guitar-gallery-wrapper uk-margin-top">
                <div class="uk-container">
                    <h3><?php echo __( 'Gallery', 'fremediti-guitars' ); ?></h3>
					<?php echo $new_images_html; ?>
                </div>
            </div>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ( $features->isEnabled() ): ?>
        <!-- Features -->
		<?php $features_html = $single_guitar->get_features_html( $post_id ); ?>
		<?php if ( ! empty( $features_html ) ): ?>
            <div class="fg-features uk-margin-top fg-read-more__block <?php echo $not_full_width_class; ?>">
                <h3><?php echo $features->getMetaboxTitle(); ?></h3>
                <div class="uk-child-width-1-3@m uk-grid" uk-grid>
					<?php echo $features_html; ?>
                </div>
            </div>
			<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-features', $not_full_width_class ); ?>
		<?php endif; ?>
        <!-- Features End -->
	<?php endif; ?>

    <!-- Sounds -->
	<?php $sounds_html = $single_guitar->get_sounds_html( $post_id, 3, true ); ?>
	<?php if ( ! empty( $sounds_html ) ): ?>
        <div class="fg-sounds uk-margin-top fg-read-more__block <?php echo $not_full_width_class; ?>">
            <h3><?php echo $sounds->getMetaboxTitle(); ?></h3>
			<?php echo $sounds_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-sounds', $not_full_width_class ); ?>
	<?php endif; ?>
    <!-- Sounds End -->

    <!-- Reviews -->
	<?php $reviews_html = $single_guitar->get_reviews_html( $post_id ); ?>

	<?php if ( ! empty( $reviews_html ) ): ?>
        <div class="fg-reviews uk-margin-top fg-read-more__block <?php echo $not_full_width_class; ?>">
            <h3><?php echo __( 'Reviews', 'fg-guitars' ); ?></h3>
			<?php echo $reviews_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-reviews', $not_full_width_class ); ?>
	<?php endif; ?>
    <!-- Reviews End -->

    <!-- Specifications -->
	<?php $custom_specs_html = $single_guitar->get_custom_specs_html( $post_id ); ?>

	<?php if ( ! empty( $custom_specs_html ) ): ?>
        <div class="fg-specifications uk-margin-top fg-read-more__block <?php echo $not_full_width_class; ?>">
            <h3><?php echo __( 'Specifications', 'fg-guitars' ); ?></h3>
			<?php echo $custom_specs_html; ?>
        </div>
		<?php echo Fremediti_Guitars_Helpers::get_read_more_button_html( '.fg-specifications', $not_full_width_class ); ?>
	<?php endif; ?>
    <!-- Specifications End -->

	<?php do_action( 'fremediti_guitars_single_fg_guitars_related_guitars_before', get_the_ID() ); ?>

    <!-- Related guitars -->
	<?php $related_guitars_html = $single_guitar->get_related_guitars_html( $post_id ); ?>

	<?php if ( ! empty( $related_guitars_html ) ): ?>
        <div class="fg-related-guitars uk-margin-top <?php echo $not_full_width_class; ?>">
            <h3><?php echo __( 'Related guitars', 'fg-guitars' ); ?></h3>
			<?php echo $related_guitars_html; ?>
        </div>
	<?php endif; ?>
    <!-- Related guitars End -->

    <div class="fg-back-to-top uk-margin-large-top uk-text-center">
        <a class="uk-button uk-button-primary" href="#body" uk-scroll><?php echo __( 'Back to top', 'fremediti-guitars' ); ?></a>
    </div>

</article>