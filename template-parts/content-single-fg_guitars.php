<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::instance()->getImageGallery( $post_id );

$image_gallery = ! is_array( $image_gallery ) ? array( $image_gallery ) : $image_gallery;

$featured_image    = array_slice( $image_gallery, 0, 1, true );
$featured_image_id = key( $featured_image );
$image_gallery     = array_slice( $image_gallery, 1, null, true );

$specifications = FG_Guitars_Specifications_Fields::instance();
$sounds         = FG_Guitars_Sounds_Fields::instance();
$features       = FG_Guitars_Features_Fields::instance();
$pricing        = FG_Guitars_Pricing_Fields::instance();

$single_guitar = Fremediti_Guitars_FG_Guitars::instance();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div uk-lightbox>
        <div class="uk-grid" uk-grid>
            <div class="uk-width-5-6@m">
                <a href="<?php echo wp_get_attachment_image_url( $featured_image_id, 'full' ); ?>">
					<?php echo wp_get_attachment_image( $featured_image_id, 'full' ); ?>
                </a>
            </div>
            <div class="uk-width-1-6@m">
                <div class="uk-flex-center uk-child-width-1-5 uk-child-width-1-1@m uk-grid-small" uk-grid>
					<?php
					foreach ( $image_gallery as $image_id => $image_url ):
						?>
                        <div class="fg-guitar-thumbs">
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

    <div class="uk-margin-medium-top">
        <ul class="uk-tab" uk-tab="animation: uk-animation-fade">
            <li><a><?php echo __( 'Overview', 'fremediti-guitars' ); ?></a></li>
            <li><a><?php echo $specifications->getMetaboxTitle(); ?></a></li>
            <li><a><?php echo $sounds->getMetaboxTitle(); ?></a></li>
			<?php
			if ( $features->isEnabled() ):
				?>
                <li><a><?php echo $features->getMetaboxTitle(); ?></a></li>
			<?php
			endif;
			?>
            <li><a><?php echo $pricing->getMetaboxTitle(); ?></a></li>
        </ul>
        <ul class="uk-switcher uk-margin-medium-top">
            <!-- Overview -->
            <li>
				<?php echo $single_guitar->get_short_description_html( $post_id ); ?>
            </li>
            <!-- Overview End -->

            <!-- Specifications -->
            <li>
				<?php echo $single_guitar->get_specs_html( $post_id ); ?>
            </li>
            <!-- Specifications End -->

            <!-- Sounds -->
            <li>
				<?php echo $single_guitar->get_sounds_html( $post_id ); ?>
            </li>
            <!-- Sounds End -->

			<?php
			if ( $features->isEnabled() ):
				?>
                <!-- Features -->
                <li>
					<?php echo $single_guitar->get_features_html( $post_id ); ?>
                </li>
                <!-- Features End -->
			<?php
			endif;
			?>

            <!-- Pricing -->
            <li>
				<?php echo $single_guitar->get_pricing_html( $post_id ); ?>
            </li>
            <!-- Pricing End -->
        </ul>
    </div>

</article>
