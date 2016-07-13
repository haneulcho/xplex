<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Store_Villa
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storevilla_html_tag_schema(); ?> >
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<?php do_action( 'storevilla_before_header' ); ?>

		<header id="masthead" class="site-header" role="banner" <?php if ( get_header_image() != '' ) { echo 'style="background-image: url(' . esc_url( get_header_image() ) . '); background-size:cover;"'; } ?>>
				<?php
					/**
					 * @see  storevilla_skip_links() - 0
					 * @see  storevilla_top_header() - 10
						**@see storevilla_top_nav (filter for top header navigation)
					 * @see  storevilla_button_header() - 20
					 * @see  storevilla_primary_navigation() - 30
					 */
					do_action( 'storevilla_header' );
				?>
		</header><!-- #masthead -->
		<?php if( !( is_home() || is_front_page() ) ) { ?>
		<div id="xbreadcrumb">
			<div class="store-container clearfix">
			<?php
			if( !( is_category() ) ) {
				do_action( 'xplex_custom_breadcrumb' );
			}
			if( is_category('webzine') || is_category('publishmarketing') || is_category('dear-reader') || is_category('interview') || is_category('p-note') ) {
			?>
				<nav class="woocommerce-breadcrumb" itemprop="breadcrumb"><a href="<?php echo get_home_url(); ?>">X-PLEX</a> &gt; <?php echo single_cat_title(); ?></nav>
			<?php
			}
			?>
			</div>
		</div>
		<?php } ?>
	<?php do_action( 'storevilla_after_header' ); ?>
	<?php	if ( wp_is_mobile() ) { // 모바일이면 메뉴 2개 바깥으로 빼기 ?>
		<ul id="m_onb">
			<li><a href="https://www.xplex.org:49408/product-category/xplex-lecture/now/"><i class="fa fa-list-alt" aria-hidden="true"></i> 모집중인 강의</a></li>
			<li><a href="https://www.xplex.org:49408/product-category/personal-coaching/"><i class="fa fa-hand-o-right" aria-hidden="true"></i> 1:1 코칭</a></li>
			<li><a href="https://www.xplex.org:49408/product-category/xbooks/"><i class="fa fa-book" aria-hidden="true"></i> 엑스북스</a></li>
		</ul>
	<?php } ?>

	<div id="content" class="site-content">
	<?php if( !( is_home() || is_front_page() ) ) { ?>
		<div class="store-container clearfix">
			<div class="store-container-inner clearfix">
	<?php }
