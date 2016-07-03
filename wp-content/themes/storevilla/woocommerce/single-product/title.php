<?php
/**
 * Single Product title
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
global $post, $product, $woocommerce;
$availability = $product->get_availability();
if ( $product->is_in_stock() ) {
	$availability['availability'] = __('신청가능', 'woocommerce');
	$message = '<span class="in_stock">' . esc_html( $availability['availability'] ) . '</span>';
}
if ( !$product->is_in_stock() ) {
	$availability['availability'] = __('신청이 마감되었습니다.', 'woocommerce');
	$message = '<span class="out_stock">' . esc_html( $availability['availability'] ) . '</span>';
}
?>
<h1 itemprop="name" class="product_title entry-title"><?php the_title(); ?></h1>
<?php echo $message; ?>
