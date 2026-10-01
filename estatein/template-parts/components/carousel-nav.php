<?php
/**
 * Carousel footer: "01 of 10" counter and previous/next buttons.
 * On mobile the section button moves here (left of the arrows), as in the design.
 *
 * @package Estatein
 *
 * @var array $args { label: string, button_label: string, button_url: string, total: int }
 */

defined( 'ABSPATH' ) || exit;

$estatein_args = wp_parse_args(
	$args,
	array(
		'label'        => '',
		'button_label' => '',
		'button_url'   => '',
		'total'        => 1,
	)
);
?>
<div class="carousel__nav">
	<?php if ( $estatein_args['button_label'] && $estatein_args['button_url'] ) : ?>
		<?php estatein_button( $estatein_args['button_label'], $estatein_args['button_url'], 'secondary', array( 'class' => 'carousel__mobile-button' ) ); ?>
	<?php endif; ?>
	<p class="carousel__count" aria-live="polite">
		<span class="carousel__current" data-carousel-current>01</span>
		<?php esc_html_e( 'of', 'estatein' ); ?>
		<span data-carousel-total><?php echo esc_html( estatein_pad( $estatein_args['total'] ) ); ?></span>
	</p>
	<div class="carousel__arrows">
		<button class="carousel__arrow" type="button" data-carousel-prev disabled>
			<span class="screen-reader-text">
				<?php
				/* translators: %s: carousel name */
				printf( esc_html__( 'Previous %s', 'estatein' ), esc_html( $estatein_args['label'] ) );
				?>
			</span>
			<?php echo estatein_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
		<button class="carousel__arrow" type="button" data-carousel-next>
			<span class="screen-reader-text">
				<?php
				/* translators: %s: carousel name */
				printf( esc_html__( 'Next %s', 'estatein' ), esc_html( $estatein_args['label'] ) );
				?>
			</span>
			<?php echo estatein_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>
</div>
