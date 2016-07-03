<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class('storevilla-blog w_li'); ?>>

	<?php
		if( has_post_thumbnail() ){
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'storevilla-blog-image', true);
	?>
		<figure>
      <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url( $image[0] ); ?>" alt="<?php the_title(); ?>"></a>
			<div class="sv-img-hover">
				<div class="holder">
				</div>
			</div>
		</figure>
	<?php } ?>

	<div class="sv-post-content">

		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<div class="description"><?php the_excerpt(); ?></div>
    <div class="post-meta">
      <span class="date"><i class="fa fa-calendar-o" aria-hidden="true"></i><?php the_time('Y년 m월 d일'); ?></span>
      <a href="<?php the_permalink(); ?>" class="sv-btn-countinuereading"><?php _e('<i class="fa fa-plus" aria-hidden="true"></i>자세히 보기','storevilla'); ?></a>
    </div>

	</div>

</article><!-- #post-## -->
