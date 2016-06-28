<?php


if ( !defined( 'ABSPATH' ) ) exit;


////////////////////////////// Account Transfer


class WC_Gateway_Lguplus_Account extends WC_Gateway_Lguplus{

	public $method 				= 'SC0030';

	public $id	 				= 'lguplus_account';

	public $method_title 		= 'LG U+ Account Transfer';

	public $title_default 		= 'Account Transfer';

	public $desc_default  		= 'Payment via real time account transfer.';

	public $require 			= array( 'SC0030' );

}


/**
* Add the Gateway to WooCommerce
**/
function woocommerce_add_Lguplus_Account( $methods ) {
	$methods[] = 'WC_Gateway_Lguplus_Account';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_Lguplus_Account' );

