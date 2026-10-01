<?php
/**
 * Single property: title bar, gallery, description + facts, features,
 * inquiry form, pricing breakdown and FAQ.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

get_header();

while ( have_posts() ) :
	the_post();
	$estatein_id       = get_the_ID();
	$estatein_property = estatein_property_data( $estatein_id );
	$estatein_features = estatein_rows( estatein_field( 'features', array(), $estatein_id ), array( 'text' ) );
	$estatein_pricing  = estatein_rows( estatein_field( 'pricing_groups', array(), $estatein_id ), array( 'title', 'rows' ) );
	?>

	<section class="section property-intro" aria-labelledby="page-title">
		<div class="container">
			<div class="property-intro__head">
				<div class="property-intro__title-wrap">
					<h1 class="property-intro__title" id="page-title"><?php the_title(); ?></h1>
					<?php if ( $estatein_property['location'] ) : ?>
						<p class="pill pill--icon"><?php echo estatein_icon( 'pin-solid', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $estatein_property['location'] ); ?></p>
					<?php endif; ?>
				</div>
				<p class="price price--lg">
					<span class="price__label"><?php esc_html_e( 'Price', 'estatein' ); ?></span>
					<span class="price__value"><?php echo esc_html( estatein_price( $estatein_property['price'] ) ); ?></span>
				</p>
			</div>

			<?php estatein_part( 'property/gallery', array( 'images' => estatein_property_gallery( $estatein_id ), 'title' => get_the_title() ) ); ?>

			<div class="property-details">
				<div class="card property-details__description">
					<h2 class="property-details__heading"><?php esc_html_e( 'Description', 'estatein' ); ?></h2>
					<div class="property-details__content"><?php the_content(); ?></div>
					<dl class="property-facts">
						<div class="property-facts__item">
							<dt><?php echo estatein_icon( 'bed', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Bedrooms', 'estatein' ); ?></dt>
							<dd><?php echo esc_html( estatein_pad( $estatein_property['bedrooms'] ) ); ?></dd>
						</div>
						<div class="property-facts__item">
							<dt><?php echo estatein_icon( 'bath', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Bathrooms', 'estatein' ); ?></dt>
							<dd><?php echo esc_html( estatein_pad( $estatein_property['bathrooms'] ) ); ?></dd>
						</div>
						<div class="property-facts__item">
							<dt><?php echo estatein_icon( 'area', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php esc_html_e( 'Area', 'estatein' ); ?></dt>
							<dd><?php echo esc_html( sprintf( /* translators: %s: area */ __( '%s Square Feet', 'estatein' ), number_format_i18n( (float) $estatein_property['area'] ) ) ); ?></dd>
						</div>
					</dl>
				</div>

				<?php if ( $estatein_features ) : ?>
					<div class="card property-details__features">
						<h2 class="property-details__heading"><?php esc_html_e( 'Key Features and Amenities', 'estatein' ); ?></h2>
						<ul class="feature-list">
							<?php foreach ( $estatein_features as $estatein_feature ) : ?>
								<?php $estatein_text = is_array( $estatein_feature ) ? ( $estatein_feature['text'] ?? '' ) : $estatein_feature; ?>
								<?php if ( $estatein_text ) : ?>
									<li class="feature-list__item"><?php echo estatein_icon( 'zap-solid', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( $estatein_text ); ?></span></li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

	<section class="section property-inquiry" id="inquire" aria-labelledby="inquire-title">
		<div class="container property-inquiry__inner">
			<?php
			estatein_part(
				'components/section-heading',
				array(
					/* translators: %s: property name */
					'title' => sprintf( __( 'Inquire About %s', 'estatein' ), get_the_title() ),
					'text'  => __( 'Interested in this property? Fill out the form below, and our real estate experts will get back to you with more details, including scheduling a viewing and answering any questions you may have.', 'estatein' ),
					'id'    => 'inquire-title',
				)
			);
			estatein_part(
				'components/inquiry-form',
				array(
					'form'        => 'property',
					'property_id' => $estatein_id,
				)
			);
			?>
		</div>
	</section>

	<?php if ( $estatein_pricing ) : ?>
		<?php estatein_part( 'property/pricing', array( 'groups' => $estatein_pricing, 'price' => $estatein_property['price'] ) ); ?>
	<?php endif; ?>

	<?php
	estatein_part(
		'components/carousel-section',
		array(
			'id'      => 'faq',
			'heading' => array(
				'title'        => estatein_default( 'home.faq_title' ),
				'text'         => estatein_default( 'home.faq_text' ),
				'button_label' => estatein_default( 'home.faq_button' ),
				'button_url'   => get_post_type_archive_link( 'faq' ),
			),
			'items'   => estatein_get_faqs(),
			'card'    => 'components/faq-card',
			'label'   => __( 'frequently asked questions', 'estatein' ),
		)
	);
endwhile;

get_footer();
