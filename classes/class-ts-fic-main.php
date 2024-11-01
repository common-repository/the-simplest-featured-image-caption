<?php
/**
 * Main plugin class
 *
 * @package ts-fic
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}

/**
 * Main class
 */
final class TS_FIC_Main {
	/**
	 * Class instance
	 *
	 * @var TS_FIC_Main
	 */
	protected static $instance = null;

	/**
	 * Main instance
	 *
	 * @return TS_FIC_Main
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Class constructor, loads main actions, methods etc.
	 */
	public function __construct() {
		// admin settings.
		add_action( 'init', array( 'TS_FIC_Settings', 'instance' ) );

		// register meta (for featured image caption).
		add_action( 'init', array( $this, 'register_meta' ) );

		// admin javascript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// show field in classic editor.
		add_filter( 'admin_post_thumbnail_html', array( $this, 'show_classic_editor_field' ), 10, 2 );

		// save field in classic editor.
		add_action( 'pre_post_update', array( $this, 'save_classic_editor_field' ), 10, 2 );

		// automatically show caption if enabled.
		$is_automatic_caption = get_option( 'ts_fic_enable_auto_captions' );

		if ( ! empty( $is_automatic_caption ) ) {
			add_filter( 'post_thumbnail_html', array( $this, 'show_caption' ), 10, 5 );
		}
	}

	/**
	 * Rewrite plugin template with theme template if exists under folder /theme-name/smoobu-calendar/
	 *
	 * @param string $template template name.
	 * @param array  $args arguments to pass to template.
	 * @return void
	 */
	public static function load_template( $template, $args = array() ) {
		// transfer required arguments.
		foreach ( $args as $key => $arg ) {
			${$key} = $arg;
		}

		// load template below.
		if ( file_exists( get_template_directory() . TS_FIC_NAME . '/' . $template . '.php' ) ) {
			// if overriden in theme.
			$load = get_template_directory() . TS_FIC_NAME . '/' . $template . '.php';
		} elseif ( file_exists( TS_FIC_PATH . 'views/' . $template . '.php' ) ) {
			// if exists at all.
			$load = TS_FIC_PATH . 'views/' . $template . '.php';
		}

		if ( ! empty( $load ) ) {
			include $load;
		} else {
			esc_html_e( 'Template not found', 'smoobu-calendar' );
		}
	}

	/**
	 * Enqueue admin scripts
	 *
	 * @return void
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( 'ts-fic-admin-featured-image-js', TS_FIC_URI . '/assets/js/admin/featured-image.js', array(), TS_FIC_VERSION, true );
	}

	/**
	 * Register meta for Gutenberg editor
	 *
	 * @return void
	 */
	public function register_meta() {
		register_meta(
			'post',
			'ts_fic_featured_image_caption',
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);
	}

	/**
	 * Show caption field in classic editor
	 *
	 * @param string $content featured image meta box content.
	 * @param int    $post_id post id.
	 * @return string
	 */
	public function show_classic_editor_field( $content, $post_id ) {
		$caption = get_post_meta( $post_id, 'ts_fic_featured_image_caption', true );

		$field  = '<p class="description">Featured Image Caption:</p>';
		$field .= '<input type="text" name="ts_fic_featured_image_caption" value="' . esc_attr( $caption ) . '">';

		return $content .= $field;
	}

	/**
	 * Save caption in classic editor
	 *
	 * @param int   $post_id post id.
	 * @param array $data post data.
	 * @return void
	 */
	public function save_classic_editor_field( $post_id, $data ) {
		if ( isset( $_POST['ts_fic_featured_image_caption'] ) ) {
			$caption = sanitize_text_field( wp_unslash( $_POST['ts_fic_featured_image_caption'] ) );

			update_post_meta( $post_id, 'ts_fic_featured_image_caption', $caption );
		}
	}

	public function show_caption( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
		$caption = self::get_caption( $post_id );

		if ( ! empty( $caption ) ) {
			$html .= '<span class="ts-fic-caption">' . esc_html( $caption ) . '</span>';
		}

		return $html;
	}

	/**
	 * Get caption for a post
	 *
	 * @param int|bool $post_id post id.
	 * @return string
	 */
	public static function get_caption( $post_id ) {
		global $post;

		$post_id = sanitize_text_field( wp_unslash( $post_id ) );

		if ( ! empty( $post_id ) &&  filter_var( $post_id, FILTER_VALIDATE_INT ) ) {
			$caption = get_post_meta( $post_id, 'ts_fic_featured_image_caption', true );
		} else {
			$caption = get_post_meta( $post->ID, 'ts_fic_featured_image_caption', true );
		}

		return $caption;
	}
}
