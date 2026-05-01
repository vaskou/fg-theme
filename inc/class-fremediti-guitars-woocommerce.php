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

		// Single product
		add_filter( 'woocommerce_product_tabs', [ $this, 'remove_product_tabs' ], 99 );
		add_action( 'woocommerce_after_single_product_summary', [ $this, 'show_description' ] );
	}

	public function remove_wp_hooks() {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// Single product
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	public function remove_product_tabs( $tabs ) {
		unset( $tabs['description'] );

		return $tabs;
	}

	public function show_description() {
		$content = get_the_content();
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		$sections = $this->parse_spec_sections( $content );

		if ( empty( $sections ) ) {
			echo $content;

			return;
		}

		?>

        <div class="fg-specifications uk-margin-top">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid" uk-grid>
				<?php
				foreach ( $sections as $section ) {
					echo $section;
				}
				?>
            </div>
        </div>
		<?php
	}

	private function parse_spec_sections( string $html ): array {
		if ( empty( trim( $html ) ) ) {
			return [];
		}

		libxml_use_internal_errors( true );
		$dom = new DOMDocument();
		$dom->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();

		$sections    = [];
		$child_nodes = iterator_to_array( $dom->childNodes );

		foreach ( $child_nodes as $index => $node ) {
			if ( ! ( $node instanceof DOMElement ) || 'h4' !== $node->nodeName ) {
				continue;
			}

			$heading         = trim( $node->textContent );
			$specs_group_key = strtolower( preg_replace( '/[\s_]+/', '-', preg_replace( '/[^a-zA-Z0-9\s_]/', '', $heading ) ) );
			$next_ul         = null;

			for ( $i = $index + 1; $i < count( $child_nodes ); $i ++ ) {
				$sibling = $child_nodes[ $i ];
				if ( ! ( $sibling instanceof DOMElement ) ) {
					continue;
				}
				if ( 'ul' === $sibling->nodeName ) {
					$next_ul = $sibling;
				}
				break;
			}

			if ( null === $next_ul ) {
				continue;
			}

			$items_html = '';
			foreach ( $next_ul->childNodes as $li ) {
				if ( ! ( $li instanceof DOMElement ) || 'li' !== $li->nodeName ) {
					continue;
				}

				$text  = trim( $li->textContent );
				$parts = explode( '|', $text, 2 );

				if ( 2 === count( $parts ) ) {
					$name       = esc_html( trim( $parts[0] ) );
					$value      = esc_html( trim( $parts[1] ) );
					$items_html .= '<li class="fg-custom-specs-group__item">'
					               . '<div class="uk-flex uk-flex-between">'
					               . '<div class="fg-custom-specs-group__item__name">' . $name . '</div>'
					               . '<div class="fg-custom-specs-group__item__value">' . $value . '</div>'
					               . '</div>'
					               . '</li>';
				} else {
					$label      = esc_html( trim( $parts[0] ) );
					$items_html .= '<li class="fg-custom-specs-group__item"><div>' . $label . '</div></li>';
				}
			}

			if ( empty( $items_html ) ) {
				continue;
			}

			ob_start();
			?>
            <div class="fg-custom-specs-group__<?php echo esc_attr( $specs_group_key ); ?>">
                <h4 class="uk-heading-divider"><?php echo esc_html( $heading ); ?></h4>
                <ul class="uk-list fg-custom-specs-group__list"><?php echo $items_html; ?></ul>
            </div>
			<?php
			$sections[] = ob_get_clean();
		}

		return $sections;
	}
}