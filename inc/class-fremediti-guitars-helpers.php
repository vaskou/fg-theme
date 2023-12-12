<?php

class Fremediti_Guitars_Helpers {

	public static function show_new_layout() {
		if ( is_admin() && defined( 'DOING_AJAX' ) && ! DOING_AJAX ) {
			return false;
		}

		$new_single_page_layout_roles = Fremediti_Guitars_Settings::get_new_single_page_layout_roles();

		if ( empty( $new_single_page_layout_roles ) ) {
			return false;
		}

		if ( in_array( 'all', $new_single_page_layout_roles ) ) {
			return true;
		}

		$user = wp_get_current_user();

		if ( empty( $user ) || empty( $user->ID ) ) {
			return false;
		}

		$user_roles = $user->roles;

		foreach ( $user_roles as $role ) {
			if ( in_array( $role, $new_single_page_layout_roles ) ) {
				return true;
			}
		}

		return false;
	}

	public static function get_read_more_button_html( $target ) {
		if ( empty( $target ) ) {
			return '';
		}

		ob_start();
		?>
        <div class="fg-read-more__button__container">
            <span class="fg-read-more__button__outside" uk-toggle="target: <?php echo $target; ?>; cls: fg-read-more__block, fg-read-less;">
                <span class="fg-read-more__button__inside">
                    <span class="fg-read-more__button__more"><?php echo __( 'Read more', 'fg-guitars' ); ?></span>
                    <span class="fg-read-more__button__less"><?php echo __( 'Read less', 'fg-guitars' ); ?></span>
                </span>
            </span>
        </div>
		<?php

		return ob_get_clean();
	}
}