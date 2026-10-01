<?php
/**
 * Inquiry form card (Properties, Property Details, Contact).
 *
 * Fields come from estatein_form_schemas() so rendering and server-side
 * validation can never drift apart.
 *
 * @package Estatein
 *
 * @var array $args { form: properties|property|contact, property_id: int }
 */

defined( 'ABSPATH' ) || exit;

$estatein_form    = isset( $args['form'] ) ? $args['form'] : 'contact';
$estatein_schemas = estatein_form_schemas();
if ( ! isset( $estatein_schemas[ $estatein_form ] ) ) {
	return;
}
$estatein_ctx = array( 'property_id' => isset( $args['property_id'] ) ? (int) $args['property_id'] : 0 );
?>
<form class="card inquiry-form inquiry-form--<?php echo esc_attr( $estatein_form ); ?>" id="form-<?php echo esc_attr( $estatein_form ); ?>" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ajax-form novalidate>
	<?php estatein_form_hidden_fields( $estatein_form ); ?>

	<div class="inquiry-form__grid">
		<?php
		foreach ( $estatein_schemas[ $estatein_form ]['fields'] as $estatein_key => $estatein_field ) {
			estatein_render_field( $estatein_form, $estatein_key, $estatein_field, $estatein_ctx );
		}
		?>
	</div>

	<div class="inquiry-form__footer">
		<div class="field field--checkbox" data-field="terms">
			<label class="checkbox">
				<input type="checkbox" name="terms" value="1" required aria-describedby="f-<?php echo esc_attr( $estatein_form ); ?>-terms-error">
				<span class="checkbox__box" aria-hidden="true"><?php echo estatein_icon( 'check', array( 'size' => 16 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
				<span>
					<?php
					printf(
						/* translators: 1: terms link, 2: privacy link */
						esc_html__( 'I agree with %1$s and %2$s', 'estatein' ),
						'<a href="' . esc_url( estatein_url( estatein_option( 'terms_url' ) ) ) . '">' . esc_html__( 'Terms of Use', 'estatein' ) . '</a>',
						'<a href="' . esc_url( estatein_url( estatein_option( 'privacy_url' ) ) ) . '">' . esc_html__( 'Privacy Policy', 'estatein' ) . '</a>'
					);
					?>
				</span>
			</label>
			<p class="field__error" id="f-<?php echo esc_attr( $estatein_form ); ?>-terms-error"></p>
		</div>
		<button class="btn btn--primary btn--lg inquiry-form__submit" type="submit"><?php esc_html_e( 'Send Your Message', 'estatein' ); ?></button>
	</div>

	<?php estatein_form_status( $estatein_form ); ?>
</form>
