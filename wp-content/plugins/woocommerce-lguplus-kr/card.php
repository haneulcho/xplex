<?php


if ( !defined( 'ABSPATH' ) ) exit;


////////////////////////////// Credit Card


class WC_Gateway_Lguplus_Credit extends WC_Gateway_Lguplus{

	public $method 				= 'SC0010';

	public $id	 				= 'lguplus_credit';

	public $method_title 		= 'LG U+ Credit Card';

	public $title_default 		= 'Credit Card';

	public $desc_default  		= 'Payment via credit card.';

	public $require 			= array( 'SC0010' );

}


/**
* Add the Gateway to WooCommerce
**/
function woocommerce_add_Lguplus_Credit( $methods ) {
	$methods[] = 'WC_Gateway_Lguplus_Credit';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_Lguplus_Credit' );

