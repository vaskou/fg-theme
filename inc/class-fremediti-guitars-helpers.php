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
}