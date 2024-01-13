<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_FG_Guitars {

	/**
	 * @var object
	 */
	private $short_description;

	/**
	 * @var object
	 */
	private $specifications;

	/**
	 * @var object
	 */
	private $custom_specifications;

	/**
	 * @var object
	 */
	private $sounds;

	/**
	 * @var object
	 */
	private $features;

	/**
	 * @var object
	 */
	private $pricing;

	/**
	 * @var object
	 */
	private $available_guitars;

	/**
	 * @var object
	 */
	private $reviews;

	private static $instance = null;

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

		if ( class_exists( 'FG_Guitars_Short_Description_Fields' ) ) {
			$this->short_description = FG_Guitars_Short_Description_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Specifications_Fields' ) ) {
			$this->specifications = FG_Guitars_Specifications_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Custom_Specifications_Fields' ) ) {
			$this->custom_specifications = FG_Guitars_Custom_Specifications_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Sounds_Fields' ) ) {
			$this->sounds = FG_Guitars_Sounds_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Features_Fields' ) ) {
			$this->features = FG_Guitars_Features_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Pricing_Fields' ) ) {
			$this->pricing = FG_Guitars_Pricing_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Available_Guitars_Fields' ) ) {
			$this->available_guitars = FG_Guitars_Available_Guitars_Fields::instance();
		}

		if ( class_exists( 'FG_Guitars_Reviews_Fields' ) ) {
			$this->reviews = FG_Guitars_Reviews_Fields::instance();
		}
	}

	public function get_description_html( $post_id ) {
		if ( ! is_a( $this->short_description, 'FG_Guitars_Short_Description_Fields' ) ) {
			return '';
		}

		$title = $this->short_description->getTitle( $post_id );

		ob_start();
		?>
        <div class="fg-guitar-description">
			<?php if ( ! empty( $title ) ): ?>
                <h3><?php esc_attr_e( $title ); ?></h3>
                <hr>
			<?php endif; ?>
            <div class="uk-text-justify">
				<?php the_content(); ?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function get_short_description_html( $post_id ) {
		if ( ! is_a( $this->short_description, 'FG_Guitars_Short_Description_Fields' ) ) {
			return '';
		}

		ob_start();
		$title                   = $this->short_description->getTitle( $post_id );
		$short_description_name  = $this->short_description->getName( $post_id );
		$short_description_type  = $this->short_description->getType( $post_id );
		$short_description_style = $this->short_description->getStyle( $post_id );
		$short_description_photo = $this->short_description->getPhoto( $post_id );
		?>
        <div class="uk-child-width-1-2@m uk-grid" uk-grid>
            <div>
				<?php if ( ! empty( $title ) ): ?>
                    <h3><?php esc_attr_e( $title ); ?></h3>
                    <hr>
				<?php endif; ?>
                <div class="uk-text-justify">
					<?php the_content(); ?>
                </div>
            </div>
            <div class="fg-short-description">
				<?php if ( ! empty( $short_description_name ) || ! empty( $short_description_type ) || ! empty( $short_description_style ) || ! empty( $short_description_photo ) ): ?>
                    <h3><?php _e( 'Description', 'fremediti-guitars' ); ?></h3>
                    <hr>
                    <dl class="uk-description-list horizontal">
						<?php if ( ! empty( $short_description_name ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getNameLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description_name ); ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_type ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getTypeLabel() ); ?></dt>
                            <dd><?php echo $short_description_type; ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_style ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getStyleLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description_style ); ?></dd>
						<?php endif; ?>
						<?php if ( ! empty( $short_description_photo ) ): ?>
                            <dt class="uk-text-right@m"><?php esc_attr_e( $this->short_description->getPhotoLabel() ); ?></dt>
                            <dd><?php esc_attr_e( $short_description_photo ); ?></dd>
						<?php endif; ?>
                    </dl>
				<?php endif; ?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	public function get_available_guitars_html( $post_id ) {
		if ( ! is_a( $this->available_guitars, 'FG_Guitars_Available_Guitars_Fields' ) ) {
			return '';
		}

		$items = $this->available_guitars->getPostMeta( $post_id );

		if ( empty( $items['available_guitars']['guitars'] ) ) {
			return '';
		}

		ob_start();

		?>

		<?php foreach ( $items['available_guitars']['guitars'] as $item ): ?>
			<?php
			$image_id  = get_post_meta( $item, 'fg_available_guitars_image_id', true );
			$image_url = wp_get_attachment_image_url( $image_id, 'full' );
			$image     = wp_get_attachment_image( $image_id, 'full' );

			if ( empty( $image_url ) ) {
				continue;
			}

			$specs_image_id  = get_post_meta( $item, 'fg_available_guitars_specs_id', true );
			$specs_image_url = wp_get_attachment_image_url( $specs_image_id, 'full' );

			$price = Fremediti_Guitars_Available_Guitars_Post_Type::instance()->get_price( $item );

			$availability = get_post_meta( $item, 'fg_available_guitars_availability', true );

			$contact_us_page = get_page_by_path( 'contact-us' );
			if ( ! empty( $contact_us_page ) ) {
				$contact_us_page_link = get_permalink( $contact_us_page->ID );
			}
			?>
            <div class="uk-flex uk-flex-middle uk-flex-between uk-grid-small fg-available-guitar fg-available-guitar-<?php echo $item; ?>" uk-grid>
                <div uk-lightbox class="fg-available-guitar__specs">
					<?php if ( ! empty( $specs_image_url ) ): ?>
                        <a href="<?php echo esc_url( $specs_image_url ); ?>" class="uk-icon-link" uk-icon="icon:info; ratio:1.2;" aria-label="info"></a>
					<?php endif; ?>
                </div>

                <div uk-lightbox class="fg-available-guitar__photo">
					<?php if ( ! empty( $image ) && ! empty( $image_url ) ): ?>
                        <a href="<?php echo esc_url( $image_url ); ?>">
							<?php echo $image; ?>
                        </a>
					<?php endif; ?>
                </div>

                <div uk-lightbox class="fg-available-guitar__availability uk-text-center">
					<?php if ( ! empty( $availability ) ): ?>
						<?php echo wpautop( $availability ); ?>
					<?php endif; ?>
                </div>

                <div class="fg-available-guitar__price">
					<?php if ( ! empty( $price ) ): ?>
						<?php echo Fremediti_Guitars_Template_Functions::price_format( $price ) ?>
					<?php endif; ?>
                </div>

                <div class="fg-available-guitar__button">
					<?php if ( ! empty( $contact_us_page_link ) ): ?>
                        <a href="<?php echo $contact_us_page_link; ?>" class="uk-button uk-button-primary uk-button-small" target="_blank">
							<?php _e( 'Contact Us', 'fremediti-guitars' ); ?>
                        </a>
					<?php endif; ?>
                </div>

            </div>
		<?php endforeach; ?>

		<?php

		return ob_get_clean();
	}

	public function get_specs_html( $post_id ) {
		if ( ! is_a( $this->specifications, 'FG_Guitars_Specifications_Fields' ) ) {
			return '';
		}

		$specs = $this->_get_specs( $post_id );

		$has_spec_variations = $this->specifications->hasVariations();

		ob_start();
		if ( $has_spec_variations ):
			echo $this->_get_specs_tabs( $specs );
		endif;

		echo $this->_get_specs_content( $specs );

		return ob_get_clean();
	}

	public function get_custom_specs_html( $post_id ) {
		if ( ! is_a( $this->custom_specifications, 'FG_Guitars_Custom_Specifications_Fields' ) ) {
			return '';
		}

		$specs_groups = $this->custom_specifications->getPostMeta( $post_id );

		$fields = $this->custom_specifications->getFields();

		$specs = [];

		foreach ( $specs_groups['specifications_group'] as $specs_group_key => $specs_group ) {
			if ( empty( $specs_group[0] ) ) {
				continue;
			}

			$specs[ $specs_group_key ] = $specs_group[0];
		}

		if ( empty( $specs ) ) {
			return '';
		}

		ob_start();
		?>

        <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid" uk-grid>
			<?php foreach ( $specs as $specs_group_key => $specs_group ): ?>
				<?php
				if ( empty( $specs_group ) ) {
					continue;
				}
				?>
                <div class="fg-custom-specs-group__<?php echo esc_attr( $specs_group_key ); ?>">
                    <h4 class="uk-heading-divider"><?php echo $this->custom_specifications->getGroupLabel( $specs_group_key ); ?></h4>

                    <ul class="uk-list fg-custom-specs-group__list">
						<?php
						foreach ( $specs_group as $field => $value ):
							$name = $fields[ $specs_group_key ]['fields'][ $field ]['name'];
							$value = 'wysiwyg' == $fields[ $specs_group_key ]['fields'][ $field ]['type'] ? wpautop( $value ) : esc_attr( $value );
							?>
                            <li class="fg-custom-specs-group__item">
                                <div class="uk-flex uk-flex-between">
                                    <div class="fg-custom-specs-group__item__name"><?php esc_attr_e( $name ); ?></div>
                                    <div class="fg-custom-specs-group__item__value"><?php echo $value; ?></div>
                                </div>
                            </li>
						<?php
						endforeach;
						?>
                    </ul>
                </div>
			<?php endforeach; ?>
        </div>
		<?php

		return ob_get_clean();
	}

	public function get_reviews_html( $post_id ) {
		if ( ! is_a( $this->reviews, 'FG_Guitars_Reviews_Fields' ) ) {
			return '';
		}

		$items = $this->reviews->getPostMeta( $post_id );

		if ( empty( $items ) ) {
			return '';
		}

		$items = array_filter( $items, function ( $item ) {
			$name = ! empty( $item['name'] ) ? esc_textarea( $item['name'] ) : '';
			$text = ! empty( $item['text'] ) ? esc_textarea( $item['text'] ) : '';

			if ( empty( $name ) || empty( $text ) ) {
				return false;
			}

			return true;
		} );

		$count = count( $items );
		$index = 0;

		ob_start();
		?>
        <div class="uk-child-width-1-2@s uk-grid" uk-grid>
			<?php foreach ( $items as $key => $review ) : ?>
				<?php $name = ! empty( $review['name'] ) ? esc_textarea( $review['name'] ) : ''; ?>
				<?php $text = ! empty( $review['text'] ) ? esc_textarea( $review['text'] ) : ''; ?>

				<?php
				$index ++;

				error_log( print_r( $index, 1 ) );
				error_log( print_r( $count - 1, 1 ) );
				error_log( print_r( ( $index == $count - 1 ), 1 ) );
				error_log( print_r( ( $index ) % 2, 1 ) );
				error_log( print_r( ( ! ( $index ) % 2 ), 1 ) );
                error_log( print_r( '--------', 1) );
				$no_border_class = ( $index == $count ) || ( ( $index == $count - 1 ) && (  ( $index ) % 2 ) ) ? 'no-border' : '';
				?>

                <div class="fg-review fg-review__<?php echo esc_attr( $key ); ?> <?php echo esc_attr( $no_border_class ); ?>">
                    <h5><?php echo $name; ?></h5>
                    <div class="fg-review__text">
						<?php echo wpautop( $text ); ?>
                    </div>
                    <hr>
                </div>
			<?php endforeach; ?>
        </div>
		<?php

		return ob_get_clean();
	}

	public function get_sounds_html( $post_id, $columns = 4, $return_empty = false ) {
		if ( ! is_a( $this->sounds, 'FG_Guitars_Sounds_Fields' ) ) {
			return '';
		}

		$sounds = $this->sounds->getPostMeta( $post_id );

		ob_start();
		if ( ! empty( $sounds ) ):
			echo Fremediti_Guitars_Template_Functions::videos_grid( $sounds, $columns );
		else:
			if ( ! $return_empty ):
				?>
                <div class="uk-text-center uk-margin-top">
                    <img src="<?php echo FREMEDITI_GUITARS_THEME_URL ?>/assets/images/coming_soon.png" alt="<?php _e( 'Guitar Videos Coming Soon' ); ?>">
                </div>
			<?php
			endif;
		endif;

		return ob_get_clean();
	}

	public function get_features_html( $post_id ) {
		if ( ! is_a( $this->features, 'FG_Guitars_Features_Fields' ) ) {
			return '';
		}

		$features = $this->features->getPostMeta( $post_id );

		$features_post_in = ! empty( $features['features']['feature'] ) ? implode( ',', $features['features']['feature'] ) : '';

		ob_start();

		if ( ! empty( $features_post_in ) ):
			echo do_shortcode( '[fg-guitar-features post__in="' . $features_post_in . '" layout="new"]' );
		endif;

		return ob_get_clean();
	}

	public function get_pricing_html( $post_id ) {
		if ( ! is_a( $this->pricing, 'FG_Guitars_Pricing_Fields' ) ) {
			return '';
		}

		$guitar = FG_Guitars_Post_Type::instance();

		$pricing_items        = $guitar->get_pricing_items( $post_id );
		$pricing_text         = $this->pricing->getPriceText( $post_id );
		$base_price_label     = ! empty( $pricing_items ) ? $this->pricing->getPriceLabel() : __( 'Price', 'fremediti-guitars' );
		$base_price           = $guitar->get_price( $post_id );
		$base_price_converted = '';

		$show_contact_us_button = apply_filters( 'fremediti_guitars_fg_guitars_show_contact_us_button', $this->pricing->getShowContactButton( $post_id ), $post_id );

		if ( function_exists( 'currency_exchange_rates_convert' ) ) {
			$base_price_converted = currency_exchange_rates_convert( $base_price, 'USD', 'EUR' );
		}

		ob_start();
		?>
        <div class="uk-child-width-1-2@m uk-grid" uk-grid>
            <div class="fg-guitar-pricing-extra-options">
				<?php
				if ( ! empty( $pricing_items ) ):
					?>
                    <h3><?php echo $this->pricing->getPricingItemsLabel(); ?></h3>
                    <table class="uk-table uk-table-small uk-table-divider uk-table-responsive">
                        <tbody>

						<?php foreach ( $pricing_items as $item ): ?>

                            <tr>
                                <td class="uk-width-5-6"><?php echo wpautop( $item['extra_option'] ); ?></td>
                                <td class="uk-width-1-6 uk-text-right">
									<?php if ( isset( $item['extra_option_price'] ) && '' != $item['extra_option_price'] ):
										$item_price = $item['extra_option_price'];
										$item_price_converted = '';
										if ( function_exists( 'currency_exchange_rates_convert' ) ) {
											$item_price_converted = currency_exchange_rates_convert( $item_price, 'USD', 'EUR' );
										}
//										Fremediti_Guitars_Template_Functions::price_without_buttons( $item_price, $item_price_converted );
										?>
                                        <span><?php echo Fremediti_Guitars_Template_Functions::price_format( $item_price ); ?></span>
									<?php
									endif;
									?>

                                </td>
                            </tr>

						<?php endforeach; ?>

                        </tbody>
                    </table>
				<?php
				endif;
				?>
            </div>
            <div class="fg-guitar-pricing">
                <h3 class="fg-base-price uk-text-right@m">
					<?php if ( empty( $show_contact_us_button ) || ! class_exists( 'FG_Guitars_Settings' ) ): ?>
						<?php echo $base_price_label; ?>: <span><?php echo Fremediti_Guitars_Template_Functions::price_format( $base_price ); ?></span>
						<?php //Fremediti_Guitars_Template_Functions::price_with_buttons( $base_price, $base_price_converted, $base_price_label ); ?>
					<?php else: ?>
						<?php $contact_page_id = FG_Guitars_Settings::get_contact_page(); ?>
						<?php echo __( 'For price', 'fremediti-guitars' ); ?> <a href="<?php echo get_permalink( $contact_page_id ); ?>" class="uk-button uk-button-primary"><?php _e( 'Contact Us', 'fremediti-guitars' ); ?></a>
					<?php endif; ?>
                </h3>
                <div>
					<?php echo wpautop( $pricing_text ); ?>
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
        <h3 class="uk-margin-medium-top"><?php _e( 'Select Configuration', 'fremediti-guitars' ); ?></h3>
        <ul class="fg-specs-variations-tabs uk-child-width-1-5 uk-grid uk-tab" uk-tab="animation: uk-animation-fade" uk-grid>
			<?php
			foreach ( $specs as $key => $spec ):
				?>
                <li>
                    <a href="">
						<?php echo ! empty( $spec['configuration_image_id'] ) ?
							wp_get_attachment_image( $spec['configuration_image_id'], 'full' ) :
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

		echo $has_spec_variations ? '<ul class="uk-switcher uk-margin-medium-top fg-specs-variations-switcher">' : '';

		ob_start();

		foreach ( $specs as $spec ):
			unset( $spec['configuration_image_id'] );
			unset( $spec['configuration_image'] );

			$divided_specs = $this->_divide_array( $spec );

			echo $has_spec_variations ? '<li>' : '';

			?>
            <div class="uk-child-width-1-2@m fg-specs uk-grid" uk-grid>
				<?php
				foreach ( $divided_specs as $spec ):
					?>
                    <div class="">
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
				$value = 'wysiwyg' == $this->specifications->getFieldType( $key ) ? wpautop( $value ) : esc_attr( $value )
				?>
                <tr>
                    <td class="uk-width-1-5@m uk-width-1-3 uk-text-bold uk-text-right@m uk-text-uppercase"><?php esc_attr_e( $this->specifications->getFieldLabel( $key ) ); ?></td>
                    <td class="uk-width-4-5@m uk-width-2-3"><?php echo $value; ?></td>
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