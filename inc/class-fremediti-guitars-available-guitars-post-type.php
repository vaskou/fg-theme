<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Available_Guitars_Post_Type {

	const POST_TYPE_NAME = 'fg_available_guitars';
	const POST_TYPE_SLUG = 'available-guitars';
	const SHORTCODE_NAME = 'fg-available-guitars';

	private static $instance = null;

	/**
	 * FG_Guitars_Post_Type constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_metaboxes' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Registers post type
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'FG Available Guitars', 'FG Available Guitars General Name', 'fremediti-guitars' ),
			'singular_name'         => _x( 'FG Available Guitar', 'FG Available Guitar Singular Name', 'fremediti-guitars' ),
			'menu_name'             => __( 'FG Available Guitars', 'fremediti-guitars' ),
			'name_admin_bar'        => __( 'FG Available Guitars', 'fremediti-guitars' ),
			'archives'              => __( 'FG Available Guitar Archives', 'fremediti-guitars' ),
			'attributes'            => __( 'FG Available Guitar Attributes', 'fremediti-guitars' ),
			'parent_item_colon'     => __( 'Parent FG Available Guitar:', 'fremediti-guitars' ),
			'all_items'             => __( 'All FG Available Guitars', 'fremediti-guitars' ),
			'add_new_item'          => __( 'Add New FG Available Guitar', 'fremediti-guitars' ),
			'add_new'               => __( 'Add New', 'fremediti-guitars' ),
			'new_item'              => __( 'New FG Available Guitar', 'fremediti-guitars' ),
			'edit_item'             => __( 'Edit FG Available Guitar', 'fremediti-guitars' ),
			'update_item'           => __( 'Update FG Available Guitar', 'fremediti-guitars' ),
			'view_item'             => __( 'View FG Available Guitar', 'fremediti-guitars' ),
			'view_items'            => __( 'View FG Available Guitars', 'fremediti-guitars' ),
			'search_items'          => __( 'Search FG Available Guitar', 'fremediti-guitars' ),
			'not_found'             => __( 'Not found', 'fremediti-guitars' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'fremediti-guitars' ),
			'featured_image'        => __( 'Featured Image', 'fremediti-guitars' ),
			'set_featured_image'    => __( 'Set Featured Image', 'fremediti-guitars' ),
			'remove_featured_image' => __( 'Remove Featured Image', 'fremediti-guitars' ),
			'use_featured_image'    => __( 'Use as Featured Image', 'fremediti-guitars' ),
			'insert_into_item'      => __( 'Insert into FG Available Guitar', 'fremediti-guitars' ),
			'uploaded_to_this_item' => __( 'Uploaded to this FG Available Guitar', 'fremediti-guitars' ),
			'items_list'            => __( 'FG Available Guitars list', 'fremediti-guitars' ),
			'items_list_navigation' => __( 'FG Available Guitars list navigation', 'fremediti-guitars' ),
			'filter_items_list'     => __( 'Filter FG Available Guitars list', 'fremediti-guitars' ),
		);

		$rewrite = array(
			'slug'       => self::POST_TYPE_SLUG,
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'         => __( 'FG Available Guitar', 'fremediti-guitars' ),
			'description'   => __( 'FG Available Guitar Description', 'fremediti-guitars' ),
			'labels'        => $labels,
			'supports'      => array( 'title', 'page-attributes' ),
			'taxonomies'    => array(),
			'hierarchical'  => false,
			'public'        => false,
			'show_ui'       => true,
			'menu_icon'     => 'dashicons-admin-post',
			'menu_position' => 30,
			'can_export'    => true,
			'rewrite'       => $rewrite,
			'map_meta_cap'  => true,
			'show_in_rest'  => false,
		);
		register_post_type( self::POST_TYPE_NAME, $args );
	}

	/**
	 * Adds metaboxes
	 */
	public function add_metaboxes() {
		if ( ! function_exists( 'new_cmb2_box' ) ) {
			return;
		}

		$metabox = new_cmb2_box( array(
			'id'           => 'fg_available_guitars',
			'title'        => __( 'Options', 'fremediti-guitars' ),
			'object_types' => array( self::POST_TYPE_NAME ), // Post type
			'show_names'   => true, // Show field names on the left
		) );

		$fields = apply_filters( 'fg_available_guitars_fields', array(
			'title_url' => array(
				'name' => __( 'Title URL', 'fremediti-guitars' ),
				'type' => 'text_url',
			),
			'image'     => array(
				'id'      => 'fg_available_guitars_image',
				'name'    => __( 'Image', 'fremediti-guitars' ),
				'type'    => 'file',
				'options' => array(
					'url' => false,
				),
				'text'    => array(
					'add_upload_file_text' => __( 'Add Image', 'fremediti-guitars' )
				),
			),
			'specs'     => array(
				'name'    => __( 'Specs', 'fremediti-guitars' ),
				'type'    => 'file',
				'options' => array(
					'url' => false,
				),
				'text'    => array(
					'add_upload_file_text' => __( 'Add Specs Image', 'fremediti-guitars' )
				),
			),
			'price'     => array(
				'name'       => __( 'Price', 'fremediti-guitars' ),
				'type'       => apply_filters( 'fg_available_guitars_price_field_type', 'text_small' ),
				'attributes' => array(
					'type' => apply_filters( 'fg_available_guitars_price_field_type', 'number' )
				)
			),
			'notes'     => array(
				'name' => __( 'Notes', 'fremediti-guitars' ),
				'type' => 'textarea_small',
			),
			'availability'     => array(
				'name' => __( 'Availability', 'fremediti-guitars' ),
				'type' => 'wysiwyg',
			),
		) );

		foreach ( $fields as $field_id => $field_args ) {
			$defaults = array(
				'id' => 'fg_available_guitars_' . $field_id,
			);

			$args = wp_parse_args( $field_args, $defaults );

			$metabox->add_field( $args );
		}
	}

	public function register_shortcodes() {
		add_shortcode( self::SHORTCODE_NAME, array( $this, 'shortcode' ) );
	}

	public function shortcode( $atts ) {

		$default = array();

		$args = shortcode_atts( $default, $atts );

		$query = $this->get_query( $args );

		$contact_us_page = get_page_by_path( 'contact-us' );
		if ( ! empty( $contact_us_page ) ) {
			$contact_us_page_link = get_permalink( $contact_us_page->ID );
		}

		ob_start();

		if ( $query->have_posts() ) :
			?>
            <div class="fg-available-guitars-wrapper">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();

					$post_id = get_the_ID();

					$image_id  = get_post_meta( $post_id, 'fg_available_guitars_image_id', true );
					$image_url = wp_get_attachment_image_url( $image_id, 'full' );
					$image     = wp_get_attachment_image( $image_id, 'full' );

					$title_url = get_post_meta( $post_id, 'fg_available_guitars_title_url', true );

					$specs_image_id  = get_post_meta( $post_id, 'fg_available_guitars_specs_id', true );
					$specs_image_url = wp_get_attachment_image_url( $specs_image_id, 'full' );

					$price                  = $this->get_price( $post_id );
					$guitar_price_converted = '';

					if ( function_exists( 'currency_exchange_rates_convert' ) ) {
						$guitar_price_converted = currency_exchange_rates_convert( $price, 'USD', 'EUR' );
					}

					$notes = get_post_meta( $post_id, 'fg_available_guitars_notes', true );

					?>
                    <div class="uk-grid" uk-grid>
                        <div class="uk-width-3-5@m uk-width-3-4@l">
							<?php
							if ( ! empty( $image ) && ! empty( $image_url ) ):
								?>
                                <div uk-lightbox>
                                    <a href="<?php echo esc_url( $image_url ); ?>">
										<?php echo $image; ?>
                                    </a>
                                </div>
							<?php
							endif;
							?>
                        </div>

                        <div class="uk-width-2-5@m uk-width-1-4@l uk-text-center uk-margin-large">
                            <h2 class="uk-h3">
								<?php if ( ! empty( $title_url ) ) : ?>
                                <a href="<?php echo $title_url; ?>" target="_blank">
									<?php endif; ?>
									<?php the_title(); ?>
									<?php if ( ! empty( $title_url ) ) : ?>
                                </a>
							<?php endif; ?>
                            </h2>
							<?php
							if ( ! empty( $image ) && ! empty( $image_url ) ):
								?>
                                <div uk-lightbox>
                                    <a href="<?php echo esc_url( $specs_image_url ); ?>" class="uk-button uk-button-primary"><?php _e( 'Specs', 'fremediti-guitars' ); ?></a>
                                </div>
							<?php
							endif;

							if ( ! empty( $price ) ):
								?>
                                <p>
									<?php echo __( 'Price:', 'fremediti-guitars' ); ?> <span><?php echo Fremediti_Guitars_Template_Functions::price_format( $price ) ?></span>
									<?php
									// Fremediti_Guitars_Template_Functions::price_with_buttons( $price, $guitar_price_converted, __( 'Price:', 'fremediti-guitars' ) );
									?>
                                </p>
							<?php
							endif;

							if ( ! empty( $notes ) ):
								?>
                                <p>
									<?php echo $notes; ?>
                                </p>
							<?php
							endif;

							if ( ! empty( $contact_us_page_link ) ):
								?>
                                <a href="<?php echo $contact_us_page_link; ?>" class="uk-button uk-button-primary" target="_blank">
									<?php _e( 'Contact Us', 'fremediti-guitars' ); ?>
                                </a>
							<?php
							endif;
							?>
                        </div>
                    </div>
				<?php

				endwhile;
				?>
            </div>
		<?php
		endif;
		wp_reset_postdata();

		return ob_get_clean();

	}

	public function get_price( $post_id ) {

		$price = get_post_meta( $post_id, 'fg_available_guitars_price', true );

		return apply_filters( 'fg_available_guitars_post_type_get_price', $price, $post_id );
	}

	/**
	 * @param $atts array
	 *
	 * @return WP_Query
	 */
	public function get_query( $atts = array() ) {
		return $this->_get_query( $atts );
	}

	/**
	 * @return int[]|WP_Post[]
	 */
	public function get_items() {
		return $this->_get_items();
	}

	/**
	 * @param $atts array
	 *
	 * @return WP_Query
	 */
	private function _get_query( $atts = array() ) {
		$default = array(
			'post_type'      => self::POST_TYPE_NAME,
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'orderby'        => 'menu_order title',
			'order'          => 'ASC',
		);

		$args = wp_parse_args( $atts, $default );

		return new WP_Query( $args );
	}

	/**
	 * @return int[]|WP_Post[]
	 */
	private function _get_items() {
		$query = $this->_get_query();

		return $query->get_posts();
	}
}