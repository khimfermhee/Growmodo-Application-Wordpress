<?php
/**
 * All testimonials ("View All Testimonials").
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		'title' => estatein_default( 'home.testimonials_title' ),
		'text'  => estatein_default( 'home.testimonials_text' ),
	)
);
?>
<section class="section" aria-label="<?php esc_attr_e( 'Testimonials', 'estatein' ); ?>">
	<div class="container">
		<ul class="grid grid--3">
			<?php foreach ( estatein_get_testimonials( 50 ) as $estatein_item ) : ?>
				<li><?php estatein_part( 'components/testimonial-card', array( 'item' => $estatein_item ) ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
<?php
get_footer();
