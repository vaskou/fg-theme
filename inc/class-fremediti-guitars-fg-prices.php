<?php

class Fremediti_Guitars_FG_Prices {

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_filter( 'fg_prices_get_multicurrency_prices', array( $this, 'get_multicurrency_prices' ), 10, 2 );
	}

	public function get_multicurrency_prices( $price, $multicurrency_prices ) {

		$current_currency = '';

		if ( isset( $multicurrency_prices[ $current_currency ] ) ) {
			$price = $multicurrency_prices[ $current_currency ];
		}

		return $price;
	}
}