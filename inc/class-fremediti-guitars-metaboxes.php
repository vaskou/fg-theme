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
		add_meta_box( self::METABOX_PREFIX . 'options', __( 'Page Options', 'fremediti-guitars' ), array( $this, 'html' ), 'page', 'side' );
	}

	public function html( $post ) {

		wp_nonce_field( 'fg_options', 'fg_options_nonce' );

		$hide_title_meta_key = self::METABOX_PREFIX . 'hide_title';
		$hide_title_value    = get_post_meta( $post->ID, $hide_title_meta_key, true );

		$full_width_meta_key = self::METABOX_PREFIX . 'full_width';
		$full_width_value    = get_post_meta( $post->ID, $full_width_meta_key, true );

		$sidebar_meta_key = self::METABOX_PREFIX . 'sidebar';
		$sidebar_value    = get_post_meta( $post->ID, $sidebar_meta_key, true );

		?>
        <div class="components-panel__row">
            <label for="<?php echo $hide_title_meta_key; ?>" class="components-base-control__label">
                <input type="checkbox" id="<?php echo $hide_title_meta_key; ?>" class="components-text-control__input" name="<?php echo $hide_title_meta_key; ?>" value="on" <?php checked( $hide_title_value, 'on' ); ?> />
				<?php _e( 'Hide page title', 'fremediti-guitars' ); ?>
            </label>
        </div>
        <div class="components-panel__row">
            <label for="<?php echo $full_width_meta_key; ?>" class="components-base-control__label">
                <input type="checkbox" id="<?php echo $full_width_meta_key; ?>" class="components-text-control__input" name="<?php echo $full_width_meta_key; ?>" value="on" <?php checked( $full_width_value, 'on' ); ?> />
				<?php _e( 'Full width page', 'fremediti-guitars' ); ?>
            </label>
        </div>
        <div class="components-panel__row">
            <label for="<?php echo $sidebar_meta_key; ?>" class="components-base-control__label">
                <input type="checkbox" id="<?php echo $sidebar_meta_key; ?>" class="components-text-control__input" name="<?php echo $sidebar_meta_key; ?>" value="on" <?php checked( $sidebar_value, 'on' ); ?> />
				<?php _e( 'Enable sidebar for this page', 'fremediti-guitars' ); ?>
            </label>
        </div>
		<?php
	}

	public function save( $post_id, $post ) {
		$meta_keys = array(
			'hide_title',
			'full_width',
			'sidebar',
		);

		if ( ! isset( $_POST['fg_options_nonce'] ) ) {
			return $post_id;
		}
		if ( ! wp_verify_nonce( $_POST['fg_options_nonce'], 'fg_options' ) ) {
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

		foreach ( $meta_keys as $meta_key ) {
			$key = self::METABOX_PREFIX . $meta_key;

			if ( ! empty( $_POST[ $key ] ) ) {
				$mydata = sanitize_text_field( $_POST[ $key ] );

				update_post_meta( $post_id, $key, $mydata );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}

		return $post_id;
	}

	public static function has_sidebar( $post_id ) {
		$meta_key = self::METABOX_PREFIX . 'sidebar';

		return get_post_meta( $post_id, $meta_key, true );
	}

	public static function is_full_width( $post_id ) {
		$meta_key = self::METABOX_PREFIX . 'full_width';

		return get_post_meta( $post_id, $meta_key, true );
	}

	public static function hide_title( $post_id ) {
		$meta_key = self::METABOX_PREFIX . 'hide_title';

		return get_post_meta( $post_id, $meta_key, true );
	}
}