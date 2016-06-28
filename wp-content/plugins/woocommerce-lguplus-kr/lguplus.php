<?php
/*
Plugin Name: WooCommerce Korean Payment Gateway with LG U+
Plugin URI: http://www.qustreet.com/woopay
Description: Korean Payment Gateway integrated with LG U+ for WooCommerce.
Version: 2.0.0
Author: planet8
Author URI: planet8.co
Copyright : Planet 8 proprietary.
Developer : Thomas ( thomas@planet8.co )
*/
if ( ! defined( 'ABSPATH' ) ) exit; 

add_action('plugins_loaded', 'woocommerce_lguplus_init', 0);
 
function woocommerce_lguplus_init() {
 
if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

// Localization
load_plugin_textdomain('wc-gateway-lguplus', false, plugin_basename( dirname( __FILE__ ) ).'/languages');

// Gateway class
class WC_Gateway_Lguplus extends WC_Payment_Gateway {

	public function __construct() {
		global $woocommerce;
		$this->init_form_fields();
		$this->init_settings();
		$this->add_extra_form_fields();

		$this->method_title		= __( $this->method_title, 'wc-gateway-lguplus' );

		$this->testmode			= $this->get_option( 'testmode' );
		$this->title			= $this->get_option( 'title' );
		$this->description		= $this->get_option( 'description' );
		$this->enabled			= $this->get_option( 'enabled' );
		$this->mertkey			= $this->get_option( 'mertkey' );
		$this->mertid			= $this->get_option( 'mertid' );
		$this->langcode			= $this->get_option( 'langcode' );
		$this->form_style		= $this->get_option( 'form_style' );

		// Actions
		add_action( 'wp_head', array( $this, 'check' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'lguplus_scripts' ) );
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt' ) );
		add_action( 'valid_'. $this->id, array( $this, 'pg_response' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );	

		add_action( 'lguplus_process_response', array( $this, 'process_response' ) );
		add_action( 'lguplus_process_noteurl', array( $this, 'process_noteurl' ) );
		add_action( 'lguplus_process_cas_noteurl', array( $this, 'process_cas_noteurl' ) );
		add_action( 'lguplus_process_smart_noteurl', array( $this, 'process_smart_noteurl' ) );
		add_action( 'lguplus_process_smart_mtnoteurl', array( $this, 'process_smart_mtnoteurl' ) );
		add_action( 'lguplus_process_smart_returnurl', array( $this, 'process_smart_returnurl' ) );
		add_action( 'lguplus_process_smart_response', array( $this, 'process_smart_response' ) );
		add_action( 'lguplus_process_smart_wapurl', array( $this, 'process_smart_wapurl' ) );
		add_action( 'lguplus_process_smart_cancelurl', array( $this, 'process_smart_cancelurl' ) );
		add_action( 'lguplus_process_smart_casnoteurl', array( $this, 'process_smart_casnoteurl' ) );

		// Payment listener/API hook
		add_action( 'woocommerce_api_wc_gateway_lguplus', array( $this, 'check_response' ) );

		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = false;
		}
	}

    function init_form_fields() {
		if ( $this->is_valid_for_use() ) {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __( 'Enable/Disable', 'wc-gateway-lguplus' ),
					'type' => 'checkbox',
					'label' => __( 'Enable this method.', 'wc-gateway-lguplus' ),
					'default' => 'yes'
				),
                'testmode' => array(
                    'title' => __( 'Enable/Disable Test Mode', 'wc-gateway-lguplus' ),
                    'type' => 'checkbox',
                    'label' => __( 'Enable test mode.', 'wc-gateway-lguplus' ),
                    'description' => '',
                    'default' => 'no'
                ),
				'title' => array(
					'title' => __( 'Title', 'wc-gateway-lguplus' ),
					'type' => 'text',
					'description' => __( 'Title that users will see during checkout.', 'wc-gateway-lguplus' ),
					'default' => __( $this->title_default, 'wc-gateway-lguplus' ),
				),
				'description' => array(
					'title' => __( 'Description', 'wc-gateway-lguplus' ),
					'type' => 'textarea',
					'description' => __( 'Description that users will see during checkout.', 'wc-gateway-lguplus' ),
					'default' => __( $this->desc_default, 'wc-gateway-lguplus' )
				),					
                'mertkey' => array(
                    'title' => __( 'Merchant Key', 'wc-gateway-lguplus' ),
                    'type' => 'text',
                    'description' => __( 'Please enter your LG U+ Merchant Key.', 'wc-gateway-lguplus' ) . ' ' . sprintf( __( 'You can to get this information in: %sLG U+ Account%s.', 'wc-gateway-lguplus' ), '<a href="http://pgweb.uplus.co.kr/tmert" target="_blank">', '</a>' ),
                    'default' => ''
                ),
                'mertid' => array(
                    'title' => __( 'Merchant ID', 'wcpaylg' ),
                    'type' => 'text',
                    'description' => __( 'Please enter your LG U+ Merchant ID.', 'wc-gateway-lguplus' ) . ' ' . sprintf( __( 'You can to get this information in: %sLG U+ Account%s.', 'wc-gateway-lguplus' ), '<a href="http://pgweb.uplus.co.kr/tmert" target="_blank">', '</a>' ),
                    'default' => ''
                ),
				'langcode' => array(
					'title' => __( 'Language', 'wc-gateway-lguplus' ),
					'type' => 'select',
					'description' => __( 'Select the language for your LG U+ form.', 'wc-gateway-lguplus' ),
					'options' => array(
						'Auto' => __( 'Use WooCommerce Language', 'wc-gateway-lguplus' ),
						'US' => __( 'English', 'wc-gateway-lguplus' ),
						'KR' => __( 'Korean', 'wc-gateway-lguplus' ),
					),
					'default' => __( 'Auto', 'wc-gateway-lguplus' ),
				),
				'form_style' => array(
					'title' => __( 'Style', 'wc-gateway-lguplus' ),
					'type' => 'select',
					'description' => __( 'Select the style for your LG U+ form.', 'wc-gateway-lguplus' ),
					'options' => array(
						'red' => 'Red',
						'blue' => 'Blue',
					)
				),
			);
		}
	}

	function add_extra_form_fields() {

	}

	function is_valid_for_use() {
		if ( ! in_array( get_woocommerce_currency(), apply_filters( 'woocommerce_lguplus_supported_currencies', array( 'KRW' ) ) ) ) {
			return false;
		}
		return true;
	}

	function admin_options() {
		echo '<h3>'.$this->method_title.'</h3>';
		if ( $this->is_valid_for_use() ) {
			echo '<table class="form-table">';
			$this->generate_settings_html();
			echo '</table>';
			echo'<table class="form-table">
				<tr valign="top">
					<th class="titledesc" scope="row">
						<label >'.__( 'Log Directory:', 'wc-gateway-lguplus' ).'</label>
					</th>
					<td class="forminp">'
						.dirname( __FILE__ )."/lgdacom/log".
						'<p class="description">
						'.__( 'Please use this log directory in the mall.conf file.', 'wc-gateway-lguplus' ).'
						</p>
					</td>
				</tr>
			</table>';
		} else {
			echo '<div class="inline error"><p><strong>' . __( 'Gateway Disabled', 'wc-gateway-lguplus' ) .'</strong>: ' . __( 'This payment method does not support your store currency.', 'wc-gateway-lguplus' ) . '</p></div>';
		}
	}

	function receipt( $order_id ) {
		$order = new WC_Order( $order_id );

		echo __( '<p> Please wait while your payment is being processed. </p>', 'wc-gateway-lguplus' );

		$currency_check = $this->currency_check( $order, $this->method );

		if ( $currency_check ) {
			echo $this->lguplus_form( $order_id );
		} else {
			echo sprintf( __( 'Your currency (%s) is not supported by this payment method.', 'wc-gateway-lguplus' ), get_post_meta( $order->id, '_order_currency', true ) );
		}
	}		

	function get_lguplus_args( $order ) {
		global $woocommerce;

		$order_id = $order->id;

		$this->billing_phone = $order->billing_phone;

		if ( sizeof( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $item ) {
				if ( $item[ 'qty' ] ) {
					$item_name = $item[ 'name' ];
				}
			}
		}

		$currency = get_woocommerce_currency();
		if ( $currency == 'KRW' ) {
			$currency = 'WON';
		}

		if ( $this->langcode == 'Auto' ) {
			$langcode = $this->get_langcode( get_locale() );
		} else {
			$langcode = $this->langcode;
		}

		$check_hash =
			array(
				'LGD_MID'				=> ($this->testmode=='yes')?'t'.$this->mertid:$this->mertid,
				'LGD_OID'				=> $order->id,
				'LGD_AMOUNT'			=> (int)$order->order_total,
				'LGD_TIMESTAMP'			=> $this->get_timestamp(),
			);

		if ( !$this->check_mobile() ) {
			$lguplus_args =
				array(
					'xpayListener'				=> 'xpayLite',
					'CST_PLATFORM'				=> ($this->testmode=='yes')?'test':'service',
					'LGD_CUSTOM_USABLEPAY'		=> $this->method,
					'LGD_MID'					=> ($this->testmode=='yes')?'t'.$this->mertid:$this->mertid,
					'LGD_OID'					=> $order->id,
					'LGD_BUYER'					=> $this->get_name_lang($order->billing_first_name, $order->billing_last_name),
					'LGD_BUYERPHONE'			=> $order->billing_phone,
					'LGD_BUYERADDRESS'			=> $order->billing_address_1.$order->billing_address_2,
					'LGD_PRODUCTINFO'			=> sanitize_text_field( $item_name ),
					'LGD_AMOUNT'				=> (int)$order->order_total,
					'LGD_BUYEREMAIL'			=> $order->billing_email,
					'LGD_CUSTOM_SKIN'			=> $this->form_style,
					'LGD_TIMESTAMP'				=> $this->get_timestamp(),
					'LGD_HASHDATA'				=> $this->get_hashdata( $check_hash, $this->mertkey ),
					'LGD_VERSION'				=> 'PHP_XPay_lite_1.0',
					'LGD_CUSTOM_FIRSTPAY'		=> $this->method,
					'LGD_NOTEURL'				=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=noteurl' ),
					'LGD_CASNOTEURL'			=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=cas_noteurl' ),
					'LGD_RESPURL'				=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=response' ),
					'LGD_CANCURL'				=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=response' ),
					'LGD_MODE'					=> ($this->testmode=='yes')?'test':'service',
					'LGD_TID'					=> '',
					'LGD_PAYTYPE'				=> $this->method,
					'LGD_PAYDATE'				=> '',
					'LGD_FINANCECODE'			=> '',
					'LGD_FINANCENAME'			=> '',
					'LGD_FINANCEAUTHNUM'		=> '',
					'LGD_ACCOUNTNUM'			=> '',
					'LGD_RESPCODE'				=> '',
					'LGD_RESPMSG'				=> '',
				);
		} else {
			$lguplus_args =
				array(
					'CST_PLATFORM'				=> ($this->testmode=='yes')?'test':'service',
					'CST_WINDOW_TYPE'			=> 'submit',
					'LGD_CUSTOM_USABLEPAY'		=> $this->method,
					'LGD_PAYTYPE'				=> $this->method,
					'CST_MID'					=> ($this->testmode=='yes')?'t'.$this->mertid:$this->mertid,
					'LGD_MID'					=> ($this->testmode=='yes')?'t'.$this->mertid:$this->mertid,
					'LGD_OID'					=> $order->id,
					'LGD_PRODUCTINFO'			=> sanitize_text_field( $item_name ),
					'LGD_AMOUNT'				=> (int)$order->order_total,
					'LGD_BUYER'					=> $this->get_name_lang($order->billing_first_name, $order->billing_last_name),
					'LGD_BUYEREMAIL'			=> $order->billing_email,
					'LGD_BUYERPHONE'			=> $order->billing_phone,
					'LGD_BUYERADDRESS'			=> $order->billing_address_1.$order->billing_address_2,
					'LGD_CUSTOM_SKIN'			=> $this->form_style,
					'LGD_CUSTOM_PROCESSTYPE'	=> 'TWOTR',
					'LGD_TIMESTAMP'				=> $this->get_timestamp(),
					'LGD_HASHDATA'				=> $this->get_hashdata( $check_hash, $this->mertkey ),
					'LGD_RETURNURL'				=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_returnurl' ),
					'LGD_NOTEURL'				=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_noteurl' ),
					'LGD_VERSION'				=> 'PHP_SmartXPay_1.0',
					'LGD_CUSTOM_ROLLBACK'		=> 'N',
					'LGD_KVPMISPNOTEURL'		=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_noteurl' ),
					'LGD_KVPMISPWAPURL'			=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_wapurl' ),
					'LGD_KVPMISPCANCELURL'		=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_cancelurl' ),
					'LGD_MTRANSFERNOTEURL'		=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_mtnoteurl' ),
					'LGD_KVPMISPAUTOAPPYN'		=> 'Y',
					'LGD_CASNOTEURL'			=> home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_casnoteurl' ),
					'LGD_ENCODING'				=> 'UTF-8',
					'LGD_RESPCODE'				=> '',
					'LGD_RESPMSG'				=> '',
					'LGD_PAYKEY'				=> '',
				);

			session_start();
			$_SESSION['PAYREQ_MAP'] = $lguplus_args;
		}

		$lguplus_args = apply_filters( 'woocommerce_lguplus_args', $lguplus_args );

		return $lguplus_args;
	}

	function lguplus_scripts() {
		if ($_SERVER['SERVER_PORT']==443) {
			if ($this->testmode=='yes') {
				$script_url = 'https://xpay.lgdacom.net:7443/xpay/js/xpay_ub_utf-8.js';
			} else {
				$script_url = 'https://xpay.lgdacom.net/xpay/js/xpay_ub_utf-8.js';
			}
		} else {
			if ($this->testmode=='yes') {
				$script_url = 'https://xpay.lgdacom.net:7080/xpay/js/xpay_ub_utf-8.js';
			} else {
				$script_url = 'https://xpay.lgdacom.net/xpay/js/xpay_ub_utf-8.js';
			}
		}
		if ( !$this->check_mobile() ) {
			wp_register_script( 'lguplus_script', $script_url, array( 'jquery' ), '1.0.0', false );
		} else {
			wp_register_script( 'lguplus_script', 'https://xpay.uplus.co.kr/xpay/js/xpay_crossplatform.js', array( 'jquery' ), '1.0.0', false );
		}

		wp_enqueue_script( 'lguplus_script' );
	}

	function currency_check( $order, $method ) {
		$currency = get_post_meta( $order->id, '_order_currency', true );

		if ( in_array( $method, apply_filters( 'woocommerce_lguplus_supported_methods', array( '104' ) ) ) ) {
			if ( $currency == 'USD' ) {
				return true;
			} else {
				return false;
			}
		} else {
			if ( $currency == 'KRW' ) {
				return true;
			} else {
				return false;
			}
		}
	}

    function lguplus_form( $order_id ) {
		global $woocommerce;

		$order = new WC_Order( $order_id );

		$lguplus_args = $this->get_lguplus_args( $order );

		$lguplus_args_array = array();

		foreach ($lguplus_args as $key => $value) {
			//$lguplus_args_array[] = esc_attr( $key ).'<input type="text" style="width:150px;" id="'.esc_attr( $key ).'" name="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" /><br>';
			$lguplus_args_array[] = '<input type="hidden" name="'.esc_attr( $key ).'" id="'.esc_attr( $key ).'" value="'.esc_attr( $value ).'" />';
		}

		$lguplus_form = "
		<form method='post' id='LGD_PAYINFO' name='LGD_PAYINFO'>".implode( '', $lguplus_args_array )." </form>";

		if ( ! $this->check_mobile() ) {
			$lguplus_form = $lguplus_form."<script type='text/javascript'>
			function doPay_ActiveX(){
				ret = xpay_check(document.getElementById('LGD_PAYINFO'), document.getElementById('LGD_MODE').value);
				document.getElementById('LGD_PAYINFO').action = document.getElementById('LGD_RESPURL').value;

				if (ret=='00') {
					var LGD_RESPCODE        = dpop.getData('LGD_RESPCODE');
					var LGD_RESPMSG         = dpop.getData('LGD_RESPMSG');

					if( '0000' == LGD_RESPCODE ) {
						var LGD_TID             = dpop.getData('LGD_TID');
						var LGD_OID             = dpop.getData('LGD_OID');
						var LGD_PAYTYPE         = dpop.getData('LGD_PAYTYPE');
						var LGD_PAYDATE         = dpop.getData('LGD_PAYDATE');
						var LGD_FINANCECODE     = dpop.getData('LGD_FINANCECODE');
						var LGD_FINANCENAME     = dpop.getData('LGD_FINANCENAME');
						var LGD_FINANCEAUTHNUM  = dpop.getData('LGD_FINANCEAUTHNUM');
						var LGD_ACCOUNTNUM      = dpop.getData('LGD_ACCOUNTNUM');
						var LGD_BUYER           = dpop.getData('LGD_BUYER');
						var LGD_BUYERPHONE      = dpop.getData('LGD_BUYERPHONE');
						var LGD_BUYERADDRESS    = dpop.getData('LGD_BUYERADDRESS');
						var LGD_PRODUCTINFO     = dpop.getData('LGD_PRODUCTINFO');
						var LGD_AMOUNT          = dpop.getData('LGD_AMOUNT');
						var LGD_NOTEURL_RESULT  = dpop.getData('LGD_NOTEURL_RESULT');

						document.getElementById('LGD_RESPCODE').value = LGD_RESPCODE;
						document.getElementById('LGD_RESPMSG').value = LGD_RESPMSG;
						document.getElementById('LGD_TID').value = LGD_TID;
						document.getElementById('LGD_OID').value = LGD_OID;
						document.getElementById('LGD_PAYTYPE').value = LGD_PAYTYPE;
						document.getElementById('LGD_PAYDATE').value = LGD_PAYDATE;
						document.getElementById('LGD_FINANCECODE').value = LGD_FINANCECODE;
						document.getElementById('LGD_FINANCENAME').value = LGD_FINANCENAME;
						document.getElementById('LGD_FINANCEAUTHNUM').value = LGD_FINANCEAUTHNUM;
						document.getElementById('LGD_ACCOUNTNUM').value = LGD_ACCOUNTNUM;
						document.getElementById('LGD_BUYER').value = LGD_BUYER;
						document.getElementById('LGD_BUYERPHONE').value = LGD_BUYERPHONE;
						document.getElementById('LGD_BUYERADDRESS').value = LGD_BUYERADDRESS;
						document.getElementById('LGD_PRODUCTINFO').value = LGD_PRODUCTINFO;
						document.getElementById('LGD_AMOUNT').value = LGD_AMOUNT;

						document.getElementById('LGD_PAYINFO').submit();
					} else {
						document.getElementById('LGD_RESPCODE').value = LGD_RESPCODE;
						document.getElementById('LGD_RESPMSG').value = LGD_RESPMSG;
						alert('".__( 'Payment failed.', 'wc-gateway-lguplus' )."' + LGD_RESPMSG );
						document.getElementById('LGD_PAYINFO').submit();
					}
				} else {
					document.getElementById('LGD_RESPCODE').value = '99'+ret;
					LGD_RESPMSG = '".__( 'Failed to install ActiveX.', 'wc-gateway-lguplus' )."';
					document.getElementById('LGD_RESPMSG').value = LGD_RESPMSG;
					alert(LGD_RESPMSG);
					document.getElementById('LGD_PAYINFO').submit();
				}
			}
			doPay_ActiveX();
			</script>";
		} else {
			$lguplus_form = $lguplus_form."<script type='text/javascript'>
			var LGD_window_type = document.getElementById('CST_WINDOW_TYPE').value;

			function launchCrossPlatform(){
				lgdwin = open_paymentwindow(document.getElementById('LGD_PAYINFO'), document.getElementById('CST_PLATFORM').value, LGD_window_type);
			}

			function getFormObject() {
				return document.getElementById('LGD_PAYINFO');
			}
			launchCrossPlatform();
			</script>";
		}
		


		return $lguplus_form;
	}

	function process_payment( $order_id ) {
		global $woocommerce;

		$order = new WC_Order( $order_id );

		if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
			return array(
				'result' 	=> 'success',
				'redirect'	=> add_query_arg( 'key', $order->order_key, add_query_arg( 'order', $order_id, get_permalink( woocommerce_get_page_id( 'pay' ) ) ) )
			);
		} else {
			return array(
				'result' 	=> 'success',
				'redirect'	=> $order->get_checkout_payment_url( true )
			);
		}
	}

    function check() {
		if ( ! empty( $_POST ) && in_array( $_POST[ 'LGD_PAYTYPE' ], $this->require ) ) {
			header('HTTP/1.1 200 OK');
			$_POST = stripslashes_deep( $_POST );
			do_action( "valid_". $this->id , $_POST );
		}
	}

	function pg_response( $posted ) {
		global $woocommerce;

		if ( isset( $posted[ 'LGD_OID' ] ) ) {
			$order_id = $this->get_orderid( $posted[ 'LGD_OID' ] );

			$order = new WC_Order( $order_id );

			if ( $this->check_mobile() ) {
				$add_mobile_meta = get_post_meta( $order->id, '_payment_method_title', true );
				if ( ! stripos( $add_mobile_meta, __( ' (Mobile)', 'wc-gateway-lguplus' ) ) ) {
					$add_mobile_meta = $add_mobile_meta.__( ' (Mobile)', 'wc-gateway-lguplus' );
				}
				update_post_meta( $order->id, '_payment_method_title', $add_mobile_meta );
			}
		} else {
			session_start();

			if ( isset( $_SESSION[ 'PAYREQ_MAP' ] ) ) {
				$payReqMap		= $_SESSION[ 'PAYREQ_MAP' ];
				$LGD_OID		= $payReqMap[ 'LGD_OID' ];

				$order_id = $this->get_orderid( $LGD_OID );

				$order = new WC_Order( $order_id );

				if ( $this->check_mobile() ) {
					$add_mobile_meta = get_post_meta( $order->id, '_payment_method_title', true );
					if ( ! stripos( $add_mobile_meta, __( ' (Mobile)', 'wc-gateway-lguplus' ) ) ) {
						$add_mobile_meta = $add_mobile_meta.__( ' (Mobile)', 'wc-gateway-lguplus' );
					}
					update_post_meta( $order->id, '_payment_method_title', $add_mobile_meta );
				}
			}
		}
	}

	function check_response() {
		global $woocommerce;

		if ( isset( $_REQUEST[ 'resp_type' ] ) ) {
			switch ( $_REQUEST[ 'resp_type' ] ) {
				case 'response' :
					if ( isset( $_REQUEST[ 'xpayListener' ] ) && $_REQUEST[ 'xpayListener' ] == 'xpayLite' ):
						global $woocommerce;
						@ob_clean();
						header('HTTP/1.1 200 OK');
						do_action('lguplus_process_response', $_REQUEST);
					endif;
					break;
				case 'noteurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_noteurl', $_REQUEST);
					break;
				case 'cas_noteurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_cas_noteurl', $_REQUEST);
					break;
				case 'smart_response' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_smart_response', $_REQUEST);
					break;
				case 'smart_noteurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_noteurl', $_REQUEST);
					break;
				case 'smart_mtnoteurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_smart_mtnoteurl', $_REQUEST);
					break;
				case 'smart_returnurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_smart_returnurl', $_REQUEST);
					break;
				case 'smart_wapurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_smart_wapurl', $_REQUEST);
					break;
				case 'smart_cancelurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_smart_cancelurl', $_REQUEST);
					break;
				case 'smart_casnoteurl' :
					global $woocommerce;
					@ob_clean();
					header('HTTP/1.1 200 OK');
					do_action('lguplus_process_cas_noteurl', $_REQUEST);
					break;
				default :
					break;
			}
		}
	}

	function process_response( $params ) {
		global $woocommerce;

		if ( !empty( $params[ 'LGD_RESPCODE' ] ) ) {
			$LGD_RESPCODE 		= $params[ 'LGD_RESPCODE' ];
			$LGD_RESPMSG 		= $params[ 'LGD_RESPMSG' ];
			$LGD_TID 			= $params[ 'LGD_TID' ];
			$LGD_OID 			= $params[ 'LGD_OID' ];
			$LGD_PAYTYPE		= $params[ 'LGD_PAYTYPE' ];
			$LGD_PAYDATE 		= $params[ 'LGD_PAYDATE' ];
			$LGD_FINANCECODE 	= $params[ 'LGD_FINANCECODE' ];
			$LGD_FINANCENAME 	= $params[ 'LGD_FINANCENAME' ];
			$LGD_FINANCEAUTHNUM = $params[ 'LGD_FINANCEAUTHNUM' ];
			$LGD_ACCOUNTNUM  	= $params[ 'LGD_ACCOUNTNUM' ];
			$LGD_BUYER			= $params[ 'LGD_BUYER' ];
			$LGD_PRODUCTINFO 	= $params[ 'LGD_PRODUCTINFO' ];
			$LGD_AMOUNT 		= $params[ 'LGD_AMOUNT' ];

			$order_id = $this->get_orderid( $LGD_OID );
			$order = new WC_Order( $order_id );

			if ( $LGD_RESPCODE == '0000' ) {
				if ( $LGD_PAYTYPE == 'SC0040' ) {
					$order->update_status( 'pending' );
					update_post_meta( $order->id, '_lguplus_tid', $LGD_TID );
					update_post_meta( $order->id, '_lguplus_bankname', $LGD_FINANCENAME );
					update_post_meta( $order->id, '_lguplus_bankcode', $LGD_FINANCECODE );
					update_post_meta( $order->id, '_lguplus_bankaccount', $LGD_ACCOUNTNUM );
					$order->add_order_note( sprintf( __( 'Waiting for payment. Payment method: %s. Bank Name: %s. Bank Account: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_FINANCENAME, $LGD_ACCOUNTNUM, $LGD_TID, $this->get_timestamp() ) );
				}

				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks'))))
						);
				} else {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
						);
				}

				$woocommerce->cart->empty_cart();
				wp_redirect( $return[ 'redirect' ] );
				exit;
			} elseif ( $LGD_AMOUNT != (int)$order->get_total() ) {
				$order->update_status( 'on-hold', sprintf( __( 'Failed to verify integrity of payment. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
				$cart_url = $woocommerce->cart->get_cart_url();
				wp_redirect($cart_url);
				exit;
			} else {
				if ( $LGD_RESPCODE == 'S053' ) {
					$cart_url = $woocommerce->cart->get_cart_url();
					wp_redirect($cart_url);
					exit;
				} else {
					$order->update_status( 'failed', sprintf( __( 'Payment failed. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
					$cart_url = $woocommerce->cart->get_cart_url();
					wp_redirect($cart_url);
					exit;
				}
			}
		} else {
			wp_die( 'LG U+ Payment Request Failure' );
		}
	}

	function process_smart_response( $params ) {
		global $woocommerce;

		$configPath = dirname( __FILE__ )."/lgdacom";

		$CST_PLATFORM               = $params[ 'CST_PLATFORM' ];
		$CST_MID                    = $params[ 'CST_MID' ];
		$LGD_MID                    = $params[ 'LGD_MID' ];
		$LGD_PAYKEY                 = $params[ 'LGD_PAYKEY' ];

		require_once($configPath."/XPayClient.php");

		$xpay = &new XPayClient( $configPath, $CST_PLATFORM );
		$xpay->Init_TX( $LGD_MID );
		$xpay->Set( 'LGD_TXNAME', 'PaymentByKey' );
		$xpay->Set( 'LGD_PAYKEY', $LGD_PAYKEY );

		if ( $xpay->TX() ) {
			$LGD_RESPCODE		= $xpay->Response_Code() ;
			$LGD_RESPMSG		= $xpay->Response_Msg();
			$LGD_TID			= $xpay->Response( 'LGD_TID', 0 );
			$LGD_MID			= $xpay->Response( 'LGD_MID', 0 );
			$LGD_OID			= $xpay->Response( 'LGD_OID', 0 );
			$LGD_PAYTYPE		= $xpay->Response( 'LGD_PAYTYPE', 0 );
			$LGD_PAYDATE		= $xpay->Response( 'LGD_PAYDATE', 0 );
			$LGD_FINANCECODE	= $xpay->Response( 'LGD_FINANCECODE', 0 );
			$LGD_FINANCENAME	= $xpay->Response( 'LGD_FINANCENAME', 0 );
			$LGD_FINANCEAUTHNUM	= $xpay->Response( 'LGD_FINANCEAUTHNUM', 0 );
			$LGD_ACCOUNTNUM		= $xpay->Response( 'LGD_ACCOUNTNUM', 0 );
			$LGD_BUYER			= $xpay->Response( 'LGD_BUYER', 0 );
			$LGD_PRODUCTINFO	= $xpay->Response( 'LGD_PRODUCTINFO', 0 );
			$LGD_AMOUNT			= $xpay->Response( 'LGD_AMOUNT', 0 );
			$LGD_TRANSAMOUNT 	= $xpay->Response( 'LGD_TRANSAMOUNT', 0 );
			$LGD_ESCROWYN		= $xpay->Response( 'LGD_ESCROWYN', 0 );
			$LGD_CARDNUM		= $xpay->Response( 'LGD_CARDNUM', 0 );

			$order_id = $this->get_orderid( $LGD_OID );
			$order = new WC_Order( $order_id );

			$add_mobile_meta = get_post_meta( $order->id, '_payment_method_title', true );
			if ( ! stripos( $add_mobile_meta, __( ' (Mobile)', 'wc-gateway-lguplus' ) ) ) {
				$add_mobile_meta = $add_mobile_meta.__( ' (Mobile)', 'wc-gateway-lguplus' );
			}
			update_post_meta( $order->id, '_payment_method_title', $add_mobile_meta );

			if ( $LGD_RESPCODE == '0000' ) {
				if ( $LGD_PAYTYPE == 'SC0040' ) {
					$order->update_status( 'pending' );
					update_post_meta( $order->id, '_lguplus_tid', $LGD_TID );
					update_post_meta( $order->id, '_lguplus_bankname', $LGD_FINANCENAME );
					update_post_meta( $order->id, '_lguplus_bankcode', $LGD_FINANCECODE );
					update_post_meta( $order->id, '_lguplus_bankaccount', $LGD_ACCOUNTNUM );
					$order->add_order_note( sprintf( __( 'Waiting for payment. Payment method: %s. Bank Name: %s. Bank Account: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_FINANCENAME, $LGD_ACCOUNTNUM, $LGD_TID, $this->get_timestamp() ) );
				} elseif ( $LGD_PAYTYPE == 'SC0010' || $LGD_PAYTYPE == 'SC0060' ) {
					$order->payment_complete();
					$order->add_order_note( sprintf( __( 'Payment notification received. Payment method: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_TID, $this->get_timestamp() ) );
				}

				$isDBOK = true;
				if( !$isDBOK ) {
					$xpay->Rollback( sprintf( __( 'Rollback sequence initiated due to failure of updating store DB. LG U+ TID: %s. LG U+ MID: %s. LG U+ OID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $tid, $mid, $oid, $this->get_timestamp() ) );
					echo sprintf( __( 'TX Rollback Response Code: %s.', 'wc-gateway-lguplus' ), $LGD_RESPCODE );
					echo sprintf( __( 'TX Rollback Response Message: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG );

					if( $xpay->Response_Code() == '0000' ) {
						echo __( 'Auto-cancel sequence successfully initiated.', 'wc-gateway-lguplus' );
					} else {
						echo __( 'Auto-cancel sequence has failed to initiate.', 'wc-gateway-lguplus' );
					}
				}

				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks'))))
						);
				} else {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
						);
				}

				$woocommerce->cart->empty_cart();
				wp_redirect( $return[ 'redirect' ] );
				exit;

			} elseif ( $LGD_AMOUNT != (int)$order->get_total() ) {
				$order->update_status( 'on-hold', sprintf( __( 'Failed to verify integrity of payment. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
				$cart_url = $woocommerce->cart->get_cart_url();
				wp_redirect($cart_url);
				exit;
			} else {
				if ( $LGD_RESPCODE == 'S053' ) {
					$cart_url = $woocommerce->cart->get_cart_url();
					wp_redirect($cart_url);
					exit;
				} else {
					$order->update_status( 'failed', sprintf( __( 'Payment failed. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
					$cart_url = $woocommerce->cart->get_cart_url();
					wp_redirect($cart_url);
					exit;
				}
			}
		} else {
			wp_die( 'LG U+ Payment Request Failure' );
		}
	}

	function process_noteurl( $params ) {
		global $woocommerce;
		if ( !empty( $params[ 'LGD_OID' ] ) ) {
			$order_id = $this->get_orderid( $params[ 'LGD_OID' ] );
			$order = new WC_Order( $order_id );

			if ( $order == null ) {
				$resultMSG = sprintf( __( 'Failed to process notification. No payment request found. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() );
				echo $resultMSG;
				exit;
			} else {
				$this->id = get_post_meta( $order->id, '_payment_method', true );
				$this->init_settings();
				$this->mertkey = $this->get_option( 'mertkey' );
			}
		} else {
			wp_die( 'LG U+ Payment Notification Failure' );
		}

		if ( !empty( $params[ 'LGD_RESPCODE' ] ) ) {
			$LGD_RESPCODE            = $params[ 'LGD_RESPCODE' ];
			$LGD_RESPMSG             = $params[ 'LGD_RESPMSG' ];
			$LGD_MID                 = $params[ 'LGD_MID' ];
			$LGD_OID                 = $params[ 'LGD_OID' ];
			$LGD_AMOUNT              = $params[ 'LGD_AMOUNT' ];
			$LGD_TID                 = $params[ 'LGD_TID' ];
			$LGD_PAYTYPE             = $params[ 'LGD_PAYTYPE' ];
			$LGD_PAYDATE             = $params[ 'LGD_PAYDATE' ];
			$LGD_HASHDATA            = $params[ 'LGD_HASHDATA' ];
			$LGD_FINANCECODE         = $params[ 'LGD_FINANCECODE' ];
			$LGD_FINANCENAME         = $params[ 'LGD_FINANCENAME' ];
			$LGD_ESCROWYN            = $params[ 'LGD_ESCROWYN' ];
			$LGD_TRANSAMOUNT         = $params[ 'LGD_TRANSAMOUNT' ];
			$LGD_EXCHANGERATE        = $params[ 'LGD_EXCHANGERATE' ];
			$LGD_CARDNUM             = $params[ 'LGD_CARDNUM' ];
			$LGD_CARDINSTALLMONTH    = $params[ 'LGD_CARDINSTALLMONTH' ];
			$LGD_CARDNOINTYN         = $params[ 'LGD_CARDNOINTYN' ];
			$LGD_TIMESTAMP           = $params[ 'LGD_TIMESTAMP' ];
			$LGD_FINANCEAUTHNUM      = $params[ 'LGD_FINANCEAUTHNUM' ];
			$LGD_PAYTELNUM           = $params[ 'LGD_PAYTELNUM' ];
			$LGD_ACCOUNTNUM          = $params[ 'LGD_ACCOUNTNUM' ];
			$LGD_CASTAMOUNT          = $params[ 'LGD_CASTAMOUNT' ];
			$LGD_CASCAMOUNT          = $params[ 'LGD_CASCAMOUNT' ];
			$LGD_CASFLAG             = $params[ 'LGD_CASFLAG' ];
			$LGD_CASSEQNO            = $params[ 'LGD_CASSEQNO' ];
			$LGD_CASHRECEIPTNUM      = $params[ 'LGD_CASHRECEIPTNUM' ];
			$LGD_CASHRECEIPTSELFYN   = $params[ 'LGD_CASHRECEIPTSELFYN' ];
			$LGD_CASHRECEIPTKIND     = $params[ 'LGD_CASHRECEIPTKIND' ];
			$LGD_OCBSAVEPOINT        = $params[ 'LGD_OCBSAVEPOINT' ];
			$LGD_OCBTOTALPOINT       = $params[ 'LGD_OCBTOTALPOINT' ];
			$LGD_OCBUSABLEPOINT      = $params[ 'LGD_OCBUSABLEPOINT' ];
		
			$LGD_BUYER               = $params[ 'LGD_BUYER' ];
			$LGD_PRODUCTINFO         = $params[ 'LGD_PRODUCTINFO' ];
			$LGD_BUYERID             = $params[ 'LGD_BUYERID' ];
			$LGD_BUYERADDRESS        = $params[ 'LGD_BUYERADDRESS' ];
			$LGD_BUYERPHONE          = $params[ 'LGD_BUYERPHONE' ];
			$LGD_BUYEREMAIL          = $params[ 'LGD_BUYEREMAIL' ];
			$LGD_BUYERSSN            = $params[ 'LGD_BUYERSSN' ];
			$LGD_PRODUCTCODE         = $params[ 'LGD_PRODUCTCODE' ];
			$LGD_RECEIVER            = $params[ 'LGD_RECEIVER' ];
			$LGD_RECEIVERPHONE       = $params[ 'LGD_RECEIVERPHONE' ];
			$LGD_DELIVERYINFO        = $params[ 'LGD_DELIVERYINFO' ];

			$LGD_MERTKEY = $this->mertkey;
			$LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY); 

			$resultMSG = sprintf( __( 'Payment notification received but an error has occured. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() );

			if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) {
				if ( $LGD_RESPCODE == '0000' ) {
					if ( $order->status == 'completed' ) {
						$order->add_order_note( sprintf( __( 'Payment notification received but order is already completed. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
						$resultMSG = 'OK';
					} else {
						$order->payment_complete();
						$order->add_order_note( sprintf( __( 'Payment notification received. Payment method: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_TID, $this->get_timestamp() ) );
						$resultMSG = 'OK';
					}
				} else {
					$order->update_status( 'failed', sprintf( __( 'Payment notification received but failed to complete process. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
					$resultMSG = 'OK';
				}
			} else {
				$order->update_status( 'failed', sprintf( __( 'Payment notification received but hash data does not match. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
				$resultMSG = __( 'Payment notification received but hash data does not match.', 'wc-gateway-lguplus' );
			}
			echo $resultMSG;
			exit;
		} else {
			wp_die( 'LG U+ Payment Notification Failure' );
		}
	}

	function process_cas_noteurl( $params ) {
		global $woocommerce;

		if ( !empty( $params[ 'LGD_OID' ] ) ) {
			$order_id = $this->get_orderid( $params[ 'LGD_OID' ] );
			$order = new WC_Order( $order_id );

			if ( $order == null ) {
				$resultMSG = sprintf( __( 'Failed to process CAS notification. No payment request found. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() );
				echo $resultMSG;
				exit;
			} else {
				$this->id = get_post_meta( $order->id, '_payment_method', true );
				$this->init_settings();
				$this->mertkey = $this->get_option( 'mertkey' );
			}
		} else {
			wp_die( 'LG U+ CAS Notification Failure' );
		}

		if ( !empty( $params[ 'LGD_RESPCODE' ] ) ) {
		    $LGD_RESPCODE            = $params[ 'LGD_RESPCODE' ];
		    $LGD_RESPMSG             = $params[ 'LGD_RESPMSG' ];
		    $LGD_MID                 = $params[ 'LGD_MID' ];
		    $LGD_OID                 = $params[ 'LGD_OID' ];
		    $LGD_AMOUNT              = $params[ 'LGD_AMOUNT' ];
		    $LGD_TID                 = $params[ 'LGD_TID' ];
		    $LGD_PAYTYPE             = $params[ 'LGD_PAYTYPE' ];
		    $LGD_PAYDATE             = $params[ 'LGD_PAYDATE' ];
		    $LGD_HASHDATA            = $params[ 'LGD_HASHDATA' ];
		    $LGD_FINANCECODE         = $params[ 'LGD_FINANCECODE' ];
		    $LGD_FINANCENAME         = $params[ 'LGD_FINANCENAME' ];
		    $LGD_ESCROWYN            = $params[ 'LGD_ESCROWYN' ];
		    $LGD_TIMESTAMP           = $params[ 'LGD_TIMESTAMP' ];
		    $LGD_ACCOUNTNUM          = $params[ 'LGD_ACCOUNTNUM' ];
		    $LGD_CASTAMOUNT          = $params[ 'LGD_CASTAMOUNT' ];
		    $LGD_CASCAMOUNT          = $params[ 'LGD_CASCAMOUNT' ];
		    $LGD_CASFLAG             = $params[ 'LGD_CASFLAG' ];
		    $LGD_CASSEQNO            = $params[ 'LGD_CASSEQNO' ];
		    $LGD_CASHRECEIPTNUM      = $params[ 'LGD_CASHRECEIPTNUM' ];
		    $LGD_CASHRECEIPTSELFYN   = $params[ 'LGD_CASHRECEIPTSELFYN' ];
		    $LGD_CASHRECEIPTKIND     = $params[ 'LGD_CASHRECEIPTKIND' ];
			$LGD_PAYER     			 = $params[ 'LGD_PAYER' ];			
		    $LGD_BUYER               = $params[ 'LGD_BUYER' ];
		    $LGD_PRODUCTINFO         = $params[ 'LGD_PRODUCTINFO' ];
		    $LGD_BUYERID             = $params[ 'LGD_BUYERID' ];
		    $LGD_BUYERADDRESS        = $params[ 'LGD_BUYERADDRESS' ];
		    $LGD_BUYERPHONE          = $params[ 'LGD_BUYERPHONE' ];
		    $LGD_BUYEREMAIL          = $params[ 'LGD_BUYEREMAIL' ];
		    $LGD_BUYERSSN            = $params[ 'LGD_BUYERSSN' ];
		    $LGD_PRODUCTCODE         = $params[ 'LGD_PRODUCTCODE' ];
		    $LGD_RECEIVER            = $params[ 'LGD_RECEIVER' ];
		    $LGD_RECEIVERPHONE       = $params[ 'LGD_RECEIVERPHONE' ];
		    $LGD_DELIVERYINFO        = $params[ 'LGD_DELIVERYINFO' ];
		
			$LGD_MERTKEY = $this->mertkey;
			$LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY); 

			$resultMSG = sprintf( __( 'CAS notification received but an error has occured. Timestamp: %s', 'wc-gateway-lguplus' ), $this->get_timestamp() );

			if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) {
				if ( $LGD_RESPCODE == '0000' ) {
					if ( $LGD_CASFLAG == 'R' ) {
						if ( $order->status == 'completed' ) {
							$order->add_order_note( sprintf( __( 'CAS notification received but order is already completed. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
							$resultMSG = 'OK';
						} else {
							$order->update_status( 'pending' );
							$order->add_order_note( sprintf( __( 'CAS notification received. Account successfully assigned. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
							$resultMSG = 'OK';
						}
					} elseif ( $LGD_CASFLAG == 'I' ) {
						$order->payment_complete();
						$order->add_order_note( sprintf( __( 'CAS notification received. Payment method: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_TID, $this->get_timestamp() ) );
						$resultMSG = 'OK';
					} elseif ( $LGD_CASFLAG == 'C' ) {
						$order->update_status( 'cancelled' );
						$order->add_order_note( sprintf( __( 'CAS notification received. Account transfer cancelled. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
						$resultMSG = 'OK';
					}
				} else {
					$order->update_status( 'failed', sprintf( __( 'CAS notification received but failed to complete process. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
					$resultMSG = 'OK';
				}
			} else {
				$order->update_status( 'failed', sprintf( __( 'CAS notification received but hash data does not match. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp(), $LGD_TIMESTAMP, $LGD_HASHDATA, $LGD_HASHDATA2 ) );
				$resultMSG = __( 'CAS notification received but hash data does not match.', 'wc-gateway-lguplus' );
			}
			echo $resultMSG;
			exit;
		} else {
			wp_die( 'LG U+ CAS Notification Failure' );
		}
	}

	function process_smart_mtnoteurl( $params ) {
		global $woocommerce;

		if ( !empty( $params[ 'LGD_OID' ] ) ) {
			$order_id = $this->get_orderid( $params[ 'LGD_OID' ] );
			$order = new WC_Order( $order_id );

			if ( $order == null ) {
				$resultMSG = sprintf( __( 'Failed to process MT notification. No payment request found. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() );
				echo $resultMSG;
				exit;
			} else {
				$this->id = get_post_meta( $order->id, '_payment_method', true );
				$this->init_settings();
				$this->mertkey = $this->get_option( 'mertkey' );

				$add_mobile_meta = get_post_meta( $order->id, '_payment_method_title', true );
				if ( ! stripos( $add_mobile_meta, __( ' (Mobile)', 'wc-gateway-lguplus' ) ) ) {
					$add_mobile_meta = $add_mobile_meta.__( ' (Mobile)', 'wc-gateway-lguplus' );
				}
				update_post_meta( $order->id, '_payment_method_title', $add_mobile_meta );
			}
		} else {
			wp_die( 'LG U+ MT Notification Failure' );
		}

		if ( !empty( $params[ 'LGD_RESPCODE' ] ) ) {
		    $LGD_RESPCODE            = $params[ 'LGD_RESPCODE' ];
		    $LGD_RESPMSG             = $params[ 'LGD_RESPMSG' ];
		    $LGD_MID                 = $params[ 'LGD_MID' ];
		    $LGD_OID                 = $params[ 'LGD_OID' ];
		    $LGD_AMOUNT              = $params[ 'LGD_AMOUNT' ];
		    $LGD_TID                 = $params[ 'LGD_TID' ];
		    $LGD_PAYTYPE             = $params[ 'LGD_PAYTYPE' ];
		    $LGD_PAYDATE             = $params[ 'LGD_PAYDATE' ];
		    $LGD_HASHDATA            = $params[ 'LGD_HASHDATA' ];
		    $LGD_FINANCECODE         = $params[ 'LGD_FINANCECODE' ];
		    $LGD_FINANCENAME         = $params[ 'LGD_FINANCENAME' ];
		    $LGD_ESCROWYN            = $params[ 'LGD_ESCROWYN' ];
		    $LGD_TRANSAMOUNT         = $params[ 'LGD_TRANSAMOUNT' ];
		    $LGD_EXCHANGERATE        = $params[ 'LGD_EXCHANGERATE' ];
		    $LGD_CARDNUM             = $params[ 'LGD_CARDNUM' ];
		    $LGD_CARDINSTALLMONTH    = $params[ 'LGD_CARDINSTALLMONTH' ];
		    $LGD_CARDNOINTYN         = $params[ 'LGD_CARDNOINTYN' ];
		    $LGD_TIMESTAMP           = $params[ 'LGD_TIMESTAMP' ];
		    $LGD_FINANCEAUTHNUM      = $params[ 'LGD_FINANCEAUTHNUM' ];
		    $LGD_PAYTELNUM           = $params[ 'LGD_PAYTELNUM' ];
		    $LGD_ACCOUNTNUM          = $params[ 'LGD_ACCOUNTNUM' ];
		    $LGD_ACCOUNTOWNER        = $params[ 'LGD_ACCOUNTOWNER' ];
		    $LGD_CASTAMOUNT          = $params[ 'LGD_CASTAMOUNT' ];
		    $LGD_CASCAMOUNT          = $params[ 'LGD_CASCAMOUNT' ];
		    $LGD_CASFLAG             = $params[ 'LGD_CASFLAG' ];
		    $LGD_CASSEQNO            = $params[ 'LGD_CASSEQNO' ];
		    $LGD_CASHRECEIPTNUM      = $params[ 'LGD_CASHRECEIPTNUM' ];
		    $LGD_CASHRECEIPTSELFYN   = $params[ 'LGD_CASHRECEIPTSELFYN' ];
		    $LGD_CASHRECEIPTKIND     = $params[ 'LGD_CASHRECEIPTKIND' ];
		    $LGD_OCBSAVEPOINT        = $params[ 'LGD_OCBSAVEPOINT' ];
		    $LGD_OCBTOTALPOINT       = $params[ 'LGD_OCBTOTALPOINT' ];
		    $LGD_OCBUSABLEPOINT      = $params[ 'LGD_OCBUSABLEPOINT' ];
		    $LGD_BUYER               = $params[ 'LGD_BUYER' ];
		    $LGD_PRODUCTINFO         = $params[ 'LGD_PRODUCTINFO' ];
		    $LGD_BUYERID             = $params[ 'LGD_BUYERID' ];
		    $LGD_BUYERADDRESS        = $params[ 'LGD_BUYERADDRESS' ];
		    $LGD_BUYERPHONE          = $params[ 'LGD_BUYERPHONE' ];
		    $LGD_BUYEREMAIL          = $params[ 'LGD_BUYEREMAIL' ];
		    $LGD_BUYERSSN            = $params[ 'LGD_BUYERSSN' ];
		    $LGD_PRODUCTCODE         = $params[ 'LGD_PRODUCTCODE' ];
		    $LGD_RECEIVER            = $params[ 'LGD_RECEIVER' ];
		    $LGD_RECEIVERPHONE       = $params[ 'LGD_RECEIVERPHONE' ];
		    $LGD_DELIVERYINFO        = $params[ 'LGD_DELIVERYINFO' ];
		
			$LGD_MERTKEY = $this->mertkey;
			$LGD_HASHDATA2 = md5($LGD_MID.$LGD_OID.$LGD_AMOUNT.$LGD_RESPCODE.$LGD_TIMESTAMP.$LGD_MERTKEY); 

			$resultMSG = sprintf( __( 'MT notification received but an error has occured. Timestamp: %s', 'wc-gateway-lguplus' ), $this->get_timestamp() );

			if ( $LGD_HASHDATA2 == $LGD_HASHDATA ) {
				if ( $LGD_RESPCODE == '0000' ) {
					$order->payment_complete();
					$order->add_order_note( sprintf( __( 'MT notification received. Payment method: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_TID, $this->get_timestamp() ) );
					$resultMSG = 'OK';
				} else {
					$order->update_status( 'failed', sprintf( __( 'MT notification received but failed to complete process. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
					$resultMSG = 'OK';
				}
			} else {
				$order->update_status( 'failed', sprintf( __( 'MT notification received but hash data does not match. Response message: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $LGD_RESPMSG, $this->get_timestamp() ) );
				$resultMSG = __( 'MT notification received but hash data does not match.', 'wc-gateway-lguplus' );
			}
			echo $resultMSG;
			exit;
		} else {
			wp_die( 'LG U+ MT Notification Failure' );
		}
	}

	function process_smart_wapurl( $params ) {
		global $woocommerce;

		session_start();

		if ( !isset( $_SESSION[ 'PAYREQ_MAP' ] ) ) {
			echo __( 'Session has expired or the request is invalid.', 'wc-gateway-lguplus' );
			exit;
		}
		$payReqMap = $_SESSION[ 'PAYREQ_MAP' ];

		$LGD_OID		= $payReqMap[ 'LGD_OID' ];
		$LGD_PAYTYPE	= $payReqMap[ 'LGD_PAYTYPE' ];
		$LGD_TID		= $payReqMap[ 'LGD_TID' ];

		if ( !empty( $payReqMap[ 'LGD_OID' ] ) ) {
			$order_id = $this->get_orderid( $LGD_OID );
			$order = new WC_Order( $order_id );

			if ( $order == null ) {
				$resultMSG = sprintf( __( 'Failed to process WAP notification. No payment request found. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() );
				echo $resultMSG;
				exit;
			} else {
				$this->id = get_post_meta( $order->id, '_payment_method', true );
				$this->init_settings();
				$this->mertkey = $this->get_option( 'mertkey' );

				$add_mobile_meta = get_post_meta( $order->id, '_payment_method_title', true );
				if ( ! stripos( $add_mobile_meta, __( ' (Mobile)', 'wc-gateway-lguplus' ) ) ) {
					$add_mobile_meta = $add_mobile_meta.__( ' (Mobile)', 'wc-gateway-lguplus' );
				}
				update_post_meta( $order->id, '_payment_method_title', $add_mobile_meta );
			}
		} else {
			wp_die( 'LG U+ WAP Notification Failure' );
		}

		switch ( $order->status ) {
			case 'processing' :
			case 'completed' :
				if ( $order->status == 'processing' ) {
					$order->add_order_note( sprintf( __( 'WAP notification received but order is already processing. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
				} else {
					$order->add_order_note( sprintf( __( 'WAP notification received but order is already completed. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_timestamp() ) );
				}

				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks'))))
						);
				} else {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
						);
				}

				$woocommerce->cart->empty_cart();
				wp_redirect( $return[ 'redirect' ] );
				exit;
				break;
			case 'pending' :
				$order->payment_complete();
				$order->add_order_note( sprintf( __( 'WAP notification received. Payment method: %s. LG U+ TID: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $this->get_paymethod_txt( $LGD_PAYTYPE ), $LGD_TID, $this->get_timestamp() ) );

				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> add_query_arg('key', $order->order_key, add_query_arg('order', $order_id, get_permalink(woocommerce_get_page_id('thanks'))))
						);
				} else {
					$return = array(
						'result' 	=> 'success',
						'redirect'	=> $this->get_return_url( $order )
						);
				}

				$woocommerce->cart->empty_cart();
				wp_redirect( $return[ 'redirect' ] );
				exit;
				break;
			default :
				$order->add_order_note( sprintf( __( 'WAP notification received but order status is invalid. Current status: %s. Timestamp: %s.', 'wc-gateway-lguplus' ), $order->status, $this->get_timestamp() ) );
				wp_die( 'LG U+ WAP Notification Failure' );
				exit;
				break;
		}
	}

	function process_smart_returnurl( $params ) {
		global $woocommerce;

		$payres_url = home_url( '/wc-api/wc_gateway_lguplus?resp_type=smart_response' );

		session_start();

		if ( !isset( $_SESSION[ 'PAYREQ_MAP' ] ) ) {
			echo __( 'Session has expired or the request is invalid.', 'wc-gateway-lguplus' );
			exit;
		}
		$LGD_RESPCODE	= $params[ 'LGD_RESPCODE' ];
		$LGD_RESPMSG	= $params[ 'LGD_RESPMSG' ];
		$LGD_PAYKEY		= '';

		$payReqMap = $_SESSION[ 'PAYREQ_MAP' ];

		if ( $LGD_RESPCODE == '0000' ){
			echo "<html>";
			echo "	<head>";
			echo "	    <script type='text/javascript'>";
			echo "		    function setLGDResult() {";
			echo "				document.getElementById('LGD_PAYINFO').submit();";
			echo "			}";
			echo "    	</script>";
			echo "	</head>";
			echo "	<body onload='setLGDResult();'>";

			$LGD_PAYKEY = $params[ 'LGD_PAYKEY' ];
			$payReqMap[ 'LGD_RESPCODE' ]	= $LGD_RESPCODE;
			$payReqMap[ 'LGD_RESPMSG' ]		= $LGD_RESPMSG;
			$payReqMap[ 'LGD_PAYKEY' ]		= $LGD_PAYKEY;
			echo '	 	<form method="post" name="LGD_PAYINFO" id="LGD_PAYINFO" action="'.$payres_url.'">';
				foreach ($payReqMap as $key => $value) {
			echo "			<input type='hidden' name='$key' id='$key' value='$value'>";
				  }
			echo '		</form>';
			echo '	</body>';
			echo '</html>';
		} elseif ( $LGD_RESPCODE == 'S053' ) {
			$LGD_OID = $params[ 'LGD_OID' ];

			$order_id = $this->get_orderid($LGD_OID);
			$order = new WC_Order( $order_id );

			$cart_url = $woocommerce->cart->get_cart_url();
			wp_redirect($cart_url);
		}
		exit;
	}

	function process_smart_cancelurl( $params ) 
	{
		global $woocommerce;

		$LGD_OID = $params[ 'LGD_OID' ];

		$order_id = $this->get_orderid($oid);
		$order = new WC_Order( $order_id );

		$cart_url = $woocommerce->cart->get_cart_url();
		wp_redirect($cart_url);
		exit;
	}

	function trim_trailing_zeros( $number ) {
		if ( strpos( $number,'.' ) !== false ) {
			$number = rtrim( $number, '0' );
		}
		return rtrim( $number, '.' );
	}

	function get_langcode( $locale ) {
		if ( $locale == 'ko_KR' ) {
			return 'KR';
		} else {
			return 'US';
		}
	}

	function get_name_lang( $first_name, $last_name ) {
		if ( get_locale() == 'ko_KR' ) {
			return $last_name.$first_name;
		} else {
			return $first_name.' '.$last_name;
		}
	}

	function get_orderid( $oid ) {
		$order_id = $oid;
		return $order_id;
	}

	function check_mobile() {
		$agent = $_SERVER['HTTP_USER_AGENT'];
		
		if( stripos ( $agent, "iPod" ) || stripos( $agent, "iPhone" ) || stripos( $agent, "iPad" ) || stripos( $agent, "Android" ) ) {
			return true;
		} else {
			return false;
		}
	}

	function get_paymethod_txt( $pay_type ) {
		switch ( $pay_type ) {
			case 'SC0010' :
				return __( 'Credit Card', 'wc-gateway-lguplus' );
			break;
			case 'SC0030' :
				return __( 'Account Transfer', 'wc-gateway-lguplus' );
			break;
			case 'SC0040' :
				return __( 'Virtual Account', 'wc-gateway-lguplus' );
			break;
			case 'SC0060' :
				return __( 'Mobile Payment', 'wc-gateway-lguplus' );
			break;
			default :
				return '';
		}
	}

	function get_hashdata( $params, $mertkey ) {
		$LGD_HASHDATA = md5( $params['LGD_MID'].$params['LGD_OID'].$params['LGD_AMOUNT'].$params['LGD_TIMESTAMP'].$mertkey );
		return $LGD_HASHDATA ;
	}

	function get_timestamp() {
		$_time = date('YmdHis');
		return $_time;
	}
}

require_once dirname( __FILE__ ) . '/card.php';
require_once dirname( __FILE__ ) . '/transfer.php';
require_once dirname( __FILE__ ) . '/virtual.php';
require_once dirname( __FILE__ ) . '/mobile.php';
}