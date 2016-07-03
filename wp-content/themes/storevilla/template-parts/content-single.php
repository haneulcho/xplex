<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('storevilla-blog w_view'); ?>>

	<ul class="blog-meta">
		<li class="sv-category"><?php the_category( ', ' ); ?></li>
		<!-- <li class="sv-author"><?php //_e('Post By :','storevilla'); ?> <?php //the_author_link(); ?></li> -->
		<li class="sv-time"><?php the_time('Y년 m월 d일'); ?></li>
		<li class="sv-comments"><?php comments_popup_link( '0 Comment', '1 Comment', '% Comments' ); ?></li>
	</ul>

	<h2><?php the_title(); ?></h2>


	<div class="entry-content">
		<?php
			the_content();

			

			wp_link_pages( array(
				'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'storevilla' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->

</article><!-- #post-## -->
