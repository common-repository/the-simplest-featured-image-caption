<?php
/**
 * Plugin settings
 *
 * @package ts-fic
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Settings class
 */
final class TS_FIC_Settings {
	/**
	 * Class instance
	 *
	 * @var TS_FIC_Settings
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return TS_FIC_Settings
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// register submenu.
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
	}

	/**
	 * Register submenu
	 *
	 * @return void
	 */
	public function register_menu() {
		// main page.
		add_menu_page( __( 'General Settings', 'ts-fic' ), __( 'Featured Image Caption', 'ts-fic' ), 'manage_options', 'ts-fic-settings', array( $this, 'show_general_settings' ), 'dashicons-clipboard', 107 );

		// submenu pages.
		add_submenu_page( 'ts-fic-settings', __( 'General Settings', 'ts-fic' ), __( 'General Settings', 'ts-fic' ), 'manage_options', 'ts-fic-settings', array( $this, 'show_general_settings' ), 5 );
		add_submenu_page( 'ts-fic-settings', __( 'Donate', 'ts-fic' ), __( 'Donate', 'ts-fic' ), 'manage_options', 'ts-fic-settings-donate', array( $this, 'show_donate_settings' ), 5 );
	}

	/**
	 * Display general settings
	 *
	 * @return void
	 */
	public function show_general_settings() {
		$saved = $this->save_general_settings();

		TS_FIC_Main::load_template(
			'admin/settings/general',
			array(
				'success' => $saved['success'],
				'message' => $saved['message'],
				'nonce'   => wp_create_nonce( 'ts_fic_settings_nonce' ),
			)
		);
	}

	/**
	 * Display donate settings
	 *
	 * @return void
	 */
	public function show_donate_settings() {
		TS_FIC_Main::load_template( 'admin/settings/donate' );
	}

	/**
	 * Save general settings on submit
	 *
	 * @return array
	 */
	private function save_general_settings() {
		// nonce verification.
		if ( isset( $_POST['ts_fic_settings_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ts_fic_settings_nonce'] ), 'ts_fic_settings_nonce' ) ) {
			// update only if submit in this specific page is pressed.
			if ( isset( $_POST['ts_fic_general_settings_save'] ) ) {
				// update API key.
				if ( isset( $_POST['ts_fic_enable_auto_captions'] ) ) {
					$enable_auto_captions = sanitize_text_field( wp_unslash( $_POST['ts_fic_enable_auto_captions'] ) );
				}

				update_option( 'ts_fic_enable_auto_captions', $enable_auto_captions );

				return array(
					'success' => true,
					'message' => __( 'Settings saved.', 'ts_fic-calendar' ),
				);
			}
		}

		return array(
			'success' => false,
			'message' => false,
		);
	}
}
