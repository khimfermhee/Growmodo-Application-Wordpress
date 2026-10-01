<?php
/**
 * "Comprehensive Pricing Details" section.
 *
 * @package Estatein
 *
 * @var array $args { groups: array, price: mixed }
 */

defined( 'ABSPATH' ) || exit;

$estatein_note = estatein_option( 'pricing_note' );
?>
<section class="section pricing" id="pricing" aria-labelledby="pricing-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => __( 'Comprehensive Pricing Details', 'estatein' ),
				/* translators: %s: property name */
				'text'  => sprintf( __( 'At Estatein, transparency is key. We want you to have a clear understanding of all costs associated with your property investment. Below, we break down the pricing for %s to help you make an informed decision', 'estatein' ), get_the_title() ),
				'id'    => 'pricing-title',
			)
		);
		?>

		<?php if ( $estatein_note ) : ?>
			<p class="pricing__note"><strong><?php esc_html_e( 'Note', 'estatein' ); ?></strong><span><?php echo esc_html( $estatein_note ); ?></span></p>
		<?php endif; ?>

		<div class="pricing__layout">
			<p class="price price--xl pricing__listing">
				<span class="price__label"><?php esc_html_e( 'Listing Price', 'estatein' ); ?></span>
				<span class="price__value"><?php echo esc_html( estatein_price( $args['price'] ) ); ?></span>
			</p>

			<div class="pricing__groups">
				<?php foreach ( $args['groups'] as $estatein_group ) : ?>
					<?php
					$estatein_group['rows'] = estatein_rows( $estatein_group['rows'], array( 'label', 'amount', 'note', 'full' ) );
					if ( empty( $estatein_group['rows'] ) ) {
						continue;
					}
					?>
					<div class="card pricing-group">
						<div class="pricing-group__head">
							<h3 class="pricing-group__title"><?php echo esc_html( $estatein_group['title'] ); ?></h3>
							<a class="btn btn--secondary" href="#inquire"><?php esc_html_e( 'Learn More', 'estatein' ); ?><span class="screen-reader-text"> <?php echo esc_html( $estatein_group['title'] ); ?></span></a>
						</div>
						<dl class="pricing-group__rows">
							<?php foreach ( $estatein_group['rows'] as $estatein_row ) : ?>
								<div class="pricing-row<?php echo ! empty( $estatein_row['full'] ) ? ' pricing-row--full' : ''; ?>">
									<dt class="pricing-row__label"><?php echo esc_html( $estatein_row['label'] ); ?></dt>
									<dd class="pricing-row__value">
										<span class="pricing-row__amount"><?php echo esc_html( $estatein_row['amount'] ); ?></span>
										<?php if ( ! empty( $estatein_row['note'] ) ) : ?>
											<span class="pill pill--sm"><?php echo esc_html( $estatein_row['note'] ); ?></span>
										<?php endif; ?>
									</dd>
								</div>
							<?php endforeach; ?>
						</dl>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
