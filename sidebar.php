<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Fremediti_Guitars
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}

$has_sidebar = apply_filters( 'fremediti_guitars_has_sidebar', false );

?>

<aside id="secondary" class="widget-area <?php echo $has_sidebar ? 'uk-width-1-3@s uk-width-1-1' : ''; ?>">
	<?php dynamic_sidebar( 'sidebar-1' ); ?>
</aside><!-- #secondary -->
