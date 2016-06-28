<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Header Template
 *
 * Here we setup all logic and XHTML that is required for the header section of all screens.
 *
 * @package WooFramework
 * @subpackage Template
 */

 global $woo_options, $woocommerce;

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php woo_title(); ?></title>
<?php woo_meta(); ?>
<link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>" />
<?php
wp_head();
woo_head();
?>
</head>
<body <?php body_class(); ?>>
<?php woo_top(); ?>

<div id="wrapper">
	<div id="inner-wrapper">

    <?php woo_header_before(); ?>

	<header id="header">

		<div class="col-full">

			<div id="header-inside">

				<?php woo_header_inside(); ?>

				<span class="nav-toggle"><a href="#navigation"><span><?php _e( 'Navigation', 'woothemes' ); ?></span></a></span>

			    <hgroup>
					<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a></h1>
					<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				</hgroup>

				<?php

					// Load the slider or hero product/intro message.
					$settings = woo_get_dynamic_values( array( 
						'featured' => 'true', 
						'enable_hero_or_intro' => 'false',
						'hero_or_intro' => 'hero-product' ) );

					if ( is_home() && ( 'true' == $settings['featured'] ) ) {
						get_template_part( 'includes/featured', 'slider' );					
					} elseif ( !is_home() && ( 'true' == $settings['enable_hero_or_intro'] ) ) {
						get_template_part( 'includes/header', $settings['hero_or_intro'] );
					}

				?>

			</div><!-- /#header-inside -->

	        <?php woo_nav_before(); ?>

			<nav id="navigation" class="col-full" role="navigation">

				<section class="menus">

					<a href="<?php echo home_url(); ?>" class="nav-home"><span><?php _e( 'Home', 'woothemes' ); ?></span></a>

			        <?php
						if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'primary-menu' ) ) {
							echo '<h3>' . woo_get_menu_name('primary-menu') . '</h3>';
							wp_nav_menu( array( 'depth' => 6, 'sort_column' => 'menu_order', 'container' => 'ul', 'menu_id' => 'main-nav', 'menu_class' => 'nav', 'theme_location' => 'primary-menu' ) );
						} else {
					?>
				        <ul id="main-nav" class="nav">
							<?php if ( is_page() ) $highlight = 'page_item'; else $highlight = 'page_item current_page_item'; ?>
							<li class="<?php echo $highlight; ?>"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'woothemes' ); ?></a></li>
							<?php wp_list_pages( 'sort_column=menu_order&depth=6&title_li=&exclude=' ); ?>
						</ul><!-- /#nav -->
					<?php } ?>

					<?php if ( is_woocommerce_activated() && isset( $woo_options['woocommerce_header_cart_link'] ) && 'true' == $woo_options['woocommerce_header_cart_link'] ) { ?>
			        	<h3><?php _e( 'Shopping Cart', 'woothemes' ); ?></h3>
			        	<ul class="nav cart">
			        		<li <?php if ( is_cart() ) { echo 'class="current-menu-item"'; } ?>>
			        		<?php
			        			global $woocommerce;
								woo_wc_cart_link();
							?>
			        		</li>
			       		</ul>
			        <?php } ?>

					<?php
						if ( 'true' == $woo_options['woo_header_contact'] ) {
					?>
						<div id="header-contact">
							<h3><?php _e( 'Call Us!', 'woothemes'); ?></h3>
							<ul class="nav">
								<?php if ( '' != $woo_options['woo_contact_number'] ) { ?>
								<li class="phone">
									<a href="tel:<?php $tel = preg_replace('/\D+/', '', $woo_options['woo_contact_number']); echo $tel; ?>"><?php echo esc_html( $woo_options['woo_contact_number'] ); ?></a>
									<span><?php echo esc_html( $woo_options['woo_contact_number'] ); ?></span>
								</li>
								<?php } ?>
							</ul>
						</div>
					<?php
						}
					?>

		    	</section><!--/.menus-->

		        <a href="#top" class="nav-close"><span><?php _e('Return to Content', 'woothemes' ); ?></span></a>

			</nav><!-- /#navigation -->

			<?php woo_nav_after(); ?>

		</div><!-- /.col-full -->

	</header><!-- /#header -->

	<?php woo_content_before(); ?>