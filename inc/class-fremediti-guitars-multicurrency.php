<?php

class Fremediti_Guitars_Multicurrency {

	/**
	 * @return string
	 */
	public static function get_current_currency() {
		return apply_filters( 'fremediti_guitars_get_current_currency', 'EUR' );
	}

	/**
	 * @return string
	 */
	public static function get_current_currency_symbol() {
		$current_currency = self::get_current_currency();

		$current_currency_symbol = self::get_currency_symbol( $current_currency );

		return apply_filters( 'fremediti_guitars_get_current_currency_symbol', $current_currency_symbol );
	}

	public static function get_currency_symbol( $currency ) {
		$currency_symbols = self::get_currency_symbols();

		$currency_symbol = ! empty( $currency_symbols[ $currency ] ) ? $currency_symbols[ $currency ] : '';

		return apply_filters( 'fremediti_guitars_get_currency_symbol', $currency_symbol, $currency );
	}

	/**
	 * @return array
	 */
	public static function get_currencies() {
		return apply_filters( 'fremediti_guitars_get_currencies', array(
			'EUR' => __( 'Euro', 'fremediti-guitars' ),
			'USD' => __( 'United States (US) dollar', 'fremediti-guitars' ),
		) );
	}

	/**
	 * @return array
	 */
	public static function get_currency_symbols() {
		return apply_filters( 'fremediti_guitars_get_currency_symbols', array(
			'EUR' => '&euro;',
			'USD' => '&#36;',
		) );
	}
}