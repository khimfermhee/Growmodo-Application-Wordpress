<?php
/**
 * Office locations with All / Regional / International filter.
 *
 * Filtering is progressive enhancement: without JS every office is listed.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_offices = estatein_get_offices();
if ( ! $estatein_offices ) {
	return;
}
$estatein_tabs = array(
	'all'           => __( 'All', 'estatein' ),
	'regional'      => __( 'Regional', 'estatein' ),
	'international' => __( 'International', 'estatein' ),
);
?>
<section class="section" id="offices" aria-labelledby="offices-title" data-office-filter>
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'contact', 'offices_title' ),
				'text'  => estatein_page_field( 'contact', 'offices_text' ),
				'id'    => 'offices-title',
			)
		);
		?>

		<div class="segmented" role="group" aria-label="<?php esc_attr_e( 'Filter offices', 'estatein' ); ?>">
			<?php foreach ( $estatein_tabs as $estatein_key => $estatein_label ) : ?>
				<button class="segmented__button" type="button" data-filter="<?php echo esc_attr( $estatein_key ); ?>" aria-pressed="<?php echo 'all' === $estatein_key ? 'true' : 'false'; ?>"><?php echo esc_html( $estatein_label ); ?></button>
			<?php endforeach; ?>
		</div>

		<ul class="grid grid--2 offices">
			<?php foreach ( $estatein_offices as $estatein_office ) : ?>
				<li class="card office-card" data-type="<?php echo esc_attr( $estatein_office['type'] ); ?>">
					<p class="office-card__label"><?php echo esc_html( $estatein_office['label'] ); ?></p>
					<h3 class="office-card__address"><?php echo esc_html( $estatein_office['address'] ); ?></h3>
					<p class="office-card__text"><?php echo esc_html( $estatein_office['text'] ); ?></p>
					<ul class="office-card__meta">
						<?php if ( $estatein_office['email'] ) : ?>
							<li><a class="pill pill--icon" href="<?php echo esc_url( 'mailto:' . $estatein_office['email'] ); ?>"><?php echo estatein_icon( 'mail', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $estatein_office['email'] ); ?></a></li>
						<?php endif; ?>
						<?php if ( $estatein_office['phone'] ) : ?>
							<li><a class="pill pill--icon" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $estatein_office['phone'] ) ); ?>"><?php echo estatein_icon( 'phone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $estatein_office['phone'] ); ?></a></li>
						<?php endif; ?>
						<?php if ( $estatein_office['city'] ) : ?>
							<li><span class="pill pill--icon"><?php echo estatein_icon( 'pin-solid', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $estatein_office['city'] ); ?></span></li>
						<?php endif; ?>
					</ul>
					<?php if ( $estatein_office['map'] ) : ?>
						<a class="btn btn--primary btn--block btn--lg" href="<?php echo esc_url( $estatein_office['map'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Get Direction', 'estatein' ); ?>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: address */ __( 'to %s (opens in a new tab)', 'estatein' ), $estatein_office['address'] ) ); ?></span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
