<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'uk-article' ); ?>>
    <header class="entry-header">
		<?php
		if ( is_singular( get_post_type() ) ) :
			the_title( '<h1 class="entry-title uk-article-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title uk-article-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;
		?>
    </header><!-- .entry-header -->

    <div class="uk-child-width-1-2@m" uk-grid>
        <div class="entry-content uk-text-justify">
			<?php
			the_content();
			?>
        </div><!-- .entry-content -->

		<?php

		if ( is_singular( get_post_type() ) ) :
			if ( has_post_thumbnail() ):
				?>
                <div uk-lightbox>
                    <a href="<?php the_post_thumbnail_url(); ?>">
						<?php the_post_thumbnail(); ?>
                    </a>
                </div>
			<?php
			endif;
		else:
			?>
            <a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark">
				<?php fremediti_guitars_post_thumbnail(); ?>
            </a>
		<?php
		endif;

		?>
    </div>

    <footer class="entry-footer">
		<?php //fremediti_guitars_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
