<?php
/**
 * Section heading: sparkle mark, title, intro text and optional button.
 *
 * @package Estatein
 *
 * @var array $args {
 *     @type string $title        Heading text.
 *     @type string $text         Intro paragraph.
 *     @type string $button_label Optional button label.
 *     @type string $button_url   Optional button URL.
 *     @type string $id           ID for the heading (aria-labelledby target).
 *     @type string $tag          Heading tag, default h2.
 *     @type bool   $sparkle      Show the sparkle mark (default true).
 *     @type string $class        Extra class.
 * }
 */

defined( 'ABSPATH' ) || exit;

$estatein_args = wp_parse_args(
	$args,
	array(
		'title'        => '',
		'text'         => '',
		'button_label' => '',
		'button_url'   => '',
		'id'           => '',
		'tag'          => 'h2',
		'sparkle'      => true,
		'class'        => '',
	)
);
$estatein_tag  = in_array( $estatein_args['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $estatein_args['tag'] : 'h2';
$estatein_has_button = $estatein_args['button_label'] && $estatein_args['button_url'];
?>
<div class="section-heading<?php echo $estatein_has_button ? ' section-heading--with-button' : ''; ?> <?php echo esc_attr( $estatein_args['class'] ); ?>">
	<div class="section-heading__copy">
		<?php
		if ( $estatein_args['sparkle'] ) {
			estatein_sparkle();
		}
		?>
		<<?php echo esc_html( $estatein_tag ); ?> class="section-heading__title"<?php echo $estatein_args['id'] ? ' id="' . esc_attr( $estatein_args['id'] ) . '"' : ''; ?>><?php echo esc_html( $estatein_args['title'] ); ?></<?php echo esc_html( $estatein_tag ); ?>>
		<?php if ( $estatein_args['text'] ) : ?>
			<p class="section-heading__text"><?php echo esc_html( $estatein_args['text'] ); ?></p>
		<?php endif; ?>
	</div>
	<?php if ( $estatein_has_button ) : ?>
		<?php estatein_button( $estatein_args['button_label'], $estatein_args['button_url'], 'secondary', array( 'class' => 'section-heading__button' ) ); ?>
	<?php endif; ?>
</div>
