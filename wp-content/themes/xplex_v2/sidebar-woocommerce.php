<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */
?>
<aside id="secondaryleft" class="widget-area left" role="complementary">
<?php
if (is_active_sidebar('sidebar-2')) {
	dynamic_sidebar( 'sidebar-2' );
}
dynamic_sidebar( 'sidebar-3' ); ?>
</aside><!-- #secondary -->
