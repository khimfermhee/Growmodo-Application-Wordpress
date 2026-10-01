<?php
/**
 * Site header: announcement bar + main navigation.
 *
 * @package Estatein
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#141414">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e( 'Skip to content', 'estatein' ); ?></a>

<?php get_template_part( 'template-parts/header/announcement' ); ?>

<header class="site-header" id="site-header">
	<div class="container site-header__inner">
		<?php get_template_part( 'template-parts/header/logo' ); ?>

		<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Main', 'estatein' ); ?>">
			<?php
			if ( has_nav_menu( 'primary' ) ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'site-nav__list',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
			} else {
				get_template_part( 'template-parts/header/menu-fallback' );
			}

			$estatein_cta_label = estatein_option( 'header_button_label' );
			$estatein_cta_url   = estatein_url( estatein_option( 'header_button_url' ) );
			$estatein_cta_cur   = is_page() && untrailingslashit( get_permalink() ) === untrailingslashit( $estatein_cta_url );
			?>
			<a class="btn <?php echo $estatein_cta_cur ? 'btn--primary' : 'btn--secondary'; ?> site-nav__cta" href="<?php echo esc_url( $estatein_cta_url ); ?>"<?php echo $estatein_cta_cur ? ' aria-current="page"' : ''; ?>><?php echo esc_html( $estatein_cta_label ); ?></a>
		</nav>

		<button class="nav-toggle" type="button" aria-expanded="false" aria-controls="site-nav" data-nav-toggle>
			<span class="screen-reader-text" data-nav-toggle-label><?php esc_html_e( 'Open menu', 'estatein' ); ?></span>
			<?php echo estatein_icon( 'menu', array( 'class' => 'nav-toggle__open', 'size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php echo estatein_icon( 'x', array( 'class' => 'nav-toggle__close', 'size' => 28 ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</button>
	</div>
</header>

<main id="main" class="site-main" tabindex="-1">
