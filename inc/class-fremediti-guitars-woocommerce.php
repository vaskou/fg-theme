<?php

class Fremediti_Guitars_Woocommerce {

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_action( 'wp', [ $this, 'remove_wp_hooks' ] );
	}

	public function remove_wp_hooks() {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	}
}