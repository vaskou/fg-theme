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
		$this->add_section( new SettingSection( 'new_single_page_layout', __( 'New single page layout', 'fremediti-guitars' ) ) );

		$settings = array(
			new SettingField( 'fremediti_guitars_gtm', __( 'Google Tag Manage Code', 'fremediti-guitars' ), 'text', 'general' ),
			new SettingField( 'fremediti_guitars_redirect_frontpage_to_el', __( 'Redirect Frontpage to Greek', 'fremediti-guitars' ), 'checkbox', 'general' ),
			new SettingField( 'fremediti_guitars_redirect_services_pages', __( 'Redirect FG Services pages if user is not from Greek IP', 'fremediti-guitars' ), 'checkbox', 'general' ),
			new SettingField( 'fremediti_guitars_services_page', __( 'FG Services page', 'fremediti-guitars' ), 'pages', 'general' ),

			new SettingField( 'fg_guitars_new_single_page_layout_roles', __( 'Enable "New single page layout" for these roles', 'fg-guitars' ), 'multiselect', 'new_single_page_layout', $this->_get_user_role_options() ),
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

	public static function get_redirect_services_pages() {
		return self::instance()->get_setting( 'fremediti_guitars_redirect_services_pages' );
	}

	public static function get_services_page() {
		return self::instance()->get_setting( 'fremediti_guitars_services_page' );
	}

	public static function get_new_single_page_layout_roles() {
		return self::instance()->get_setting( 'fg_guitars_new_single_page_layout_roles' );
	}


	private function _get_user_role_options() {
		$user_role_options = array(
			'options' => array(
				'all' => __( 'All', 'fremediti-guitars' ),
			)
		);

		$roles = wp_roles()->roles;
		foreach ( $roles as $key => $role ) {
			$name = translate_user_role( $role['name'] );

			$user_role_options['options'][ esc_attr( $key ) ] = $name;
		}

		return $user_role_options;
	}
}