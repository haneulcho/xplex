<?php
/**
 * Plugin Name: WooCommerce Shipping Method for Korean
 * Plugin URI: http://www.qustreet.com/woopay
 * Description: Helps you customize WooCommerce Shipping Method 
 * Version: 1.0.0
 * Author: planet8
 * Author URI: http://www.planet8.co
 *
 *
 * Copyright: © 2013 Moon (orpheous09@gmail.com)
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 *
 * @package    WooCommerce Shipping KR
 * @author     Moon
 * @since      1.0
 */

/**
 * Plugin Setup
 *
 * @since 1.0
 */

// Check if WooCommerce is active
if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) )
	return;
	

 

//if ( ! class_exists( 'WC_Shipping_Method' ) ) {
//	require_once(WP_PLUGIN_DIR.'/woocommerce/classes/class-wc-settings-api.php');
//	require_once(WP_PLUGIN_DIR.'/woocommerce/classes/shipping/class-wc-shipping-method.php');
//
//}

/**
 * Main Class
 *
 * @since 1.0
 */
function woocommerce_shipping_kr_init() {
	load_plugin_textdomain('WC_Ship_Kr', false, dirname( plugin_basename( __FILE__ ) ) . '/');

	class WC_Ship_Kr extends WC_Shipping_Method {
	//class WC_Ship_Kr extends woocommerce_shipping_method {
		/**
		 * __construct function.
		 *
		 * @access public
		 * @return void
		 */
		function __construct() {
	        $this->id 						= 'flat_rate_kr';
	        $this->method_title 			= __( 'Flat rate_kr', 'woocommerce' );
	
			$this->flat_rate_option 		= 'woocommerce_flat_rates_kr';
			$this->admin_page_heading 		= __( 'Flat Rates KR', 'woocommerce' );
			$this->admin_page_description 	= __( 'Flat rates KR let you define a standard rate per item, or per order.', 'woocommerce' );
	
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );
			add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_flat_rates_kr' ) );
//			add_filter( 'woocommerce_ship_class_kr_cost',  array( &$this,'get_cost_per_class'), 30, 2 );

	
	    	$this->init();
	    }
	
	
	    /**
	     * init function.
	     *
	     * @access public
	     * @return void
	     */
	    function init() {
	    	// Load the form fields.
			$this->init_form_fields();
	
			// Load the settings.
			$this->init_settings();
	
			// Define user set variables
	        $this->enabled		  = $this->settings['enabled'];
			$this->title 		  = $this->settings['title'];
			$this->availability   = $this->settings['availability'];
			$this->countries 	  = $this->settings['countries'];
			$this->type 		  = $this->settings['type'];
			$this->tax_status	  = $this->settings['tax_status'];
			$this->cost 		  = $this->settings['cost'];
			$this->cost_per_order = isset( $this->settings['cost_per_order'] ) ? $this->settings['cost_per_order'] : '';
			$this->fee 			  = $this->settings['fee'];
			$this->minimum_fee 	  = isset( $this->settings['minimum_fee'] ) ? $this->settings['minimum_fee'] : '';
			$this->options 		  = isset( $this->settings['options'] ) ? $this->settings['options'] : '';
	
			// Get options
			$this->options		  = (array) explode( "\n", $this->options );
	
			// Load Flat rates
			$this->get_flat_rates();
	    }
	
	
	    /**
	     * Initialise Gateway Settings Form Fields
	     *
	     * @access public
	     * @return void
	     */
	    function init_form_fields() {
	    	global $woocommerce;
	
	    	$this->form_fields = array(
				'enabled' => array(
								'title' 		=> __( 'Enable/Disable', 'woocommerce' ),
								'type' 			=> 'checkbox',
								'label' 		=> __( 'Enable this shipping method', 'woocommerce' ),
								'default' 		=> 'no',
							),
				'title' => array(
								'title' 		=> __( 'Method Title', 'woocommerce' ),
								'type' 			=> 'text',
								'description' 	=> __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
								'default'		=> __( 'Flat Rate KR', 'woocommerce' ),
							),
				'cost_per_order' => array(
								'title' 		=> __( 'Cost per order', 'woocommerce' ),
								'type' 			=> 'text',
								'description'	=> __( 'Enter a cost per order, e.g. 5.00. Leave blank to disable.', 'woocommerce' ),
								'default'		=> '',
							),
				'availability' => array(
								'title' 		=> __( 'Method availability', 'woocommerce' ),
								'type' 			=> 'select',
								'default' 		=> 'all',
								'class'			=> 'availability',
								'options'		=> array(
									'all' 		=> __( 'All allowed countries', 'woocommerce' ),
									'specific' 	=> __( 'Specific Countries', 'woocommerce' ),
								),
							),
				'countries' => array(
								'title' 		=> __( 'Specific Countries', 'woocommerce' ),
								'type' 			=> 'multiselect',
								'class'			=> 'chosen_select',
								'css'			=> 'width: 450px;',
								'default' 		=> '',
								'options'		=> $woocommerce->countries->countries,
							),
				'type' => array(
								'title' 		=> __( 'Calculation Type', 'woocommerce' ),
								'type' 			=> 'select',
								'description' 	=> '',
								'default' 		=> 'order',
								'options' 		=> array(
									'class' 	=> __( 'Per Class - charge shipping for each shipping class in an order', 'woocommerce' ),
								),
							),
				'tax_status' => array(
								'title' 		=> __( 'Tax Status', 'woocommerce' ),
								'type' 			=> 'select',
								'description' 	=> '',
								'default' 		=> 'taxable',
								'options'		=> array(
									'taxable' 	=> __( 'Taxable', 'woocommerce' ),
									'none' 		=> __( 'None', 'woocommerce' ),
								),
							),
				'cost' => array(
								'title' 		=> __( 'Default Cost', 'woocommerce' ),
								'type' 			=> 'text',
								'description'	=> __( 'Cost excluding tax. Enter an amount, e.g. 2.50.', 'woocommerce' ),
								'default' 		=> '',
							),
				'fee' => array(
								'title' 		=> __( 'Default Handling Fee', 'woocommerce' ),
								'type' 			=> 'text',
								'description'	=> __( 'Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank to disable.', 'woocommerce' ),
								'default'		=> '',
							),
				'minimum_fee' => array(
								'title' 		=> __( 'Minimum Fee', 'woocommerce' ),
								'type' 			=> 'text',
								'description'	=> __( 'Enter a minimum fee amount. Fee\'s less than this will be increased. Leave blank to disable.', 'woocommerce' ),
								'default'		=> '',
							),
				'options' => array(
								'title' 		=> __( 'Shipping Options', 'woocommerce' ),
								'type' 			=> 'textarea',
								'description'	=> __( 'Optional extra shipping options with additional costs (one per line). Example: <code>Option Name|Cost|Per-order (yes or no)</code>. Example: <code>Priority Mail|6.95|yes</code>. If per-order is set to no, it will use the "Calculation Type" setting.', 'woocommerce' ),
								'default'		=> '',
							),
				);
	
	    }
	
	
	    /**
	     * calculate_shipping function.
	     *
	     * @access public
	     * @param array $package (default: array())
	     * @return void
	     */
	    function calculate_shipping( $package = array() ) {
	    	global $woocommerce;
	    	$this->rates = array();
	    	$cost_per_order = ( isset( $this->cost_per_order ) && ! empty( $this->cost_per_order ) ) ? $this->cost_per_order : 0;
	
	    	if ( $this->type == 'order' ) {
	    		$shipping_total = $this->order_shipping( $package );
	
	    		$rate = array(
					'id' 	=> $this->id,
					'label' => $this->title,
					'cost' 	=> $shipping_total + $cost_per_order,
				);
			} elseif ( $this->type == 'class' ) {
				$shipping_total = $this->class_shipping( $package );
	
	    		$rate = array(
					'id' 	=> $this->id,
					'label' => $this->title,
					'cost' 	=> $shipping_total + $cost_per_order,
				);
			} elseif ( $this->type == 'item' ) {
				$costs = $this->item_shipping( $package );
				$costs['order'] = $cost_per_order;
	
				$rate = array(
					'id' 		=> $this->id,
					'label' 	=> $this->title,
					'cost' 		=> $costs,
					'calc_tax' 	=> 'per_item',
				);
			}
	
			// Register the rate
			$this->add_rate( $rate );
	
			// Add any extra rates
			if ( sizeof( $this->options ) > 0) foreach ( $this->options as $option ) {
	
				$this_option = preg_split( '~\s*\|\s*~', trim( $option ) );
	
				if ( sizeof( $this_option ) !== 3 ) continue;
	
				$extra_rate = $rate;
	
				$extra_rate['id']    = $this->id . ':' . sanitize_title($this_option[0]);
				$extra_rate['label'] = $this_option[0];
	
				$per_order_cost = ( $this_option[2] == 'yes' ) ? 1 : 0;
				$this_cost = $this_option[1];
	
				if ( is_array( $extra_rate['cost'] ) ) {
					if ( $per_order_cost ) {
						$extra_rate['cost']['order'] = $this_cost;
					} else {
						$total_quantity = 0;
	
						// Shipping per item
						foreach ( $package['contents'] as $item_id => $values )
							if ( $values['quantity'] > 0 && $values['data']->needs_shipping() )
								$total_quantity += $values['quantity'];
	
						// Per-product shipping
						$extra_rate['cost']['order'] = $this_cost * $total_quantity;
					}
				} else {
					// If using shipping per class, multiple the cost by the classes we found
					if ( ! $per_order_cost && $this->type == 'class' ) {
						$this_cost = $this_cost * $found_shipping_classes;
					}
	
					$extra_rate['cost'] = $extra_rate['cost'] + $this_cost;
				}
	
				$this->add_rate( $extra_rate );
			}
	    }
	
	
	    /**
	     * order_shipping function.
	     *
	     * @access public
	     * @param array $package
	     * @return float
	     */
	    function order_shipping( $package ) {
	    	$cost 	= null;
	    	$ma 	= null;
	
			if ( sizeof( $this->flat_rates_kr ) > 0 ) {
	
	    		$found_shipping_classes = array();
	
	    		// Find shipping classes for products in the cart
	    		if ( sizeof( $package['contents'] ) > 0 ) {
	    			foreach ( $package['contents'] as $item_id => $values ) {
	    				if ( $values['data']->needs_shipping() )
	    					$found_shipping_classes[] = $values['data']->get_shipping_class();
	    			}
	    		}
	
	    		$found_shipping_classes = array_unique( $found_shipping_classes );
	
	    		// Find most expensive class (if found)
	    		foreach ( $found_shipping_classes as $shipping_class ) {
	    			if ( isset( $this->flat_rates_kr[ $shipping_class ] ) ) {
	    				if ( $this->flat_rates_kr[ $shipping_class ]['cost'] > $cost ) {
	    					$cost 	= $this->flat_rates_kr[ $shipping_class ]['cost'];
	    					$ma	= $this->flat_rates_kr[ $shipping_class ]['ma'];
	    				}
	    			} else {
	    				// No matching classes so use defaults
	    				if ( $this->cost > $cost ) {
	    					$cost 	= $this->cost;
	    					$ma	= $this->ma;
	    				}
	    			}
	    		}
	
			}
	
			// Default rates
			if ( is_null( $cost ) ) {
				$cost = $this->cost;
				$ma = $this->ma;
			}
	
			// Shipping for whole order
			return $cost + $this->get_fee( $ma, $package['contents_cost'] );
	    }
	
	
	    /**
	     * class_shipping function.
	     *
	     * @access public
	     * @param array $package
	     * @return float
	     */
	    function class_shipping( $package ) {
			$cost 	= null;
	    	$ma 	= null;

			if ( sizeof( $this->flat_rates_kr ) > 0 ) {
	    		$found_shipping_classes = array();
/*
	    	echo "<pre> Moon Class_Shipping\n";
	    	print_r($package['contents']);
	    	echo "\n Moon Class_Shipping End\n </pre>";
*/	
	    		// Find shipping classes for products in the cart. Store prices too, so we can calc a ma for the class.
	    		if ( sizeof( $package['contents'] ) > 0 ) {

	    			foreach ( $package['contents'] as $item_id => $values ) {
//		    			echo "<pre> Moon Item Data(Ship)\n";
//		    			print_r($values);
//		    			echo "\n Moon Item Data End(Ship)\n </pre>";

	    				if ( $values['data']->needs_shipping() ) {
	    					if ( isset( $found_shipping_classes[ $values['data']->get_shipping_class() ] ) ) {
	    						$found_shipping_classes[ $values['data']->get_shipping_class() ] = ( $values['data']->get_price() * $values['quantity'] ) + $found_shipping_classes[ $values['data']->get_shipping_class() ];
	    					} else {
	    						$found_shipping_classes[ $values['data']->get_shipping_class() ] = ( $values['data']->get_price() * $values['quantity'] );
	    					}
	    				}

	    			}
	    		}
	
	    		$found_shipping_classes = array_unique( $found_shipping_classes );
	    		// For each found class, add up the costs and ma(Minimum Amount)s
	    		foreach ( $found_shipping_classes as $shipping_class => $class_price ) {
	    			if ( isset( $this->flat_rates_kr[ $shipping_class ] ) ) {
	    				if ( $class_price < $this->flat_rates_kr[ $shipping_class ]['ma']) {
	    					$cost 	+= $this->flat_rates_kr[ $shipping_class ]['cost'];
//	    					echo "<pre> Moon Class Shipping Cost\n";
//	    					print_r($shipping_class);
//	    					echo "\n Moon Class Shipping Cost End\n </pre>";
	    				} else {
		    				$cost 	+= 0 ;
//	    					echo "<pre> Moon Free Class Shipping Cost\n";
//	    					print_r($shipping_class);
//	    					echo "\n Moon Free Class Shipping Cost End\n </pre>";
	    				}
	    			}
	    			else {
	    				// Class not set so we use default rate
	    				$cost 	+= $this->cost;
	    				// $ma	+= $this->get_fee( $this->ma, $class_price );
	    			}
	    			
	    		}
			}
	
			// Total
			//return $cost + $ma;
			return $cost;
	    }
	
	
	    /**
	     * item_shipping function.
	     *
	     * @access public
	     * @param array $package
	     * @return array
	     */
	    function item_shipping( $package ) {
			// Per item shipping so we pass an array of costs (per item) instead of a single value
			$costs = array();
	
			// Shipping per item
			foreach ( $package['contents'] as $item_id => $values ) {
				$_product = $values['data'];
	
				if ( $values['quantity'] > 0 && $_product->needs_shipping() ) {
					$shipping_class = $_product->get_shipping_class();
	
					if ( isset( $this->flat_rates_kr[ $shipping_class ] ) ) {
						$cost 	= $this->flat_rates_kr[ $shipping_class ]['cost'];
	    				$ma	= $this->get_fee( $this->flat_rates_kr[ $shipping_class ]['ma'], $_product->get_price() );
					} else {
						$cost 	= $this->cost;
						$ma	= $this->get_fee( $this->ma, $_product->get_price() );
					}
	
					$costs[ $item_id ] = ( ( $cost + $ma ) * $values['quantity'] );
				}
			}
	
			return $costs;
	    }
	
	
		/**
		 * Admin Panel Options
		 * - Options for bits like 'title' and availability on a country-by-country basis
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function admin_options() {
			global $woocommerce;
	    	?>
	    	<h3><?php echo $this->admin_page_heading; ?></h3>
	    	<p><?php echo $this->admin_page_description; ?></p>
	    	<table class="form-table">
	    	<?php
	    		// Generate the HTML For the settings form.
	    		$this->generate_settings_html();
	    		?>
		    	<tr valign="top">
		            <th scope="row" class="titledesc"><?php _e( 'Flat Rates KR', 'woocommerce' ); ?>:</th>
		            <td class="forminp" id="<?php echo $this->id; ?>_flat_rates">
		            	<table class="shippingrows widefat" cellspacing="0">
		            		<thead>
		            			<tr>
		            				<th class="check-column"><input type="checkbox"></th>
		            				<th class="shipping_class"><?php _e( 'Shipping Class', 'woocommerce' ); ?></th>
		        	            	<th><?php _e( 'Cost', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e( 'Cost, excluding tax.', 'woocommerce' ); ?>">[?]</a></th>
		        	            	<th><?php _e( 'Minumum Amount', 'woocommerce' ); ?> <a class="tips" data-tip="<?php _e( 'Minimum Amount', 'woocommerce' ); ?>">[?]</a></th>
		            			</tr>
		            		</thead>
		            		<tfoot>
		            			<tr>
		            				<th colspan="2"><a href="#" class="add button"><?php _e( '+ Add Flat Rate', 'woocommerce' ); ?></a></th>
		            				<th colspan="2"><small><?php _e( 'Add rates for shipping classes here &mdash; they will override the default costs defined above.', 'woocommerce' ); ?></small> <a href="#" class="remove button"><?php _e( 'Delete selected rates', 'woocommerce' ); ?></a></th>
		            			</tr>
		            		</tfoot>
		            		<tbody class="flat_rates">
		                	<?php
		                	$i = -1;
		                	if ( $this->flat_rates_kr ) {
		                		foreach ( $this->flat_rates_kr as $class => $rate ) {
			                		$i++;
	
			                		echo '<tr class="flat_rate">
			                			<td class="check-column"><input type="checkbox" name="select" /></td>
			                			<td class="flat_rate_class">
			                					<select name="'. $this->id .'_class[' . $i . ']" class="select">';
	
			                		if ( $woocommerce->shipping->get_shipping_classes() ) {
				                		foreach ( $woocommerce->shipping->get_shipping_classes() as $shipping_class ) {
				                			echo '<option value="'.$shipping_class->slug.'" '.selected($shipping_class->slug, $class, false).'>'.$shipping_class->name.'</option>';
				                		}
			                		} else {
			                			echo '<option value="">'.__('Select a class&hellip;', 'woocommerce').'</option>';
			                		}
	
					                echo '</select>
					               		</td>
					                    <td><input type="text" value="' . $rate['cost'] . '" name="'. $this->id .'_cost[' . $i . ']" placeholder="'.__( '0.00', 'woocommerce' ).'" size="4" /></td>
					                    <td><input type="text" value="' . $rate['ma'] . '" name="'. $this->id .'_ma[' . $i . ']" placeholder="'.__( '0.00', 'woocommerce' ).'" size="4" /></td>
				                    </tr>';
		                		}
		                	}
		                	?>
		                	</tbody>
		                </table>
		            </td>
		        </tr>
			</table><!--/.form-table-->
	       	<script type="text/javascript">
				jQuery(function() {
	
					jQuery('#<?php echo $this->id; ?>_flat_rates a.add').live('click', function(){
	
						var size = jQuery('#<?php echo $this->id; ?>_flat_rates tbody .flat_rate').size();
	
						jQuery('<tr class="flat_rate">\
							<td class="check-column"><input type="checkbox" name="select" /></td>\
	            			<td class="flat_rate_class">\
	            				<select name="<?php echo $this->id; ?>_class[' + size + ']" class="select">\
		               				<?php
		               				if ($woocommerce->shipping->get_shipping_classes()) :
				                		foreach ($woocommerce->shipping->get_shipping_classes() as $class) :
				                			echo '<option value="'.$class->slug.'">'.$class->name.'</option>';
				                		endforeach;
			                		else :
			                			echo '<option value="">'.__('Select a class&hellip;', 'woocommerce').'</option>';
			                		endif;
		               				?>\
		               			</select>\
		               		</td>\
		                    <td><input type="text" name="<?php echo $this->id; ?>_cost[' + size + ']" placeholder="0.00" size="4" /></td>\
		                    <td><input type="text" name="<?php echo $this->id; ?>_ma[' + size + ']" placeholder="0.00" size="4" /></td>\
	                    </tr>').appendTo('#<?php echo $this->id; ?>_flat_rates table tbody');
	
						return false;
					});
	
					// Remove row
					jQuery('#<?php echo $this->id; ?>_flat_rates a.remove').live('click', function(){
						var answer = confirm("<?php _e('Delete the selected rates?', 'woocommerce'); ?>")
						if (answer) {
							jQuery('#<?php echo $this->id; ?>_flat_rates table tbody tr td.check-column input:checked').each(function(i, el){
								jQuery(el).closest('tr').remove();
							});
						}
						return false;
					});
	
				});
			</script>
	    	<?php
	    } // End admin_options()
	
	
	    /**
	     * process_flat_rates function.
	     *
	     * @access public
	     * @return void
	     */
	    function process_flat_rates_kr() {
			// Save the rates
			$flat_rate_class = array();
			$flat_rate_cost = array();
			$flat_rate_ma = array();
			$flat_rates = array();
			if ( isset( $_POST[ $this->id . '_class'] ) ) $flat_rate_class = array_map( 'woocommerce_clean', $_POST[ $this->id . '_class'] );
			if ( isset( $_POST[ $this->id . '_cost'] ) )  $flat_rate_cost  = array_map( 'woocommerce_clean', $_POST[ $this->id . '_cost'] );
			if ( isset( $_POST[ $this->id . '_ma'] ) )   $flat_rate_ma   = array_map( 'woocommerce_clean', $_POST[ $this->id . '_ma'] );
	
			// Get max key
			$values = $flat_rate_class;
			ksort( $values );
			$value = end( $values );
			$key = key( $values );
	
			for ( $i = 0; $i <= $key; $i++ ) {
				if ( isset( $flat_rate_class[ $i ] ) && isset( $flat_rate_cost[ $i ] ) && isset( $flat_rate_ma[ $i ] ) ) {
	
					$flat_rate_cost[$i] = number_format($flat_rate_cost[$i], 2,  '.', '');
	
					// Add to flat rates array
					$flat_rates[ sanitize_title($flat_rate_class[$i]) ] = array(
						'cost' => $flat_rate_cost[ $i ],
						'ma'  => $flat_rate_ma[ $i ],
					);
				}
			}
	
			update_option( $this->flat_rate_option, $flat_rates );
	
			$this->get_flat_rates();
	    }
	
	
	    /**
	     * get_flat_rates function.
	     *
	     * @access public
	     * @return void
	     */
	    function get_flat_rates() {
	    	$this->flat_rates_kr = array_filter( (array) get_option( $this->flat_rate_option ) );
	    }
	
	    public function get_cost_per_class($package, $ship_class="")
	    {
		    $cost_array 	= array();
	    	$ma 	= null;
//	    	echo "<pre> MOON : \n" ; print_r($package); echo "</pre>";
//	    	echo "<pre> SUN : \n" ;  print_r($ship_class); echo "</pre>";
//			echo "Moon:Test";


			if ( sizeof( $this->flat_rates_kr ) > 0 ) {
	    		$found_shipping_classes = array();
	    		// Find shipping classes for products in the cart. Store prices too, so we can calc a ma for the class.
	    		if ( sizeof( $package ) > 0 ) {

	    			foreach ( $package as $item_id => $values ) {

	    				if ( $values['data']->needs_shipping() ) {
	    					if ( isset( $found_shipping_classes[ $values['data']->get_shipping_class() ] ) ) {
	    						$found_shipping_classes[ $values['data']->get_shipping_class() ] = ( $values['data']->get_price() * $values['quantity'] ) + $found_shipping_classes[ $values['data']->get_shipping_class() ];
	    					} else {
	    						$found_shipping_classes[ $values['data']->get_shipping_class() ] = ( $values['data']->get_price() * $values['quantity'] );
	    					}
	    				}

	    			}
	    		} else {
//		    		echo "<pre> MOON : \n" ; print_r($package); echo "</pre>";

	    		}
	
	    		$found_shipping_classes = array_unique( $found_shipping_classes );
	    		// For each found class, add up the costs and ma(Minimum Amount)s
	    		foreach ( $found_shipping_classes as $shipping_class => $class_price ) {
//		    		echo "<pre> MOON : \n" ; print_r($shipping_class); echo "</pre>";

	    			if ( isset( $this->flat_rates_kr[ $shipping_class ] )  && ( $shipping_class == $ship_class ) ) {
	    				if ( $class_price < $this->flat_rates_kr[ $shipping_class ]['ma']) {
	    					$cost 	+= $this->flat_rates_kr[ $shipping_class ]['cost'];
/*
	    					echo "<pre> Moon Class Shipping Cost\n";
	    					print_r($shipping_class);
	    					echo "\n Moon Class Shipping Cost End\n </pre>";
*/
	    				} else {
		    				$cost 	+= 0 ;
/*
	    					echo "<pre> Moon Free Class Shipping Cost\n";
	    					print_r($shipping_class);
	    					echo "\n Moon Free Class Shipping Cost End\n </pre>";
*/
	    				}
	    			}
	    			else {
	    				// Class not set so we use default rate
	    				$cost 	+= $this->cost;
	    				// $ma	+= $this->get_fee( $this->ma, $class_price );
	    			}
	    			
	    		}
			}
	
			// Total
			//return $cost + $ma;
			return $cost;
			
	    }
	    public function get_ship_class_cost($ship_class)
	    {
	    	$cost = 0; 
		    if ( sizeof( $this->flat_rates_kr ) > 0 ) {
		    	$cost = $this->flat_rates_kr[ $ship_class ]['cost'];
		    }
		    return $cost;
	    }
	    public function get_ship_class_ma($ship_class)
	    {
	    	$ma = 0; 
		    if ( sizeof( $this->flat_rates_kr ) > 0 ) {
		    	$ma = $this->flat_rates_kr[ $ship_class ]['ma'];
		    }
		    return $ma;
		    
	    }

	}
}

