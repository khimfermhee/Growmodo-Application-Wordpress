<?php
/**
 * Site footer: call to action band, newsletter, link columns, bottom bar.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
</main>

<?php
if ( ! is_404() ) {
	estatein_part( 'components/cta' );
}
?>

<footer class="site-footer">
	<div class="container site-footer__top">
		<div class="site-footer__brand">
			<?php get_template_part( 'template-parts/header/logo' ); ?>
			<form class="newsletter" id="form-newsletter" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" data-ajax-form novalidate>
				<?php estatein_form_hidden_fields( 'newsletter' ); ?>
				<label class="screen-reader-text" for="newsletter-email"><?php esc_html_e( 'Email address for our newsletter', 'estatein' ); ?></label>
				<?php echo estatein_icon( 'mail-plus', array( 'class' => 'newsletter__icon', 'size' => 20 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<input class="newsletter__input" id="newsletter-email" type="email" name="email" required autocomplete="email" placeholder="<?php esc_attr_e( 'Enter Your Email', 'estatein' ); ?>">
				<button class="newsletter__submit" type="submit">
					<span class="screen-reader-text"><?php esc_html_e( 'Subscribe', 'estatein' ); ?></span>
					<?php echo estatein_icon( 'send-solid', array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<?php estatein_form_status( 'newsletter' ); ?>
			</form>
		</div>

		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer', 'estatein' ); ?>">
			<?php foreach ( estatein_footer_columns() as $estatein_column ) : ?>
				<div class="footer-nav__col">
					<a class="footer-nav__heading" href="<?php echo esc_url( $estatein_column['url'] ); ?>"><?php echo esc_html( $estatein_column['title'] ); ?></a>
					<?php if ( $estatein_column['links'] ) : ?>
						<ul class="footer-nav__list">
							<?php foreach ( $estatein_column['links'] as $estatein_link ) : ?>
								<li><a href="<?php echo esc_url( $estatein_link['url'] ); ?>"><?php echo esc_html( $estatein_link['title'] ); ?></a></li>
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</nav>
	</div>

	<div class="site-footer__bottom">
		<div class="container site-footer__bottom-inner">
			<p class="site-footer__legal">
				<span><?php echo esc_html( estatein_option( 'copyright' ) ); ?></span>
				<a href="<?php echo esc_url( estatein_url( estatein_option( 'terms_url' ) ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'estatein' ); ?></a>
			</p>
			<ul class="socials">
				<?php foreach ( estatein_socials() as $estatein_social ) : ?>
					<?php
					if ( 'instagram' === $estatein_social['key'] ) {
						continue; // The footer design shows four networks.
					}
					?>
					<li>
						<a class="socials__link" href="<?php echo esc_url( $estatein_social['url'] ); ?>" target="_blank" rel="noopener noreferrer">
							<?php echo estatein_icon( $estatein_social['key'], array( 'size' => 22 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="screen-reader-text"><?php echo esc_html( $estatein_social['label'] ); ?> <?php esc_html_e( '(opens in a new tab)', 'estatein' ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
