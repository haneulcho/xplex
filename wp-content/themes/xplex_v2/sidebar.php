<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

$post_sidebar = 'leftsidebar';

// webzine 카테고리이면서 글 보기 화면일 때만 왼쪽 사이드바를 출력한다.
if( is_category(array('webzine','publishmarketing','dear-reader','interview','p-note')) ) {
	if( !is_singular(array('post')) ) {
		$webzine_sidebar = false;
	} else {
		$webzine_sidebar = true;
	}
} else {
	$webzine_sidebar = true;
}

if( $webzine_sidebar ) {
?>
	<aside id="secondaryleft" class="widget-area left" role="complementary">
	<?php
	if (is_active_sidebar('sidebar-2')) {
		dynamic_sidebar( 'sidebar-2' );
	}
	/**
	 * Display Product Search
	 * @since  1.0.0
	 * @uses  is_woocommerce_activated() check if WooCommerce is activated
	 * @return void
	 */

		if ( is_woocommerce_activated() ) { ?>
			<div class="advance-search">
				<?php storevilla_product_search(); ?>
			</div>
		<?php } else{ ?>
			<div class="normal-search">
				<?php get_search_form(); ?>
			</div>
		<?php }
	dynamic_sidebar( 'sidebar-3' ); ?>
	</aside><!-- #secondary -->
<?php } ?>
