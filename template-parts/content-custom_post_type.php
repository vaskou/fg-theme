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

			if ( class_exists( 'FG_Pickups_Post_Type' ) && FG_Pickups_Post_Type::POST_TYPE_NAME == get_post_type() ):
				$fg_pickups_image_id = FG_Pickups_Post_Type::getInstance()->get_pickup_image_id( get_the_ID() );
				$url = wp_get_attachment_url( $fg_pickups_image_id );
				if ( ! empty( $url ) ):
					?>
                    <div uk-lightbox>
                        <a href="<?php echo esc_url( $url ); ?>" class="uk-button uk-button-primary">
							<?php _e( 'Specs', 'fremediti-guitars' ); ?>
                        </a>
                    </div>
				<?php
				endif;
			endif

			?>
        </div><!-- .entry-content -->

	    <?php fremediti_guitars_post_thumbnail(); ?>
    </div>

    <footer class="entry-footer">
		<?php //fremediti_guitars_entry_footer(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
