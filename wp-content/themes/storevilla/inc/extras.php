<?php
/**
 * Custom functions that act independently of the theme templates.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Store_Villa
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function storevilla_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// if(is_category(array('webzine','publishmarketing','dear-reader','interview','p-note','kids-book-story')) || is_singular(array('post'))) {
	// 	if(!$theme_webzine){
	// 			$theme_webzine = 'webzine';
	// 	}
	// 	$classes[] = $theme_webzine;
	// }

	// body에 xbooks 클래스 삽입
	if(is_category(array('xbooks'))) {
		$is_xbooks = true;
	} else if ( is_singular(array('post')) ) {
			global $post;
			$cats = get_the_category($post->ID);
			$cats_slug = $cats[0]->slug;
			$cats_id = $cats[0]->cat_ID;
			$cats_parent_id = $cats[0]->category_parent;

			if ( $cats_slug == 'xbooks' || $cats_id == 82 || $cats_parent_id == 82 ) {
				$is_xbooks = true;
			} else {
				$is_xbooks = false;
			}
	} else {
		$is_xbooks = false;
	}

	if($is_xbooks) {
		if(!$theme_webzine){
			$theme_webzine = 'xbooks';
		}
		$classes[] = $theme_webzine;
	}

	// body에 webzine 클래스 삽입
	if(is_category(array('webzine','publishmarketing','dear-reader','interview','p-note','kids-book-story'))) {
		$is_webzine = true;
	} else if ( is_singular(array('post')) ) {
			global $post;
			$cats = get_the_category($post->ID);
			$cats_slug = $cats[0]->slug;
			$cats_id = $cats[0]->cat_ID;
			$cats_parent_id = $cats[0]->category_parent;

			if ( $cats_slug == 'webzine' || $cats_id == 53 || $cats_parent_id == 53 ) {
				$is_webzine = true;
			} else {
				$is_webzine = false;
			}
	} else {
		$is_webzine = false;
	}

	if($is_webzine) {
		if(!$theme_webzine){
				$theme_webzine = 'webzine';
		}
		$classes[] = $theme_webzine;
	}

	if(is_singular(array( 'post','page' ))){
        global $post;
        $post_sidebar = 'leftsidebar';
        $classes[] = $post_sidebar;
  }

    if ( is_woocommerce_activated() ) {

        if( is_product_category() || is_shop() ) {
            $woo_page_layout = get_theme_mod( 'storevilla_woocommerce_products_page_layout','rightsidebar' );
            if(!$woo_page_layout){
                $woo_page_layout = 'leftsidebar';
            }
            $classes[] = $woo_page_layout;
        }

        if( is_singular('product') ) {
            $woo_page_layout = get_theme_mod( 'storevilla_woocommerce_single_products_page_layout','rightsidebar' );
            if(!$woo_page_layout){
                $woo_page_layout = 'leftsidebar';
            }
            $classes[] = $woo_page_layout;
        }
    }

	return $classes;
}
add_filter( 'body_class', 'storevilla_body_classes' );



/**
 * Query WooCommerce activation
 * @since  1.0.0
 */
if ( ! function_exists( 'is_woocommerce_activated' ) ) {
	function is_woocommerce_activated() {
		return class_exists( 'woocommerce' ) ? true : false;
	}
}

/**
 * Schema type
 * @return string schema itemprop type
 * @since  1.0.0
 */
function storevilla_html_tag_schema() {
	$schema 	= 'http://schema.org/';
	$type 		= 'WebPage';

	// Is single post
	if ( is_singular( 'post' ) ) {
		$type 	= 'Article';
	}

	// Is author page
	elseif ( is_author() ) {
		$type 	= 'ProfilePage';
	}

	// Is search results page
	elseif ( is_search() ) {
		$type 	= 'SearchResultsPage';
	}

	echo 'itemscope="itemscope" itemtype="' . esc_attr( $schema ) . esc_attr( $type ) . '"';
}

