<?php

class Fremediti_Guitars_Helpers {

	public static function show_new_layout() {
		if ( is_admin() && defined( 'DOING_AJAX' ) && ! DOING_AJAX ) {
			return false;
		}

		$new_single_page_layout_roles = Fremediti_Guitars_Settings::get_new_single_page_layout_roles();

		return self::_check_selected_roles( $new_single_page_layout_roles );
	}

	public static function show_new_images() {
		if ( is_admin() && defined( 'DOING_AJAX' ) && ! DOING_AJAX ) {
			return false;
		}

		$new_single_page_images_roles = Fremediti_Guitars_Settings::get_new_single_page_images_roles();

		return self::_check_selected_roles( $new_single_page_images_roles );
	}

	public static function show_new_some_versions_section() {
		if ( is_admin() && defined( 'DOING_AJAX' ) && ! DOING_AJAX ) {
			return false;
		}

		$selected_roles = Fremediti_Guitars_Settings::get_new_single_page_some_versions_section_roles();

		return self::_check_selected_roles( $selected_roles );

	}

	public static function get_read_more_button_html( $target, $classes = '' ) {
		if ( empty( $target ) ) {
			return '';
		}

		ob_start();
		?>
        <div class="fg-read-more__button__container <?php echo $classes; ?>">
            <span class="fg-read-more__button__outside" uk-toggle="target: <?php echo $target; ?>; cls: fg-read-more__block, fg-read-less;">
                <span class="fg-read-more__button__inside">
                    <span class="fg-read-more__button__more"><?php echo __( 'Read more', 'fremediti-guitars' ); ?></span>
                    <span class="fg-read-more__button__less"><?php echo __( 'Read less', 'fremediti-guitars' ); ?></span>
                </span>
            </span>
        </div>
		<?php

		return ob_get_clean();
	}

	private static function _check_selected_roles( $selected_roles ) {
		if ( empty( $selected_roles ) ) {
			return false;
		}

		if ( in_array( 'all', $selected_roles ) ) {
			return true;
		}

		$user = wp_get_current_user();

		if ( empty( $user ) || empty( $user->ID ) ) {
			return false;
		}

		$user_roles = $user->roles;

		foreach ( $user_roles as $role ) {
			if ( in_array( $role, $selected_roles ) ) {
				return true;
			}
		}

		return false;
	}
}