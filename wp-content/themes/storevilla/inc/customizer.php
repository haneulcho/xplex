<?php
/**
 * Store Villa Theme Customizer.
 *
 * @package Store_Villa
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function storevilla_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->add_section( 'storevilla_header_options', array(
		'title'           =>      __('Header Options', 'storevilla'),
		'priority'        =>      '111',
    ));

    $wp_customize->add_setting('storevilla_top_header', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_top_header', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Top Header', 'storevilla'),
		'section' => 'storevilla_header_options',
		'settings' => 'storevilla_top_header',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));


    $wp_customize->add_section( 'storevilla_web_page_layout', array(
        'title'           =>      __('Web Page Layout Options', 'storevilla'),
        'priority'        =>      '111',
    ));

    $wp_customize->add_setting('storevilla_web_page_layout_options', array(
        'default' => 'disable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
    ));

    $wp_customize->add_control('storevilla_web_page_layout_options', array(
        'type' => 'radio',
        'label' => __('Enable / Disable Top Header', 'storevilla'),
        'section' => 'storevilla_web_page_layout',
        'settings' => 'storevilla_web_page_layout_options',
        'choices' => array(
         'enable' => __('Box Layout', 'storevilla'),
         'disable' => __('Full Width Layout', 'storevilla')
        )
    ));


	$wp_customize->add_setting('storevilla_top_left_options',  array(
        'default' =>  'nav',
        'sanitize_callback' => 'storevilla_top_header_sanitize'
    ));

    $wp_customize->add_control('storevilla_top_left_options', array(
        'section'       => 'storevilla_header_options',
        'label'         =>  __('Top Header Options', 'storevilla'),
        'type'          =>  'radio',
        'choices' => array(
             'nav' => __('Top Navigation', 'storevilla'),
             'quickinfo'     => __('Quick Info', 'storevilla'),
           )
    ));


    $wp_customize->add_setting('storevilla_email_icon', array(
        'default' => 'fa fa-envelope',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_email_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Email Icon', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_email_icon',
        'active_callback' => 'storevilla_top_header_optons',
    ));

	$wp_customize->add_setting('storevilla_email_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_email_title',array(
        'type' => 'text',
        'label' => __('Email Address', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_email_title',
        'active_callback' => 'storevilla_top_header_optons',
    ));


    $wp_customize->add_setting('storevilla_phone_icon', array(
        'default' => 'fa fa-phone',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_phone_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Phone Icon', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_phone_icon',
        'active_callback' => 'storevilla_top_header_optons',
    ));

	$wp_customize->add_setting('storevilla_phone_number', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_phone_number',array(
        'type' => 'text',
        'label' => __('Phone Number', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_phone_number',
        'active_callback' => 'storevilla_top_header_optons',
    ));


    $wp_customize->add_setting('storevilla_address_icon', array(
        'default' => 'fa fa-map-marker',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_address_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Address Icon', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_address_icon',
        'active_callback' => 'storevilla_top_header_optons',
    ));

	$wp_customize->add_setting('storevilla_map_address', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_map_address',array(
        'type' => 'text',
        'label' => __('Address', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_map_address',
        'active_callback' => 'storevilla_top_header_optons',
    ));



    $wp_customize->add_setting('storevilla_shop_open_icon', array(
        'default' => 'fa fa-clock-o',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_shop_open_icon',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Shop Open Time Icon', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_shop_open_icon',
        'active_callback' => 'storevilla_top_header_optons',
    ));

	$wp_customize->add_setting('storevilla_shop_open_time', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_shop_open_time',array(
        'type' => 'text',
        'label' => __('Shop Opening Time', 'storevilla'),
        'section' => 'storevilla_header_options',
        'setting' => 'storevilla_shop_open_time',
        'active_callback' => 'storevilla_top_header_optons',
    ));


	$wp_customize->add_section( 'storevilla_main_banner_area', array(
		'title'           =>      __('Main Banner Section Area', 'storevilla'),
		'priority'        =>      '111',
    ));

    $wp_customize->add_setting('storevilla_main_banner_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_main_banner_settings', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Main Banner Area', 'storevilla'),
		'section' => 'storevilla_main_banner_area',
		'settings' => 'storevilla_main_banner_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));


	$wp_customize->add_setting( 'storevilla_main_banner_slider', array(
      'sanitize_callback' => 'storevilla_sanitize_text',
      'default' => '',
      'transport' => 'postMessage'
    ));

    $wp_customize->add_control( new Storevilla_Pro_General_Repeater( $wp_customize, 'storevilla_main_banner_slider', array(
      'label'   => esc_html__('Main Slider Section','storevilla'),
      'section' => 'storevilla_main_banner_area',
      'description' => __('Upload Slider Image With Slider Title, Description, Link & Button Text','storevilla'),
          'image_control' => true,
          'title_control' => true,
          'text_control' => true,
          'link_control' => true,
          'subtitle_control' => true
    )));



	$wp_customize->add_section( 'storevilla_main_header_promo_area', array(
		'title'           =>      __('Header Promo Section Area', 'storevilla'),
		'priority'        =>      '112',
    ));

    $wp_customize->add_setting('storevilla_main_header_promo_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_main_header_promo_settings', array(
		'type' => 'radio',
		'label' => __('Enable / Disable Main Header Promo Area', 'storevilla'),
		'section' => 'storevilla_main_header_promo_area',
		'settings' => 'storevilla_main_header_promo_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));


	$wp_customize->add_setting( 'storevilla_promo_area_one_image', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'storevilla_promo_area_one_image', array(
        'section'       =>      'storevilla_main_header_promo_area',
        'label'         =>      __('Upload Promo One Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting('storevilla_promo_area_one_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_one_title',array(
        'type' => 'text',
        'label' => __('Promo One Title', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_one_title',
    ));

    $wp_customize->add_setting('storevilla_promo_area_one_desc', array(
        'default' => '',
       	'sanitize_callback' => 'esc_textarea', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_one_desc',array(
        'type' => 'textarea',
        'label' => __('Promo One Short Description', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_one_desc',
    ));


    $wp_customize->add_setting('storevilla_promo_area_one_link', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_one_link',array(
        'type' => 'text',
        'label' => __('Promo One Link', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_one_link',
    ));

    $wp_customize->add_setting( 'storevilla_promo_area_two_image', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'storevilla_promo_area_two_image', array(
        'section'       =>      'storevilla_main_header_promo_area',
        'label'         =>      __('Upload Promo Two Image', 'storevilla'),
        'type'          =>      'image',
    )));


    $wp_customize->add_setting('storevilla_promo_area_two_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_two_title',array(
        'type' => 'text',
        'label' => __('Promo Two Title', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_two_title',
    ));

    $wp_customize->add_setting('storevilla_promo_area_two_desc', array(
        'default' => '',
       	'sanitize_callback' => 'esc_textarea', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_two_desc',array(
        'type' => 'textarea',
        'label' => __('Promo Two Short Description', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_two_desc',
    ));


    $wp_customize->add_setting('storevilla_promo_area_two_link', array(
        'default' => '',
        'sanitize_callback' => 'esc_url_raw',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_promo_area_two_link',array(
        'type' => 'text',
        'label' => __('Promo Two Link', 'storevilla'),
        'section' => 'storevilla_main_header_promo_area',
        'setting' => 'storevilla_promo_area_two_link',
    ));


		// XPLEX Index Hero Img Area
		$wp_customize->add_section( 'xplex_main_heroimg_area', array(
			'title'           =>      __('XPLEX Index Hero Img Area', 'storevilla'),
			'priority'        =>      '112',
	    ));

	    $wp_customize->add_setting('xplex_main_heroimg_area_settings', array(
	        'default' => 'enable',
	        'capability' => 'edit_theme_options',
	        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
		));

		$wp_customize->add_control('xplex_main_heroimg_area_settings', array(
			'type' => 'radio',
			'label' => __('Enable / Disable XPLEX Index Hero Img', 'storevilla'),
			'section' => 'xplex_main_heroimg_area',
			'settings' => 'xplex_main_heroimg_area_settings',
			'choices' => array(
	         'enable' => __('Enable', 'storevilla'),
	         'disable' => __('Disable', 'storevilla')
	        )
		));


		$wp_customize->add_setting( 'xplex_main_heroimg_area_image', array(
	        'default'       =>      '',
	        'sanitize_callback' => 'esc_url_raw' // done
	    ));

	    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'xplex_main_heroimg_area_image', array(
	        'section'       =>      'xplex_main_heroimg_area',
	        'label'         =>      __('Upload XPLEX Index Hero Image', 'storevilla'),
	        'type'          =>      'image',
	    )));

	    $wp_customize->add_setting('xplex_main_heroimg_area_title', array(
	        'default' => '',
	        'sanitize_callback' => 'storevilla_text_sanitize',  // done
	        'transport' => 'postMessage'
	    ));

	    $wp_customize->add_control('xplex_main_heroimg_area_title',array(
	        'type' => 'text',
	        'label' => __('Index Image Title', 'storevilla'),
	        'section' => 'xplex_main_heroimg_area',
	        'setting' => 'xplex_main_heroimg_area_title',
	    ));

	$imagepath =  get_template_directory_uri() . '/images/';

    // Start of the WooCommerce Design Options
    $wp_customize->add_panel('storevilla_woocommerce_design_options', array(
      'capabitity' => 'edit_theme_options',
      'description' => __('Mange products and singel product page settings', 'storevilla'),
      'priority' => 113,
      'title' => __('WooCommerce Products Area', 'storevilla')
    ));


    // site archive layout setting
    $wp_customize->add_section('storevilla_woocommerce_products_settings', array(
      'priority' => 2,
      'title' => __('Products Pages Settings', 'storevilla'),
      'panel' => 'storevilla_woocommerce_design_options'
    ));

    $wp_customize->add_setting('storevilla_woocommerce_products_page_layout', array(
      'default' => 'rightsidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout'  //done
    ));

    $wp_customize->add_control(new Storevilla_Image_Radio_Control($wp_customize, 'storevilla_woocommerce_products_page_layout', array(
      'type' => 'radio',
      'label' => __('Select Products pages Layout', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_products_page_layout',
      'choices' => array(
              'leftsidebar' => $imagepath.'left-sidebar.png',
              'rightsidebar' => $imagepath.'right-sidebar.png',
            )
    )));

    $wp_customize->add_setting('storevilla_woocommerce_product_row', array(
      'default' => '3',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout_row'  //done
    ));

    $wp_customize->add_control('storevilla_woocommerce_product_row', array(
      'type' => 'select',
      'label' => __('Select Products Pages Row', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_product_row',
      'choices' => array(
              '2' => '2',
              '3' => '3',
              '4' => '4',
    )));

    $wp_customize->add_setting('storevilla_woocommerce_display_product_number', array(
      'default' => 12,
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_number_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_display_product_number', array(
      'type' => 'number',
      'label' => __('Enter Products Display Per Page', 'storevilla'),
      'section' => 'storevilla_woocommerce_products_settings',
      'settings' => 'storevilla_woocommerce_display_product_number'
    ));



    // WooCommerce Singel Product Page Settings
    $wp_customize->add_section('storevilla_woocommerce_single_products_page_settings', array(
      'priority' => 2,
      'title' => __('Single Products Page Settings', 'storevilla'),
      'panel' => 'storevilla_woocommerce_design_options'
    ));

    $wp_customize->add_setting('storevilla_woocommerce_single_products_page_layout', array(
      'default' => 'rightsidebar',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_radio_sanitize_layout'  //done
    ));

    $wp_customize->add_control(new Storevilla_Image_Radio_Control($wp_customize, 'storevilla_woocommerce_single_products_page_layout', array(
      'type' => 'radio',
      'label' => __('Select Single Products Page Layout', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_single_products_page_layout',
      'choices' => array(
              'leftsidebar' => $imagepath.'left-sidebar.png',
              'rightsidebar' => $imagepath.'right-sidebar.png',
            )
    )));

    $wp_customize->add_setting('storevilla_woocommerce_singel_product_page_upsell_title', array(
      'default' => 'Up Sell Products',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_text_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_singel_product_page_upsell_title', array(
      'type' => 'text',
      'label' => __('Enter Up Sell Title', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_singel_product_page_upsell_title'
    ));


    $wp_customize->add_setting('storevilla_woocommerce_product_page_related_title', array(
      'default' => 'Related Products',
      'capability' => 'edit_theme_options',
      'sanitize_callback' => 'storevilla_text_sanitize'  // done
    ));

    $wp_customize->add_control('storevilla_woocommerce_product_page_related_title', array(
      'type' => 'text',
      'label' => __('Enter Related Products Title', 'storevilla'),
      'section' => 'storevilla_woocommerce_single_products_page_settings',
      'settings' => 'storevilla_woocommerce_product_page_related_title'
    ));



    $wp_customize->add_section( 'storevilla_brands_logo_area', array(
		'title'           =>      __('Brands Logo Section Area', 'storevilla'),
		'priority'        =>      '114',
    ));

    $wp_customize->add_setting('storevilla_brands_area_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_brands_area_settings', array(
		'type' => 'radio',
		'label' => __('Options Enable/Disable Brands Loga Area', 'storevilla'),
		'section' => 'storevilla_brands_logo_area',
		'settings' => 'storevilla_brands_area_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));

    $wp_customize->add_setting('storevilla_brands_top_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_brands_top_title',array(
        'type' => 'text',
        'label' => __('Brands Top Title', 'storevilla'),
        'section' => 'storevilla_brands_logo_area',
        'setting' => 'storevilla_brands_top_title',
    ));

    $wp_customize->add_setting('storevilla_brands_main_title', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_brands_main_title',array(
        'type' => 'text',
        'label' => __('Brands Main Title', 'storevilla'),
        'section' => 'storevilla_brands_logo_area',
        'setting' => 'storevilla_brands_main_title',
    ));

	$wp_customize->add_setting( 'storevilla_brands_logo', array(
      'sanitize_callback' => 'storevilla_sanitize_text',
      'default' => '',
      'transport' => 'postMessage'
    ));

    $wp_customize->add_control( new Storevilla_Pro_General_Repeater( $wp_customize, 'storevilla_brands_logo', array(
      'label'   => esc_html__('Our Brands Logo Area','storevilla'),
      'section' => 'storevilla_brands_logo_area',
      'description' => __('Upload Your Brands Logo Here','storevilla'),
          'image_control' => true,
    )));


	// Services Area
	$wp_customize->add_section( 'storevilla_services_area', array(
		'title'           =>      __('Services Section Area', 'storevilla'),
		'priority'        =>      '115',
    ));

    $wp_customize->add_setting('storevilla_services_area_settings', array(
        'default' => 'enable',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
	));

	$wp_customize->add_control('storevilla_services_area_settings', array(
		'type' => 'radio',
		'label' => __('Options Enable/Disable Service Area', 'storevilla'),
		'section' => 'storevilla_services_area',
		'settings' => 'storevilla_services_area_settings',
		'choices' => array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
        )
	));

	 // Services Area One
	$wp_customize->add_setting('storevilla_services_icon_one', array(
        'default' => 'fa fa-truck',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_services_icon_one',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Service Icon One', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_services_icon_one',
    ));

	$wp_customize->add_setting('storevilla_service_title_one', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_title_one',array(
        'type' => 'text',
        'label' => __('Service One Title', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_title_one',
    ));

    $wp_customize->add_setting('storevilla_service_desc_one', array(
        'default' => '',
       	'sanitize_callback' => 'esc_textarea', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_desc_one',array(
        'type' => 'textarea',
        'label' => __('Service Area Very Short Description', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_desc_one',
    ));

    // Services Area Two
    $wp_customize->add_setting('storevilla_services_icon_two', array(
        'default' => 'fa fa-headphones',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_services_icon_two',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-headphones','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
       'label' => __('Service Icon Two', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_services_icon_two',
    ));

	$wp_customize->add_setting('storevilla_service_title_two', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // Done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_title_two',array(
        'type' => 'text',
        'label' => __('Service Two Title', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_title_two',
    ));

    $wp_customize->add_setting('storevilla_service_desc_two', array(
        'default' => '',
       	'sanitize_callback' => 'esc_textarea',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_desc_two',array(
        'type' => 'textarea',
        'label' => __('Service Area Very Short Description', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_desc_two',
    ));

    // Services Area Three
    $wp_customize->add_setting('storevilla_services_icon_three', array(
        'default' => 'fa fa-dollar',
        'sanitize_callback' => 'storevilla_text_sanitize', // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_services_icon_three',array(
        'type' => 'text',
        'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'storevilla' ), 'fa fa-dollar','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
        'label' => __('Service Icon Three', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_services_icon_three',
    ));

	$wp_customize->add_setting('storevilla_service_title_three', array(
        'default' => '',
        'sanitize_callback' => 'storevilla_text_sanitize',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_title_three',array(
        'type' => 'text',
        'label' => __('Service Three Title', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_title_three',
    ));

    $wp_customize->add_setting('storevilla_service_desc_three', array(
        'default' => '',
       	'sanitize_callback' => 'esc_textarea',  // done
        'transport' => 'postMessage'
    ));

    $wp_customize->add_control('storevilla_service_desc_three',array(
        'type' => 'textarea',
        'label' => __('Service Area Very Short Description', 'storevilla'),
        'section' => 'storevilla_services_area',
        'setting' => 'storevilla_service_desc_three',
    ));



	$wp_customize->add_section( 'storevilla_copyright', array(
		'title'           =>      __('Copyright Message Section', 'storevilla'),
		'priority'        =>      '116',
    ));

    $wp_customize->add_setting('storevilla_footer_copyright', array(
         'default' => '',
         'capability' => 'edit_theme_options',
         'sanitize_callback' => 'esc_textarea'  //done
    ));

	$wp_customize->add_control('storevilla_footer_copyright', array(
	 'type' => 'textarea',
	 'label' => __('Copyright', 'storevilla'),
	 'section' => 'storevilla_copyright',
	 'settings' => 'storevilla_footer_copyright'
	));

	// Payment Logo Section
    $wp_customize->add_section( 'paymentlogo_images', array(
		'title'           =>      __('Payment Logo Section', 'storevilla'),
		'priority'        =>      '117',
    ));

    $wp_customize->add_setting( 'paymentlogo_image_one', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw' // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_one', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_two', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_two', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_three', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_three', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_four', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'   // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_four', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_five', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'   // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_five', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));

    $wp_customize->add_setting( 'paymentlogo_image_six', array(
        'default'       =>      '',
        'sanitize_callback' => 'esc_url_raw'  // done
    ));

    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'paymentlogo_image_six', array(
        'section'       =>      'paymentlogo_images',
        'label'         =>      __('Upload Payment Logo Image', 'storevilla'),
        'type'          =>      'image',
    )));


    function storevilla_checkbox_sanitize($input) {
      if ( $input == 1 ) {
         return 1;
      } else {
         return 0;
      }
    }

    function storevilla_radio_enable_disable_sanitize($input) {
       $valid_keys = array(
         'enable' => __('Enable', 'storevilla'),
         'disable' => __('Disable', 'storevilla')
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }
    }

    function storevilla_top_header_sanitize($input) {
       $valid_keys = array(
         'nav' => __('Top Navigation', 'storevilla'),
         'quickinfo'     => __('Quick Info', 'storevilla'),
       );
       if ( array_key_exists( $input, $valid_keys ) ) {
          return $input;
       } else {
          return '';
       }
    }


    function storevilla_text_sanitize( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }

    function storevilla_radio_sanitize_layout($input) {
        $imagepath =  get_template_directory_uri() . '/images/';
        $valid_keys = array(
         'leftsidebar' => $imagepath.'left-sidebar.png',
         'rightsidebar' => $imagepath.'right-sidebar.png',
        );
        if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
        } else {
         return '';
        }
    }

    function storevilla_radio_sanitize_layout_row($input) {
      $valid_keys = array(
          '2' => '2',
          '3' => '3',
          '4' => '4',
      );
      if ( array_key_exists( $input, $valid_keys ) ) {
         return $input;
      } else {
         return '';
      }
    }

    function storevilla_number_sanitize( $int ) {
        return absint( $int );
    }

    function storevilla_sanitize_text( $input ) {
        return wp_kses_post( force_balance_tags( $input ) );
    }


    function storevilla_top_header_optons(){
     $header_optons = get_theme_mod('storevilla_top_left_options');
       if( $header_optons == 'quickinfo') {
          return true;
       }
     return false;
    }

}
add_action( 'customize_register', 'storevilla_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
**/
function storevilla_customize_preview_js() {
	wp_enqueue_script( 'storevilla_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'storevilla_customize_preview_js' );
