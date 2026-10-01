<?php
/**
 * "Start Your Real Estate Journey Today" band shown above the footer.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_title = estatein_option( 'cta_title' );
if ( ! $estatein_title ) {
	return;
}
?>
<section class="cta-band" aria-labelledby="cta-title">
	<div class="container cta-band__inner">
		<div class="cta-band__copy">
			<h2 class="cta-band__title" id="cta-title"><?php echo esc_html( $estatein_title ); ?></h2>
			<p class="cta-band__text"><?php echo esc_html( estatein_option( 'cta_text' ) ); ?></p>
		</div>
		<?php estatein_button( estatein_option( 'cta_button_label' ), estatein_option( 'cta_button_url' ), 'primary', array( 'class' => 'cta-band__button' ) ); ?>
	</div>
</section>
