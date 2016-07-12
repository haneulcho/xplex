<?php
/**
 * Template Name: Board
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
				while ( have_posts() ) : the_post();
      ?>
          <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
            <?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
            </header><!-- .entry-header -->

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
      <?php
					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar();

get_footer();
