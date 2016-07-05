<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Store_Villa
 */

get_header(); ?>
	<div id="primary" class="content-area w_list">
		<main id="main" class="site-main" role="main">

		<?php
		if ( have_posts() ) : ?>

			<header class="page-header">
				<?php
					// webzine 카테고리이면 하위 카테고리 grouping 출력
					// webzine 카테고리가 아니면 포스트 일반 출력
					/*
					유재건의 출판마케팅 이야기: publishmarketing 49
					디어리더: dear-reader 19
					인터뷰: interview 52
					퍼블리셔'스 노트: p-note 48
					*/
					$cat = get_category( get_query_var( 'cat' ) );
					$cat_id = $cat->cat_ID;
					$cat_slug = $cat->slug;
					$cat_name = $cat->name;
					$limit_per_page = 6;

					$cats[] = array('id' => 49, 'slug' => 'publishmarketing', 'name' => '유재건의 출판마케팅 이야기');
					$cats[] = array('id' => 19, 'slug' => 'dear-reader', 'name' => '디어리더');
					$cats[] = array('id' => 48, 'slug' => 'p-note', 'name' => '퍼블리셔\'스 노트');
					$cats[] = array('id' => 69, 'slug' => 'kids-book-story', 'name' => '고은경의 어린이책 이야기');
					$cats[] = array('id' => 52, 'slug' => 'interview', 'name' => '인터뷰 (Fox 이야기)');

					echo '<h1 class="page-title">X-저널</h1>';
				?>
				<ul class="w_header_cat">
					<?php
						foreach ($cats as $row) {
							$cid = $row['id'];
							$cslug = $row['slug'];
							$cname = $row['name'];
							if ( $cat_id == $cid ) {
								$w_class = 'w_active';
							} else {
								$w_class = '';
							}
							echo '<li class="'.$w_class.'"><a href="./'.$cslug.'"><i class="fa fa-angle-right" aria-hidden="true"></i>'.$cname.'</a></li>';
						}
					?>
				</ul>
			</header><!-- .page-header -->

			<?php
			if ( $cat_slug == 'webzine' ) {
				foreach ($cats as $row) {
					$cid = $row['id'];
					$cslug = $row['slug'];
					$cname = $row['name'];
					$cat_query = new WP_Query("cat=".$cid."&posts_per_page=".$limit_per_page);

					/* Start the Loop */
					echo '<div class="w_group"><h2>'.$cname.'<a href="./'.$cslug.'">전체 글 보기</a></h2><div class="w_child">';

					while ( $cat_query->have_posts() ) : $cat_query->the_post();
					get_template_part( 'template-parts/content', 'archive' );
					endwhile;

					echo '</div></div>';
				} // end foreach
			} else {
				echo '<div class="w_group"><h2>'.$cat_name.'</h2><div class="w_child">';
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/content', 'archive' );

				endwhile;
				echo '</div></div>';
				the_posts_pagination( array(
					'prev_text' => '&larr;',
					'next_text' => '&rarr;'
				) );
			} // end else

		else :

			get_template_part( 'template-parts/content', 'none' );

		endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->
<?php get_sidebar();

get_footer();
