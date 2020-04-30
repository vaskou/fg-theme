<?php
$post_id       = get_the_ID();
$image_gallery = FG_Guitars_Images_Fields::getInstance()->getImageGallery( $post_id );

$featured_image_id = array_key_first( $image_gallery );
$image_gallery     = array_slice( $image_gallery, 1, null, true );

$short_description = FG_Guitars_Short_Description_Fields::getInstance();
$specifications    = FG_Guitars_Specifications_Fields::getInstance();
$sounds            = FG_Guitars_Sounds_Fields::getInstance();
$features          = FG_Guitars_Features_Fields::getInstance();
$pricing           = FG_Guitars_Pricing_Fields::getInstance();

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
                <div class="uk-child-width-1-2@m" uk-grid>
                    <div>
                        <h3><?php esc_attr_e( $short_description->getTitle( $post_id ) ); ?></h3>
                        <hr>
                        <div class="uk-text-justify">
							<?php the_content(); ?>
                        </div>
                    </div>
                    <div>
                        <h3><?php _e( 'Description', 'fremediti-guitars' ); ?></h3>
                        <hr>
                        <dl class="uk-description-list horizontal">
                            <dt class="uk-text-right@m"><?php esc_attr_e( $short_description->getNameLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description->getName( $post_id ) ); ?></dd>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $short_description->getTypeLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description->getType( $post_id ) ); ?></dd>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $short_description->getStyleLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description->getStyle( $post_id ) ); ?></dd>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $short_description->getPhotoLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description->getPhoto( $post_id ) ); ?></dd>
                        </dl>
                    </div>
                </div>
            </li>
            <!-- Overview End -->

            <!-- Specifications -->
            <li>
                <div class="uk-child-width-1-2@m fg-specs" uk-grid>
                    <?php echo Fremediti_Guitars_FG_Guitars::getInstance()->get_specs_html($post_id); ?>
                </div>
            </li>
            <!-- Specifications End -->

            <li></li>
			<?php
			if ( $features->isEnabled() ):
				?>
                <li></li>
			<?php
			endif;
			?>
            <li></li>
        </ul>
    </div>

</article>
