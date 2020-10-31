<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Videos_Post_Type {

	const POST_TYPE_NAME = 'fg_videos';
	const POST_TYPE_SLUG = 'videos';
	const SHORTCODE_NAME = 'fg-videos';

	private static $instance = null;

	/**
	 * FG_Guitars_Post_Type constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'cmb2_admin_init', array( $this, 'add_metaboxes' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_shortcode_metabox' ) );
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
			'name'                  => _x( 'FG Videos', 'FG Videos General Name', 'fremediti-guitars' ),
			'singular_name'         => _x( 'FG Video', 'FG Video Singular Name', 'fremediti-guitars' ),
			'menu_name'             => __( 'FG Videos', 'fremediti-guitars' ),
			'name_admin_bar'        => __( 'FG Videos', 'fremediti-guitars' ),
			'archives'              => __( 'FG Video Archives', 'fremediti-guitars' ),
			'attributes'            => __( 'FG Video Attributes', 'fremediti-guitars' ),
			'parent_item_colon'     => __( 'Parent FG Video:', 'fremediti-guitars' ),
			'all_items'             => __( 'All FG Videos', 'fremediti-guitars' ),
			'add_new_item'          => __( 'Add New FG Video', 'fremediti-guitars' ),
			'add_new'               => __( 'Add New', 'fremediti-guitars' ),
			'new_item'              => __( 'New FG Video', 'fremediti-guitars' ),
			'edit_item'             => __( 'Edit FG Video', 'fremediti-guitars' ),
			'update_item'           => __( 'Update FG Video', 'fremediti-guitars' ),
			'view_item'             => __( 'View FG Video', 'fremediti-guitars' ),
			'view_items'            => __( 'View FG Videos', 'fremediti-guitars' ),
			'search_items'          => __( 'Search FG Video', 'fremediti-guitars' ),
			'not_found'             => __( 'Not found', 'fremediti-guitars' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'fremediti-guitars' ),
			'featured_image'        => __( 'Featured Image', 'fremediti-guitars' ),
			'set_featured_image'    => __( 'Set Featured Image', 'fremediti-guitars' ),
			'remove_featured_image' => __( 'Remove Featured Image', 'fremediti-guitars' ),
			'use_featured_image'    => __( 'Use as Featured Image', 'fremediti-guitars' ),
			'insert_into_item'      => __( 'Insert into FG Video', 'fremediti-guitars' ),
			'uploaded_to_this_item' => __( 'Uploaded to this FG Video', 'fremediti-guitars' ),
			'items_list'            => __( 'FG Videos list', 'fremediti-guitars' ),
			'items_list_navigation' => __( 'FG Videos list navigation', 'fremediti-guitars' ),
			'filter_items_list'     => __( 'Filter FG Videos list', 'fremediti-guitars' ),
		);

		$rewrite = array(
			'slug'       => self::POST_TYPE_SLUG,
			'with_front' => true,
			'pages'      => true,
			'feeds'      => true,
		);

		$args = array(
			'label'         => __( 'FG Video', 'fremediti-guitars' ),
			'description'   => __( 'FG Video Description', 'fremediti-guitars' ),
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
			'id'           => 'fg_videos',
			'title'        => __( 'Images', 'fremediti-guitars' ),
			'object_types' => array( self::POST_TYPE_NAME ), // Post type
			'show_names'   => true, // Show field names on the left

		) );

		$group_id = $metabox->add_field( array(
			'id'      => 'fg_videos_group',
			'type'    => 'group',
			'options' => array(
				'group_title'   => __( 'Video {#}', 'fremediti-guitars' ),
				'add_button'    => __( 'Add Another Video', 'fremediti-guitars' ),
				'remove_button' => __( 'Remove Video', 'fremediti-guitars' ),
				'sortable'      => true,
			),
		) );

		$metabox->add_group_field( $group_id, array(
			'id'   => 'title',
			'name' => __( 'Video Title', 'fremediti-guitars' ),
			'type' => 'text'
		) );

		$metabox->add_group_field( $group_id, array(
			'id'   => 'url',
			'name' => __( 'Youtube video code', 'fremediti-guitars' ),
			'type' => 'text'
		) );
	}

	public function add_shortcode_metabox() {
		add_meta_box( 'fg_videos_options', __( 'Shortcode', 'fremediti-guitars' ), array( $this, 'shortcode_metabox_html' ), self::POST_TYPE_NAME, 'side' );
	}

	public function shortcode_metabox_html( $post ) {
		?>

        <div>
            <a href="#" onclick="copyToClipboard(this);"><code>[<?php echo self::SHORTCODE_NAME; ?> id=<?php echo $post->ID; ?>]</code></a>
            <span class="copied-shortcode" style="display: none;"><?php echo __( 'Copied', 'fremediti-guitars' ); ?></span>
        </div>

		<?php
	}

	public function register_shortcodes() {
		add_shortcode( self::SHORTCODE_NAME, array( $this, 'videos_shortcode' ) );
	}

	public function videos_shortcode( $atts ) {

		$default = array(
			'id' => ''
		);

		$args = shortcode_atts( $default, $atts );

		if ( empty( $args['id'] ) ) {
			return '';
		}

		$videos = get_post_meta( $args['id'], 'fg_videos_group', true );

		if ( empty( $videos ) ) {
			return '';
		}

		ob_start();

		?>
        <div class="fg-videos uk-child-width-1-2@s uk-child-width-1-4@m uk-grid-medium" uk-grid uk-lightbox>
			<?php
			foreach ( $videos as $video ):
				if ( empty( $video['url'] ) ) {
					continue;
				}
				?>
                <div>
                    <a href="//youtube.com/watch?v=<?php echo $video['url']; ?>" data-attrs="width: 1280; height: 720;">
                        <img src="//img.youtube.com/vi/<?php echo $video['url']; ?>/sddefault.jpg" alt="<?php _e( 'Guitar Video', 'fremediti-guitars' ); ?>"/>
                    </a>
                    <h4><?php esc_attr_e( $video['title'] ); ?></h4>
                </div>
			<?php
			endforeach;
			?>
        </div>
		<?php
		return ob_get_clean();

	}
}