/**
 * Storevilla Woocommerce Query
*/
if ( is_woocommerce_activated() ) {

    function storevilla_woocommerce_query($product_type, $product_category, $product_number){

        $product_args       =   '';

        global $product_label_custom;

        if($product_type == 'category'){
            $product_args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id',
                     'terms'     => $product_category
                    )
                ),
                'posts_per_page' => $product_number
            );
        }

        elseif($product_type == 'latest_product'){
            $product_label_custom = __('New', 'storevilla');
            $product_args = array(
                'post_type' => 'product',
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id',
                     'terms'     => $product_category
                    )
                ),
                'posts_per_page' => $product_number
            );
        }

        elseif($product_type == 'feature_product'){
            $product_args = array(
                'post_type'        => 'product',
                'meta_key'         => '_featured',
                'meta_value'       => 'yes',
                'tax_query' => array(
                    array('taxonomy'  => 'product_cat',
                     'field'     => 'id',
                     'terms'     => $product_category
                    )
                ),
                'posts_per_page'   => $product_number
            );
        }

        elseif($product_type == 'upsell_product'){
            $product_args = array(
                'post_type'         => 'product',
                'posts_per_page'    => 10,
                'meta_key'          => 'total_sales',
                'orderby'           => 'meta_value_num',
                'posts_per_page'    => $product_number
            );
        }

        elseif($product_type == 'on_sale'){
            $product_args = array(
            'post_type'      => 'product',
            'posts_per_page'    => $product_number,
            'meta_query'     => array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'           => '_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                ),
                array( // Variable products type
                    'key'           => '_min_variation_sale_price',
                    'value'         => 0,
                    'compare'       => '>',
                    'type'          => 'numeric'
                )
            ));
        }

        return $product_args;
    }
}



/**
 * Advance WooCommerce Product Search With Category
*/
if(!function_exists ('storevilla_product_search')){

	function storevilla_product_search(){

		if ( is_woocommerce_activated() ) {

			$args = array(
				'number'     => '',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'include'    => array()
			);
			$product_categories = get_terms( 'product_cat', $args );
			$categories_show = '<option value="">'.__('All Categories','storevilla').'</option>';
			$check = '';
			if(is_search()){
				if(isset($_GET['term']) && $_GET['term']!=''){
					$check = $_GET['term'];
				}
			}
			$checked = '';
			$allcat = __('All Categories','storevilla');
			$categories_show .= '<optgroup class="sv-advance-search" label="'.$allcat.'">';
			foreach($product_categories as $category){
				if(isset($category->slug)){
					if(trim($category->slug) == trim($check)){
						$checked = 'selected="selected"';
					}
					$categories_show  .= '<option '.$checked.' value="'.$category->slug.'">'.$category->name.'</option>';
					$checked = '';
				}
			}
			$categories_show .= '</optgroup>';
			$form = '<form role="search" method="get" id="searchform"  action="' . esc_url( home_url( '/'  ) ) . '">
							<div class="sv_search_form">
							 <input type="text" value="' . get_search_query() . '" name="s" id="s" placeholder="' .__('여기에 강의 키워드를 입력하세요.','storevilla'). '" />
							 <button type="submit" id="searchsubmit"><i class="fa fa-search"></i></button>
							 <input type="hidden" name="post_type" value="product" />
							 <input type="hidden" name="taxonomy" value="product_cat" />
						 </div>
					</form>';
			echo $form;
		}
	}
}



/**
** Store_Villa payment logo section
**/

if ( ! function_exists( 'storevilla_payment_logo' ) ) {

    function storevilla_payment_logo() {
      $payment_logo_one = esc_url( get_theme_mod('paymentlogo_image_one') );
      $payment_logo_two = esc_url( get_theme_mod('paymentlogo_image_two') );
      $payment_logo_three = esc_url( get_theme_mod('paymentlogo_image_three') );
      $payment_logo_four = esc_url( get_theme_mod('paymentlogo_image_four') );
      $payment_logo_five = esc_url( get_theme_mod('paymentlogo_image_five') );
      $payment_logo_six = esc_url( get_theme_mod('paymentlogo_image_six') );
  	?>
	    <div class="payment-accept">
	      <?php if(!empty($payment_logo_one)) { ?>
	          <img src="<?php echo esc_url($payment_logo_one)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_two)) { ?>
	          <img src="<?php echo esc_url($payment_logo_two)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_three)) { ?>
	          <img src="<?php echo esc_url($payment_logo_three)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_four)) { ?>
	          <img src="<?php echo esc_url($payment_logo_four)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_five)) { ?>
	          <img src="<?php echo esc_url($payment_logo_five)?>" alt="" />
	      <?php } ?>
	      <?php if(!empty($payment_logo_six)) { ?>
	          <img src="<?php echo esc_url($payment_logo_six)?>" alt="" />
	      <?php } ?>
	    </div>
  	<?php
	}
}

