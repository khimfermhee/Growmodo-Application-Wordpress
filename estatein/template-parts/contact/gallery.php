<?php
/**
 * "Explore Estatein's World" photo collage.
 *
 * Layout (desktop): wide | wide / wide | half half / copy | wide.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_images = (array) estatein_page_field( 'contact', 'gallery' );
$estatein_images = array_values( array_filter( $estatein_images ) );
if ( ! $estatein_images ) {
	return;
}
?>
<section class="section" id="gallery" aria-labelledby="gallery-title">
	<div class="container">
		<div class="card card--pattern world">
			<?php foreach ( array_slice( $estatein_images, 0, 6 ) as $estatein_i => $estatein_image ) : ?>
				<div class="world__img world__img--<?php echo (int) $estatein_i + 1; ?>">
					<?php echo estatein_image( $estatein_image, 'estatein-wide', array( 'alt' => __( 'Estatein team and workspace', 'estatein' ), 'sizes' => '(min-width: 1200px) 570px, 90vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
			<?php endforeach; ?>
			<div class="world__copy">
				<?php
				estatein_part(
					'components/section-heading',
					array(
						'title' => estatein_page_field( 'contact', 'gallery_title' ),
						'text'  => estatein_page_field( 'contact', 'gallery_text' ),
						'id'    => 'gallery-title',
					)
				);
				?>
			</div>
		</div>
	</div>
</section>
