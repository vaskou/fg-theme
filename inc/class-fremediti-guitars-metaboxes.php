<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Metaboxes {

	const METABOX_PREFIX = 'fg_theme_';

	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		$this->init();
	}

	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add' ), 10, 2 );
		add_action( 'save_post', array( $this, 'save' ), 10, 2 );
	}

	public function add( $post_type, $post ) {
		add_meta_box( self::METABOX_PREFIX . 'sidebar', __( 'Sidebar', 'fremediti-guitars' ), array( $this, 'html' ), 'page', 'side' );
	}

	public function html( $post ) {

		wp_nonce_field( 'fg_sidebar', 'fg_sidebar_nonce' );

		$meta_key = self::METABOX_PREFIX . 'sidebar';

		$value = get_post_meta( $post->ID, $meta_key, true );

		?>
        <div class="components-base-control">
            <label for="<?php echo $meta_key; ?>" class="components-base-control__label">
                <input type="checkbox" id="<?php echo $meta_key; ?>" class="components-text-control__input" name="<?php echo $meta_key; ?>" value="on" <?php checked( $value, 'on' ); ?> />
				<?php _e( 'Description for this field', 'textdomain' ); ?>
            </label>
        </div>
		<?php
	}

	public function save( $post_id, $post ) {
		$meta_key = self::METABOX_PREFIX . 'sidebar';
		if ( ! isset( $_POST['fg_sidebar_nonce'] ) ) {
			return $post_id;
		}
		if ( ! wp_verify_nonce( $_POST['fg_sidebar_nonce'], 'fg_sidebar' ) ) {
			return $post_id;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			}
		}

		$mydata = sanitize_text_field( $_POST[ $meta_key ] );

		update_post_meta( $post_id, $meta_key, $mydata );

		return $post_id;
	}

	public static function has_sidebar( $post_id ) {
		$meta_key = self::METABOX_PREFIX . 'sidebar';

		return get_post_meta( $post_id, $meta_key, true );
	}
}