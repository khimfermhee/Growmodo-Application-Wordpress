<?php
/**
 * FAQ teaser card – "Read More" opens the answer on the FAQ page.
 *
 * @package Estatein
 *
 * @var array $args { item: { question, summary, slug } }
 */

defined( 'ABSPATH' ) || exit;

$estatein_f   = $args['item'];
$estatein_url = get_post_type_archive_link( 'faq' );
$estatein_url = ( $estatein_url ? $estatein_url : home_url( '/faqs/' ) ) . '#' . $estatein_f['slug'];
?>
<article class="card faq-card">
	<h3 class="faq-card__question"><?php echo esc_html( $estatein_f['question'] ); ?></h3>
	<p class="faq-card__summary"><?php echo esc_html( $estatein_f['summary'] ); ?></p>
	<a class="btn btn--secondary faq-card__button" href="<?php echo esc_url( $estatein_url ); ?>">
		<?php esc_html_e( 'Read More', 'estatein' ); ?>
		<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: question */ __( 'about: %s', 'estatein' ), $estatein_f['question'] ) ); ?></span>
	</a>
</article>
