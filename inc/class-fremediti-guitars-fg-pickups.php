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
		add_action( 'fremediti_guitars_custom_post_type_after_content', array( $this, 'add_fg_pickup_specs' ) );
		add_action( 'fremediti_guitars_custom_post_type_after_content_row', array( $this, 'add_fg_pickup_extra_row' ) );
	}

	public function add_fg_pickup_specs() {
		if ( class_exists( 'FG_Pickups_Post_Type' ) && FG_Pickups_Post_Type::POST_TYPE_NAME == get_post_type() ):
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

		if ( class_exists( 'FG_Pickups_Post_Type' ) && FG_Pickups_Post_Type::POST_TYPE_NAME == get_post_type() ):
			$pickups_instance = FG_Pickups_Post_Type::instance();

			$price        = $pickups_instance->get_price( $post_id );
			$availability = $pickups_instance->get_availability( $post_id );
			?>

            <div class="uk-child-width-1-2@m uk-grid uk-flex-middle" uk-grid>
                <div class="uk-text-center">
					<?php if ( ! empty( $price ) ): ?>
                        <p>
                            <span class="uk-text-large">&euro;<?php esc_attr_e( $price, 'fremediti-guitars' ); ?></span>
                        </p>
					<?php endif; ?>

					<?php if ( ! empty( $availability ) ): ?>
                        <p>
                            <span class="uk-text-bolder">
                                <?php _e( 'Availability', 'fremediti-guitars' ); ?>: <?php esc_attr_e( $availability, 'fremediti-guitars' ); ?>
                            </span>
                        </p>
					<?php endif; ?>
                    <a href="<?php echo home_url( '/contact-us' ); ?>" class="uk-button uk-button-primary">
						<?php _e( 'Contact Us', 'fremediti-guitars' ); ?>
                    </a>
                </div>

				<?php
				$video = $pickups_instance->get_video( $post_id );
				$args  = array(
					'videos' => array( $video )
				);

				if ( ! empty( $video ) ):
					?>
                    <div>
						<?php get_template_part( 'template-parts/video-template', null, $args ); ?>
                    </div>
				<?php
				endif;
				?>
            </div>
		<?php
		endif;

	}
}