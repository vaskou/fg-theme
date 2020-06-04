<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

$class = 'uk-article';
$class = !is_singular() ? $class . ' uk-margin-large-top' : $class;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
    <header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title uk-article-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title uk-article-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
            <div class="entry-meta uk-margin-bottom">
				<?php
				Fremediti_Guitars_Template_Functions::posted_on();
				//				Fremediti_Guitars_Template_Functions::posted_by();
				?>
            </div><!-- .entry-meta -->
		<?php endif; ?>
    </header><!-- .entry-header -->

    <div <?php if ( ! is_singular() ): ?> class="uk-child-width-1-1@m uk-grid" uk-grid <?php endif; ?> >

		<?php
		if ( is_singular() ) :
			?>
            <div class="uk-align-right@m">
				<?php /*Fremediti_Guitars_Template_Functions::post_thumbnail();*/ ?>
            </div>
		<?php
		endif;
		?>

        <div class="entry-content uk-text-justify">
			<?php
			the_content( sprintf(
				wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'fremediti-guitars' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			) );

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'fremediti-guitars' ),
				'after'  => '</div>',
			) );
			?>
        </div><!-- .entry-content -->

		<?php
		if ( ! is_singular() ) :
//			Fremediti_Guitars_Template_Functions::post_thumbnail();
		endif;
		?>

    </div>
    <footer class="entry-footer">
		<?php //Fremediti_Guitars_Template_Functions::entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
