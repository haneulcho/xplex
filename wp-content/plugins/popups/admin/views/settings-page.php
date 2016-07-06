<?php
/**
 * Settings page template
 * @since  1.1
 */
?>
<div class="wrap">
	<h2>Popups <?php echo SocialPopup::VERSION;
		if( class_exists('PopupsP') ){
			echo ' - Premium v', PopupsP::VERSION;
		}
		?></h2>
	<form name="spu-settings" method="post">
		<table class="form-table">
			<?php do_action( 'spu/settings_page/before' ); ?>
			<tr valign="top" class="">
				<th><label for="ajax_mode"><?php _e( 'Ajax mode 사용', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="ajax_mode" name="spu_settings[ajax_mode]" value="1" <?php checked(@$opts['ajax_mode'], 1); ?> />
					<p class="help"><?php _e( 'Ajax로 팝업창을 로딩합니다. 캐시 플러그인과 호환 작동하지만, 모든 플러그인과 호환되지 않을 수 있습니다.', 'popups' ); ?></p>
				</td>
			</tr>

			<tr valign="top" class="">
				<th><label for="debug"><?php _e( 'Debug mode 사용', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="debug" name="spu_settings[debug]" value="1" <?php checked(@$opts['debug'], 1); ?> />
					<p class="help"><?php _e( '무압축 스크립트를 사용합니다. Debug mode를 활성화할 경우 페이지 로딩 속도가 늘어납니다.', 'popups' ); ?></p>
				</td>

			</tr>
			<tr valign="top" class="">
				<th><label for="safe"><?php _e( 'Safe mode 사용', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="safe" name="spu_settings[safe]" value="1" <?php checked(@$opts['safe'], 1); ?> />
					<p class="help"><?php _e( '모든 팝업창을 스크린 맨 위로 이동시킵니다. (테마 호환 이유로 활성화를 권장하지 않습니다.)', 'popups' ); ?></p>
				</td>

			</tr>
			<tr valign="top" class="">
				<th><label for="style"><?php _e( 'shortcode 스타일 제거', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="style" name="spu_settings[shortcodes_style]" value="1" <?php checked(@$opts['shortcodes_style'], 1); ?> />
					<p class="help"><?php _e( '팝업창 플러그인 shortcode에는 기본 스타일 양식이 지정되어 있습니다. shortcode 스타일을 개별적으로 관리하려면 체크하세요.', 'popups' ); ?></p>
				</td>

			</tr>
			<tr valign="top" class="">
				<th><label for="style"><?php _e( 'Unload Facebook javascript', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="style" name="spu_settings[facebook]" value="1" <?php checked(@$opts['facebook'], 1); ?> />
					<p class="help"><?php _e( '페이스북 개별 스크립트를 사용하려면 체크를 해제하세요. (활성화를 권장하지 않습니다.)', 'popups' ); ?></p>
				</td>

			</tr>
			<tr valign="top" class="">
				<th><label for="style"><?php _e( 'Unload Google javascript', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="style" name="spu_settings[google]" value="1" <?php checked(@$opts['google'], 1); ?> />
					<p class="help"><?php _e( '구글+ 개별 스크립트를 사용하려면 체크를 해제하세요. (활성화를 권장하지 않습니다.)', 'popups' ); ?></p>
				</td>

			</tr>
			<tr valign="top" class="">
				<th><label for="style"><?php _e( 'Unload Twitter javascript', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="style" name="spu_settings[twitter]" value="1" <?php checked(@$opts['twitter'], 1); ?> />
					<p class="help"><?php _e( '트위터 개별 스크립트를 사용하려면 체크를 해제하세요. (활성화를 권장하지 않습니다.)', 'popups' ); ?></p>
				</td>

			</tr>
			<?php do_action( 'spu/settings_page/after' ); ?>

			<tr valign="top" class="">
				<th><label for="uninstall"><?php _e( '비활성화시 모든 데이터 제거', 'popups' ); ?></label></th>
				<td colspan="3">
					<label><input type="checkbox" id="uninstall" name="spu_settings[uninstall]" value="1" <?php checked(@$opts['uninstall'], 1); ?> />
						<p class="help"><?php _e( '플러그인을 비활성화하면 DB에서 생성한 모든 팝업창과 저장된 설정 데이터들이 한 번에 제거됩니다. (활성화를 권장하지 않습니다.)', 'popups' ); ?></p>
				</td>

			</tr>
			<tr><td><input type="submit" class="button-primary" value="<?php _e( '설정 저장하기', 'popups' );?>"/></td>
			<?php wp_nonce_field('spu_save_settings','spu_nonce'); ?>
		</table>
	</form>
</div>
