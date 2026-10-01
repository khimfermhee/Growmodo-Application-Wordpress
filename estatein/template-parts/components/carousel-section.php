<?php
/**
 * Reusable "heading + card carousel" section (properties, testimonials,
 * FAQs, clients). Cards are rendered by the template part named in $args['card'].
 *
 * @package Estatein
 *
 * @var array $args {
 *     @type string $id        Section ID (anchor).
 *     @type array  $heading   Args for components/section-heading.
 *     @type array  $items     Card data.
 *     @type string $card      Template part slug for one card.
 *     @type string $item_key  Arg name the card expects for its data.
 *     @type array  $card_args Extra args passed to every card.
 *     @type string $label     Accessible carousel name.
 *     @type string $modifier  CSS modifier (cols-3 / cols-2).
 * }
 */

defined( 'ABSPATH' ) || exit;

$estatein_args = wp_parse_args(
	$args,
	array(
		'id'        => '',
		'heading'   => array(),
		'items'     => array(),
		'card'      => '',
		'item_key'  => 'item',
		'card_args' => array(),
		'label'     => '',
		'modifier'  => 'cols-3',
	)
);

if ( ! $estatein_args['items'] ) {
	return;
}

$estatein_heading_id = $estatein_args['id'] . '-title';
$estatein_heading    = array_merge( $estatein_args['heading'], array( 'id' => $estatein_heading_id ) );
$estatein_count      = count( $estatein_args['items'] );
?>
<section class="section" id="<?php echo esc_attr( $estatein_args['id'] ); ?>" aria-labelledby="<?php echo esc_attr( $estatein_heading_id ); ?>">
	<div class="container">
		<?php estatein_part( 'components/section-heading', $estatein_heading ); ?>

		<div class="carousel carousel--<?php echo esc_attr( $estatein_args['modifier'] ); ?>" data-carousel role="group" aria-roledescription="<?php esc_attr_e( 'carousel', 'estatein' ); ?>" aria-label="<?php echo esc_attr( $estatein_args['label'] ); ?>">
			<div class="carousel__track" data-carousel-track tabindex="0">
				<?php foreach ( $estatein_args['items'] as $estatein_i => $estatein_item ) : ?>
					<div class="carousel__slide" role="group" aria-roledescription="<?php esc_attr_e( 'slide', 'estatein' ); ?>" aria-label="<?php echo esc_attr( sprintf( '%d / %d', $estatein_i + 1, $estatein_count ) ); ?>">
						<?php estatein_part( $estatein_args['card'], array_merge( $estatein_args['card_args'], array( $estatein_args['item_key'] => $estatein_item ) ) ); ?>
					</div>
				<?php endforeach; ?>
			</div>
			<?php
			estatein_part(
				'components/carousel-nav',
				array(
					'label'        => $estatein_args['label'],
					'button_label' => $estatein_heading['button_label'] ?? '',
					'button_url'   => $estatein_heading['button_url'] ?? '',
					'total'        => $estatein_count,
				)
			);
			?>
		</div>
	</div>
</section>
