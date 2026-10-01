<?php
/**
 * Four quick-link cards (Find Your Dream Home …), shared by Home and Services.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_links = estatein_option( 'quick_links' );
if ( ! $estatein_links ) {
	return;
}
?>
<section class="quick-links" id="features" aria-label="<?php esc_attr_e( 'What we offer', 'estatein' ); ?>">
	<ul class="quick-links__grid">
		<?php foreach ( $estatein_links as $estatein_link ) : ?>
			<li>
				<a class="quick-links__card" href="<?php echo esc_url( estatein_url( $estatein_link['url'] ) ); ?>">
					<?php echo estatein_icon( 'arrow-up-right', array( 'class' => 'quick-links__arrow', 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php echo estatein_icon_badge( $estatein_link['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="quick-links__title"><?php echo esc_html( $estatein_link['title'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>
