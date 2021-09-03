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

		$redirection_on = Fremediti_Guitars_Settings::get_setting( 'fremediti_guitars_redirect_frontpage_to_el' );

		if ( empty( $redirection_on ) ) {
			return;
		}

		$country = self::get_country_from_current_ip();

		if ( ! empty( $sitepress ) && is_a( $sitepress, 'SitePress' ) && ! empty( $country ) ) {

			$args['skip_missing'] = intval( true );
			$languages            = $sitepress->get_ls_languages( $args );

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

	/**
	 * @return false|string|null
	 */
	public static function get_country_from_current_ip() {

		if ( ! function_exists( 'geoip_detect2_get_info_from_current_ip' ) ) {
			return false;
		}

		$geo_info = geoip_detect2_get_info_from_current_ip();

		return $geo_info->country->isoCode;

	}
}