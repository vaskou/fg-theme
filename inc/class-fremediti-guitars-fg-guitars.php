<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_FG_Guitars {

	private $short_description = object;
	private $specifications = object;
	private $sounds = object;
	private $features = object;
	private $pricing = object;

	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		if ( class_exists( 'FG_Guitars_Short_Description_Fields' ) ) {
			$this->short_description = FG_Guitars_Short_Description_Fields::getInstance();
		}

		if ( class_exists( 'FG_Guitars_Specifications_Fields' ) ) {
			$this->specifications = FG_Guitars_Specifications_Fields::getInstance();
		}

		if ( class_exists( 'FG_Guitars_Sounds_Fields' ) ) {
			$this->sounds = FG_Guitars_Sounds_Fields::getInstance();
		}

		if ( class_exists( 'FG_Guitars_Features_Fields' ) ) {
			$this->features = FG_Guitars_Features_Fields::getInstance();
		}

		if ( class_exists( 'FG_Guitars_Pricing_Fields' ) ) {
			$this->pricing = FG_Guitars_Pricing_Fields::getInstance();
		}
	}

	public function get_short_description_html( $post_id ) {
		ob_start();
		$title                   = $this->short_description->getTitle( $post_id );
		$short_description_name  = $this->short_description->getName( $post_id );
		$short_description_type  = $this->short_description->getType( $post_id );
		$short_description_style = $this->short_description->getStyle( $post_id );
		$short_description_photo = $this->short_description->getPhoto( $post_id );
		?>
        <div class="uk-child-width-1-2@m" uk-grid>
            <div>
				<?php if ( ! empty( $title ) ): ?>
                    <h3><?php esc_attr_e( $title ); ?></h3>
                    <hr>
				<?php endif; ?>
                <div class="uk-text-justify">
					<?php the_content(); ?>
                </div>
            </div>
            <div>
				<?php if ( ! empty( $short_description_name ) && ! empty( $short_description_type ) && ! empty( $short_description_style ) && ! empty( $short_description_photo ) ): ?>
                    <h3><?php _e( 'Description', 'fremediti-guitars' ); ?></h3>
                    <hr>
                    <dl class="uk-description-list horizontal">
						<?php if ( ! empty( $short_description_name ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getNameLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $this->short_description->getName( $post_id ) ); ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_type ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getTypeLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $this->short_description->getType( $post_id ) ); ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_style ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getStyleLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $this->short_description->getStyle( $post_id ) ); ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_photo ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getPhotoLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $this->short_description->getPhoto( $post_id ) ); ?></dd>
						<?php endif; ?>
                    </dl>
				<?php endif; ?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function get_specs_html( $post_id ) {
		$specs = $this->_get_specs( $post_id );

		$has_spec_variations = $this->specifications->hasVariations();

		ob_start();
		if ( $has_spec_variations ):
			echo $this->_get_specs_tabs( $specs );
		endif;

		echo $this->_get_specs_content( $specs );

		return ob_get_clean();
	}

	public function get_sounds_html( $post_id ) {
		$sounds = $this->sounds->getPostMeta( $post_id );

		ob_start();
		if ( ! empty( $sounds ) ):
			?>
            <div uk-lightbox>
                <div class="uk-child-width-1-2 uk-child-width-1-4@m" uk-grid>
					<?php
					foreach ( $sounds as $sound ):
						?>
                        <a href="//youtube.com/watch?v=<?php echo $sound; ?>" data-attrs="width: 1280; height: 720;">
                            <img src="//img.youtube.com/vi/<?php echo $sound; ?>/sddefault.jpg" alt="<?php _e( 'Guitar Video', 'fremediti-guitars' ); ?>"/>
                        </a>
					<?php
					endforeach;
					?>
                </div>
            </div>
		<?php
		else:
			?>
            <div class="uk-text-center uk-margin-top">
                <img src="<?php echo FREMEDITI_GUITARS_THEME_URL ?>/assets/images/coming_soon.png" alt="<?php _e( 'Guitar Videos Coming Soon' ); ?>">
            </div>
		<?php
		endif;

		return ob_get_clean();
	}

	public function get_features_html( $post_id ) {
		$features = $this->features->getPostMeta( $post_id );

		$features_post_in = ! empty( $features['features']['feature'] ) ? implode( ',', $features['features']['feature'] ) : '';
		$pickups_post_in  = ! empty( $features['features']['pickup'] ) ? implode( ',', $features['features']['pickup'] ) : '';

		ob_start();

		if ( ! empty( $features_post_in ) ):
			echo do_shortcode( '[fg-features post__in="' . $features_post_in . '"]' );
		endif;

		if ( ! empty( $pickups_post_in ) ):
			echo do_shortcode( '[fg-pickups post__in="' . $pickups_post_in . '"]' );
		endif;

		return ob_get_clean();
	}

	public function get_pricing_html( $post_id ) {
		$pricing_items = $this->pricing->getPriceItems( $post_id );
		$pricing_text  = $this->pricing->getPriceText( $post_id );
		$base_price    = $this->pricing->getPrice( $post_id );

		if ( function_exists( 'currency_exchange_rates_convert' ) ) {
			$base_price_converted = currency_exchange_rates_convert( $base_price, 'EUR' );
		}

		ob_start();
		?>
        <div class="uk-child-width-1-2@m" uk-grid>
			<?php
			if ( ! empty( $pricing_items ) ):
				?>
                <div class="fg-guitar-pricing-extra-options">
                    <h3><?php echo $this->pricing->getPriceItemsLabel(); ?></h3>
                    <table class="uk-table uk-table-small uk-table-divider uk-table-responsive">
                        <tbody>
						<?php
						foreach ( $pricing_items as $item ):
							$item_price = number_format_i18n( $item['extra_option_price'] );
							?>
                            <tr>
                                <td class="uk-width-5-6"><?php esc_attr_e( $item['extra_option'] ); ?></td>
                                <td class="uk-width-1-6 uk-text-right">
                                    <span class="fg-original-price">&dollar;<?php esc_attr_e( $item_price ); ?></span>
									<?php
									if ( function_exists( 'currency_exchange_rates_convert' ) ) {
										$item_price_converted = currency_exchange_rates_convert( $item_price, 'EUR' );
									}
									if ( ! empty( $item_price_converted ) ):
										?>
                                        <span class="fg-converted-price uk-hidden">&euro;<?php esc_attr_e( number_format_i18n( $item_price_converted ) ); ?></span>
									<?php
									endif;
									?>
                                </td>
                            </tr>
						<?php
						endforeach;
						?>
                        </tbody>
                    </table>
                </div>
			<?php
			endif;
			?>
            <div class="fg-guitar-pricing">
                <h3 class="fg-base-price uk-text-right@m">
					<?php if ( ! empty( $base_price ) ): ?>
						<?php echo $this->pricing->getPriceLabel(); ?> : <span class="fg-original-price">&dollar;<?php esc_attr_e( number_format_i18n( $base_price ) ); ?>*</span>
					<?php endif; ?>
					<?php
					if ( ! empty( $base_price_converted ) ):
						?>
                        <span class="fg-converted-price uk-hidden">&euro;<?php esc_attr_e( number_format_i18n( $base_price_converted ) ); ?>* </span>
                        <button class="uk-button fg-usd fg-currency-button fg-selected">&dollar;</button>
                        <button class="uk-button fg-eur fg-currency-button">&euro;</button>
					<?php
					endif;
					?>
                </h3>
                <div>
					<?php esc_attr_e( $pricing_text ); ?>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	private function _get_specs( $post_id ) {
		$new_specs = array();

		$specs = $this->specifications->getPostMeta( $post_id );

		foreach ( $specs as $spec ) {
			$spec        = array_filter( $spec );
			$new_specs[] = $spec;
		}

		return $new_specs;
	}

	private function _get_specs_tabs( $specs = array() ) {
		ob_start();
		?>
        <ul class="fg-specs-variations-tabs" uk-tab>
			<?php
			foreach ( $specs as $key => $spec ):
				?>
                <li>
                    <a href="">
						<?php echo ! empty( $spec['configuration_image_id'] ) ?
							wp_get_attachment_image( $spec['configuration_image_id'], 'thumbnail' ) :
							sprintf( __( 'Configuration %s', 'fremediti-guitars' ), $key + 1 ); ?>
                    </a>
                </li>
			<?php
			endforeach;
			?>
        </ul>
		<?php
		return ob_get_clean();
	}

	private function _get_specs_content( $specs = array() ) {
		$has_spec_variations = $this->specifications->hasVariations();

		echo $has_spec_variations ? '<ul class="uk-switcher uk-margin-top">' : '';

		ob_start();

		foreach ( $specs as $spec ):
			unset( $spec['configuration_image_id'] );
			unset( $spec['configuration_image'] );

			$divided_specs = $this->_divide_array( $spec );

			echo $has_spec_variations ? '<li>' : '';

			?>
            <div class="uk-child-width-1-2@m fg-specs" uk-grid>
				<?php
				foreach ( $divided_specs as $spec ):
					?>
                    <div class="uk-margin-remove-top">
						<?php echo $this->_specs_table( $spec ); ?>
                    </div>
				<?php
				endforeach;
				?>
            </div>
			<?php

			echo $has_spec_variations ? '</li>' : '';

		endforeach;

		echo $has_spec_variations ? '</ul>' : '';

		return ob_get_clean();
	}

	private function _specs_table( $specs ) {
		if ( empty( $specs ) || ! is_array( $specs ) ) {
			return '';
		}

		ob_start();
		?>
        <table class="uk-table uk-table-small uk-table-divider uk-table-responsive">
            <tbody>
			<?php
			foreach ( $specs as $key => $value ):
				?>
                <tr>
                    <td class="uk-width-1-5@m uk-width-1-3 uk-text-bold uk-text-right@m uk-text-uppercase"><?php esc_attr_e( $this->specifications->getFieldLabel( $key ) ); ?></td>
                    <td class="uk-width-4-5@m uk-width-2-3"><?php esc_attr_e( $value ); ?></td>
                </tr>
			<?php
			endforeach;
			?>
            </tbody>
        </table>
		<?php

		return ob_get_clean();
	}

	private function _divide_array( $array ) {
		$half_array = round( count( $array ) / 2 );

		return array(
			array_slice( $array, 0, $half_array, true ),
			array_slice( $array, $half_array, null, true )
		);
	}
}