/**
 * Limit word function
 */

if ( ! function_exists( 'storevilla_word_count' ) ) {

    function storevilla_word_count($string, $limit) {
        $stringtags = strip_tags($string);
        $stringtags = strip_shortcodes($stringtags);
        $words = explode(' ', $stringtags);
        return implode(' ', array_slice($words, 0, $limit));
    }
}

/* moving the comment text field to bottom */
function xplex_move_comment_field_to_bottom( $fields ) {
	$comment_field = $fields['comment'];
	unset( $fields['comment'] );
	$fields['comment'] = $comment_field;
	return $fields;
}
add_filter( 'comment_form_fields', 'xplex_move_comment_field_to_bottom' );

/**
 * Store Villa Header Promo Function Area
 */

if ( ! function_exists( 'storevilla_promo_area' ) ) {

    function storevilla_promo_area() {

        $header_promo = esc_attr( get_theme_mod( 'storevilla_main_header_promo_area', 'enable' ) );

        $promo_one_image = esc_url( get_theme_mod( 'storevilla_promo_area_one_image' ) );
        $promo_one_title = get_theme_mod( 'storevilla_promo_area_one_title' );
        $promo_one_desc = esc_textarea( get_theme_mod( 'storevilla_promo_area_one_desc' ) );
        $promo_one_link = esc_url( get_theme_mod( 'storevilla_promo_area_one_link' ) );

        $promo_two_image = esc_url( get_theme_mod( 'storevilla_promo_area_two_image' ) );
        $promo_two_title = get_theme_mod( 'storevilla_promo_area_two_title' );
        $promo_two_desc = esc_textarea( get_theme_mod( 'storevilla_promo_area_two_desc' ) );
        $promo_two_link = esc_url( get_theme_mod( 'storevilla_promo_area_two_link' ) );
    ?>
        <div class="banner-header-promo">
            <div class="store-promo-wrap">
                <a href="<?php echo $promo_one_link; ?>"/>
                    <div class="sv-promo-area promo-one" <?php if(!empty( $promo_one_image )) { ?> style="background-image:url(<?php echo $promo_one_image; ?>);"<?php } ?>>
                        <div class="promo-wrapper">
                            <?php if(!empty( $promo_one_title ) ) { ?><h2><?php echo $promo_one_title; ?></h2><?php } ?>
                            <?php if(!empty( $promo_one_desc ) ) { ?><p><?php echo $promo_one_desc; ?></p><?php } ?>
                        </div>
                    </div>
                </a>
            </div>

            <div class="store-promo-wrap">
                <a href="<?php echo $promo_two_link; ?>"/>
                    <div class="sv-promo-area" <?php if(!empty( $promo_two_image )) { ?> style="background-image:url(<?php echo $promo_two_image; ?>);"<?php } ?>>
                        <div class="promo-wrapper">
                            <?php if(!empty( $promo_two_title ) ) { ?><h2><?php echo $promo_two_title; ?></h2><?php } ?>
                            <?php if(!empty( $promo_two_desc ) ) { ?><p><?php echo $promo_two_desc; ?></p><?php } ?>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    <?php
    }
}


/**
 * XPLEX Index Hero Img Area
 */

