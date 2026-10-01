<?php
/**
 * Search form.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;

$estatein_id = wp_unique_id( 'search-' );
?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="<?php echo esc_attr( $estatein_id ); ?>"><?php esc_html_e( 'Search for:', 'estatein' ); ?></label>
	<input class="field__input search-form__input" type="search" id="<?php echo esc_attr( $estatein_id ); ?>" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search…', 'estatein' ); ?>">
	<button class="btn btn--primary search-form__submit" type="submit">
		<?php echo estatein_icon( 'search', array( 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'estatein' ); ?></span>
	</button>
</form>
