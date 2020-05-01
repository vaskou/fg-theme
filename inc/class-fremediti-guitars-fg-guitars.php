<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_FG_Guitars {

	private $specifications = object;

	private static $instance = null;

	public static function getInstance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		if ( class_exists( 'FG_Guitars_Specifications_Fields' ) ) {
			$this->specifications = FG_Guitars_Specifications_Fields::getInstance();
		}
	}

	public function get_specs( $post_id ) {
		$new_specs = array();

		$specs = $this->specifications->getPostMeta( $post_id );

		foreach ( $specs as $spec ) {
			$spec        = array_filter( $spec );
			$new_specs[] = $spec;
		}

		return $new_specs;
	}

	public function get_specs_html( $post_id ) {
		$specs = $this->get_specs( $post_id );

		$has_spec_variations = $this->specifications->hasVariations();

		ob_start();
		if ( $has_spec_variations ):
			echo $this->_get_specs_tabs( $specs );
		endif;

		echo $this->_get_specs_content( $specs );

		return ob_get_clean();
	}

	private function _get_specs_tabs( $specs = array() ) {
		ob_start();
		?>
        <ul uk-tab>
			<?php
			foreach ( $specs as $spec ):
				if ( ! empty( $spec['configuration_image_id'] ) ):
					?>
                    <li><a><?php echo wp_get_attachment_image( $spec['configuration_image_id'], 'thumbnail' ); ?></a></li>
				<?php
				endif;
			endforeach;
			?>
        </ul>
		<?php
		return ob_get_clean();
	}


	private function _get_specs_content( $specs = array() ) {
		$has_spec_variations = $this->specifications->hasVariations();

		echo $has_spec_variations ? '<ul class="uk-switcher uk-margin-top">' : '';

		ob_start();

		foreach ( $specs as $spec ):
			unset( $spec['configuration_image_id'] );
			unset( $spec['configuration_image'] );

			$divided_specs = $this->_divide_array( $spec );

			echo $has_spec_variations ? '<li>' : '';

			?>
            <div class="uk-child-width-1-2@m fg-specs" uk-grid>
				<?php
				foreach ( $divided_specs as $spec ):
					?>
                    <div class="uk-margin-remove-top">
						<?php echo $this->_specs_table( $spec ); ?>
                    </div>
				<?php
				endforeach;
				?>
            </div>
			<?php

			echo $has_spec_variations ? '</li>' : '';

		endforeach;

		echo $has_spec_variations ? '</ul>' : '';

		return ob_get_clean();
	}

	private function _specs_table( $specs ) {
		if ( empty( $specs ) || ! is_array( $specs ) ) {
			return '';
		}

		ob_start();
		?>
        <table class="uk-table uk-table-small uk-table-divider uk-table-responsive">
            <tbody>
			<?php
			foreach ( $specs as $key => $value ):
				?>
                <tr>
                    <td class="uk-width-1-5@m uk-width-1-3 uk-text-bold uk-text-right@m uk-text-uppercase"><?php esc_attr_e( $this->specifications->getFieldLabel( $key ) ); ?></td>
                    <td class="uk-width-4-5@m uk-width-2-3"><?php esc_attr_e( $value ); ?></td>
                </tr>
			<?php
			endforeach;
			?>
            </tbody>
        </table>
		<?php

		return ob_get_clean();
	}

	private function _divide_array( $array ) {
		$half_array = count( $array ) / 2;

		return array(
			array_slice( $array, 0, $half_array, true ),
			array_slice( $array, $half_array, null, true )
		);
	}
}