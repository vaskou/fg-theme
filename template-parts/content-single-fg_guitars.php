<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::getInstance()->getImageGallery( $post_id );

$featured_image    = array_slice( $image_gallery, 0, 1, true );
$featured_image_id = key( $featured_image );
$image_gallery     = array_slice( $image_gallery, 1, null, true );

$specifications = FG_Guitars_Specifications_Fields::getInstance();
$sounds         = FG_Guitars_Sounds_Fields::getInstance();
$features       = FG_Guitars_Features_Fields::getInstance();
$pricing        = FG_Guitars_Pricing_Fields::getInstance();

$single_guitar = Fremediti_Guitars_FG_Guitars::getInstance();
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

    <div>
        <ul class="" uk-tab>
            <li><a><?php echo __( 'Overview', 'fremediti-guitars' ); ?></a></li>
            <li><a><?php echo $specifications->getMetaboxId(); ?></a></li>
            <li><a><?php echo $sounds->getMetaboxId(); ?></a></li>
			<?php
			if ( $features->isEnabled() ):
				?>
                <li><a><?php echo $features->getMetaboxId(); ?></a></li>
			<?php
			endif;
			?>
            <li><a><?php echo $pricing->getMetaboxId(); ?></a></li>
        </ul>
        <ul class="uk-switcher">
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
