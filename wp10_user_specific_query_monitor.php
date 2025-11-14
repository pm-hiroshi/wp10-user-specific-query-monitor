<?php
/*
Plugin Name: WP10 User Specific Query Monitor
Plugin URI:
Description: 自分だけにQuery Monitorを有効化するプラグイン (プロフィール画面で有効化できます)
Version: 0.2
Author: PRESSMAN HS
Author URI: https://www.pressman.ne.jp/
Text Domain:
License: GNU GPL v2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class WP10_User_Specific_QM_Control
 */
class WP10_User_Specific_QM_Control {

	/**
	 * Cookie名
	 *
	 * @var string
	 */
	private const COOKIE_NAME = 'enable_qm_for_you';

	/**
	 * コンストラクタ
	 */
	public function __construct() {
		add_action( 'show_user_profile', array( $this, 'qm_enable_checkbox' ) );
		add_action( 'edit_user_profile', array( $this, 'qm_enable_checkbox' ) );

		add_action( 'personal_options_update', array( $this, 'update_qm_cookie' ) );
		add_action( 'edit_user_profile_update', array( $this, 'update_qm_cookie' ) );

		add_action( 'muplugins_loaded', array( $this, 'control_qm_activation_for_user' ) );

		add_action( 'admin_bar_menu', array( $this, 'add_user_specific_indicator' ), 1000 );
		add_action( 'wp_head', array( $this, 'add_qm4u_styles' ) );
		add_action( 'admin_head', array( $this, 'add_qm4u_styles' ) );
	}

	/**
	 * Query Monitorの有効化チェックボックスを表示する
	 *
	 * @return void
	 */
	public function qm_enable_checkbox() {
		$is_qm_enabled_for_user = isset( $_COOKIE[ self::COOKIE_NAME ] );

		// QMの有効状態をチェック
		$is_qm_active = is_plugin_active( 'query-monitor/query-monitor.php' );

		?>
		<h3>Query Monitor個人設定</h3>
		<table class="form-table">
			<tr>
			<th><label for="qm_enable">Query Monitorを使用する</label></th>
				<td>
					<?php if ( $is_qm_active ) : ?>
						<input type="checkbox" name="qm_enable" id="qm_enable" value="1" <?php checked( $is_qm_enabled_for_user, true ); ?>>
						<p class="description">チェックすると、独自のCOOKIE(<?php echo esc_html( self::COOKIE_NAME ); ?>)が保存され、30日間有効になります。チェックを外すとCOOKIEは削除されます。<p><strong>注意：</strong> この設定は、Query Monitorプラグインが事前に有効化されていることを前提としています。</p></p>
					<?php else : ?>
						<span class="description">注意: Query Monitorプラグインが現在無効化されています。この設定を利用する前に、<strong>Query Monitorを有効化してください</strong>。</span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php
	}


	/**
	 * Query Monitorの有効化チェックボックスの値をcookieに保存する
	 *
	 * @param int $user_id ユーザーID
	 * @return void
	 */
	public function update_qm_cookie( int $user_id ) {
		if ( isset( $_POST['qm_enable'] ) ) {
			setcookie( self::COOKIE_NAME, '1', time() + DAY_IN_SECONDS * 30, COOKIEPATH, COOKIE_DOMAIN );
		} else {
			setcookie( self::COOKIE_NAME, '', time() - 3600, COOKIEPATH, COOKIE_DOMAIN );
		}
	}

	/**
	 * Query Monitorの有効化チェックボックスの値に応じて、QMを有効化する
	 *
	 * @return void
	 */
	public function control_qm_activation_for_user() {
		if ( ! isset( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			define( 'QM_DISABLED', true );
		}
	}

	/**
	 * Query Monitorがユーザーに対して有効化されているかチェック
	 *
	 * @return bool
	 */
	private function is_qm_enabled_for_user() {
		if ( ! isset( $_COOKIE[ self::COOKIE_NAME ] ) ) {
			return false;
		}

		if ( ! is_plugin_active( 'query-monitor/query-monitor.php' ) ) {
			return false;
		}

		return true;
	}

	/**
	 * ツールバーに「QM4U」の表示を追加
	 *
	 * @param WP_Admin_Bar $wp_admin_bar
	 * @return void
	 */
	public function add_user_specific_indicator( WP_Admin_Bar $wp_admin_bar ) {
		if ( ! $this->is_qm_enabled_for_user() ) {
			return;
		}

		$wp_admin_bar->add_node( [
			'id'    => 'qm-user-specific',
			'title' => 'QM4U',
			'meta'  => [
				'class' => 'qm-user-specific-indicator',
			],
		] );
	}

	/**
	 * QM4Uメニューアイテムのスタイルを追加
	 *
	 * @return void
	 */
	public function add_qm4u_styles() {
		if ( ! $this->is_qm_enabled_for_user() ) {
			return;
		}

		?>
		<style>
			#wp-admin-bar-qm-user-specific > .ab-item {
				background-color: #dc3232 !important;
				color: #fff !important;
			}
			#wp-admin-bar-qm-user-specific:hover > .ab-item {
				background-color: #a00 !important;
			}
		</style>
		<?php
	}
}

new WP10_User_Specific_QM_Control();
