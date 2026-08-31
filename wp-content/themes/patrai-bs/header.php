<?php
/**
 * Site header.
 *
 * @package Patrai_BS
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link visually-hidden-focusable" href="#main-content"><?php esc_html_e( 'Skip to content', 'patrai-bs' ); ?></a>
<header class="site-header">
	<div class="topbar">
		<div class="container d-flex align-items-center justify-content-between gap-3">
			<div class="topbar-contact d-flex align-items-center gap-3 gap-lg-4">
				<a href="<?php echo esc_url( patrai_bs_phone_href( patrai_bs_option( 'phone' ) ) ); ?>"><?php echo patrai_bs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span><?php echo esc_html( patrai_bs_option( 'phone' ) ); ?></span></a>
				<a class="d-none d-sm-inline-flex" href="mailto:<?php echo esc_attr( antispambot( patrai_bs_option( 'email' ) ) ); ?>"><?php echo patrai_bs_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <span><?php echo esc_html( antispambot( patrai_bs_option( 'email' ) ) ); ?></span></a>
			</div>
			<div class="topbar-meta d-flex align-items-center gap-3">
				<span class="since-badge"><?php echo esc_html( patrai_bs_option( 'since' ) ); ?></span>
				<div class="top-social d-none d-md-flex" aria-label="<?php esc_attr_e( 'Social links', 'patrai-bs' ); ?>">
					<a href="<?php echo esc_url( patrai_bs_option( 'facebook' ) ); ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a>
					<a href="<?php echo esc_url( patrai_bs_option( 'twitter' ) ); ?>" target="_blank" rel="noopener" aria-label="X">x</a>
					<a href="<?php echo esc_url( patrai_bs_option( 'linkedin' ) ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">in</a>
				</div>
			</div>
		</div>
	</div>
	<nav class="navbar navbar-expand-xl bg-white" aria-label="<?php esc_attr_e( 'Primary navigation', 'patrai-bs' ); ?>">
		<div class="container">
			<div class="navbar-brand mb-0"><?php echo patrai_bs_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#patraiPrimaryNav" aria-controls="patraiPrimaryNav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'patrai-bs' ); ?>">
				<span></span><span></span><span></span>
			</button>
			<div class="collapse navbar-collapse" id="patraiPrimaryNav">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'navbar-nav ms-auto align-items-xl-center',
						'fallback_cb'    => 'patrai_bs_menu_fallback',
						'depth'          => 2,
						'walker'         => new Patrai_BS_Primary_Nav_Walker(),
					)
				);
				?>
			</div>
		</div>
	</nav>
</header>
