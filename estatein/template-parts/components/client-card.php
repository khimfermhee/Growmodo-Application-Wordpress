<?php
/**
 * Client card (About → Our Valued Clients).
 *
 * @package Estatein
 *
 * @var array $args { item: { name, since, domain, category, quote, website } }
 */

defined( 'ABSPATH' ) || exit;

$estatein_c = $args['item'];
?>
<article class="card client-card">
	<div class="client-card__head">
		<div>
			<?php if ( $estatein_c['since'] ) : ?>
				<p class="client-card__since"><?php echo esc_html( sprintf( /* translators: %s: year */ __( 'Since %s', 'estatein' ), $estatein_c['since'] ) ); ?></p>
			<?php endif; ?>
			<h3 class="client-card__name"><?php echo esc_html( $estatein_c['name'] ); ?></h3>
		</div>
		<?php if ( $estatein_c['website'] ) : ?>
			<a class="btn btn--secondary client-card__button" href="<?php echo esc_url( $estatein_c['website'] ); ?>" target="_blank" rel="noopener noreferrer">
				<?php esc_html_e( 'Visit Website', 'estatein' ); ?>
				<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: company */ __( 'of %s (opens in a new tab)', 'estatein' ), $estatein_c['name'] ) ); ?></span>
			</a>
		<?php endif; ?>
	</div>
	<dl class="client-card__facts">
		<div>
			<dt><?php echo estatein_icon( 'layout-grid', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Domain', 'estatein' ); ?></dt>
			<dd><?php echo esc_html( $estatein_c['domain'] ); ?></dd>
		</div>
		<div>
			<dt><?php echo estatein_icon( 'zap', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Category', 'estatein' ); ?></dt>
			<dd><?php echo esc_html( $estatein_c['category'] ); ?></dd>
		</div>
	</dl>
	<?php if ( $estatein_c['quote'] ) : ?>
		<blockquote class="client-card__quote">
			<p class="client-card__quote-label"><?php esc_html_e( 'What They Said', 'estatein' ); ?> <span aria-hidden="true">🤗</span></p>
			<p><?php echo esc_html( $estatein_c['quote'] ); ?></p>
		</blockquote>
	<?php endif; ?>
</article>
