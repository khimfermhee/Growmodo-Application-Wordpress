<?php
/**
 * One services section in either layout:
 *  - grid:  heading on top, 3-column cards, CTA card fills the last row.
 *  - split: heading + CTA card on the left, 2×2 cards on the right.
 *
 * @package Estatein
 *
 * @var array $args { section: array }
 */

defined( 'ABSPATH' ) || exit;

$estatein_s  = $args['section'];
$estatein_id = $estatein_s['slug'];

$estatein_cta = function () use ( $estatein_s ) {
	if ( ! $estatein_s['cta_title'] ) {
		return;
	}
	?>
	<div class="card card--pattern service-cta">
		<div class="service-cta__head">
			<h3 class="service-cta__title"><?php echo esc_html( $estatein_s['cta_title'] ); ?></h3>
			<?php estatein_button( $estatein_s['cta_label'], $estatein_s['cta_url'], 'secondary', array( 'class' => 'service-cta__button' ) ); ?>
		</div>
		<p class="service-cta__text"><?php echo esc_html( $estatein_s['cta_text'] ); ?></p>
	</div>
	<?php
};

$estatein_cards = function () use ( $estatein_s ) {
	foreach ( $estatein_s['services'] as $estatein_service ) {
		?>
		<li class="card service-card">
			<div class="service-card__head">
				<?php echo estatein_icon_badge( $estatein_service['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<h3 class="service-card__title"><?php echo esc_html( $estatein_service['title'] ); ?></h3>
			</div>
			<p><?php echo esc_html( $estatein_service['text'] ); ?></p>
		</li>
		<?php
	}
};
?>
<section class="section services-section services-section--<?php echo esc_attr( $estatein_s['layout'] ); ?>" id="<?php echo esc_attr( $estatein_id ); ?>" aria-labelledby="<?php echo esc_attr( $estatein_id ); ?>-title">
	<div class="container">
		<?php if ( 'split' === $estatein_s['layout'] ) : ?>
			<div class="services-split">
				<div class="services-split__intro">
					<?php
					estatein_part(
						'components/section-heading',
						array(
							'title' => $estatein_s['name'],
							'text'  => $estatein_s['intro'],
							'id'    => $estatein_id . '-title',
						)
					);
					$estatein_cta();
					?>
				</div>
				<ul class="services-split__grid">
					<?php $estatein_cards(); ?>
				</ul>
			</div>
		<?php else : ?>
			<?php
			estatein_part(
				'components/section-heading',
				array(
					'title' => $estatein_s['name'],
					'text'  => $estatein_s['intro'],
					'id'    => $estatein_id . '-title',
				)
			);
			?>
			<ul class="services-grid">
				<?php $estatein_cards(); ?>
				<li class="services-grid__cta"><?php $estatein_cta(); ?></li>
			</ul>
		<?php endif; ?>
	</div>
</section>
