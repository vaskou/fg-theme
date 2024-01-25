<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Fremediti_Guitars
 */

$class = 'uk-flex';
?>

<div id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
    <div class="fg-article-wrapper uk-flex-1">
        <header class="entry-header">
			<?php
			if ( is_singular( get_post_type() ) ) :
				the_title( '<h1 class="entry-title uk-article-title">', '</h1>' );
			else :
				the_title( '<h4 class="entry-title">', '</h4>' );
			endif;
			?>
        </header><!-- .entry-header -->

        <div class="entry">
            <div class="uk-margin-bottom">
				<?php
				$post_thumbnail = Fremediti_Guitars_Template_Functions::post_thumbnail( '', '', true, false );

				echo apply_filters( 'fremediti_guitars_custom_post_type_thumbnail', $post_thumbnail, get_the_ID() );
				?>
            </div>

            <div class="entry-content uk-text-justify">
				<?php
				echo wpautop( get_the_content() );

				do_action( 'fremediti_guitars_custom_post_type_after_content' );

				?>
            </div><!-- .entry-content -->
        </div>

		<?php do_action( 'fremediti_guitars_custom_post_type_after_content_row' ); ?>
    </div>
</div><!-- #post-<?php the_ID(); ?> -->
