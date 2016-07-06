<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>

<p><?php _e( '페이스북 좋아요, 구글+ 및 트위터 팔로우를 표시하려면 소셜 shortcode를 사용하세요. 사용 가능한 옵션을 확인하고 X-PLEX 계정 환경으로 설정값을 수정하세요.:', 'popups' );?></p>

<p><strong><?php _e( 'Facebook Page', 'popups' );?>:</strong></p>
<p>
[spu-facebook-page href="" name="" show_faces="" hide_cover="" width=""]
</p>
<a href="fb-opts" onclick="jQuery('#fbpage-opts').slideToggle();return false;"><?php _e( 'View Facebook Page Options', 'popups' );?></a>
<ul id="fbpage-opts" style="display:none;">
	<li><b>href:</b> <?php _e( 'Your Facebook page url', 'popups' );?></li>
	<li><b>name:</b> <?php _e( 'Your page name', 'popups' );?></li>
	<li><b>show_faces:</b> <?php _e( 'true|false <b>Default value:</b> true', 'popups' );?></li>
	<li><b>hide_cover:</b> <?php _e( 'true|false <b>Default value:</b> false', 'popups' );?></li>
	<li><b>width:</b> <?php _e( 'number <b>Default value:</b> 500', 'popups' );?></b></li>
</ul>

<p><strong><?php _e( 'Facebook Button', 'popups' );?>:</strong></p>
<p>
[spu-facebook href="" layout="" show_faces="" share="" action="" width=""]
</p>
<a href="fb-opts" onclick="jQuery('#fb-opts').slideToggle();return false;"><?php _e( 'View Facebook Options', 'popups' );?></a>
<ul id="fb-opts" style="display:none;">
	<li><b>href:</b> <?php _e( 'Your Facebook page url', 'popups' );?></li>
	<li><b>layout:</b> <?php _e( 'standard, box_count, button <b>Default value:</b> button_count', 'popups' );?></li>
	<li><b>show_faces:</b> <?php _e( 'true <b>Default value:</b> false', 'popups' );?></li>
	<li><b>share:</b> <?php _e( 'true <b>Default value:</b> false', 'popups' );?></li>
	<li><b>action:</b> <?php _e( 'recommend <b>Default value:</b> like', 'popups' );?></li>
	<li><b>width:</b> <?php _e( 'number <b>Default value:</b>', 'popups' );?></li>
</ul>
<p><strong><?php _e( 'Google+ Button', 'popups' );?>:</strong></p>
<p>
[spu-google url="" size="" annotation=""]
</p>
<a href="go-opts" onclick="jQuery('#go-opts').slideToggle();return false;"><?php _e( 'View Google+ Options', 'popups' );?></a>
<ul id="go-opts" style="display:none;">
	<li><b>url:</b> <?php _e( 'Your Google+ url', 'popups' );?></li>
	<li><b>size:</b> <?php _e( 'small, standard, tall <b>Default value:</b> medium', 'popups' );?></li>
	<li><b>annotation:</b> <?php _e( 'inline, none <b>Default value:</b> bubble', 'popups' );?></li>
</ul>
<p><strong><?php _e( 'Twitter Button', 'popups' );?>:</strong></p>
<p>
[spu-twitter user="" show_count="" size="" lang=""]
</p>
<a href="tw-opts" onclick="jQuery('#tw-opts').slideToggle();return false;"><?php _e( 'View Twitter Options', 'popups' );?></a>
<ul id="tw-opts" style="display:none;">
	<li><b>user:</b> <?php _e( 'Your Twitter user <b>Default value: </b>', 'popups' ); echo ' ' . apply_filters( 'spu/social/tw_user', 'xplex' ); ?></li>
	<li><b>show_count:</b> <?php _e( 'false <b>Default value:</b> true', 'popups' );?></li>
	<li><b>size:</b> <?php _e( 'large <b>Default value:</b> ""', 'popups' );?></li>
	<li><b>lang:</b> </li>
</ul>
<h3 style="padding-left:0;margin: 20px 0;"><strong><?php _e('사용 가능한 기타 Shortcodes:', 'popups' );?></strong></h3>
<p><strong><?php _e( '닫기 버튼', 'popups' );?>:</strong></p>
<p>
[spu-close class="" text="" align=""]
</p>
<a href="close-opts" onclick="jQuery('#close-opts').slideToggle();return false;"><?php _e( 'View Close shortcode Options', 'popups' );?></a>
<ul id="close-opts" style="display:none;">
	<li><b>class:</b> <?php _e( 'Pass a custom class to style your button', 'popups' );?></li>
	<li><b>text:</b> <?php _e( 'Button label - <b>Default value:</b> Close', 'popups' );?></li>
	<li><b>align:</b> <?php _e( 'left, right, center, none - <b>Default value:</b> center', 'popups' );?></li>
</ul>