if ( ! function_exists( 'xplex_main_heroimg' ) ) {

    function xplex_main_heroimg() {

        $heroimg = esc_attr( get_theme_mod( 'xplex_main_heroimg_area_settings', 'enable' ) );

        $heroimg_image = esc_url( get_theme_mod( 'xplex_main_heroimg_area_image' ) );
        $heroimg_title = get_theme_mod( 'xplex_main_heroimg_area_title' );

				if(!empty( $heroimg ) && $heroimg == 'enable') {
    ?>
        <div class="heroimg">
					<?php if(!empty( $heroimg_title ) ) { ?><h2><?php echo $heroimg_title; ?></h2><?php } ?>
					<?php if(!empty( $heroimg_image )) { ?>
						<img src="<?php echo $heroimg_image; ?>" title="<?php echo $heroimg_title; ?>" />
					<?php } ?>
        </div>
    <?php
				}
    }
}

/* XPLEX Mobile AD Area */
if ( ! function_exists( 'xplex_ad' ) ) {

    function xplex_ad() {

        $xad = esc_attr( get_theme_mod( 'xplex_ad_area_settings', 'enable' ) );

        $xad_image = esc_url( get_theme_mod( 'xplex_ad_area_image' ) );
        $xad_title = get_theme_mod( 'xplex_ad_area_title' );
				$xad_link = esc_url( get_theme_mod( 'xplex_ad_area_link' ) );

				if(!empty( $xad ) && $xad == 'enable') {
					if ( wp_is_mobile() ) {
    ?>
        <div class="xad">
					<?php if(!empty( $xad_image )) { ?>
						<a href="<?php echo $xad_link; ?>"><img src="<?php echo $xad_image; ?>" title="<?php echo $xad_title; ?>" /></a>
					<?php } ?>
        </div>
    <?php
					} // 모바일에서만 표시
				}
    }
}

/* XPLEX Quick Menu Area */
if ( ! function_exists( 'xplex_quick_menu' ) ) {
    function xplex_quick_menu() {
        $qmenu = esc_attr( get_theme_mod( 'xplex_quick_menu_area_settings', 'enable' ) );

				if( !empty( $qmenu ) && $qmenu == 'enable' ) {
					echo '<div id="qnb" class="store-container"><ul>';
					for ( $i=1; $i<5; $i++ ) {
						$qmenu_icon = 'xplex_quick_menu_icon_'.$i;
						$qmenu_label = 'xplex_quick_menu_label_'.$i;
						$qmenu_link = 'xplex_quick_menu_link_'.$i;

						$qicon = '<i class="'.get_theme_mod( $qmenu_icon ).'" aria-hidden="true"></i>';
						$qlabel = get_theme_mod( $qmenu_label );
						$qlink = esc_url( get_theme_mod( $qmenu_link ) );

						// www.xplex.org 포함하지 않으면 새창으로 링크 띄우기
						if( preg_match('/www.xplex.org/', $qlink) ) {
							$qlink = '<a href="'.$qlink.'">';
						} else {
							$qlink = '<a href="'.$qlink.'" target="_blank">';
						}
    ?>
		        <li>
							<?php if(!empty( $qlink ) ) { ?><?php echo $qlink; ?><?php echo $qicon; ?><?php echo $qlabel; ?></a><?php } ?>
		        </li>
    <?php
					} // for END
					echo '</ul></div>';
				} // if END
    }
}

// 어드민 사용자 정보 프론트 이름, 성별, 연령대, 직업 필드 추가
do_action('show_user_profile', $profileuser);
do_action('edit_user_profile', $profileuser);

add_action('show_user_profile', 'add_extra_xplex_fields');
add_action('edit_user_profile', 'add_extra_xplex_fields');

function add_extra_xplex_fields($user) {
?>
	<h3>XPLEX 회원가입 추가 정보</h3>
	<table class="form-table">
		<tr>
			<th><label for="xplex_sex">성별</label></th>
			<td><input type="text" name="xplex_sex" value="<?php echo esc_attr(get_the_author_meta( 'xplex_sex', $user->ID )); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="xplex_age">연령대</label></th>
			<td><input type="text" name="xplex_age" value="<?php echo esc_attr(get_the_author_meta( 'xplex_age', $user->ID )); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="xplex_job">직업</label></th>
			<td><input type="text" name="xplex_job" value="<?php echo esc_attr(get_the_author_meta( 'xplex_job', $user->ID )); ?>" class="regular-text" /></td>
		</tr>
	</table>
<?php
}

