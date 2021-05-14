<?php

class Fremediti_Guitars_Multilanguage {

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_action( 'template_redirect', array( $this, 'language_redirect' ) );
	}

	public function language_redirect() {
		global $sitepress;

		if ( ! empty( $sitepress ) && is_a( $sitepress, 'SitePress' ) && function_exists( 'geoip_detect2_get_info_from_current_ip' ) ) {

			$args['skip_missing'] = intval( true );
			$languages            = $sitepress->get_ls_languages( $args );

			$geo_info = geoip_detect2_get_info_from_current_ip();
			$country  = $geo_info->country->isoCode;

			$this_lang = $sitepress->get_this_lang();

			$is_already_redirected = ! empty( $_COOKIE['fg_redirected_to_el'] ) ? $_COOKIE['fg_redirected_to_el'] : false;

			if ( is_front_page() &&
			     ! $is_already_redirected &&
			     'GR' == $country && 'el' != $this_lang && ! empty( $languages['el']['url'] )
			) {
				$url = $languages['el']['url'];

				$expire = time() + 14 * DAY_IN_SECONDS;
				setcookie( 'fg_redirected_to_el', true, $expire, '/' );

				wp_safe_redirect( $url );
				exit();
			}
		}
	}
}