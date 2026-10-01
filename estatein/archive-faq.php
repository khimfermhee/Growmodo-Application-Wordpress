<?php
/**
 * All FAQs as an accessible accordion (native <details>). Links from the
 * FAQ cards (#question-slug) open the matching answer.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		'title' => estatein_default( 'home.faq_title' ),
		'text'  => estatein_default( 'home.faq_text' ),
	)
);
?>
<section class="section" aria-label="<?php esc_attr_e( 'Questions and answers', 'estatein' ); ?>">
	<div class="container container--narrow">
		<div class="accordion" data-accordion>
			<?php foreach ( estatein_get_faqs( 50 ) as $estatein_i => $estatein_faq ) : ?>
				<details class="card accordion__item" id="<?php echo esc_attr( $estatein_faq['slug'] ); ?>"<?php echo 0 === $estatein_i ? ' open' : ''; ?>>
					<summary class="accordion__question">
						<span><?php echo esc_html( $estatein_faq['question'] ); ?></span>
						<?php echo estatein_icon( 'chevron-down', array( 'class' => 'accordion__chevron' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</summary>
					<div class="accordion__answer entry__content"><?php echo wp_kses_post( $estatein_faq['answer'] ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
		<div class="card empty-state">
			<h2><?php esc_html_e( 'Still have questions?', 'estatein' ); ?></h2>
			<p><?php esc_html_e( 'Our agents are happy to help with anything not covered here.', 'estatein' ); ?></p>
			<?php estatein_button( __( 'Contact Us', 'estatein' ), estatein_option( 'header_button_url' ), 'primary' ); ?>
		</div>
	</div>
</section>
<?php
get_footer();
