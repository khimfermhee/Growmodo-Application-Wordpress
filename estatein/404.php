<?php
/**
 * 404 page.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="section error-404" aria-labelledby="page-title">
	<div class="container container--narrow">
		<p class="error-404__code" aria-hidden="true">404</p>
		<h1 class="page-hero__title" id="page-title"><?php esc_html_e( 'This property has left the market', 'estatein' ); ?></h1>
		<p class="page-hero__text"><?php esc_html_e( 'The page you are looking for doesn\'t exist or has moved. Let\'s get you back on track.', 'estatein' ); ?></p>
		<div class="hero__actions">
			<?php estatein_button( __( 'Back to Home', 'estatein' ), home_url( '/' ), 'secondary', array( 'class' => 'btn--lg' ) ); ?>
			<?php estatein_button( __( 'Browse Properties', 'estatein' ), estatein_properties_url(), 'primary', array( 'class' => 'btn--lg' ) ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
