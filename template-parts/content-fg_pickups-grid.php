<?php

$class = '';
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $class ); ?>>
	<?php Fremediti_Guitars_Template_Functions::post_thumbnail( '', '' ); ?>

    <a class="post-title uk-text-center uk-display-block uk-margin-small-top" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1" rel="bookmark">
		<?php the_title( '<span class="entry-title uk-h4">', '</h2>' ); ?>
    </a>
</article>
