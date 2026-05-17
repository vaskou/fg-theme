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

		// Products archive
		add_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'product_loop_image_wrapper_open' ], 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', [ $this, 'product_loop_image_wrapper_close' ], 100 );
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'product_loop_button_wrapper_open' ], 9 );
		add_action( 'woocommerce_after_shop_loop_item', [ $this, 'product_loop_button_wrapper_close' ], 11 );

		// Single product
		add_filter( 'woocommerce_product_tabs', [ $this, 'remove_product_tabs' ], 99 );
		add_action( 'woocommerce_after_single_product_summary', [ $this, 'show_description' ] );

		// Checkout — wrap #order_review_heading + #order_review in a single card.
		add_action( 'woocommerce_checkout_before_order_review_heading', [ $this, 'order_review_wrapper_open' ] );
		add_action( 'woocommerce_checkout_after_order_review', [ $this, 'order_review_wrapper_close' ] );
	}

	public function remove_wp_hooks() {
		remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

		// Single product
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}

	public function product_loop_image_wrapper_open() {
		?>
        <div class="fg-product-loop-image-wrapper">
		<?php
	}

	public function product_loop_image_wrapper_close() {
		?>
        </div>
		<?php
	}

	public function product_loop_button_wrapper_open() {
		?>
        <div class="fg-product-loop-buttons-wrapper">
		<?php
	}

	public function product_loop_button_wrapper_close() {
		?>
        </div>
		<?php
	}

	public function order_review_wrapper_open() {
		?>
        <div class="fg-order-review">
		<?php
	}

	public function order_review_wrapper_close() {
		?>
        </div>
		<?php
	}

	public function remove_product_tabs( $tabs ) {
		unset( $tabs['description'] );

		return $tabs;
	}

	public function show_description() {
		$content = get_the_content();
		$content = apply_filters( 'the_content', $content );
		$content = str_replace( ']]>', ']]&gt;', $content );

		?>

        <div class="fg-product-content uk-margin-top uk-margin-bottom">
			<?php echo $this->transform_content( $content ); ?>
        </div>
		<?php
	}

	private function transform_content( string $html ): string {
		if ( empty( trim( $html ) ) ) {
			return '';
		}

		libxml_use_internal_errors( true );
		$dom = new DOMDocument();
		$dom->loadHTML( '<?xml encoding="UTF-8">' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
		libxml_clear_errors();

		$output     = '';
		$spec_group = [];
		$nodes      = iterator_to_array( $dom->childNodes );
		$count      = count( $nodes );

		for ( $i = 0; $i < $count; $i ++ ) {
			$node = $nodes[ $i ];

			if ( $node instanceof DOMElement && 'h4' === $node->nodeName ) {
				$next_element = null;
				$next_index   = null;

				for ( $j = $i + 1; $j < $count; $j ++ ) {
					if ( $nodes[ $j ] instanceof DOMElement ) {
						$next_element = $nodes[ $j ];
						$next_index   = $j;
						break;
					}
				}

				if ( null !== $next_element && 'ul' === $next_element->nodeName ) {
					$rendered = $this->render_spec_section( $node, $next_element, $dom );
					if ( ! empty( $rendered ) ) {
						$spec_group[] = $rendered;
					}
					$i = $next_index;
				} else {
					$output     .= $this->flush_spec_group( $spec_group );
					$spec_group = [];
					$output     .= $dom->saveHTML( $node );
				}
			} else {
				$output     .= $this->flush_spec_group( $spec_group );
				$spec_group = [];
				$output     .= $dom->saveHTML( $node );
			}
		}

		$output .= $this->flush_spec_group( $spec_group );

		return $output;
	}

	private function render_spec_section( DOMElement $h4, DOMElement $ul, DOMDocument $dom ): string {
		$heading         = trim( $h4->textContent );
		$specs_group_key = strtolower( preg_replace( '/[\s_]+/', '-', preg_replace( '/[^a-zA-Z0-9\s_]/', '', $heading ) ) );

		$items_html = '';
		foreach ( $ul->childNodes as $li ) {
			if ( ! ( $li instanceof DOMElement ) || 'li' !== $li->nodeName ) {
				continue;
			}

			$inner_html = '';
			foreach ( $li->childNodes as $child ) {
				$inner_html .= $dom->saveHTML( $child );
			}
			$inner_html = trim( $inner_html );
			$parts      = explode( '|', $inner_html, 2 );

			if ( 2 === count( $parts ) ) {
				$name       = esc_html( trim( strip_tags( $parts[0] ) ) );
				$value      = wp_kses_post( trim( $parts[1] ) );
				$items_html .= '<li class="fg-custom-specs-group__item">'
				               . '<div class="uk-flex uk-flex-between">'
				               . '<div class="fg-custom-specs-group__item__name">' . $name . '</div>'
				               . '<div class="fg-custom-specs-group__item__value">' . $value . '</div>'
				               . '</div>'
				               . '</li>';
			} else {
				$label      = esc_html( trim( strip_tags( $parts[0] ) ) );
				$items_html .= '<li class="fg-custom-specs-group__item"><div>' . $label . '</div></li>';
			}
		}

		if ( empty( $items_html ) ) {
			return '';
		}

		ob_start();
		?>
        <div class="fg-custom-specs-group__<?php echo esc_attr( $specs_group_key ); ?>">
            <h4 class="uk-heading-divider"><?php echo esc_html( $heading ); ?></h4>
            <ul class="uk-list fg-custom-specs-group__list"><?php echo $items_html; ?></ul>
        </div>
		<?php
		return ob_get_clean();
	}

	private function flush_spec_group( array $sections ): string {
		if ( empty( $sections ) ) {
			return '';
		}

		ob_start();
		?>
        <div class="fg-specifications uk-margin-top">
            <div class="uk-child-width-1-3@m uk-child-width-1-2@s uk-grid" uk-grid>
				<?php echo implode( '', $sections ); ?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}