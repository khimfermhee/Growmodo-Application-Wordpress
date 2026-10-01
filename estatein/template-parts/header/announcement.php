<?php
/**
 * Dismissible announcement bar (state remembered in localStorage).
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

// true_false needs explicit handling: an unchecked toggle must hide the bar.
$estatein_enabled = function_exists( 'get_field' ) ? get_field( 'announcement_enabled', 'option' ) : null;
if ( null !== $estatein_enabled && '' !== $estatein_enabled && ! $estatein_enabled ) {
	return;
}

$estatein_text = estatein_option( 'announcement_text' );
$estatein_link = estatein_option( 'announcement_link' );
$estatein_url  = estatein_url( estatein_option( 'announcement_url' ) );
?>
<div class="announcement" data-announcement role="region" aria-label="<?php esc_attr_e( 'Announcement', 'estatein' ); ?>">
	<p class="announcement__text">
		<span aria-hidden="true">✨</span><?php echo esc_html( $estatein_text ); ?>
		<?php if ( $estatein_link && $estatein_url ) : ?>
			<a href="<?php echo esc_url( $estatein_url ); ?>"><?php echo esc_html( $estatein_link ); ?></a>
		<?php endif; ?>
	</p>
	<button class="announcement__close" type="button" data-announcement-close>
		<span class="screen-reader-text"><?php esc_html_e( 'Dismiss announcement', 'estatein' ); ?></span>
		<?php echo estatein_icon( 'x', array( 'size' => 14 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</button>
</div>