// 어드민 사용자 추가정보 저장
function save_extra_xplex_fields($user_id) {
	update_user_meta($user_id,'xplex_sex', sanitize_text_field($_POST['xplex_sex']));
	update_user_meta($user_id,'xplex_age', sanitize_text_field($_POST['xplex_age']));
	update_user_meta($user_id,'xplex_job', sanitize_text_field($_POST['xplex_job']));
}

add_action('personal_options_update', 'save_extra_xplex_fields');
add_action('edit_user_profile_update', 'save_extra_xplex_fields');

// 회원가입 프론트 이름, 성별, 연령대, 직업 필드 추가
function wooc_extra_register_fields() {
?>
	<p class="form-row form-row-first">
	<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?> <span class="required">*</span></label>
	<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>
	<div class="clear"></div>
	<p class="form-row form-row-first" style="width:60px">성별</p>
	<div style="float:left">
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_sex" value="남성">남</label>
		<label style="display:inline-block;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_sex" value="여성">여</label>
	</div>
	<div class="clear"></div>
	<p class="form-row form-row-first" style="width:60px">연령대</p>
	<div style="float:left;margin:4px 0 10px 0">
		<label style="display:block;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_age" value="20대">20대</label>
		<label style="display:block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_age" value="30대">30대</label>
		<label style="display:block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_age" value="40대">40대</label>
		<label style="display:block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_age" value="50대 이상">50대 이상</label>
	</div>
	<div class="clear"></div>
	<p class="form-row form-row-first" style="width:60px">직업</p>
	<div style="width:73%;float:left;margin:0 0 10px 0">
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_job" value="출판관련 종사자">출판관련 종사자</label>
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_job" value="직장인">직장인</label>
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_job" value="주부">주부</label>
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_job" value="학생">학생</label>
		<label style="display:inline-block;margin-right:10px;vertical-align:-3px"><input type="radio" class="input-radio" style="margin-right:5px" name="xplex_job" value="기타">기타</label>
	</div>
	</p>
	<div class="clear"></div>
<?php
}

add_action('woocommerce_register_form', 'wooc_extra_register_fields');

// 이름 필드 유효성 검사
function wooc_validate_extra_register_fields($username, $email, $validation_errors) {
	if ( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: 이름은 꼭 입력하셔야 합니다!', 'woocommerce' ) );
	}
}

add_action('woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 1);

// 이름: 워드프레스 기본 정보와 우커머스 정보에 저장
// 성별, 연령대, 직업: 워드프레스 기본 정보에 저장
function wooc_save_extra_register_fields($customer_id) {
	if ( isset( $_POST['billing_first_name'] ) ) {
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}
	if ( isset( $_POST['xplex_sex'] ) ) {
		update_user_meta( $customer_id, 'xplex_sex', $_POST['xplex_sex'] );
	}
	if ( isset( $_POST['xplex_age'] ) ) {
		update_user_meta( $customer_id, 'xplex_age', $_POST['xplex_age'] );
	}
	if ( isset( $_POST['xplex_job'] ) ) {
		update_user_meta( $customer_id, 'xplex_job', $_POST['xplex_job'] );
	}
}

add_action('woocommerce_created_customer', 'wooc_save_extra_register_fields');

// 회원가입시 아이디대신 이름을 Display Name으로 기본 설정
function registration_save_displayname($user_id) {
	if ( isset( $_POST['billing_first_name']) ){
		$pretty_name = $_POST['billing_first_name'];
		wp_update_user( array ('ID' => $user_id, 'display_name'=> $pretty_name) ) ;
	}
}

add_action('user_register', 'registration_save_displayname', 1000);

// 카테고리가 books일 때 신청하기를 구매하기로 텍스트 수정
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );
function woo_custom_cart_button_text() {
	global $product;
	$terms = wp_get_post_terms($product->id,'product_cat');
	foreach ( $terms as $term ) $categories[] = $term->slug;
	if ( in_array('xbooks', $categories) ) {
		return __( '구매하기', 'woocommerce' );
	} else {
		return __( '신청하기', 'woocommerce' );
	}
}

