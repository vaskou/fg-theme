<?php

defined( 'ABSPATH' ) or die();

class Fremediti_Guitars_Template_Functions {

	private static $instance = null;

	/**
	 * FG_Guitars_Post_Type constructor.
	 */
	private function __construct() {
	}

	public static function instance() {
		if ( self::$instance == null ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	public static function posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
//			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
		/* translators: %s: post date. */
			esc_html_x( 'Posted on %s', 'post date', 'fremediti-guitars' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.

	}

	/**
	 * Prints HTML with meta information for the current author.
	 */
	public static function posted_by() {
		$byline = sprintf(
		/* translators: %s: post author. */
			esc_html_x( 'by %s', 'post author', 'fremediti-guitars' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.

	}

	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	public static function entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'fremediti-guitars' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'fremediti-guitars' ) . '</span>', $categories_list ); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'fremediti-guitars' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'fremediti-guitars' ) . '</span>', $tags_list ); // WPCS: XSS OK.
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
					/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'fremediti-guitars' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'fremediti-guitars' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}

	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	public static function post_thumbnail( $before = '', $after = '', $is_singular = false, $echo = true ) {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return '';
		}

		ob_start();

		echo $before;

		if ( is_singular( get_post_type() ) || $is_singular ) :
			?>

            <div uk-lightbox>
                <a href="<?php the_post_thumbnail_url(); ?>">
					<?php the_post_thumbnail(); ?>
                </a>
            </div><!-- .post-thumbnail -->

		<?php else : ?>

            <a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" rel="bookmark">
				<?php
				the_post_thumbnail( 'post-thumbnail', array(
					'alt' => the_title_attribute( array(
						'echo' => false,
					) ),
				) );
				?>
            </a>

		<?php
		endif; // End is_singular().

		echo $after;

		$html = ob_get_clean();

		if ( $echo ) {
			echo $html;
		} else {
			return $html;
		}
	}

	public static function price_format( $price, $currency = '' ) {
		if ( '' == $price ) {
			return null;
		}

		if ( empty( $currency ) ) {
			$currency = Fremediti_Guitars_Multicurrency::get_current_currency();
		}

		$currency_symbol = Fremediti_Guitars_Multicurrency::get_currency_symbol( $currency );

		switch ( $currency ) {
			case 'USD':
				$formatted_price = sprintf( '%s%s', $currency_symbol, esc_attr( number_format( $price, 2, '.', ',' ) ) );
				break;
			case 'EUR':
			default:
				$formatted_price = sprintf( '%s %s', esc_attr( number_format( $price, 2, ',', '.' ) ), $currency_symbol );
				break;
		}

		return apply_filters( 'fremediti_guitars_price_format', $formatted_price, $price, $currency, $currency_symbol );
	}

	public static function price_without_buttons( $price, $converted_price, $label = '', $original_currency = 'EUR', $converted_currency = 'USD' ) {
		if ( ! empty( $price ) ): ?>
			<?php echo $label; ?> <span class="fg-original-price"><?php echo self::price_format( $price, $original_currency ); ?></span>
		<?php endif; ?>
		<?php
		if ( ! empty( $converted_price ) ):
			?>
            <span class="fg-converted-price uk-hidden"><?php echo self::price_format( $converted_price, $converted_currency ); ?></span>
		<?php
		endif;
	}

	public static function price_with_buttons( $price, $converted_price, $label = '', $original_currency = 'EUR', $converted_currency = 'USD' ) {
		self::price_without_buttons( $price, $converted_price, $label, $original_currency, $converted_currency );

		$original_currency_symbol  = Fremediti_Guitars_Multicurrency::get_currency_symbol( $original_currency );
		$converted_currency_symbol = Fremediti_Guitars_Multicurrency::get_currency_symbol( $converted_currency );

		if ( ! empty( $converted_price ) ):
			?>
            <button class="uk-button fg-original-currency-symbol fg-currency-button fg-selected"><?php echo $original_currency_symbol; ?></button>
            <button class="uk-button fg-converted-currency-symbol fg-currency-button"><?php echo $converted_currency_symbol; ?></button>
		<?php
		endif;

	}

	public static function videos_grid( $videos, $columns = 4, $class = '' ) {

		$videos_array = array();

		foreach ( $videos as $key => $video ) {
			if ( is_array( $video ) ) {
				$videos_array[ $key ]['url']   = ! empty( $video['url'] ) ? $video['url'] : '';
				$videos_array[ $key ]['title'] = ! empty( $video['title'] ) ? $video['title'] : '';
			} else {
				$videos_array[ $key ]['url']   = ! empty( $video ) ? $video : '';
				$videos_array[ $key ]['title'] = '';
			}
		}

		$args = array(
			'videos'  => $videos_array,
			'columns' => $columns,
			'class'   => $class,
		);

		ob_start();

		get_template_part( 'template-parts/video-template', null, $args );

		return ob_get_clean();
	}
}