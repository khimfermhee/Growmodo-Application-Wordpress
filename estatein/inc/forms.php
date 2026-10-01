<?php
/**
 * Inquiry + newsletter forms.
 *
 * No form plugin needed: one handler validates, stores each submission as a
 * private "Inquiry" post (so nothing is lost when a free host blocks mail)
 * and emails the team. Works with and without JavaScript:
 *  - JS: fetch() to admin-ajax.php, JSON response, inline messages.
 *  - No JS: classic POST to admin-post.php and redirect back with a status.
 *
 * Spam protection: nonce, honeypot field and a minimum fill-in time.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

/**
 * Field definitions per form. Single source for rendering + validation.
 *
 * @return array
 */
function estatein_form_schemas() {
	$name_email_phone = array(
		'first_name' => array( 'label' => __( 'First Name', 'estatein' ), 'type' => 'text', 'required' => true, 'placeholder' => __( 'Enter First Name', 'estatein' ), 'autocomplete' => 'given-name' ),
		'last_name'  => array( 'label' => __( 'Last Name', 'estatein' ), 'type' => 'text', 'required' => true, 'placeholder' => __( 'Enter Last Name', 'estatein' ), 'autocomplete' => 'family-name' ),
		'email'      => array( 'label' => __( 'Email', 'estatein' ), 'type' => 'email', 'required' => true, 'placeholder' => __( 'Enter your Email', 'estatein' ), 'autocomplete' => 'email' ),
		'phone'      => array( 'label' => __( 'Phone', 'estatein' ), 'type' => 'tel', 'required' => false, 'placeholder' => __( 'Enter Phone Number', 'estatein' ), 'autocomplete' => 'tel' ),
	);
	$message          = array(
		'message' => array( 'label' => __( 'Message', 'estatein' ), 'type' => 'textarea', 'required' => true, 'placeholder' => __( 'Enter your Message here..', 'estatein' ) ),
	);
	$counts           = array(
		'1'  => '1',
		'2'  => '2',
		'3'  => '3',
		'4'  => '4',
		'5+' => '5+',
	);

	$filters = estatein_property_filters();

	return array(
		'contact'    => array(
			'label'  => __( 'Contact form', 'estatein' ),
			'fields' => array_merge(
				$name_email_phone,
				array(
					'inquiry_type' => array(
						'label'       => __( 'Inquiry Type', 'estatein' ),
						'type'        => 'select',
						'required'    => true,
						'placeholder' => __( 'Select Inquiry Type', 'estatein' ),
						'choices'     => array(
							'buying'      => __( 'Buying a property', 'estatein' ),
							'selling'     => __( 'Selling a property', 'estatein' ),
							'renting'     => __( 'Renting', 'estatein' ),
							'investment'  => __( 'Investment advice', 'estatein' ),
							'management'  => __( 'Property management', 'estatein' ),
							'other'       => __( 'Other', 'estatein' ),
						),
					),
					'heard_about'  => array(
						'label'       => __( 'How Did You Hear About Us?', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select', 'estatein' ),
						'choices'     => array(
							'search'   => __( 'Search engine', 'estatein' ),
							'social'   => __( 'Social media', 'estatein' ),
							'referral' => __( 'Friend or referral', 'estatein' ),
							'ads'      => __( 'Advertisement', 'estatein' ),
							'other'    => __( 'Other', 'estatein' ),
						),
					),
				),
				$message
			),
		),
		'properties' => array(
			'label'  => __( 'Property match request', 'estatein' ),
			'fields' => array_merge(
				$name_email_phone,
				array(
					'location'      => array(
						'label'       => __( 'Preferred Location', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select Location', 'estatein' ),
						'choices'     => $filters['location']['choices'] ? $filters['location']['choices'] : array(
							'california' => 'California',
							'florida'    => 'Florida',
							'new-york'   => 'New York',
							'texas'      => 'Texas',
						),
					),
					'property_type' => array(
						'label'       => __( 'Property Type', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select Property Type', 'estatein' ),
						'choices'     => $filters['type']['choices'] ? $filters['type']['choices'] : array(
							'villa'     => 'Villa',
							'apartment' => 'Apartment',
							'townhouse' => 'Townhouse',
						),
					),
					'bathrooms'     => array(
						'label'       => __( 'No. of Bathrooms', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select no. of Bathrooms', 'estatein' ),
						'choices'     => $counts,
					),
					'bedrooms'      => array(
						'label'       => __( 'No. of Bedrooms', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select no. of Bedrooms', 'estatein' ),
						'choices'     => $counts,
					),
					'budget'        => array(
						'label'       => __( 'Budget', 'estatein' ),
						'type'        => 'select',
						'required'    => false,
						'placeholder' => __( 'Select Budget', 'estatein' ),
						'choices'     => $filters['price']['choices'],
						'wide'        => true,
					),
					'contact_method' => array(
						'label'    => __( 'Preferred Contact Method', 'estatein' ),
						'type'     => 'contact_method',
						'required' => false,
						'choices'  => array(
							'phone' => __( 'Phone', 'estatein' ),
							'email' => __( 'Email', 'estatein' ),
						),
						'wide'     => true,
					),
				),
				$message
			),
		),
		'property'   => array(
			'label'  => __( 'Property inquiry', 'estatein' ),
			'fields' => array_merge(
				$name_email_phone,
				array(
					'property' => array( 'label' => __( 'Selected Property', 'estatein' ), 'type' => 'property', 'required' => false ),
				),
				$message
			),
		),
		'newsletter' => array(
			'label'  => __( 'Newsletter signup', 'estatein' ),
			'fields' => array(
				'email' => array( 'label' => __( 'Email', 'estatein' ), 'type' => 'email', 'required' => true, 'placeholder' => __( 'Enter Your Email', 'estatein' ), 'autocomplete' => 'email' ),
			),
		),
	);
}

/**
 * Hidden fields every form needs (action, nonce, honeypot, timestamp).
 *
 * @param string $form Form key.
 */
function estatein_form_hidden_fields( $form ) {
	?>
	<input type="hidden" name="action" value="estatein_form">
	<input type="hidden" name="form" value="<?php echo esc_attr( $form ); ?>">
	<input type="hidden" name="est_ts" value="<?php echo esc_attr( (string) time() ); ?>">
	<?php wp_nonce_field( 'estatein_form', 'est_nonce', false ); ?>
	<input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr( remove_query_arg( array( 'form_status', 'form_id' ), wp_unslash( $_SERVER['REQUEST_URI'] ?? '' ) ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized ?>">
	<div class="hp-field" aria-hidden="true">
		<label for="est-website-<?php echo esc_attr( $form ); ?>"><?php esc_html_e( 'Leave this field empty', 'estatein' ); ?></label>
		<input type="text" id="est-website-<?php echo esc_attr( $form ); ?>" name="website" tabindex="-1" autocomplete="off">
	</div>
	<?php
}

/**
 * Status message area (filled by JS, or by the no-JS redirect).
 *
 * @param string $form Form key.
 */
function estatein_form_status( $form ) {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended -- display only.
	$status = isset( $_GET['form_status'], $_GET['form_id'] ) && $form === $_GET['form_id'] ? sanitize_key( $_GET['form_status'] ) : '';
	// phpcs:enable
	$message = '';
	if ( 'success' === $status ) {
		$message = estatein_form_success_message( $form );
	} elseif ( 'error' === $status ) {
		$message = __( 'Please check the form – some required fields are missing or invalid.', 'estatein' );
	}
	printf(
		'<div class="form-status%1$s" role="status" aria-live="polite" data-form-status>%2$s</div>',
		$status ? ' form-status--' . esc_attr( $status ) : '',
		esc_html( $message )
	);
}

/**
 * Success copy per form.
 *
 * @param string $form Form key.
 * @return string
 */
function estatein_form_success_message( $form ) {
	if ( 'newsletter' === $form ) {
		return __( 'Thanks for subscribing! Watch your inbox for new listings.', 'estatein' );
	}
	return __( 'Thank you! Your message has been sent. Our team will get back to you within one business day.', 'estatein' );
}

/**
 * Validate and sanitize a submission.
 *
 * @param string $form Form key.
 * @param array  $raw  Raw (unslashed) POST data.
 * @return array { data: array, errors: array }
 */
function estatein_validate_form( $form, $raw ) {
	$schemas = estatein_form_schemas();
	$data    = array();
	$errors  = array();

	// The "Preferred Contact Method" inputs can carry the phone/email too.
	foreach ( array( 'phone', 'email' ) as $key ) {
		if ( empty( $raw[ $key ] ) && ! empty( $raw[ $key . '_alt' ] ) ) {
			$raw[ $key ] = $raw[ $key . '_alt' ];
		}
	}

	foreach ( $schemas[ $form ]['fields'] as $key => $field ) {
		switch ( $field['type'] ) {
			case 'email':
				$value = sanitize_email( $raw[ $key ] ?? '' );
				if ( '' !== ( $raw[ $key ] ?? '' ) && ! is_email( $value ) ) {
					$errors[ $key ] = __( 'Please enter a valid email address.', 'estatein' );
				}
				break;
			case 'textarea':
				$value = sanitize_textarea_field( $raw[ $key ] ?? '' );
				break;
			case 'select':
				$value = sanitize_text_field( $raw[ $key ] ?? '' );
				if ( '' !== $value && ! array_key_exists( $value, $field['choices'] ) ) {
					$value = '';
				}
				break;
			case 'contact_method':
				$value = sanitize_key( $raw[ $key ] ?? '' );
				$value = array_key_exists( $value, $field['choices'] ) ? $value : '';
				break;
			case 'property':
				$id    = absint( $raw['property_id'] ?? 0 );
				$value = ( $id && 'property' === get_post_type( $id ) ) ? get_the_title( $id ) . ' (#' . $id . ')' : '';
				break;
			case 'tel':
				$value = preg_replace( '/[^0-9+()\-.\s]/', '', (string) ( $raw[ $key ] ?? '' ) );
				break;
			default:
				$value = sanitize_text_field( $raw[ $key ] ?? '' );
		}

		$value = trim( (string) $value );
		if ( ! empty( $field['required'] ) && '' === $value && ! isset( $errors[ $key ] ) ) {
			/* translators: %s: field label */
			$errors[ $key ] = sprintf( __( '%s is required.', 'estatein' ), $field['label'] );
		}
		$data[ $key ] = $value;
	}

	if ( 'newsletter' !== $form && empty( $raw['terms'] ) ) {
		$errors['terms'] = __( 'Please accept the Terms of Use and Privacy Policy.', 'estatein' );
	}

	return array(
		'data'   => $data,
		'errors' => $errors,
	);
}

/**
 * Handle a submission (admin-post.php and admin-ajax.php).
 */
function estatein_handle_form() {
	$is_ajax = wp_doing_ajax();
	$raw     = wp_unslash( $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing -- verified below.
	$form    = sanitize_key( $raw['form'] ?? '' );
	$schemas = estatein_form_schemas();

	$respond = function ( $success, $message, $errors = array() ) use ( $is_ajax, $form ) {
		if ( $is_ajax ) {
			wp_send_json(
				array(
					'success' => $success,
					'message' => $message,
					'errors'  => $errors,
				),
				$success ? 200 : 422
			);
		}
		$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		$back = add_query_arg(
			array(
				'form_status' => $success ? 'success' : 'error',
				'form_id'     => $form,
			),
			$back
		);
		wp_safe_redirect( $back . '#form-' . $form );
		exit;
	};

	if ( ! isset( $schemas[ $form ] ) ) {
		$respond( false, __( 'Unknown form.', 'estatein' ) );
	}
	if ( ! isset( $raw['est_nonce'] ) || ! wp_verify_nonce( $raw['est_nonce'], 'estatein_form' ) ) {
		$respond( false, __( 'Your session expired. Please refresh the page and try again.', 'estatein' ) );
	}

	// Bots fill the hidden honeypot or submit instantly: pretend success, store nothing.
	$too_fast = ( time() - absint( $raw['est_ts'] ?? 0 ) ) < 3;
	if ( ! empty( $raw['website'] ) || $too_fast ) {
		$respond( true, estatein_form_success_message( $form ) );
	}

	$result = estatein_validate_form( $form, $raw );
	if ( $result['errors'] ) {
		$respond( false, __( 'Please fix the highlighted fields.', 'estatein' ), $result['errors'] );
	}

	$data = $result['data'];
	estatein_store_submission( $form, $data );
	estatein_notify_submission( $form, $data );

	$respond( true, estatein_form_success_message( $form ) );
}
add_action( 'admin_post_estatein_form', 'estatein_handle_form' );
add_action( 'admin_post_nopriv_estatein_form', 'estatein_handle_form' );
add_action( 'wp_ajax_estatein_form', 'estatein_handle_form' );
add_action( 'wp_ajax_nopriv_estatein_form', 'estatein_handle_form' );

/**
 * Save a submission as a private Inquiry post.
 *
 * @param string $form Form key.
 * @param array  $data Clean data.
 * @return int Post ID.
 */
function estatein_store_submission( $form, $data ) {
	$schemas = estatein_form_schemas();
	$who     = trim( ( $data['first_name'] ?? '' ) . ' ' . ( $data['last_name'] ?? '' ) );
	$who     = $who ? $who : ( $data['email'] ?? '' );

	$post_id = wp_insert_post(
		array(
			'post_type'   => 'est_inquiry',
			'post_status' => 'publish',
			'post_title'  => sprintf( '%s – %s', $schemas[ $form ]['label'], $who ),
		),
		true
	);

	if ( ! is_wp_error( $post_id ) ) {
		update_post_meta( $post_id, '_estatein_form', $form );
		update_post_meta( $post_id, '_estatein_fields', $data );
		update_post_meta( $post_id, '_estatein_page', esc_url_raw( wp_get_referer() ? wp_get_referer() : '' ) );
		return (int) $post_id;
	}
	return 0;
}

/**
 * Email the team about a submission.
 *
 * @param string $form Form key.
 * @param array  $data Clean data.
 */
function estatein_notify_submission( $form, $data ) {
	$schemas   = estatein_form_schemas();
	$recipient = estatein_option( 'form_recipient' );
	$recipient = is_email( $recipient ) ? $recipient : get_option( 'admin_email' );

	$lines = array();
	foreach ( $schemas[ $form ]['fields'] as $key => $field ) {
		if ( '' !== ( $data[ $key ] ?? '' ) ) {
			$value = $data[ $key ];
			if ( isset( $field['choices'][ $value ] ) ) {
				$value = $field['choices'][ $value ];
			}
			$lines[] = $field['label'] . ': ' . $value;
		}
	}

	$headers = array();
	if ( ! empty( $data['email'] ) ) {
		$headers[] = 'Reply-To: ' . $data['email'];
	}

	wp_mail(
		$recipient,
		/* translators: 1: site name, 2: form label */
		sprintf( __( '[%1$s] New %2$s', 'estatein' ), wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ), $schemas[ $form ]['label'] ),
		implode( "\n", $lines ) . "\n\n" . __( 'All submissions are also saved under Inquiries in the dashboard.', 'estatein' ),
		$headers
	);
}

/**
 * Render one form field from the schema.
 *
 * @param string $form  Form key (used for unique IDs).
 * @param string $key   Field key.
 * @param array  $field Field definition.
 * @param array  $ctx   Context, e.g. property_id.
 */
function estatein_render_field( $form, $key, $field, $ctx = array() ) {
	$id       = 'f-' . $form . '-' . $key;
	$required = ! empty( $field['required'] );
	$req_attr = $required ? ' required aria-required="true"' : '';
	$classes  = 'field field--' . $field['type'] . ( ! empty( $field['wide'] ) ? ' field--wide' : '' );

	echo '<div class="' . esc_attr( $classes ) . '" data-field="' . esc_attr( $key ) . '">';

	if ( 'contact_method' === $field['type'] ) {
		?>
		<fieldset class="field__fieldset">
			<legend class="field__label"><?php echo esc_html( $field['label'] ); ?></legend>
			<div class="contact-method">
				<label class="contact-method__option">
					<?php echo estatein_icon( 'phone', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<input class="contact-method__text" type="tel" name="phone_alt" placeholder="<?php esc_attr_e( 'Enter Your Number', 'estatein' ); ?>" aria-label="<?php esc_attr_e( 'Phone number for contact', 'estatein' ); ?>" data-mirror="phone">
					<input class="contact-method__radio" type="radio" name="contact_method" value="phone" checked aria-label="<?php esc_attr_e( 'Contact me by phone', 'estatein' ); ?>">
				</label>
				<label class="contact-method__option">
					<?php echo estatein_icon( 'mail', array( 'size' => 18 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<input class="contact-method__text" type="email" name="email_alt" placeholder="<?php esc_attr_e( 'Enter Your Email', 'estatein' ); ?>" aria-label="<?php esc_attr_e( 'Email for contact', 'estatein' ); ?>" data-mirror="email">
					<input class="contact-method__radio" type="radio" name="contact_method" value="email" aria-label="<?php esc_attr_e( 'Contact me by email', 'estatein' ); ?>">
				</label>
			</div>
		</fieldset>
		<?php
		echo '</div>';
		return;
	}

	printf(
		'<label class="field__label" for="%1$s">%2$s%3$s</label>',
		esc_attr( $id ),
		esc_html( $field['label'] ),
		$required ? '<span class="screen-reader-text"> ' . esc_html__( '(required)', 'estatein' ) . '</span>' : ''
	);

	switch ( $field['type'] ) {
		case 'textarea':
			printf(
				'<textarea class="field__input" id="%1$s" name="%2$s" rows="5" placeholder="%3$s"%4$s aria-describedby="%1$s-error"></textarea>',
				esc_attr( $id ),
				esc_attr( $key ),
				esc_attr( $field['placeholder'] ?? '' ),
				$req_attr // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
			break;

		case 'select':
			echo '<div class="field__select">';
			printf( '<select class="field__input" id="%1$s" name="%2$s"%3$s aria-describedby="%1$s-error">', esc_attr( $id ), esc_attr( $key ), $req_attr ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			printf( '<option value="">%s</option>', esc_html( $field['placeholder'] ?? '' ) );
			foreach ( $field['choices'] as $value => $label ) {
				printf( '<option value="%1$s">%2$s</option>', esc_attr( $value ), esc_html( $label ) );
			}
			echo '</select>';
			echo estatein_icon( 'chevron-down', array( 'class' => 'field__chevron' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			break;

		case 'property':
			$pid   = isset( $ctx['property_id'] ) ? (int) $ctx['property_id'] : 0;
			$label = $pid ? get_the_title( $pid ) : '';
			$loc   = $pid ? (string) estatein_field( 'location_label', '', $pid ) : '';
			echo '<div class="field__with-icon">';
			printf(
				'<input class="field__input" id="%1$s" type="text" value="%2$s" readonly>',
				esc_attr( $id ),
				esc_attr( trim( $label . ( $loc ? ', ' . $loc : '' ) ) )
			);
			printf( '<input type="hidden" name="property_id" value="%d">', (int) $pid );
			echo estatein_icon( 'pin-solid', array( 'class' => 'field__icon' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo '</div>';
			break;

		default:
			printf(
				'<input class="field__input" id="%1$s" type="%2$s" name="%3$s" placeholder="%4$s" autocomplete="%5$s"%6$s aria-describedby="%1$s-error">',
				esc_attr( $id ),
				esc_attr( $field['type'] ),
				esc_attr( $key ),
				esc_attr( $field['placeholder'] ?? '' ),
				esc_attr( $field['autocomplete'] ?? 'off' ),
				$req_attr // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			);
	}

	printf( '<p class="field__error" id="%s-error"></p>', esc_attr( $id ) );
	echo '</div>';
}
