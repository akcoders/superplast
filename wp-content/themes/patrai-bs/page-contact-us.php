<?php
/**
 * Contact page and native form.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/page', 'hero', array( 'title' => 'Contact Us', 'text' => 'Tell us what needs to work. We’ll help frame the product conversation.', 'image' => 'img/background/contact-us-banner.jpg' ) );
	$status = isset( $_GET['contact'] ) ? sanitize_key( wp_unslash( $_GET['contact'] ) ) : '';
	?>
	<main id="main-content">
		<section class="section-space contact-section"><div class="container"><div class="row g-5">
			<div class="col-lg-5"><span class="eyebrow text-primary">Let’s talk</span><h2 class="section-title">Start with the application</h2><div class="entry-content mb-4"><?php the_content(); ?></div>
				<div class="contact-detail-list">
					<div class="contact-detail"><span><?php echo patrai_bs_icon( 'location' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong>Manufacturing address</strong><p><?php echo esc_html( patrai_bs_option( 'address' ) ); ?></p></div></div>
					<div class="contact-detail"><span><?php echo patrai_bs_icon( 'phone' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong>Call us</strong><p><a href="<?php echo esc_url( patrai_bs_phone_href( patrai_bs_option( 'phone' ) ) ); ?>"><?php echo esc_html( patrai_bs_option( 'phone' ) ); ?></a><br><a href="<?php echo esc_url( patrai_bs_phone_href( patrai_bs_option( 'phone_secondary' ) ) ); ?>"><?php echo esc_html( patrai_bs_option( 'phone_secondary' ) ); ?></a></p></div></div>
					<div class="contact-detail"><span><?php echo patrai_bs_icon( 'mail' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><div><strong>Email</strong><p><a href="mailto:<?php echo esc_attr( antispambot( patrai_bs_option( 'email' ) ) ); ?>"><?php echo esc_html( antispambot( patrai_bs_option( 'email' ) ) ); ?></a><br><a href="mailto:<?php echo esc_attr( antispambot( patrai_bs_option( 'email_secondary' ) ) ); ?>"><?php echo esc_html( antispambot( patrai_bs_option( 'email_secondary' ) ) ); ?></a></p></div></div>
				</div>
			</div>
			<div class="col-lg-7"><div class="contact-form-card"><div class="form-heading"><span>Requirement enquiry</span><h2>How can we help?</h2></div>
				<?php if ( 'success' === $status ) : ?><div class="alert alert-success">Thank you. Your enquiry has been sent successfully.</div><?php elseif ( $status ) : ?><div class="alert alert-danger">The enquiry could not be sent. Please check the required fields or contact us directly.</div><?php endif; ?>
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="row g-3">
					<input type="hidden" name="action" value="patrai_contact"><?php wp_nonce_field( 'patrai_contact', 'patrai_contact_nonce' ); ?>
					<div class="col-md-6"><label for="contact-name" class="form-label">Name <span>*</span></label><input class="form-control" id="contact-name" name="name" type="text" autocomplete="name" required></div>
					<div class="col-md-6"><label for="contact-email" class="form-label">Email <span>*</span></label><input class="form-control" id="contact-email" name="email" type="email" autocomplete="email" required></div>
					<div class="col-md-6"><label for="contact-phone" class="form-label">Phone</label><input class="form-control" id="contact-phone" name="phone" type="tel" autocomplete="tel"></div>
					<div class="col-md-6"><label for="contact-subject" class="form-label">Requirement</label><select class="form-select" id="contact-subject" name="subject"><option>Product enquiry</option><option>Technical discussion</option><option>Request a quote</option><option>Brochure / company information</option></select></div>
					<div class="col-12"><label for="contact-message" class="form-label">Application details <span>*</span></label><textarea class="form-control" id="contact-message" name="message" rows="6" required placeholder="Please include application, dimensions, material, quantity or operating conditions where available."></textarea></div>
					<div class="col-12"><button class="btn btn-primary btn-lg w-100" type="submit">Send Enquiry <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></button></div>
				</form>
			</div></div>
		</div></div></section>
		<section class="contact-bottom"><div class="container"><div class="contact-bottom-inner"><div><span class="eyebrow">Prefer a quick conversation?</span><h2>Connect directly on WhatsApp</h2></div><a class="btn btn-light btn-lg" href="<?php echo esc_url( patrai_bs_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo patrai_bs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Start WhatsApp Chat</a></div></div></section>
	</main>
	<?php
endwhile;
get_footer();
