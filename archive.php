<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

get_header();

do_action( 'fremediti_guitars_archive_before' );
$has_sidebar = apply_filters( 'fremediti_guitars_has_sidebar', false );
?>

    <div id="primary" class="content-area <?php echo $has_sidebar ? 'uk-width-2-3@m uk-width-1-1' : ''; ?>">
        <main id="main" class="site-main">

			<?php if ( have_posts() ) : ?>

                <header class="page-header">
					<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="archive-description">', '</div>' );
					?>
                </header><!-- .page-header -->
                <div class="uk-grid uk-child-width-1-2@m" uk-grid>
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;
					?>
                </div>
				<?php
				the_posts_pagination( array(
					'type'      => 'list',
					'prev_text' => '<span uk-pagination-previous><span class="uk-hidden">' . __( 'Previous', 'fremediti-guitars' ) . '</span></span>',
					'next_text' => '<span uk-pagination-next><span class="uk-hidden">' . __( 'Next', 'fremediti-guitars' ) . '</span></span>',
				) );

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>

        </main><!-- #main -->
    </div><!-- #primary -->

<?php

do_action( 'fremediti_guitars_archive_after' );

get_footer();
