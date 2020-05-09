<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Gallery_Post_Type {

	const POST_TYPE_NAME = 'fg_gallery';
	const POST_TYPE_SLUG = 'gallery';
	const GALLERY_SHORTCODE_NAME = 'fg-gallery';

	private static $instance = null;

	/**
	 * FG_Guitars_Post_Type constructor.
	 */
	private function __construct() {
	}

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function init() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_metaboxes' ) );
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Registers post type
	 */
	public function register_post_type() {
		$labels = array(
			'name'                  => _x( 'FG Galleries', 'FG Galleries General Name', 'fremediti-guitars' ),
			'singular_name'         => _x( 'FG Gallery', 'FG Gallery Singular Name', 'fremediti-guitars' ),
			'menu_name'             => __( 'FG Galleries', 'fremediti-guitars' ),
			'name_admin_bar'        => __( 'FG Galleries', 'fremediti-guitars' ),
			'archives'              => __( 'FG Gallery Archives', 'fremediti-guitars' ),
			'attributes'            => __( 'FG Gallery Attributes', 'fremediti-guitars' ),
			'parent_item_colon'     => __( 'Parent FG Gallery:', 'fremediti-guitars' ),
			'all_items'             => __( 'All FG Galleries', 'fremediti-guitars' ),
			'add_new_item'          => __( 'Add New FG Gallery', 'fremediti-guitars' ),
			'add_new'               => __( 'Add New', 'fremediti-guitars' ),
			'new_item'              => __( 'New FG Gallery', 'fremediti-guitars' ),
			'edit_item'             => __( 'Edit FG Gallery', 'fremediti-guitars' ),
			'update_item'           => __( 'Update FG Gallery', 'fremediti-guitars' ),
			'view_item'             => __( 'View FG Gallery', 'fremediti-guitars' ),
			'view_items'            => __( 'View FG Galleries', 'fremediti-guitars' ),
			'search_items'          => __( 'Search FG Gallery', 'fremediti-guitars' ),
			'not_found'             => __( 'Not found', 'fremediti-guitars' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'fremediti-guitars' ),
			'featured_image'        => __( 'Featured Image', 'fremediti-guitars' ),
			'set_featured_image'    => __( 'Set Featured Image', 'fremediti-guitars' ),
			'remove_featured_image' => __( 'Remove Featured Image', 'fremediti-guitars' ),
			'use_featured_image'    => __( 'Use as Featured Image', 'fremediti-guitars' ),
			'insert_into_item'      => __( 'Insert into FG Gallery', 'fremediti-guitars' ),
			'uploaded_to_this_item' => __( 'Uploaded to this FG Gallery', 'fremediti-guitars' ),
			'items_list'            => __( 'FG Galleries list', 'fremediti-guitars' ),
			'items_list_navigation' => __( 'FG Galleries list navigation', 'fremediti-guitars' ),
			'filter_items_list'     => __( 'Filter FG Galleries list', 'fremediti-guitars' ),
		);

		$rewrite = array(
			'slug'       => self::POST_TYPE_SLUG,
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'         => __( 'FG Gallery', 'fremediti-guitars' ),
			'description'   => __( 'FG Gallery Description', 'fremediti-guitars' ),
			'labels'        => $labels,
			'supports'      => array( 'title' ),
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
			'id'           => 'fg_gallery',
			'title'        => __( 'Images', 'fremediti-guitars' ),
			'object_types' => array( self::POST_TYPE_NAME ), // Post type
			'show_names'   => true, // Show field names on the left
		) );

		$metabox->add_field( array(
			'id'   => 'fg_gallery_images',
			'name' => __( 'Images', 'fg-guitars' ),
			'type' => 'file_list'
		) );
	}

	public function register_shortcodes() {
		add_shortcode( self::GALLERY_SHORTCODE_NAME, array( $this, 'gallery_shortcode' ) );
	}

	public function gallery_shortcode( $atts ) {

		$default = array(
			'id' => ''
		);

		$args = shortcode_atts( $default, $atts );

		if ( empty( $args['id'] ) ) {
			return '';
		}

		$images = get_post_meta( $args['id'], 'fg_gallery_images', true );

//		var_dump( $images );
		if ( empty( $images ) ) {
			return '';
		}

		ob_start();

		?>
        <div class="fg-gallery uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-5@m" uk-grid="masonry: true" uk-lightbox>
			<?php
			foreach ( $images as $image_id => $image_url ):
				?>
                <div>
                    <a href="<?php echo esc_url( $image_url ); ?>">
                        <div uk-scrollspy="cls:uk-animation-scale-up;">
							<?php echo wp_get_attachment_image( $image_id, 'medium' ); ?>
                        </div>
                    </a>
                </div>
			<?php
			endforeach;
			?>
        </div>
		<?php
		return ob_get_clean();

	}
}