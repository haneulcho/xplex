<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>

<table class="form-table">

	<?php do_action( 'spu/metaboxes/before_display_options', $opts );?>
	<tr valign="top">
		<th><label for="spu_position"><?php _e( '팝업창 위치', 'popups' ); ?></label></th>
		<td>
			<select id="spu_position" name="spu[css][position]" class="widefat">
				<option value="centered" <?php selected($opts['css']['position'], 'centered'); ?>><?php _e( 'Centered', 'popups' ); ?></option>
				<option value="top-left" <?php selected($opts['css']['position'], 'top-left'); ?>><?php _e( 'Top Left', 'popups' ); ?></option>
				<option value="top-right" <?php selected($opts['css']['position'], 'top-right'); ?>><?php _e( 'Top Right', 'popups' ); ?></option>
				<option value="bottom-left" <?php selected($opts['css']['position'], 'bottom-left'); ?>><?php _e( 'Bottom Left', 'popups' ); ?></option>
				<option value="bottom-right" <?php selected($opts['css']['position'], 'bottom-right'); ?>><?php _e( 'Bottom Right', 'popups' ); ?></option>
				<?php do_action( 'spu/metaboxes/positions', $opts );?>
			</select>
		</td>
		<td colspan="2"></td>
	</tr>
	<tr valign="top">
		<th><label for="spu_trigger"><?php _e( '팝업창 정의 액션', 'popups' ); ?></label></th>
		<td class="spu-sm">
			<select id="spu_trigger" name="spu[trigger]" class="widefat">

					<option value="seconds" <?php selected($opts['trigger'], 'seconds'); ?>><?php _e( '페이지 접속 N초(1 입력시 1초, 2 입력시 2초) 후 팝업창이 나타납니다.', 'popups' ); ?></option>
					<option value="percentage" <?php selected($opts['trigger'], 'percentage'); ?>>% <?php _e( '만큼의 스크롤시(10 입력시 페이지 전체 스크롤 길이의 10%를 지나치면) 팝업창이 나타납니다.', 'popups' ); ?></option>
					<option value="manual" <?php selected($opts['trigger'], 'manual'); ?>><?php _e( '직접 액션을 정의합니다. (사용하지 않습니다.)', 'popups' ); ?></option>
					<?php do_action( 'spu/metaboxes/trigger_options', $opts );?>
			</select>
		</td>
		<td>
			<input type="number" class="spu-trigger-number" name="spu[trigger_number]" min="0" value="<?php echo esc_attr($opts['trigger_number']); ?>"  />
			<?php do_action( 'spu/metaboxes/trigger_values', $opts );?>
		</td>
	</tr>
	<tr valign="top" class="auto_hide">
		<th><label for="spu_auto_hide"><?php _e( '자동 숨김 옵션', 'popups' ); ?></label></th>
		<td colspan="3">
			<label><input type="radio" id="spu_auto_hide_1" name="spu[auto_hide]" value="1" <?php checked($opts['auto_hide'], 1); ?> /> <?php _e( 'Yes' ); ?></label> &nbsp;
			<label><input type="radio" id="spu_auto_hide_0" name="spu[auto_hide]" value="0" <?php checked($opts['auto_hide'], 0); ?> /> <?php _e( 'No' ); ?></label> &nbsp;
			<p class="help"><?php _e( '방문자가 스크롤을 위로 다시 올리면 팝업창이 자동으로 숨김 처리 되도록 하시겠습니까?', 'popups' ); ?></p>
		</td>
	</tr>
	<tr valign="top">
	<th><label><?php _e( '애니메이션 효과', 'popups' ); ?></label></th>
		<td colspan="3">
			<select id="spu_animation" name="spu[animation]" class="widefat">
				<option value="fade" <?php selected($opts['animation'], 'fade'); ?> > <?php _e( 'Fade In', 'popups' ); ?></option>
				<option value="slide" <?php selected($opts['animation'], 'slide'); ?> > <?php _e( 'Slide In', 'popups' ); ?></option>
				<?php do_action( 'spu/metaboxes/animations', $opts );?>
			</select>
			<p class="help"><?php _e( 'Slide 효과는 기본 팝업창 위치를 모서리로 설정했을 때만 적용됩니다.', 'popups' ); ?></p>
		</td>
	</tr>

	<tr valign="top">
		<th><label for="spu_cookie"><?php _e( '쿠키 만료 기간 설정', 'popups' ); ?></label></th>
		<td colspan="3">
			<input type="number" id="spu_cookie" name="spu[cookie]" min="0" step="1" value="<?php echo esc_attr($opts['cookie']); ?>" />
			<p class="help"><?php _e( '팝업창을 닫았을 때, 며칠동안 팝업창 숨기기를 유지할 것인지 설정합니다. (1 입력시 1일 후 재출력, 7 입력시 일주일 후 재출력)', 'popups' ); ?></p>
		</td>

	</tr>
	<tr valign="top">
		<th><label for="spu_test_mode"><?php _e( '테스트 모드 활성화', 'popups' ); ?></label></th>
		<td colspan="3">
			<label><input type="radio" id="spu_test_mode_1" name="spu[test_mode]" value="1" <?php checked($opts['test_mode'], 1); ?> /> <?php _e( 'Yes' ); ?></label> &nbsp;
			<label><input type="radio" id="spu_test_mode_0" name="spu[test_mode]" value="0" <?php checked($opts['test_mode'], 0); ?> /> <?php _e( 'No' ); ?></label> &nbsp;
			<p class="help"><?php _e( '테스트 모드를 활성화하면 쿠키 만료 기간에 설정한 값과 관계 없이 팝업창이 출력됩니다. (관리자만 확인 가능)', 'popups' ); ?></p>
		</td>
	</tr>
	<tr valign="top" class="conversion_close">
		<th><label for="spu_conversion_close"><?php _e( '팝업창 자동 전환', 'popups' ); ?></label></th>
		<td colspan="3">
			<label><input type="radio" id="spu_conversion_close_1" name="spu[conversion_close]" value="1" <?php checked($opts['conversion_close'], 1); ?> /> <?php _e( 'Yes' ); ?></label> &nbsp;
			<label><input type="radio" id="spu_conversion_close_0" name="spu[conversion_close]" value="0" <?php checked($opts['conversion_close'], 0); ?> /> <?php _e( 'No' ); ?></label> &nbsp;
			<p class="help"><?php _e( '예 선택시 소셜 shortcode를 사용할 때 각 SNS 버튼을 누르면 새 창 열림과 함께 기본 팝업창이 자동으로 사라집니다.', 'popups' ); ?></p>
		</td>
	</tr>
	<tr valign="top" class="powered_link">
		<th><label for="spu_powered_link"><?php _e( 'powered by link? 설정', 'popups' ); ?></label></th>
		<td colspan="3">
			<label><input type="radio" id="spu_powered_link_1" name="spu[powered_link]" value="1" <?php checked($opts['powered_link'], 1); ?> /> <?php _e( 'Yes' ); ?></label> &nbsp;
			<label><input type="radio" id="spu_powered_link_0" name="spu[powered_link]" value="0" <?php checked($opts['powered_link'], 0); ?> /> <?php _e( 'No' ); ?></label> &nbsp;
			<p class="help"><?php echo sprintf(__( '팝업창 박스 하단에 "powered by" 링크를 나타내겠습니까? 링크는 <a href="%s">settings</a>에서 수정할 수 있습니다.', 'popups' ), admin_url('edit.php?post_type=spucpt&page=spu_settings')); ?></p>
		</td>
	</tr>
	<?php do_action( 'spu/metaboxes/after_display_options', $opts );?>
</table>
<?php wp_nonce_field( 'spu_options', 'spu_options_nonce' ); ?>
