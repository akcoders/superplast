<?php
/**
 * Site footer.
 *
 * @package Patrai_BS
 */
?>
<footer class="site-footer">
	<div class="footer-main">
		<div class="container">
			<div class="row g-5">
				<div class="col-lg-4">
					<div class="footer-brand"><?php echo patrai_bs_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<p><?php echo esc_html( patrai_bs_option( 'tagline' ) ); ?> — application-focused polymer products backed by manufacturing experience since 1988.</p>
					<div class="footer-social d-flex gap-2">
						<a href="<?php echo esc_url( patrai_bs_option( 'facebook' ) ); ?>" target="_blank" rel="noopener" aria-label="Facebook">f</a>
						<a href="<?php echo esc_url( patrai_bs_option( 'twitter' ) ); ?>" target="_blank" rel="noopener" aria-label="X">x</a>
						<a href="<?php echo esc_url( patrai_bs_option( 'linkedin' ) ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn">in</a>
					</div>
				</div>
				<div class="col-6 col-lg-2">
					<h2><?php esc_html_e( 'Explore', 'patrai-bs' ); ?></h2>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">About Us</a></li>
						<li><a href="<?php echo esc_url( home_url( '/our-journey/' ) ); ?>">Our Journey</a></li>
						<li><a href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>">Our Products</a></li>
						<li><a href="<?php echo esc_url( home_url( '/case-studies/' ) ); ?>">Case Studies</a></li>
					</ul>
				</div>
				<div class="col-6 col-lg-2">
					<h2><?php esc_html_e( 'Products', 'patrai-bs' ); ?></h2>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/product/cooling-tower-components/' ) ); ?>">Cooling Towers</a></li>
						<li><a href="<?php echo esc_url( home_url( '/product/biological-media/' ) ); ?>">Water Technology</a></li>
						<li><a href="<?php echo esc_url( home_url( '/product/building-products/' ) ); ?>">Building Products</a></li>
						<li><a href="<?php echo esc_url( home_url( '/product/pvc-profiles/' ) ); ?>">PVC Profiles</a></li>
					</ul>
				</div>
				<div class="col-lg-4">
					<h2><?php esc_html_e( 'Connect with us', 'patrai-bs' ); ?></h2>
					<ul class="footer-contact">
						<li><?php echo patrai_bs_icon( 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span><?php echo esc_html( patrai_bs_option( 'address' ) ); ?></span></li>
						<li><?php echo patrai_bs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="<?php echo esc_url( patrai_bs_phone_href( patrai_bs_option( 'phone' ) ) ); ?>"><?php echo esc_html( patrai_bs_option( 'phone' ) ); ?></a></li>
						<li><?php echo patrai_bs_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><a href="mailto:<?php echo esc_attr( antispambot( patrai_bs_option( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( patrai_bs_option( 'email' ) ) ); ?></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	<div class="footer-bottom">
		<div class="container d-flex flex-column flex-md-row justify-content-between gap-2">
			<span>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( patrai_bs_option( 'company_name' ) ); ?>.</span>
			<a href="<?php echo esc_url( patrai_bs_option( 'brochure_url' ) ); ?>" target="_blank" rel="noopener">Download Company Brochure</a>
		</div>
	</div>
</footer>
<a class="whatsapp-float" href="<?php echo esc_url( patrai_bs_whatsapp_url() ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'Chat with Super Plast on WhatsApp', 'patrai-bs' ); ?>">
	<?php echo patrai_bs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	<span><?php esc_html_e( 'WhatsApp', 'patrai-bs' ); ?></span>
</a>
<?php wp_footer(); ?>
</body>
</html>
