<?php
/**
 * Statistic boxes (200+ Happy Customers …), shared by Home and About.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_stats = estatein_rows( estatein_option( 'stats' ), array( 'number', 'label' ), estatein_default( 'options.stats' ) );
if ( ! $estatein_stats ) {
	return;
}
?>
<ul class="stats">
	<?php foreach ( $estatein_stats as $estatein_stat ) : ?>
		<li class="stats__item">
			<span class="stats__number"><?php echo esc_html( $estatein_stat['number'] ); ?></span>
			<span class="stats__label"><?php echo esc_html( $estatein_stat['label'] ); ?></span>
		</li>
	<?php endforeach; ?>
</ul>
