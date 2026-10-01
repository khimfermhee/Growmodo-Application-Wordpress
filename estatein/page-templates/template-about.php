<?php
/**
 * Template Name: About Us
 *
 * Sections: journey → values → achievements → process → team → clients.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

$estatein_journey_image = estatein_page_field( 'about', 'journey_image' );
?>

<section class="section about-journey" id="journey" aria-labelledby="journey-title">
	<div class="container about-journey__inner">
		<div class="about-journey__copy">
			<?php
			estatein_part(
				'components/section-heading',
				array(
					'title' => estatein_page_field( 'about', 'journey_title' ),
					'text'  => estatein_page_field( 'about', 'journey_text' ),
					'id'    => 'journey-title',
					'tag'   => 'h1',
				)
			);
			estatein_part( 'components/stats' );
			?>
		</div>
		<div class="about-journey__media">
			<?php
			if ( $estatein_journey_image ) {
				echo estatein_image( $estatein_journey_image, 'large', array( 'alt' => '', 'loading' => 'eager', 'sizes' => '(min-width: 1200px) 610px, 100vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			} else {
				printf(
					'<img src="%s" width="1260" height="876" alt="%s" decoding="async">',
					esc_url( ESTATEIN_URI . '/assets/images/about-journey.webp' ),
					esc_attr__( 'Hand holding a model house', 'estatein' )
				);
			}
			?>
		</div>
	</div>
</section>

<section class="section about-values" aria-labelledby="values-title">
	<div class="container about-values__inner">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'about', 'values_title' ),
				'text'  => estatein_page_field( 'about', 'values_text' ),
				'id'    => 'values-title',
			)
		);
		?>
		<ul class="card values-grid">
			<?php foreach ( estatein_rows( estatein_page_field( 'about', 'values' ), array( 'icon', 'title', 'text' ), estatein_default( 'about.values' ) ) as $estatein_value ) : ?>
				<li class="values-grid__item">
					<div class="values-grid__head">
						<span class="values-grid__icon" aria-hidden="true"><?php echo estatein_icon( $estatein_value['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<h3 class="values-grid__title"><?php echo esc_html( $estatein_value['title'] ); ?></h3>
					</div>
					<p><?php echo esc_html( $estatein_value['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section" id="achievements" aria-labelledby="achievements-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'about', 'achievements_title' ),
				'text'  => estatein_page_field( 'about', 'achievements_text' ),
				'id'    => 'achievements-title',
			)
		);
		?>
		<ul class="grid grid--3">
			<?php foreach ( estatein_rows( estatein_page_field( 'about', 'achievements' ), array( 'title', 'text' ), estatein_default( 'about.achievements' ) ) as $estatein_item ) : ?>
				<li class="card card--glow achievement">
					<h3 class="achievement__title"><?php echo esc_html( $estatein_item['title'] ); ?></h3>
					<p><?php echo esc_html( $estatein_item['text'] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="section" id="process" aria-labelledby="process-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'about', 'process_title' ),
				'text'  => estatein_page_field( 'about', 'process_text' ),
				'id'    => 'process-title',
			)
		);
		?>
		<ol class="grid grid--3 steps">
			<?php foreach ( estatein_rows( estatein_page_field( 'about', 'steps' ), array( 'title', 'text' ), estatein_default( 'about.steps' ) ) as $estatein_i => $estatein_step ) : ?>
				<li class="step">
					<p class="step__label"><?php echo esc_html( sprintf( /* translators: %s: step number */ __( 'Step %s', 'estatein' ), estatein_pad( $estatein_i + 1 ) ) ); ?></p>
					<div class="step__card">
						<h3 class="step__title"><?php echo esc_html( $estatein_step['title'] ); ?></h3>
						<p><?php echo esc_html( $estatein_step['text'] ); ?></p>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>

<section class="section" id="team" aria-labelledby="team-title">
	<div class="container">
		<?php
		estatein_part(
			'components/section-heading',
			array(
				'title' => estatein_page_field( 'about', 'team_title' ),
				'text'  => estatein_page_field( 'about', 'team_text' ),
				'id'    => 'team-title',
			)
		);
		?>
		<ul class="grid grid--4 team-grid">
			<?php foreach ( estatein_get_team() as $estatein_member ) : ?>
				<li class="card team-card">
					<div class="team-card__media">
						<?php echo estatein_image( $estatein_member['image'], 'estatein-team', array( 'alt' => $estatein_member['name'], 'sizes' => '(min-width: 1200px) 260px, (min-width: 768px) 45vw, 90vw' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<?php if ( $estatein_member['twitter'] ) : ?>
							<a class="team-card__social" href="<?php echo esc_url( $estatein_member['twitter'] ); ?>" target="_blank" rel="noopener noreferrer">
								<?php echo estatein_icon( 'twitter', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: name */ __( '%s on Twitter (opens in a new tab)', 'estatein' ), $estatein_member['name'] ) ); ?></span>
							</a>
						<?php endif; ?>
					</div>
					<h3 class="team-card__name"><?php echo esc_html( $estatein_member['name'] ); ?></h3>
					<p class="team-card__role"><?php echo esc_html( $estatein_member['role'] ); ?></p>
					<?php if ( $estatein_member['email'] ) : ?>
						<a class="team-card__hello" href="<?php echo esc_url( 'mailto:' . $estatein_member['email'] ); ?>">
							<span><?php esc_html_e( 'Say Hello', 'estatein' ); ?> <span aria-hidden="true">👋</span></span>
							<span class="team-card__send" aria-hidden="true"><?php echo estatein_icon( 'send-solid', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: name */ __( 'to %s by email', 'estatein' ), $estatein_member['name'] ) ); ?></span>
						</a>
					<?php endif; ?>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<?php
estatein_part(
	'components/carousel-section',
	array(
		'id'       => 'clients',
		'heading'  => array(
			'title' => estatein_page_field( 'about', 'clients_title' ),
			'text'  => estatein_page_field( 'about', 'clients_text' ),
		),
		'items'    => estatein_get_clients(),
		'card'     => 'components/client-card',
		'label'    => __( 'clients', 'estatein' ),
		'modifier' => 'cols-2',
	)
);

get_footer();
