<?php
$post_id = get_the_ID();

$class = '';

$pickups_instance = FG_Pickups_Post_Type::instance();

$price                  = $pickups_instance->get_price( $post_id );
$price_set              = $pickups_instance->get_price_set( $post_id );
$prices_grid_visibility = $pickups_instance->get_prices_grid_visibility( $post_id );

$classes = array();

if ( empty( $price ) ) {
	$classes[] = 'hide-price';
}

if ( empty( $price_set ) ) {
	$classes[] = 'hide-price_set';
}

foreach ( $prices_grid_visibility as $hide_item ) {
	$classes[] = 'hide-' . $hide_item;
}

$pickup_prices_class = implode( ' ', array_unique( $classes ) );


?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	<?php Fremediti_Guitars_Template_Functions::post_thumbnail( '', '' ); ?>

    <a class="post-title uk-text-center uk-display-block uk-margin-small-top" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" rel="bookmark">
		<?php the_title( '<span class="entry-title uk-h4">', '</h2>' ); ?>
    </a>


    <div class="fg-pickups-prices uk-text-center <?php echo $pickup_prices_class; ?>">
        <span class="fg-pickups-prices__price-single"><?php echo Fremediti_Guitars_Template_Functions::price_format( $price ); ?></span>
        <span class="fg-pickups-prices__divider">-</span>
        <span class="fg-pickups-prices__price-set"><?php echo Fremediti_Guitars_Template_Functions::price_format( $price_set ) . ' ' . __( 'Set', 'fremediti-guitars' ); ?></span>
    </div>
</article>
