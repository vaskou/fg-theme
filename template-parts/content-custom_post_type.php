<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

$class = 'uk-article uk-flex';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
    <div class="fg-article-wrapper uk-flex-1">
        <header class="entry-header">
			<?php
			if ( is_singular( get_post_type() ) ) :
				the_title( '<h1 class="entry-title uk-article-title">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title">', '</h2>' );
//			the_title( '<h2 class="entry-title uk-article-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>
        </header><!-- .entry-header -->

        <div class="uk-child-width-1-2@m uk-grid" uk-grid>
            <div class="uk-flex-last@m">
				<?php Fremediti_Guitars_Template_Functions::post_thumbnail( '', '', true ); ?>
            </div>

            <div class="entry-content uk-text-justify">
				<?php
				echo wpautop( get_the_content() );

				do_action( 'fremediti_guitars_custom_post_type_after_content' );

				?>
            </div><!-- .entry-content -->
        </div>

		<?php do_action( 'fremediti_guitars_custom_post_type_after_content_row' ); ?>

        <footer class="entry-footer">
			<?php //Fremediti_Guitars_Template_Functions::entry_footer(); ?>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
