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

		// XPLEX Index Hero Img Area
		$wp_customize->add_section( 'xplex_main_heroimg_area', array(
			'title'           =>      __('XPLEX Index Hero Img Area', 'storevilla'),
			'priority'        =>      '111',
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

			// XPLEX Mobile AD Area
			$wp_customize->add_section( 'xplex_ad_area', array(
				'title'           =>      __('XPLEX Mobile AD Area', 'storevilla'),
				'priority'        =>      '115',
		    ));

		    $wp_customize->add_setting('xplex_ad_area_settings', array(
		        'default' => 'enable',
		        'capability' => 'edit_theme_options',
		        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
			));

			$wp_customize->add_control('xplex_ad_area_settings', array(
				'type' => 'radio',
				'label' => __('Enable / Disable XPLEX Mobile AD', 'storevilla'),
				'section' => 'xplex_ad_area',
				'settings' => 'xplex_ad_area_settings',
				'choices' => array(
		         'enable' => __('Enable', 'storevilla'),
		         'disable' => __('Disable', 'storevilla')
		        )
			));


			$wp_customize->add_setting( 'xplex_ad_area_image', array(
		        'default'       =>      '',
		        'sanitize_callback' => 'esc_url_raw' // done
		    ));

		    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'xplex_ad_area_image', array(
		        'section'       =>      'xplex_ad_area',
		        'label'         =>      __('Upload Mobile AD Image', 'storevilla'),
		        'type'          =>      'image',
		    )));

		    $wp_customize->add_setting('xplex_ad_area_title', array(
		        'default' => '',
		        'sanitize_callback' => 'storevilla_text_sanitize',  // done
		        'transport' => 'postMessage'
		    ));

		    $wp_customize->add_control('xplex_ad_area_title',array(
		        'type' => 'text',
						'description' => __('배너 제목을 적어주세요. (화면에 표시되지 않습니다.)', 'storevilla'),
		        'label' => __('Mobile AD Image Title', 'storevilla'),
		        'section' => 'xplex_ad_area',
		        'setting' => 'xplex_ad_area_title',
		    ));

				$wp_customize->add_setting('xplex_ad_area_link', array(
					'default' => '',
					'sanitize_callback' => 'storevilla_text_sanitize',  // done
					'transport' => 'postMessage'
				));

				$wp_customize->add_control('xplex_ad_area_link',array(
					'type' => 'text',
					'description' => __('모바일 배너 클릭시 이동할 링크를 적어주세요.', 'storevilla'),
					'label' => __('Mobile AD Link', 'storevilla'),
					'section' => 'xplex_ad_area',
					'setting' => 'xplex_ad_area_link',
				));

		// XPLEX Quick Menu Area
		$wp_customize->add_section( 'xplex_quick_menu_area', array(
			'title'           =>      __('XPLEX Quick Menu Area', 'storevilla'),
			'priority'        =>      '114',
			));

		$wp_customize->add_setting('xplex_quick_menu_area_settings', array(
			'default' => 'enable',
			'capability' => 'edit_theme_options',
			'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
		));

		$wp_customize->add_control('xplex_quick_menu_area_settings', array(
			'type' => 'radio',
			'label' => __('Enable / Disable XPLEX Quick Menu', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'settings' => 'xplex_quick_menu_area_settings',
			'choices' => array(
	         'enable' => __('Enable', 'storevilla'),
	         'disable' => __('Disable', 'storevilla')
	        )
		));

		// Quick Menu 1
		$wp_customize->add_setting('xplex_quick_menu_icon_1', array(
			'default' => 'fa fa-list-alt',
			'sanitize_callback' => 'storevilla_text_sanitize', // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_icon_1',array(
			'type' => 'text',
			'description' => '1번 퀵메뉴 아이콘으로 사용할 FontAwesome CSS 클래스명을 적어주세요. <a href="http://fontawesome.io/cheatsheet/" target="_blank">FontAwesome 클래스명 확인하기</a>',
			'label' => __('Quick Menu Icon 1', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_icon_1',
		));

		$wp_customize->add_setting('xplex_quick_menu_label_1', array(
			'default' => '모집중인 강의',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_label_1',array(
			'type' => 'text',
			'description' => __('1번 퀵메뉴 라벨을 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Label 1', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_label_1',
		));

		$wp_customize->add_setting('xplex_quick_menu_link_1', array(
			'default' => '',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_link_1',array(
			'type' => 'text',
			'description' => __('1번 퀵메뉴 클릭시 이동할 링크를 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Link 1', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_link_1',
		));

		// Quick Menu 2
		$wp_customize->add_setting('xplex_quick_menu_icon_2', array(
			'default' => 'fa fa-shopping-basket',
			'sanitize_callback' => 'storevilla_text_sanitize', // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_icon_2',array(
			'type' => 'text',
			'description' => '2번 퀵메뉴 아이콘으로 사용할 FontAwesome CSS 클래스명을 적어주세요.</a>',
			'label' => __('Quick Menu Icon 2', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_icon_2',
		));

		$wp_customize->add_setting('xplex_quick_menu_label_2', array(
			'default' => '장바구니',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_label_2',array(
			'type' => 'text',
			'description' => __('2번 퀵메뉴 라벨을 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Label 2', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_label_2',
		));

		$wp_customize->add_setting('xplex_quick_menu_link_2', array(
			'default' => '',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_link_2',array(
			'type' => 'text',
			'description' => __('2번 퀵메뉴 클릭시 이동할 링크를 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Link 2', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_link_2',
		));

		// Quick Menu 3
		$wp_customize->add_setting('xplex_quick_menu_icon_3', array(
			'default' => 'fa fa-thumbs-o-up',
			'sanitize_callback' => 'storevilla_text_sanitize', // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_icon_3',array(
			'type' => 'text',
			'description' => '3번 퀵메뉴 아이콘으로 사용할 FontAwesome CSS 클래스명을 적어주세요.</a>',
			'label' => __('Quick Menu Icon 3', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_icon_3',
		));

		$wp_customize->add_setting('xplex_quick_menu_label_3', array(
			'default' => '페이스북',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_label_3',array(
			'type' => 'text',
			'description' => __('3번 퀵메뉴 라벨을 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Label 3', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_label_3',
		));

		$wp_customize->add_setting('xplex_quick_menu_link_3', array(
			'default' => '',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_link_3',array(
			'type' => 'text',
			'description' => __('3번 퀵메뉴 클릭시 이동할 링크를 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Link 3', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_link_3',
		));

		// Quick Menu 4
		$wp_customize->add_setting('xplex_quick_menu_icon_4', array(
			'default' => 'fa fa-pencil-square-o',
			'sanitize_callback' => 'storevilla_text_sanitize', // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_icon_4',array(
			'type' => 'text',
			'description' => '4번 퀵메뉴 아이콘으로 사용할 FontAwesome CSS 클래스명을 적어주세요.</a>',
			'label' => __('Quick Menu Icon 4', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_icon_4',
		));

		$wp_customize->add_setting('xplex_quick_menu_label_4', array(
			'default' => '문의하기',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_label_4',array(
			'type' => 'text',
			'description' => __('4번 퀵메뉴 라벨을 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Label 4', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_label_4',
		));

		$wp_customize->add_setting('xplex_quick_menu_link_4', array(
			'default' => '',
			'sanitize_callback' => 'storevilla_text_sanitize',  // done
			'transport' => 'postMessage'
		));

		$wp_customize->add_control('xplex_quick_menu_link_4',array(
			'type' => 'text',
			'description' => __('4번 퀵메뉴 클릭시 이동할 링크를 적어주세요.', 'storevilla'),
			'label' => __('Quick Menu Link 4', 'storevilla'),
			'section' => 'xplex_quick_menu_area',
			'setting' => 'xplex_quick_menu_link_4',
		));

		// XPLEX Index Banner Area
		$wp_customize->add_section( 'storevilla_main_banner_area', array(
			'title'           =>      __('XPLEX Index Banner Area', 'storevilla'),
			'priority'        =>      '112',
	    ));

	    $wp_customize->add_setting('storevilla_main_banner_settings', array(
	        'default' => 'enable',
	        'capability' => 'edit_theme_options',
	        'sanitize_callback' => 'storevilla_radio_enable_disable_sanitize'  //done
		));

		$wp_customize->add_control('storevilla_main_banner_settings', array(
			'type' => 'radio',
			'label' => __('Enable / Disable XPLEX Index Banner', 'storevilla'),
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
	          'text_control' => false,
	          'link_control' => true,
	          'subtitle_control' => false
	    )));

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

	// XPLEX Copyright Section
	$wp_customize->add_section( 'xplex_footer_section', array(
		'title'           =>      __('XPLEX Footer Section', 'storevilla'),
		'priority'        =>      '116',
    ));

    $wp_customize->add_setting('xplex_footer_information', array(
         'default' => '',
         'capability' => 'edit_theme_options',
    ));

	$wp_customize->add_control('xplex_footer_information', array(
	 'type' => 'textarea',
	 'label' => __('XPLEX Information', 'storevilla'),
	 'section' => 'xplex_footer_section',
	 'settings' => 'xplex_footer_information'
	));

	$wp_customize->add_setting('xplex_footer_copyright', array(
			 'default' => '',
			 'capability' => 'edit_theme_options',
	));

	$wp_customize->add_control('xplex_footer_copyright', array(
	'type' => 'textarea',
	'label' => __('XPLEX Copyright', 'storevilla'),
	'section' => 'xplex_footer_section',
	'settings' => 'xplex_footer_copyright'
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
