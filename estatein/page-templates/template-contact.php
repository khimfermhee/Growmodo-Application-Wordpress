<?php
/**
 * Template Name: Contact
 *
 * Hero → contact cards → contact form → office locations (filterable) → gallery.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

estatein_part(
	'components/page-hero',
	array(
		'title' => estatein_page_field( 'contact', 'hero_title' ),
		'text'  => estatein_page_field( 'contact', 'hero_text' ),
	)
);

$estatein_email = estatein_option( 'contact_email' );
$estatein_phone = estatein_option( 'contact_phone' );
?>

<section class="quick-links quick-links--contact" aria-label="<?php esc_attr_e( 'Ways to reach us', 'estatein' ); ?>">
	<ul class="quick-links__grid">
		<li>
			<a class="quick-links__card" href="<?php echo esc_url( 'mailto:' . $estatein_email ); ?>">
				<?php echo estatein_icon( 'arrow-up-right', array( 'class' => 'quick-links__arrow', 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo estatein_icon_badge( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="quick-links__title"><?php echo esc_html( $estatein_email ); ?></span>
			</a>
		</li>
		<li>
			<a class="quick-links__card" href="<?php echo esc_url( 'tel:' . preg_replace( '/[^0-9+]/', '', $estatein_phone ) ); ?>">
				<?php echo estatein_icon( 'arrow-up-right', array( 'class' => 'quick-links__arrow', 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo estatein_icon_badge( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="quick-links__title"><?php echo esc_html( $estatein_phone ); ?></span>
			</a>
		</li>
		<li>
			<a class="quick-links__card" href="#offices">
				<?php echo estatein_icon( 'arrow-up-right', array( 'class' => 'quick-links__arrow', 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php echo estatein_icon_badge( 'map-pin' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="quick-links__title"><?php echo esc_html( estatein_option( 'contact_hq_label' ) ); ?></span>
			</a>
		</li>
		<li>
			<div class="quick-links__card quick-links__card--static">
				<?php echo estatein_icon( 'arrow-up-right', array( 'class' => 'quick-links__arrow', 'size' => 30 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="icon-badge icon-badge--md" aria-hidden="true"><span class="icon-badge__inner"><svg width="24" height="24" viewBox="0 0 34 34" focusable="false"><path d="M0 0a17 17 0 0 1 17 17V0a17 17 0 0 1 17 17A17 17 0 0 1 0 17Z" fill="currentColor"/></svg></span></span>
				<ul class="quick-links__socials">
					<?php
					$estatein_order   = array( 'instagram', 'linkedin', 'facebook' );
					$estatein_socials = array_filter(
						estatein_socials(),
						function ( $social ) use ( $estatein_order ) {
							return in_array( $social['key'], $estatein_order, true );
						}
					);
					usort(
						$estatein_socials,
						function ( $a, $b ) use ( $estatein_order ) {
							return array_search( $a['key'], $estatein_order, true ) <=> array_search( $b['key'], $estatein_order, true );
						}
					);
					?>
					<?php foreach ( $estatein_socials as $estatein_social ) : ?>
						<li><a href="<?php echo esc_url( $estatein_social['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $estatein_social['label'] ); ?><span class="screen-reader-text"> <?php esc_html_e( '(opens in a new tab)', 'estatein' ); ?></span></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</li>
	</ul>
</section>

<section class="section" id="connect" aria-labelledby="connect-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'contact', 'form_title' ),
				'text'  => estatein_page_field( 'contact', 'form_text' ),
				'id'    => 'connect-title',
			)
		);
		estatein_part( 'components/inquiry-form', array( 'form' => 'contact' ) );
		?>
	</div>
</section>

<?php estatein_part( 'contact/offices' ); ?>
<?php estatein_part( 'contact/gallery' ); ?>

<?php
get_footer();
