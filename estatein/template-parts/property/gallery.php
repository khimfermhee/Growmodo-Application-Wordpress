<?php
/**
 * Property gallery: thumbnail strip + 2-up slider (1-up on mobile).
 *
 * @package Estatein
 *
 * @var array $args { images: array, title: string }
 */

defined( 'ABSPATH' ) || exit;

$estatein_images = array_values( array_filter( (array) $args['images'] ) );
if ( ! $estatein_images ) {
	return;
}
$estatein_count = count( $estatein_images );
?>
<div class="card gallery" data-gallery role="group" aria-roledescription="<?php esc_attr_e( 'carousel', 'estatein' ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: property */ __( '%s photos', 'estatein' ), $args['title'] ) ); ?>">
	<div class="gallery__thumbs" role="group" aria-label="<?php esc_attr_e( 'Choose photo', 'estatein' ); ?>">
		<?php foreach ( $estatein_images as $estatein_i => $estatein_image ) : ?>
			<button class="gallery__thumb<?php echo 0 === $estatein_i ? ' is-active' : ''; ?>" type="button" aria-pressed="<?php echo 0 === $estatein_i ? 'true' : 'false'; ?>" data-gallery-thumb="<?php echo (int) $estatein_i; ?>">
				<?php echo estatein_image( $estatein_image, 'estatein-thumb', array( 'alt' => '', 'sizes' => '125px' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: 1: number, 2: total */ __( 'Photo %1$d of %2$d', 'estatein' ), $estatein_i + 1, $estatein_count ) ); ?></span>
			</button>
		<?php endforeach; ?>
	</div>

	<div class="gallery__track" data-gallery-track tabindex="0" aria-label="<?php esc_attr_e( 'Photos (scroll horizontally)', 'estatein' ); ?>">
		<?php foreach ( $estatein_images as $estatein_i => $estatein_image ) : ?>
			<div class="gallery__slide" role="group" aria-roledescription="<?php esc_attr_e( 'slide', 'estatein' ); ?>" aria-label="<?php echo esc_attr( sprintf( '%d / %d', $estatein_i + 1, $estatein_count ) ); ?>">
				<?php
				echo estatein_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					$estatein_image,
					'estatein-gallery',
					array(
						'alt'     => sprintf( /* translators: 1: property, 2: number */ __( '%1$s – photo %2$d', 'estatein' ), $args['title'], $estatein_i + 1 ),
						'sizes'   => '(min-width: 1200px) 600px, (min-width: 768px) 45vw, 90vw',
						'loading' => $estatein_i < 2 ? 'eager' : 'lazy',
					)
				);
				?>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="gallery__controls">
		<button class="carousel__arrow" type="button" data-gallery-prev disabled>
			<span class="screen-reader-text"><?php esc_html_e( 'Previous photo', 'estatein' ); ?></span>
			<?php echo estatein_icon( 'arrow-left' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
		<div class="gallery__dots" data-gallery-dots aria-hidden="true"></div>
		<button class="carousel__arrow" type="button" data-gallery-next>
			<span class="screen-reader-text"><?php esc_html_e( 'Next photo', 'estatein' ); ?></span>
			<?php echo estatein_icon( 'arrow-right' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>
</div>
