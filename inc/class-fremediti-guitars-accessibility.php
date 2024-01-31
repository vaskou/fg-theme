<?php

class Fremediti_Guitars_Accessibility {

	private $forms_count = 0;

	private static $_instance;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'wp_get_attachment_image_attributes' ], 10, 2 );

		add_filter( 'wpcf7_form_elements', [ $this, 'fix_contact_form_accessibility' ] );

		add_action( 'wp_enqueue_scripts', [ $this, 'contact_form_recaptcha_accessibility' ] );
	}

	/**
	 * @param array $atts
	 * @param WP_Post $attachment
	 *
	 * @return array
	 */
	public function wp_get_attachment_image_attributes( $atts, $attachment ) {

		if ( empty( $atts['alt'] ) ) {
			$atts['alt'] = $attachment->post_title;
		}

		return $atts;
	}

	public function fix_contact_form_accessibility( $element ) {

		$this->forms_count ++;

		preg_match_all( '/\sname="[a-z-]+"/', $element, $name_matches );

		if ( ! empty( $name_matches[0] ) ) {
			foreach ( $name_matches[0] as $match ) {
				preg_match_all( '/"([a-z-]+)"/', $match, $title_matches );

				if ( ! empty( $title_matches[1][0] ) ) {
					$id      = $title_matches[1][0] . '-' . $this->forms_count;
					$replace = $match . ' title="' . $title_matches[1][0] . '" id="' . $id . '"';
					$element = str_replace( $match, $replace, $element );

					$search  = 'for="' . $title_matches[1][0] . '"';
					$replace = 'for="' . $id . '"';
					$element = str_replace( $search, $replace, $element );
				}
			}
		}

		return $element;
	}

	public function contact_form_recaptcha_accessibility() {
		// Contact Form 7 Recaptcha - WCAG fix
		if ( class_exists( 'WPCF7_RECAPTCHA' ) ) {
			$service = WPCF7_RECAPTCHA::get_instance();

			if ( ! $service->is_active() ) {
				return;
			}

			wp_enqueue_script( 'google-recaptcha',
				add_query_arg(
					array(
						'onload' => 'onloadCallback',
						'render' => $service->get_sitekey(),
					),
					'https://www.google.com/recaptcha/api.js'
				),
				array(),
				'3.0',
				true
			);
		}
	}
}