add_filter( 'woocommerce_product_add_to_cart_text', 'woo_archive_custom_cart_button_text' );
function woo_archive_custom_cart_button_text() {
	if( has_term( array('xbooks'), 'product_cat' ) ) {
		return __( '구매하기', 'woocommerce' );
	} else {
		return __( '신청하기', 'woocommerce' );
	}
}

/* Custom Customizer Class */

if(class_exists( 'WP_Customize_control')) :

    class Storevilla_Image_Radio_Control extends WP_Customize_Control {
        public $type = 'radioimage';
        public function render_content() {
            $name = '_customize-radio-' . $this->id;
            ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <div id="input_<?php echo $this->id; ?>" class="image">
                <?php foreach ( $this->choices as $value => $label ) : ?>
                        <label for="<?php echo $this->id . $value; ?>">
                            <input class="image-select" type="radio" value="<?php echo esc_attr( $value ); ?>" name="<?php echo esc_attr( $name ); ?>" id="<?php echo $this->id . $value; ?>" <?php $this->link(); checked( $this->value(), $value ); ?>>
                            <img src="<?php echo esc_html( $label ); ?>"/>
                        </label>
                <?php endforeach; ?>
            </div>
            <?php
        }
    }

endif;

/* WooCommerce Action and filter ADD and REMOVE Section */

remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );

remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

// 세일상품 뱃지 표기 체크박스 필드 추가
add_action( 'woocommerce_product_options_general_product_data', 'xplex_add_custom_general_fields' );
add_action( 'woocommerce_process_product_meta', 'xplex_add_custom_general_fields_save' );

function xplex_add_custom_general_fields() {
  global $woocommerce, $post;
  echo '<div class="options_group">';
  woocommerce_wp_checkbox (
    array (
      'id'            => 'is_sale_badge',
      'label'         => __('세일 뱃지 표시', 'woocommerce' ),
      'description'   => __( '체크하면 상품에 세일 뱃지가 생겨요.', 'woocommerce' )
    )
  );
  echo '</div>';
}

// 세일상품 뱃지 표기 여부 우커머스 상품 정보에 저앙
function xplex_add_custom_general_fields_save( $post_id ){
	$xplex_is_sale_badge = isset( $_POST['is_sale_badge'] ) ? 'yes' : 'no';
	update_post_meta( $post_id, 'is_sale_badge', $xplex_is_sale_badge );
}

// 세일상품 뱃지 상품 보기에 출력
add_filter('woocommerce_sale_flash', 'woo_custom_hide_sales_flash');
function woo_custom_hide_sales_flash()
{
		global $post, $product;
		// 세일상품 뱃지 표기 여부 가져오기
		$is_sale_badge = get_post_meta( $post->ID, 'is_sale_badge', true );
		if ( $product->is_on_sale() ) {
				if ($is_sale_badge == 'yes') {
					return '<span class="onsale">' . __( 'SALE!', 'woocommerce' ) . '</span>';
				}
		}
}

function storevilla_woocommerce_template_loop_product_thumbnail(){ ?>
    <div class="item-img">

        <?php
						global $post, $product;
						// 세일상품 뱃지 상품 리스트에 출력
						// 세일상품 뱃지 표기 여부 가져오기
						$is_sale_badge = get_post_meta( $post->ID, 'is_sale_badge', true );
						if ( $product->is_on_sale() ) {
								if ($is_sale_badge == 'yes') {
									echo '<div class="new-label new-top-right">Sale!</div>';
								}
						}
				?>
        <?php
            global $product_label_custom;
            if ($product_label_custom != ''){
                echo '<div class="new-label new-top-left">'.$product_label_custom.'</div>';
            }
        ?>
        <a class="product-image" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
            <?php echo woocommerce_get_product_thumbnail(); ?>
        </a>
    </div>
<?php
}
add_action( 'woocommerce_before_shop_loop_item_title', 'storevilla_woocommerce_template_loop_product_thumbnail', 10 );


