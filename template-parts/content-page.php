<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php if ( ! Fremediti_Guitars_Metaboxes::hide_title( get_the_ID() ) ): ?>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header><!-- .entry-header -->
    <?php endif; ?>

	<?php Fremediti_Guitars_Template_Functions::post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		the_content();

		/*wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'fremediti-guitars' ),
			'after'  => '</div>',
		) );*/
		?>
	</div><!-- .entry-content -->

	<?php if ( get_edit_post_link() ) : ?>
		<footer class="entry-footer">
			<?php
//			edit_post_link(
//				sprintf(
//					wp_kses(
//						/* translators: %s: Name of current post. Only visible to screen readers */
//						__( 'Edit <span class="screen-reader-text">%s</span>', 'fremediti-guitars' ),
//						array(
//							'span' => array(
//								'class' => array(),
//							),
//						)
//					),
//					get_the_title()
//				),
//				'<span class="edit-link">',
//				'</span>'
//			);
			?>
		</footer><!-- .entry-footer -->
	<?php endif; ?>
</article><!-- #post-<?php the_ID(); ?> -->
