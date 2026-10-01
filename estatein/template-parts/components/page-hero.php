<?php
/**
 * Inner page hero: large title + intro on a soft gradient band.
 *
 * @package Estatein
 *
 * @var array $args { title, text, class }
 */

defined( 'ABSPATH' ) || exit;

$estatein_args = wp_parse_args(
	$args,
	array(
		'title' => get_the_title(),
		'text'  => '',
		'class' => '',
	)
);
?>
<section class="page-hero <?php echo esc_attr( $estatein_args['class'] ); ?>" aria-labelledby="page-title">
	<div class="container">
		<h1 class="page-hero__title" id="page-title"><?php echo esc_html( $estatein_args['title'] ); ?></h1>
		<?php if ( $estatein_args['text'] ) : ?>
			<p class="page-hero__text"><?php echo esc_html( $estatein_args['text'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
