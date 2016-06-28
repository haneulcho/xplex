<?php
/**
 * Plugin Name: WooCommerce Address for Korean
 * Plugin URI: http://www.qustreet.com/woopay
 * Description: It makes korean address search easy within woocommerce framework.
 * Version: 1.0.0
 * Author: planet8
 * Author URI: planet8.co
 * Text Domain: woocommerce_address_korean
 * PHP version >= 5.3
 * @package planet8_woocommerce
 * @category Extension
 * @author planet8
 */

define( PLUGIN_URL, plugin_dir_url( __FILE__ ) );

add_action( 'woocommerce_init', 'woocommerce_address_init', 1 );

function woocommerce_address_init() {

    class WC_Address_Korean {

        /**
        * This function hooks basic class methods.
        *
        */
        public function __construct(){
            add_filter( 'woocommerce_billing_fields', array( $this, 'address_modify' ) );
            add_filter( 'woocommerce_shipping_fields', array( $this, 'address_modify_ship' ) );

            add_filter( 'woocommerce_get_country_locale' , array( $this, 'korea_order' ) );

            add_action( 'wp_enqueue_scripts', array( $this, 'script_and_style' ) );    
                         
            add_action( 'wp_enqueue_scripts', array( $this, 'change_checkout_js' ) );                 
        }


        function script_and_style() {
          
            wp_enqueue_script( 'WC_Address_Korean', PLUGIN_URL. 'fetch.js', array( 'jquery' ) );
      
            wp_register_style( 'WC_Address_Korean_css', plugins_url('main.css', __FILE__) );

            wp_enqueue_style( 'WC_Address_Korean_css' );   
            wp_enqueue_script( 'the_chosen_one', PLUGIN_URL. 'chosen.jquery.min.js', array( 'jquery') );
            wp_dequeue_script( 'wc-chosen' );
            wp_deregister_script( 'wc-chosen' );                 
            wp_enqueue_script( 'wc-chosen', PLUGIN_URL. 'chosen.jquery.min.js', array( 'chosen'), false, true );               
            wp_register_style( 'chosen_css', plugins_url('chosen.min.css', __FILE__) );
            wp_enqueue_style( 'chosen_css' );   
       

        }

        public function korea_order() {
            $locale = array( 
                    'KR' => array( 
                        'postcode_before_city' => false,
                        'state'     => array(
                            'required' => false 
                            ),
                        'country'   => array( 
                            'required' => false
                            ),
                        ),
                );

            return $locale;
        }

       function change_checkout_js() {
            if ( is_page( woocommerce_get_page_id( 'checkout' ) ) ){
				if ( version_compare( WOOCOMMERCE_VERSION, '2.1', '<' ) ) {
					wp_dequeue_script( 'wc-checkout' );
					wp_deregister_script( 'wc-checkout' );
	                wp_register_script( 'wc-checkout', PLUGIN_URL.'checkout.js', array( 'jquery', 'woocommerce' ) );
				}
                wp_enqueue_script( 'wc-checkout' );
            }   
        }

        public function address_modify(){
            $fields = array(

                'first_name'              => array(
                    'label'             => __( '이름', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-first' ),
                ),
                'clear_this'              => array(
                    'type'              => 'clear',
                    'label'             => __( 'blank',  'woocommerce' ),
                    'clear'             => true
                 ),
                'auto-complete'     => array(
                    'label'             => __( '주소검색', 'woocommerce' ),                    
                    'class'             => array( 'form-row-wide' ),
                    'type'              => 'auto',
                ),               

                'postcode'           => array(
                    'label'             => __( '우편번호', 'woocommerce' ),
                    'placeholder'       => __( '우편번호', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-first', 'address-field' ),
                ),                
                'city'               => array(
                    'label'             => __( '지역', 'woocommerce' ),
                    'placeholder'       => __( '지역', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-last', 'address-field' ),
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),                                
                'address_1'          => array(
                    'label'             => __( '주소', 'woocommerce' ),
                    'placeholder'       => _x( '기본주소', 'placeholder', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-wide', 'address-field' ),
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),
                'address_2'          => array(
                    'placeholder'       => _x( '상세주소', 'placeholder', 'woocommerce' ),
                    'class'             => array( 'form-row-wide', 'address-field' ),
                    'required'          => false,
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),
                           

            );
   
            $address_fields = array();

            foreach ( $fields as $key => $value ) {
                $address_fields['billing_' . $key] = $value;
            }

            $address_fields['billing_email'] = array(
                'label'         => __( '이메일', 'woocommerce' ),
                'required'      => true,
                'class'         => array( 'form-row-first' ),
                'validate'      => array( 'email' ),
            );
            $address_fields['billing_phone'] = array(
                'label'         => __( '휴대폰', 'woocommerce' ),
                'required'      => true,
                'class'         => array( 'form-row-last' ),
                'clear'         => true
            );

            return $address_fields;
                        
        }
            


        public function address_modify_ship() {
            $fields = array(

                'first_name'              => array(
                    'label'             => __( '이름', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-first' ),
                ),
                'clear_this'              => array(
                    'type'              => 'clear',
                    'label'             => __( 'black',  'woocommerce' ),
                    'clear'             => true
                ),
                'auto-complete'     => array(
                    'label'             => __( '주소검색', 'woocommerce' ),                    
                    'class'             => array( 'form-row-wide' ),
                    'type'              => 'auto2',
                ),               

                'postcode'           => array(
                    'label'             => __( '우편번호', 'woocommerce' ),
                    'placeholder'       => __( '우편번호', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-first', 'address-field' ),
                ),                
                'city'               => array(
                    'label'             => __( '지역', 'woocommerce' ),
                    'placeholder'       => __( '지역', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-last', 'address-field' ),
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),          
                'address_1'          => array(
                    'label'             => __( '주소', 'woocommerce' ),
                    'placeholder'       => _x( '기본주소', 'placeholder', 'woocommerce' ),
                    'required'          => true,
                    'class'             => array( 'form-row-wide', 'address-field' ),
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),
                'address_2'          => array(
                    'placeholder'       => _x( '상세주소', 'placeholder', 'woocommerce' ),
                    'class'             => array( 'form-row-wide', 'address-field' ),
                    'required'          => false,
                    'custom_attributes' => array(
                        'autocomplete'     => 'no'
                    )
                ),
                      
                'clear_this'              => array(
                    'type'              => 'clear',
                    'label'             => __( 'black',  'woocommerce' ),
                    'clear'             => true
                    )
            );
   
            $address_fields = array();

            foreach ( $fields as $key => $value ) {
                $address_fields['shipping_' . $key] = $value;
            }

            $address_fields['shipping_email'] = array(
                'label'         => __( '이메일', 'woocommerce' ),
                'required'      => true,
                'class'         => array( 'form-row-first' ),
                'validate'      => array( 'email' ),
            );
            $address_fields['shipping_phone'] = array(
                'label'         => __( '휴대폰', 'woocommerce' ),
                'required'      => true,
                'class'         => array( 'form-row-last' ),
                'clear'         => true
            );

            return $address_fields;            
        }

    }

    new WC_Address_Korean();
}



if ( ! function_exists( 'woocommerce_form_field' ) ) {

        function woocommerce_form_field( $key, $args, $value = null ) {
            global $woocommerce;

            $defaults = array(
                'type'              => 'text',
                'label'             => '',
                'placeholder'       => '',
                'maxlength'         => false,
                'required'          => false,
                'class'             => array(),
                'label_class'       => array(),
                'return'            => false,
                'options'           => array(),
                'custom_attributes' => array(),
                'validate'          => array(),
                'default'           => '',
            );

            $args = wp_parse_args( $args, $defaults  );

            if ( ( ! empty( $args['clear'] ) ) ) $after = '<div class="clear"></div>'; else $after = '';

            if ( $args['required'] ) {
                $args['class'][] = 'validate-required';
                $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce'  ) . '">*</abbr>';
            } else {
                $required = '';
            }

            $args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

            if ( is_null( $value ) )
                $value = $args['default'];

            // Custom attribute handling
            $custom_attributes = array();

            if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) )
                foreach ( $args['custom_attributes'] as $attribute => $attribute_value )
                    $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';

            if ( ! empty( $args['validate'] ) )
                foreach( $args['validate'] as $validate )
                    $args['class'][] = 'validate-' . $validate;

            switch ( $args['type'] ) {

            case "auto" :

                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

                if ( $args['label'] ) 
                    $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>'; 

                $field .= '                             
                            <select name="" id="wc-address-korean" class="">
                                <option value="">
                                    찾고자 하는 주소의 동/읍/면 이름을 입력하세요.
                                </option>
                            </select>

                            </p>
                            ';

                $regkey = '8b142bc02b2247ff81386538561722';
                $target = 'post';

                break;
            case "auto2" :

         
                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';


                if ( $args['label'] )
                    $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

                $field .= '                             
                            <select name="" id="address-sel2" class="">
                                <option>  찾고자 하는 주소의 동/읍/면 이름을 입력하세요. </option>
                            </select>
                            </p>
                            ';

                $field .='
                        <div class="clear"></div>
                        ';

                $regkey = '8b142bc02b2247ff81386538561722';
                $target = 'post';

                break;
         
            case "textarea" :
                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

                if ( $args['label'] )
                    $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required  . '</label>';

                $field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" cols="5" rows="2" ' . implode( ' ', $custom_attributes ) . '>'. esc_textarea( $value  ) .'</textarea>
                    </p>' . $after;

                break;
            case "checkbox" :

                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">
                        <input type="' . $args['type'] . '" class="input-checkbox" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" value="1" '.checked( $value, 1, false ) .' />
                        <label for="' . esc_attr( $key ) . '" class="checkbox ' . implode( ' ', $args['label_class'] ) .'" ' . implode( ' ', $custom_attributes ) . '>' . $args['label'] . $required . '</label>
                    </p>' . $after;

                break;
            case "password" :

                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

                if ( $args['label'] )
                    $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

                $field .= '<input type="password" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />
                    </p>' . $after;

                break;
            case "text" :

                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

                if ( $args['label'] )
                    $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . $required . '</label>';

                $field .= '<input type="text" class="input-text" name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" placeholder="' . $args['placeholder'] . '" '.$args['maxlength'].' value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />
                    </p>' . $after;

                break;
            case "select" :

                $options = '';

                if ( ! empty( $args['options'] ) )
                    foreach ( $args['options'] as $option_key => $option_text )
                        $options .= '<option value="' . esc_attr( $option_key ) . '" '. selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) .'</option>';

                    $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';

                    if ( $args['label'] )
                        $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label']. $required . '</label>';

                    $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" class="select" ' . implode( ' ', $custom_attributes ) . '>
                            ' . $options . '
                        </select>
                    </p>' . $after;

                break;
            case "clear" :
               
                $field = '<p class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) .'" id="' . esc_attr( $key ) . '_field">';
                $field .= '<div class="clear"></div>';

                break;

            default :

                $field = apply_filters( 'woocommerce_form_field_' . $args['type'], '', $key, $args, $value );

                break;
            }

            if ( $args['return'] ) return $field; else echo $field;
        }

}

add_action( 'plugins_loaded', 'get_url_now' );

function get_url_now() {
    if ( isset( $_GET['get_url'] ) ){
        $check = $_GET['get_url'];
        if ( $check ) {
            echo '"'. PLUGIN_URL .'"';
            exit;
        }
    }
}

