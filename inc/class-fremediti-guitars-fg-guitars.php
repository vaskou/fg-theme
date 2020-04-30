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

		$has_spec_variations = $this->specifications->hasVariations();

		if ( $has_spec_variations ) {
			foreach ( $specs as $spec ) {
				$new_specs[] = $this->_divide_array( $spec );
			}
		} else {
			$new_specs = $this->_divide_array( $specs[0] );
		}

		return $new_specs;
	}

	public function get_specs_html( $post_id ) {
		$specs = $this->get_specs( $post_id );

		ob_start();
		foreach ( $specs as $spec ):
			?>
            <div class="uk-margin-remove-top">
				<?php echo $this->_specs_table( $spec ); ?>
            </div>
		<?php
		endforeach;

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
		$half_array = (count( $array ) / 2) + 1;

		return array(
			array_slice( $array, 0, $half_array, true ),
			array_slice( $array, $half_array + 1, null, true )
		);
	}
}