/**
 * add_flat_rate_method function.
 *
 * @package		WooCommerce/Classes/Shipping
 * @access public
 * @param array $methods
 * @return array
 */
 
add_action('woocommerce_shipping_init', 'woocommerce_shipping_kr_init');

function add_flat_rate_kr_method( $methods ) {
	$methods[] = 'WC_Ship_Kr';
	return $methods;
}

add_filter( 'woocommerce_shipping_methods', 'add_flat_rate_kr_method' );

function get_cost_per_class($package, $ship_class="")
{
	global $woocommerce;
	$cost = 0;
	$method = NULL;

	$shipping_method = $woocommerce->shipping->get_shipping_methods();
//	echo "<pre> Moon 1 : "; print_r($available_methods); echo "</pre>";

	if (count($shipping_method) <= 0 ) {
		$method = new WC_Ship_Kr();
	}elseif ( 1 === count( $shipping_method ) ) {
//		echo "<pre> Moon 1 : ";print_r($available_methods[0]);  echo "</pre>";
		$method =  $shipping_method[0];
	} else {
		foreach ( $shipping_method as $tmp_method )
		{
//		echo "<pre> Moon 1 : ";print_r($tmp_method);  echo "</pre>";
			if ( esc_attr( $tmp_method->id )  == "flat_rate_kr" ) {
				$method = $tmp_method;
				break;
			}

		}
	}
	
	if ($method != NULL) {
		$cost = $method->get_cost_per_class($package, $ship_class);
	} else {
//			echo "<pre> Moon 2 : ";  echo "</pre>";
	}

	return $cost;

}
add_filter( 'woocommerce_ship_class_kr_cost',  'get_cost_per_class', 30, 2 );

function get_info_ship_class($ship_class)
{
	global $woocommerce;
	$info_ship_class = array();
	$method = NULL;

	$shipping_method = $woocommerce->shipping->get_shipping_methods();
	if (count($shipping_method) <= 0 ) {
		$method = new WC_Ship_Kr();
	}elseif ( 1 === count( $shipping_method ) ) {
		$method =  $shipping_method[0];
	} else {
		foreach ( $shipping_method as $tmp_method )
		{
			if ( esc_attr( $tmp_method->id )  == "flat_rate_kr" ) {
				$method = $tmp_method;
				break;
			}
		}
	}
	
	if ($method != NULL) {
		$cost = $method->get_ship_class_cost($ship_class);
		$ma = $method->get_ship_class_ma($ship_class);
		$info_ship_class['cost'] = $cost;
		$info_ship_class['ma'] = $ma;
	}

	return $info_ship_class;

}
add_filter( 'woocommerce_ship_class_kr_info', 'get_info_ship_class', 30, 1 ); 


//end file