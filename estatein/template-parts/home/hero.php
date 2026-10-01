<?php
/**
 * Home hero: headline, buttons, stats, building image with rotating badge.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_image = estatein_page_field( 'home', 'hero_image' );
$estatein_badge = estatein_page_field( 'home', 'hero_badge_text' );
?>
<section class="hero" id="hero" aria-labelledby="hero-title">
	<div class="hero__media">
		<?php
		if ( $estatein_image ) {
			echo estatein_image( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				$estatein_image,
				'large',
				array(
					'class'         => 'hero__image',
					'alt'           => '',
					'loading'       => 'eager',
					'fetchpriority' => 'high',
					'sizes'         => '(min-width: 1200px) 50vw, 100vw',
				)
			);
		} else {
			printf(
				'<img class="hero__image" src="%1$s" srcset="%2$s 640w, %1$s 1200w" sizes="(min-width: 1200px) 50vw, 100vw" width="1200" height="1062" alt="" fetchpriority="high" decoding="async">',
				esc_url( ESTATEIN_URI . '/assets/images/hero-building.webp' ),
				esc_url( ESTATEIN_URI . '/assets/images/hero-building-640.webp' )
			);
		}
		?>
	</div>

	<div class="hero__content">
		<div class="hero__copy">
			<h1 class="hero__title" id="hero-title"><?php echo esc_html( estatein_page_field( 'home', 'hero_title' ) ); ?></h1>
			<p class="hero__text"><?php echo esc_html( estatein_page_field( 'home', 'hero_text' ) ); ?></p>
			<div class="hero__actions">
				<?php estatein_button( estatein_page_field( 'home', 'hero_secondary_label' ), estatein_page_field( 'home', 'hero_secondary_url' ), 'secondary', array( 'class' => 'btn--lg' ) ); ?>
				<?php estatein_button( estatein_page_field( 'home', 'hero_primary_label' ), estatein_page_field( 'home', 'hero_primary_url' ), 'primary', array( 'class' => 'btn--lg' ) ); ?>
			</div>
			<?php estatein_part( 'components/stats' ); ?>
		</div>

		<?php if ( $estatein_badge ) : ?>
			<a class="hero-badge" href="<?php echo esc_url( estatein_properties_url() ); ?>">
				<svg class="hero-badge__ring" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
					<defs><path id="hero-badge-path" d="M60 60m-46 0a46 46 0 1 1 92 0a46 46 0 1 1-92 0"/></defs>
					<text><textPath href="#hero-badge-path" textLength="285"><?php echo esc_html( $estatein_badge ); ?> ✦</textPath></text>
				</svg>
				<span class="hero-badge__center"><?php echo estatein_icon( 'arrow-up-right', array( 'size' => 26 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span class="screen-reader-text"><?php echo esc_html( $estatein_badge ); ?></span>
			</a>
		<?php endif; ?>
	</div>
</section>
