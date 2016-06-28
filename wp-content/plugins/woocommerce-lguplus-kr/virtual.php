<?php


if ( !defined( 'ABSPATH' ) ) exit;


////////////////////////////// Virtual Account


class WC_Gateway_Lguplus_Virtual extends WC_Gateway_Lguplus{

	public $method 				= 'SC0040';

	public $id	 				= 'lguplus_virtual';

	public $method_title 		= 'LG U+ Virtual Account';

	public $title_default 		= 'Virtual Account';

	public $desc_default  		= 'Payment via virtual account transfer.';

	public $require 			= array( 'SC0040' );

}


/**
* Add the Gateway to WooCommerce
**/
function woocommerce_add_Lguplus_Virtual( $methods ) {
	$methods[] = 'WC_Gateway_Lguplus_Virtual';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_Lguplus_Virtual' );

