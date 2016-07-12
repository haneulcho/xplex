<?php
/**
 * Template part for displaying page content in page.php.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('storevilla-blog w_view'); ?>>
	<ul class="blog-meta">
		<li class="sv-category">출판문화공간 엑스플렉스(X-PLEX)</li>
	</ul>

	<?php the_title( '<h2>', '</h2>' ); ?>

	<div class="entry-content">
		<?php
			the_content();

			if ( function_exists( 'sharing_display' ) ) {
				sharing_display( '', true );
			}

			if ( class_exists( 'Jetpack_Likes' ) ) {
				$custom_likes = new Jetpack_Likes;
				echo $custom_likes->post_likes( '' );
			}

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'storevilla' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
