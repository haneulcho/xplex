<?php


if ( !defined( 'ABSPATH' ) ) exit;


////////////////////////////// Mobile Payment


class WC_Gateway_Lguplus_Mobile extends WC_Gateway_Lguplus{

	public $method 				= 'SC0060';

	public $id	 				= 'lguplus_mobile';

	public $method_title 		= 'LG U+ Mobile Payment';

	public $title_default 		= 'Mobile Payment';

	public $desc_default  		= 'Payment via mobile phone.';

	public $require 			= array( 'SC0060' );

}


/**
* Add the Gateway to WooCommerce
**/
function woocommerce_add_Lguplus_Mobile( $methods ) {
	$methods[] = 'WC_Gateway_Lguplus_Mobile';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'woocommerce_add_Lguplus_Mobile' );

