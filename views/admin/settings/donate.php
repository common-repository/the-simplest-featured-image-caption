<?php
/**
 * Donate settings view
 *
 * @package ts-fic
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No skiddies please!' );
}
?>
<div class="wrap">

	<h1><?php esc_html_e( 'Featured Image Caption - Donate', 'ts-fic' ); ?></h1>

	<p>
		<?php 
		echo sprintf(
			wp_kses_post( 'If you found this plugin useful, you can always <a href="%s" target="_blank">buy me a coffee</a>. Your support and doantions will help me keep maintain this plugin and keep it up to date!', 'ts-fic' ),
			'https://thesimplestwp.com'
		);
		?>
	</P>
</div>