/* Product Block Title Area */
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
function storevilla_woocommerce_template_loop_product_title(){
    global $product;
    if( is_home() || is_front_page() ) {
        $term = wp_get_post_terms($product->id,'product_cat',array('fields'=>'ids'));
        if(!empty( $term[0] )) {
            $procut_cat = get_term_by( 'id', $term[0], 'product_cat' );
            $category_link = get_term_link( $term[0],'product_cat' );
        }
    }
 ?>
    <div class="block-item-title">
        <?php  if(!empty( $term[0] )) { ?>
            <span>
                <a href="<?php esc_url( $category_link ); ?>">
                    <?php  echo esc_attr( $procut_cat->name ); ?>
                </a>
            </span>
        <?php } ?>
        <h3><a title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
    </div>
<?php }

/* webzine 웹진 페이지 요약문 글자수 줄이기 */
function fix_the_excerpt($text) {
  return str_replace('[...]', '...', $text);
}
add_filter('the_excerpt', 'fix_the_excerpt');

function custom_excerpt_length( $length ) {
	return 30;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

add_action( 'woocommerce_shop_loop_item_title', 'storevilla_woocommerce_template_loop_product_title', 10 );

/* Product Add to Cart and View Details */
/* xplex 장바구니에 담기 / 위시리스트 / 자세히 보기 */
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
function storevilla_woocommerce_template_loop_add_to_cart(){
?>
    <div class="xproduct product-button-wrap clearfix">
			<ul class="add-to-links">
				<?php
				global $product;
				if( function_exists( 'YITH_WCQV' ) ){
					$quick_view = YITH_WCQV_Frontend();
					remove_action( 'woocommerce_after_shop_loop_item', array( $quick_view, 'yith_add_quick_view_button' ), 15 );
					$label = esc_html( get_option( 'yith-wcqv-button-label' ) );
					echo '<li><a href="#" class="link-quickview yith-wcqv-button" data-product_id="' . $product->id . '"><i class="fa fa-search" aria-hidden="true"></i></a></li>';
				}

				if( function_exists( 'YITH_WCWL' ) ){
					$url = add_query_arg( 'add_to_wishlist', $product->id );
					?>
					<li>
						<a class="link-wishlist" href="<?php echo $url ?>">
							<?php _e('<i class="fa fa-heart-o" aria-hidden="true"></i> WISH','storevilla'); ?>
						</a>
					</li>
					<?php
				}
				?>
				<li>
					<?php woocommerce_template_loop_add_to_cart(); ?>
				</li>
				<li>
					<a class="villa-details" title="<?php the_title_attribute(); ?>" href="<?php the_permalink(); ?>">
              <?php _e('자세히 보기','storevilla'); ?>
          </a>
				</li>
	    </ul>
		</div>
<?php
}
add_action( 'woocommerce_after_shop_loop_item_title' ,'storevilla_woocommerce_template_loop_add_to_cart', 11 );

/* move the sharing and like buttons */
function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}

add_action( 'loop_start', 'jptweak_remove_share' );

/* remove product availability */
add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);
function custom_get_availability( $availability, $_product ) {
}

/**
 * Woo Commerce Number of row filter Function
**/

add_filter('loop_shop_columns', 'storevilla_loop_columns');
if (!function_exists('storevilla_loop_columns')) {
    function storevilla_loop_columns() {
        if(get_theme_mod('storevilla_woocommerce_product_row','3')){
            $xr = get_theme_mod('storevilla_woocommerce_product_row','3');
        } else {
            $xr = 3;
        }
        return $xr;
    }
}

add_action( 'body_class',  'storevilla_woo_body_class');
if (!function_exists('storevilla_woo_body_class')) {
    function storevilla_woo_body_class( $class ) {
           $class[] = 'columns-'.storevilla_loop_columns();
           return $class;
    }
}

/**
 * Woo Commerce Number of Columns filter Function
**/
$column = get_theme_mod('storevilla_woocommerce_display_product_number','12');
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return '.$column.';' ), 20 );


