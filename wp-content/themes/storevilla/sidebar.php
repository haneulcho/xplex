<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

$post_sidebar = 'leftsidebar';

if( is_active_sidebar('sidebar-2')){
	?>
		<aside id="secondaryleft" class="widget-area left" role="complementary">
			<?php dynamic_sidebar( 'sidebar-2' ); ?>
		</aside><!-- #secondary -->
	<?php
}
