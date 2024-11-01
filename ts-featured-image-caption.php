<?php
/**
 * Plugin Name: The Simplest: Featured Image Caption
 * Description: Add caption for featured image.
 * Version: 1.0.0
 * Author: thesimplestwp // Mindaugas Jakubauskas
 * Author URI: https://thesimplestwp.com
 * Text Domain: ts-fic
 * Domain Path: /languages
 *
 * @package ts-fic
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

// constants.
require_once plugin_dir_path( __FILE__ ) . 'constants.php';

// required classes.
require_once TS_FIC_PATH . 'classes/class-ts-fic-main.php';
require_once TS_FIC_PATH . 'classes/class-ts-fic-settings.php';

/**
 * Initialize main class
 *
 * @return bool|TS_FIC_Main
 */
function ts_fic_main_init() {
	if ( ! class_exists( 'TS_FIC_Main' ) ) {
		return false;
	} else {
		return TS_FIC_Main::instance();
	}
}

// initialize.
ts_fic_main_init();

/**
 * Display caption function holder.
 *
 * @param int $post_id post id.
 * @return string
 */
function get_ts_fic_caption( $post_id = false ) {
	return TS_FIC_Main::get_caption( $post_id );
}
