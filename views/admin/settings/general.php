<?php
/**
 * General settings view
 *
 * @package ts-fic
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}
?>
<div class="wrap">
	<?php
	TS_FIC_Main::load_template(
		'admin/settings/parts/notice',
		array(
			'success' => $success,
			'message' => $message,
		)
	);
	?>

	<h1><?php esc_html_e( 'Featured Image Caption - General Settings', 'ts-fic' ); ?></h1>

	<form action="" method="POST">
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label for="ts_fic_enable_auto_captions">
							<?php esc_html_e( 'Enable Automatic Captions', 'ts-fic' ); ?>
						</label>
					</th>
					<td>
						<input type="checkbox" name="ts_fic_enable_auto_captions" id="ts_fic_enable_auto_captions" value="1" <?php echo checked( get_option( 'ts_fic_enable_auto_captions' ) ); ?>>
						<p class="description"><?php esc_html_e( 'If checked, captions will be automatically shown in pages, posts and everywhere else just below featured image.', 'ts-fic' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<label for="ts_fic_manual_caption">
							<?php esc_html_e( 'Manual Caption Display', 'ts-fic' ); ?>
						</label>
					</th>
					<td>
						<input type="text" name="ts_fic_manual_caption" id="ts_fic_manual_caption" class="regular-text" value="<?php echo esc_html( '<?php echo get_ts_fic_caption( $post_id ); ?>' ); ?>" disabled>
						<p class="description"><?php esc_html_e( 'Use this PHP code to manually show caption.', 'ts-fic' ); ?></p>
						<p class="description"><?php echo wp_kses_post( '<b>$post_id</b> is optional. If empty, current post ID will be used.', 'ts-fic' ); ?></p>
					</td>
				</tr>
			</tbody>
		</table>
		<p class="submit">
			<input type="submit" name="ts_fic_general_settings_save" class="button button-primary" value="<?php esc_html_e( 'Save Changes', 'ts-fic' ); ?>">
		</p>
		<input type="hidden" name="ts_fic_settings_nonce" value="<?php echo esc_attr( $nonce ); ?>">
	</form>
</div>
