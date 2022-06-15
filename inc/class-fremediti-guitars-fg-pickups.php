<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_FG_Pickups {

	private static $instance = null;

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_filter( 'fremediti_guitars_custom_post_type_thumbnail', array( $this, 'pickup_thumbnail' ), 10, 2 );
		add_action( 'fremediti_guitars_custom_post_type_after_content', array( $this, 'add_fg_pickup_specs' ) );
		add_action( 'fremediti_guitars_custom_post_type_after_content_row', array( $this, 'add_fg_pickup_extra_row' ) );
	}

	public function pickup_thumbnail( $html, $post_id ) {
		if ( $this->_is_fg_pickups() ):
			$fg_pickups_image_id = FG_Pickups_Post_Type::instance()->get_pickup_single_image_id( $post_id );
			$url = wp_get_attachment_url( $fg_pickups_image_id );

			if ( ! empty( $url ) ):
				ob_start();
				?>
                <div uk-lightbox>
                    <a href="<?php echo esc_url( $url ); ?>">
						<?php echo wp_get_attachment_image( $fg_pickups_image_id, 'post-thumbnail' ); ?>
                    </a>
                </div>
				<?php
				$html = ob_get_clean();
			endif;

		endif;

		return $html;
	}

	public function add_fg_pickup_specs() {
		if ( $this->_is_fg_pickups() ):

			$fg_pickups_image_id = FG_Pickups_Post_Type::instance()->get_pickup_image_id( get_the_ID() );
			$url = wp_get_attachment_url( $fg_pickups_image_id );

			if ( ! empty( $url ) ):
				?>
                <div uk-lightbox>
                    <a href="<?php echo esc_url( $url ); ?>" class="uk-button uk-button-primary">
						<?php _e( 'Specs', 'fremediti-guitars' ); ?>
                    </a>
                </div>
			<?php
			endif;

		endif;
	}

	public function add_fg_pickup_extra_row() {
		$post_id = get_the_ID();

		if ( $this->_is_singular_fg_pickups() ):
			$pickups_instance = FG_Pickups_Post_Type::instance();

			$price           = $pickups_instance->get_price( $post_id );
			$price_set       = $pickups_instance->get_price_set( $post_id );
			$availability    = $pickups_instance->get_availability( $post_id );
			$notes           = $pickups_instance->get_notes( $post_id );
			?>

            <div class="uk-child-width-1-2@m uk-grid" uk-grid>
                <div class="uk-text-left">
					<?php if ( ! empty( $price ) ): ?>
                        <p>
                            <span class="uk-text-large"><?php echo __( 'Price for single', 'fremediti-guitars' ) . ': ' . Fremediti_Guitars_Template_Functions::price_format( $price ); ?></span>
                        </p>
					<?php endif; ?>

					<?php if ( ! empty( $price_set ) ): ?>
                        <p>
                            <span class="uk-text-large"><?php echo __( 'Price for set', 'fremediti-guitars' ) . ': ' . Fremediti_Guitars_Template_Functions::price_format( $price_set ); ?></span>
                        </p>
					<?php endif; ?>

					<?php if ( ! empty( $availability ) ): ?>
                        <p>
                            <span class="uk-text-bolder">
                                <?php _e( 'Availability', 'fremediti-guitars' ); ?>: <?php esc_attr_e( $availability, 'fremediti-guitars' ); ?>
                            </span>
                        </p>
					<?php endif; ?>

					<?php if ( ! empty( $notes ) ): ?>
                        <p>
							<?php echo esc_attr( $notes ); ?>
                        </p>
					<?php endif; ?>

					<?php if ( ! empty( $price ) && ! empty( $availability ) ): ?>
                        <a href="<?php echo home_url( '/contact-us' ); ?>" class="uk-button uk-button-primary">
							<?php _e( 'Contact Us', 'fremediti-guitars' ); ?>
                        </a>
					<?php endif; ?>
                </div>

				<?php
				$video = $pickups_instance->get_video( $post_id );

				if ( ! empty( $video ) ) {
					echo Fremediti_Guitars_Template_Functions::videos_grid( array( $video ), 1 );
				}
				?>
            </div>
		<?php
		endif;

	}

	private function _is_fg_pickups() {
		if ( class_exists( 'FG_Pickups_Post_Type' ) ) {
			$post_type = FG_Pickups_Post_Type::POST_TYPE_NAME;
			if ( $post_type == get_post_type() ) {
				return true;
			}
		}

		return false;
	}

	private function _is_singular_fg_pickups() {
		if ( class_exists( 'FG_Pickups_Post_Type' ) ) {
			$post_type = FG_Pickups_Post_Type::POST_TYPE_NAME;
			if ( $post_type == get_post_type() && is_singular( $post_type ) ) {
				return true;
			}
		}

		return false;
	}
}