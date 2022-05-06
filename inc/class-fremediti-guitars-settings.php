<?php

use WordpressCustomSettings\SettingsSetup;
use WordpressCustomSettings\SettingSection;
use WordpressCustomSettings\SettingField;

class Fremediti_Guitars_Settings extends SettingsSetup {

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	protected function __construct() {

		$this->set_submenu_parent_slug( 'themes.php' );

		$this->set_page_title( __( 'Fremediti Guitars Settings', 'fremediti-guitars' ) );
		$this->set_menu_title( __( 'Fremediti Guitars Settings', 'fremediti-guitars' ) );
		$this->set_menu_slug( 'fremediti-guitars-settings' );

		$this->add_section( new SettingSection( 'general', __( 'General', 'fremediti-guitars' ) ) );

		$settings = array(
			new SettingField( 'fremediti_guitars_gtm', __( 'Google Tag Manage Code', 'fremediti-guitars' ), 'text', 'general' ),
			new SettingField( 'fremediti_guitars_redirect_frontpage_to_el', __( 'Redirect Frontpage to Greek', 'fremediti-guitars' ), 'checkbox', 'general' ),
		);

		foreach ( $settings as $setting ) {
			$this->add_setting_field( $setting );
		}

		parent::__construct();
	}

	public static function get_gtm() {
		return self::instance()->get_setting( 'fremediti_guitars_gtm' );
	}

	public static function get_redirect_frontpage_to_el() {
		return self::instance()->get_setting( 'fremediti_guitars_redirect_frontpage_to_el' );
	}

}