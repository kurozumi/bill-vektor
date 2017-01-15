<?php

class Bill_Admin {

	public static $version = '0.0.0';

	// define( 'Bill_URL', get_template_directory_uri() );

	public static function init() {
		add_action( 'admin_menu' , array( __CLASS__, 'add_admin_menu'), 10, 2);
		add_action( 'admin_init' , array( __CLASS__, 'admin_init'), 10, 2);
		add_action( 'admin_print_styles-toplevel_page_bill-setting-page', array( __CLASS__, 'admin_enqueue_scripts' ) );
	}

	public static function add_admin_menu() 
	{
		$page_title = '請求設定';
		$menu_title = '請求設定';
		$capability = 'administrator';
		$menu_slug  = 'bill-setting-page';
		$function	= array( __CLASS__, 'setting_page');
		// $icon_url	= '';
		// $position	= '';
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function );
	}

	public static function setting_page()
	{
		// delete_option('bill-setting');
		?>
		<div class="wrap">
		<h2>請求設定</h2>
		<form id="bill-setting-form" method="post" action="">
		<?php wp_nonce_field( 'bill-nonce-key', 'bill-setting-page' );?>
		<?php $options = get_option('bill-setting', bill_options_default());?>
		<table class="form-table">
		<tr>
		<th>請求者名</th>
		<td><input type="text" name="bill-setting[own-name]" value="<?php echo esc_attr( $options['own-name'] ) ?>"></td>
		</tr>
		<tr>
		<th>住所</th>
		<td><textarea name="bill-setting[own-address]" rows="4"><?php echo esc_textarea( $options['own-address'] ) ?></textarea></td>
		</tr>
		<tr>
		<th>ロゴ画像</th>
		<td>
		<?php
		$attr = array(
			'id'    => 'thumb_own-logo',
			'src'   => '',
			'class' => 'input_thumb',
		);
		if ( isset( $options['own-logo'] ) && $options['own-logo'] ){
			echo wp_get_attachment_image( $options['own-logo'], 'medium', false, $attr );
		} else {
			echo '<img src="'.get_template_directory_uri().'/assets/images/no-image.png" id="thumb_own-logo" alt="" class="input_thumb" style="width:150px;height:auto;">';
		}
		?>
		<input type="hidden" name="bill-setting[own-logo]" id="own-logo" value="<?php echo esc_attr( $options['own-logo'] ) ?>" style="width:60%;" />  
		<button id="media_own-logo" class="media_btn btn btn-default button button-default"><?php _e('画像を選択', '');?></button></td>
		</tr>
		<tr>
		<th>印鑑画像</th>
		<td>
		<?php
		$attr = array(
			'id'    => 'thumb_own-seal',
			'src'   => '',
			'class' => 'input_thumb',
		);
		if ( isset( $options['own-seal'] ) && $options['own-seal'] ){
			echo wp_get_attachment_image( $options['own-seal'], 'medium', false, $attr );
		} else {
			echo '<img src="'.get_template_directory_uri().'/assets/images/no-image.png" id="thumb_own-seal" alt="" class="input_thumb" style="width:150px;height:auto;">';
		}
		?>
		<input type="hidden" name="bill-setting[own-seal]" id="own-seal" value="<?php echo esc_attr( $options['own-seal'] ) ?>" style="width:60%;" />  
		<button id="media_own-seal" class="media_btn btn btn-default button button-default"><?php _e('画像を選択', '');?></button></td>
		</tr>
		<tr>
		<th>振込先</th>
		<td><textarea name="bill-setting[own-payee]" rows="3"><?php echo esc_textarea( $options['own-payee'] ) ?></textarea></td>
		</tr>
		<tr>
		<th>備考</th>
		<td><textarea name="bill-setting[remarks]" rows="4"><?php echo esc_textarea( $options['remarks'] ) ?></textarea></td>
		</tr>

		</table>
		<p><input type="submit" value="設定を保存" class="button button-primary button-large"></p>
		</form>
		</div>
		<?php
	}

	public static function admin_enqueue_scripts() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script( 'bill-setting-page-js', get_template_directory_uri().'/inc/setting-page/setting-page.js', array( 'jquery' ), self::$version );
		wp_enqueue_style( 'bill-setting-page-style', get_template_directory_uri().'/inc/setting-page/setting-page.css', array(), self::$version, 'all' );
	}

	public static function admin_init()
	{

		if ( isset( $_POST['bill-setting-page'] ) && $_POST['bill-setting-page'] ) {
					
			if ( check_admin_referer( 'bill-nonce-key', 'bill-setting-page' ) ) {
				// 保存処理

				if ( isset( $_POST['bill-setting'] ) && $_POST['bill-setting'] ) {
					update_option( 'bill-setting', $_POST['bill-setting'] );
				} else {
					update_option( 'bill-setting', '' );
				}

				wp_safe_redirect( menu_page_url( 'bill-setting-page', false ) );
			}
		}
	}

}

Bill_Admin::init();