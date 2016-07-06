<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;?>

<table class="form-table">

		<?php do_action( 'spu/metaboxes/before_appearance_options', $opts );?>
		<tr valign="top">
			<td colspan="3" class="spu-bg-opacity">
				<label for="spu_bgopacity"><?php _e( '팝업창 배경 투명도 설정', 'popups' ); ?></label>
				<input type="number" id="spu_bgopacity" name="spu[css][bgopacity]" min="0" step="0.1" max="1" value="<?php echo esc_attr($opts['css']['bgopacity']); ?>" />
				<p class="help"><?php _e( '투명도 최댓값은 1입니다. (투명도 100% = 1), 배경을 보이지 않게 하려면 최솟값 0을 적용하세요. (투명도 0% = 0, 투명도 30% = 0.3)', 'popups' ); ?></p>
			</td>

		</tr>
		<tr valign="top" class="spu-appearance">
			<td class="spu-border-width">
				<label class="spu-label" for="spu-background-color"><?php _e( '배경색', 'popups' ); ?></label>
				<input id="spu-background-color" name="spu[css][background_color]" type="text" class="spu-color-field" value="<?php echo esc_attr($opts['css']['background_color']); ?>" />
			</td>
			<td class="spu-text-color">
				<label class="spu-label" for="spu-color"><?php _e( '기본 글자색', 'popups' ); ?></label>
				<input id="spu-color" name="spu[css][color]" type="text" class="spu-color-field" value="<?php echo esc_attr($opts['css']['color']); ?>" />
			</td>
			<td class="spu-box-width">
				<label class="spu-label" for="spu-width"><?php _e( '박스 너비', 'popups' ); ?></label>
				<input id="spu-width" name="spu[css][width]" id="spu-box-width" type="text" class="small" value="<?php echo esc_attr($opts['css']['width']); ?>" />
			</td>
		</tr>
		<tr valign="top" class="spu-appearance">
			<td class="spu-border-color">
				<label class="spu-label" for="spu-border-color"><?php _e( '테두리색', 'popups' ); ?></label>
				<input name="spu[css][border_color]" id="spu-border-color" type="text" class="spu-color-field" value="<?php echo esc_attr($opts['css']['border_color']); ?>" />
			</td>
			<td class="spu-border-width">
				<label class="spu-label" for="spu-border-width"><?php _e( '테두리 두께', 'popups' ); ?></label>
				<input name="spu[css][border_width]" id="spu-border-width" type="number" min="0" max="25" value="<?php echo esc_attr($opts['css']['border_width']); ?>" /> px
			</td>
			<td></td>
		</tr>
		<tr class="spu-appearance">
			<td colspan="3">
				<label class="spu-label" for="spu-custom-css"><?php _e( 'Custom CSS', 'popups' ); ?></label>
				<div id="custom_css_container">
					<div name="custom_css" id="custom_css" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 200px; position: relative;"></div>
				</div>
				<?php
				if( !isset( $opts['css']['custom_css'] ) ) {
					$popup_id = get_the_id();
					$opts['css']['custom_css'] = "/*
* X-PLEX 팝업창에 Custom CSS (사용자 정의 CSS)를 적용하려면 여기에 내용을 작성하세요.
* CSS를 시작할 때는 #spu-{$popup_id} { } 양식을 사용합니다.
* 팝업창 기본 스타일보다 Custom CSS의 우선순위를 높이려면 !important 속성을 덮어쓰세요.
*/";
				}
				?>
				<textarea name="spu[css][custom_css]" id="spu-custom-css" style="display: none;"><?php echo esc_attr($opts['css']['custom_css']); ?></textarea>
			</td>
		</tr>
		<?php do_action( 'spu/metaboxes/after_appearance_options', $opts );?>
	</table>