/**
 * Woo Commerce Add Content Primary Div Function
**/
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
if (!function_exists('storevilla_woocommerce_output_content_wrapper')) {
    function storevilla_woocommerce_output_content_wrapper(){ ?>
        <div id="primary" class="content-area">
            <main id="main" class="site-main" role="main">
    <?php   }
}
add_action( 'woocommerce_before_main_content', 'storevilla_woocommerce_output_content_wrapper', 10 );

remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
if (!function_exists('storevilla_woocommerce_output_content_wrapper_end')) {
    function storevilla_woocommerce_output_content_wrapper_end(){ ?>
            </main><!-- #main -->
        </div><!-- #primary -->
    <?php   }
}
add_action( 'woocommerce_after_main_content', 'storevilla_woocommerce_output_content_wrapper_end', 10 );


/**
 * Remove WooCommerce Default Sidebar
**/
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
function storevilla_woocommerce_get_sidebar(){
    get_sidebar('woocommerce');
}
add_action( 'woocommerce_sidebar', 'storevilla_woocommerce_get_sidebar', 10);



/**
 * The Excerpt [...] remove function
 **/
function storevilla_excerpt_more( $more ) {
    return '';
}
add_filter('excerpt_more', 'storevilla_excerpt_more');

/**
 * Change the Breadcrumb Arrow Function
 **/
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );
//add_filter( 'woocommerce_breadcrumb_defaults', 'storevilla_change_breadcrumb_delimiter' );
function storevilla_change_breadcrumb_delimiter() {
		$args = array(
			'delimiter'   => ' &gt; ',
			'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'X-PLEX', 'breadcrumb', 'woocommerce' ),
		);
		woocommerce_breadcrumb($args);
}
add_action( 'xplex_custom_breadcrumb', 'storevilla_change_breadcrumb_delimiter', 20 );

/**
 * Woo Commerce Social Share
**/

// 2016.11.08 젯팩 업데이트에 따른 sns공유버튼 중복출력 버그로 기존 코드 제거

// remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 55 );
// function storevilla_woocommerce_template_single_sharing() {
//     <div class="storevilla-social">
//
// 				if ( function_exists( 'sharing_display' ) ) {
// 					sharing_display( '', true );
// 				}
//
// 				if ( class_exists( 'Jetpack_Likes' ) ) {
// 					$custom_likes = new Jetpack_Likes;
// 					echo $custom_likes->post_likes( '' );
// 				}
//
//     </div>
// }
// add_action( 'woocommerce_single_product_summary', 'storevilla_woocommerce_template_single_sharing', 50 );

/**
 * Woo Commerce Related product
**/
add_filter( 'woocommerce_output_related_products_args', 'storevilla_related_products_args' );
function storevilla_related_products_args( $args ) {
    $args['posts_per_page']     = 6;
    $args['columns']            = 3;
    return $args;
}

/**
 * Add the Custom Media Size image in Image Uplaod Light Box
**/
/*add_filter( 'image_size_names_choose', 'storevilla_media_uploader_custom_sizes' );
function storevilla_media_uploader_custom_sizes( $sizes ) {
    $sv_custom_iamge = array(
        'storevilla_blog_image' => __('Banner Slider Image','storevilla'),
    );
    $sv_sizes = array_merge( $sizes, $sv_custom_iamge );
    return $sv_sizes;
}*/


/**
 ** Retina images image generate function
**/
function retina_support_attachment_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    retina_support_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }

    return $metadata;
}
add_filter( 'wp_generate_attachment_metadata', 'retina_support_attachment_meta', 10, 2 );

/**
 * Create retina-ready images
 * Referenced via retina_support_attachment_meta().
**/
function retina_support_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );

            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );

            $info = $resized_file->get_size();

            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}


/**
 * Delete retina-ready images
 * This function is attached to the 'delete_attachment' filter hook.
**/
function delete_retina_support_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
    $path = pathinfo( $meta['file'] );
    foreach ( $meta as $key => $value ) {
        if ( 'sizes' === $key ) {
            foreach ( $value as $sizes => $size ) {
                $original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
                $retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
                if ( file_exists( $retina_filename ) )
                    unlink( $retina_filename );
            }
        }
    }
}
add_filter( 'delete_attachment', 'delete_retina_support_images' );
