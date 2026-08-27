<?php
/**
 * Homepage.
 *
 * @package Patrai_BS
 */

get_header();
$slides = new WP_Query( array( 'post_type' => 'patrai_slide', 'posts_per_page' => 8, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) );
?>
<main id="main-content">
	<section class="home-hero" aria-label="<?php esc_attr_e( 'Featured solutions', 'patrai-bs' ); ?>">
		<?php if ( $slides->have_posts() ) : ?>
		<div id="patraiHeroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="6500" data-bs-pause="hover">
			<div class="carousel-indicators">
				<?php for ( $i = 0; $i < $slides->post_count; $i++ ) : ?><button type="button" data-bs-target="#patraiHeroCarousel" data-bs-slide-to="<?php echo esc_attr( $i ); ?>" class="<?php echo 0 === $i ? 'active' : ''; ?>" <?php echo 0 === $i ? 'aria-current="true"' : ''; ?> aria-label="<?php echo esc_attr( 'Slide ' . ( $i + 1 ) ); ?>"></button><?php endfor; ?>
			</div>
			<div class="carousel-inner">
				<?php $slide_index = 0; while ( $slides->have_posts() ) : $slides->the_post(); ?>
					<div class="carousel-item <?php echo 0 === $slide_index ? 'active' : ''; ?>">
						<div class="hero-media">
							<?php
							if ( has_post_thumbnail() ) {
								the_post_thumbnail( 'full', array( 'class' => 'hero-image', 'loading' => 0 === $slide_index ? 'eager' : 'lazy', 'fetchpriority' => 0 === $slide_index ? 'high' : 'auto', 'decoding' => 'async', 'sizes' => '100vw' ) );
							}
							?>
						</div>
						<div class="hero-overlay"></div>
						<div class="container hero-container">
							<div class="hero-copy">
								<span class="eyebrow"><?php echo esc_html( get_post_meta( get_the_ID(), '_patrai_slide_kicker', true ) ); ?></span>
								<h1><?php the_title(); ?></h1>
								<?php if ( has_excerpt() ) : ?><p><?php echo esc_html( get_the_excerpt() ); ?></p><?php endif; ?>
								<div class="hero-actions d-flex flex-wrap gap-3">
									<?php $button_url = get_post_meta( get_the_ID(), '_patrai_slide_button_url', true ); ?>
									<a class="btn btn-primary btn-lg" href="<?php echo esc_url( $button_url ?: home_url( '/our-products/' ) ); ?>"><?php echo esc_html( get_post_meta( get_the_ID(), '_patrai_slide_button_label', true ) ?: 'Explore Solutions' ); ?> <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
									<a class="btn btn-outline-light btn-lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Discuss a Requirement</a>
								</div>
							</div>
						</div>
					</div>
				<?php $slide_index++; endwhile; wp_reset_postdata(); ?>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#patraiHeroCarousel" data-bs-slide="prev"><span class="carousel-control-prev-icon" aria-hidden="true"></span><span class="visually-hidden">Previous</span></button>
			<button class="carousel-control-next" type="button" data-bs-target="#patraiHeroCarousel" data-bs-slide="next"><span class="carousel-control-next-icon" aria-hidden="true"></span><span class="visually-hidden">Next</span></button>
		</div>
		<?php else : ?>
		<div class="hero-fallback">
			<?php echo patrai_bs_asset_image( 'img/slider/slider02.jpg', 'full', array( 'class' => 'hero-image', 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high' ), 'hero.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="hero-overlay"></div><div class="container hero-container"><div class="hero-copy"><span class="eyebrow"><?php echo esc_html( patrai_bs_option( 'hero_kicker' ) ); ?></span><h1><?php echo esc_html( patrai_bs_option( 'hero_title' ) ); ?></h1><p><?php echo esc_html( patrai_bs_option( 'hero_text' ) ); ?></p><a class="btn btn-primary btn-lg" href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>">Explore Products</a></div></div>
		</div>
		<?php endif; ?>
	</section>

	<section class="quick-value-section" aria-label="<?php esc_attr_e( 'Core strengths', 'patrai-bs' ); ?>">
		<div class="container">
			<div class="quick-value-grid">
				<div class="quick-value"><span class="value-number">01</span><div><strong>Application-led</strong><small>Selection starts with your operating need.</small></div></div>
				<div class="quick-value"><span class="value-number">02</span><div><strong>Polymer expertise</strong><small>Material and geometry considered together.</small></div></div>
				<div class="quick-value"><span class="value-number">03</span><div><strong>Responsive support</strong><small>Clear communication from enquiry onward.</small></div></div>
			</div>
		</div>
	</section>

	<section class="section-space about-preview overflow-hidden">
		<div class="container">
			<div class="row align-items-center g-5 g-xl-6">
				<div class="col-lg-6">
					<div class="about-image-wrap">
						<?php echo patrai_bs_asset_image( 'img/home/cooling_towers.jpg', 'patrai-wide', array( 'class' => 'about-main-image', 'alt' => 'Super Plast industrial product applications', 'loading' => 'lazy', 'decoding' => 'async' ), 'about.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<div class="experience-card"><strong>1988</strong><span>Manufacturing roots.<br>Engineering forward.</span></div>
						<div class="dot-pattern" aria-hidden="true"></div>
					</div>
				</div>
				<div class="col-lg-6">
					<span class="eyebrow text-primary">About Super Plast</span>
					<h2 class="section-title"><?php echo esc_html( patrai_bs_option( 'about_title' ) ); ?></h2>
					<p class="section-lead"><?php echo esc_html( patrai_bs_option( 'about_text' ) ); ?></p>
					<div class="feature-list row g-3">
						<div class="col-sm-6"><div class="feature-item"><?php echo patrai_bs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>Cooling tower components</span></div></div>
						<div class="col-sm-6"><div class="feature-item"><?php echo patrai_bs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>Water treatment media</span></div></div>
						<div class="col-sm-6"><div class="feature-item"><?php echo patrai_bs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>Building products</span></div></div>
						<div class="col-sm-6"><div class="feature-item"><?php echo patrai_bs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><span>Custom PVC profiles</span></div></div>
					</div>
					<a class="btn btn-outline-primary mt-4" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>">Know Our Company <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
				</div>
			</div>
		</div>
	</section>

	<?php $feature_cards = new WP_Query( array( 'post_type' => 'patrai_feature', 'posts_per_page' => 8, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) ); ?>
	<?php if ( $feature_cards->have_posts() ) : ?>
	<section id="why-choose-us" class="home-feature-showcase" aria-label="<?php esc_attr_e( 'Why choose Super Plast', 'patrai-bs' ); ?>">
		<div class="feature-showcase-grid">
			<?php $feature_index = 0; while ( $feature_cards->have_posts() ) : $feature_cards->the_post(); $feature_url = get_post_meta( get_the_ID(), '_patrai_feature_url', true ) ?: home_url( '/about-us/' ); $feature_icon = get_post_meta( get_the_ID(), '_patrai_feature_icon', true ) ?: 'technology'; ?>
			<article class="feature-showcase-card feature-tone-<?php echo esc_attr( ( $feature_index % 4 ) + 1 ); ?>">
				<a href="<?php echo esc_url( $feature_url ); ?>">
					<?php if ( has_post_thumbnail() ) : ?><span class="feature-showcase-bg"><?php the_post_thumbnail( 'patrai-wide', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => '' ) ); ?></span><?php endif; ?>
					<span class="feature-showcase-overlay" aria-hidden="true"></span>
					<span class="feature-showcase-content">
						<span class="feature-showcase-number"><?php echo esc_html( str_pad( (string) ( $feature_index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
						<span class="feature-showcase-icon"><?php echo patrai_bs_feature_icon( $feature_icon ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
						<strong><?php the_title(); ?></strong>
						<?php if ( has_excerpt() ) : ?><span class="feature-showcase-description"><?php echo esc_html( get_the_excerpt() ); ?></span><?php endif; ?>
						<b>Explore <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b>
					</span>
				</a>
			</article>
			<?php $feature_index++; endwhile; wp_reset_postdata(); ?>
		</div>
	</section>
	<?php endif; ?>

	<section class="section-space soft-section products-preview">
		<div class="container">
			<div class="section-heading d-flex flex-column flex-lg-row align-items-lg-end justify-content-between gap-3">
				<div><span class="eyebrow text-primary">Engineered product families</span><h2 class="section-title mb-0">Solutions shaped around the application</h2></div>
				<a class="text-link" href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>">View all products <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
			</div>
			<div class="row g-4">
				<?php $products = new WP_Query( array( 'post_type' => 'patrai_product', 'posts_per_page' => 6, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) ); ?>
				<?php while ( $products->have_posts() ) : $products->the_post(); ?><div class="col-md-6 col-xl-4"><?php get_template_part( 'template-parts/product', 'card' ); ?></div><?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="section-space industries-showcase">
		<div class="container">
			<div class="row align-items-end mb-5 g-3"><div class="col-lg-8"><span class="eyebrow text-primary">Three focused sectors</span><h2 class="section-title mb-0">One practical polymer-engineering mindset</h2></div><div class="col-lg-4"><p class="mb-0 text-secondary">Explore by application area, then talk to us about dimensions, environment and operating requirements.</p></div></div>
			<div class="row g-4">
				<?php
				$sectors = array(
					array( 'Cooling Tower Components', 'Fill media, drift control, distribution and installation accessories.', 'img/background/cooling_tower.jpg', '/product/cooling-tower-components/' ),
					array( 'Water & Wastewater', 'Biological carriers, settling media and structured treatment solutions.', 'img/background/Water_Wastewater_Technology.jpg', '/product/biological-media/' ),
					array( 'Building & Profiles', 'FRP gratings, uPVC louvers and custom flexible or rigid profiles.', 'img/background/buildingTechnology.jpg', '/product/building-products/' ),
				);
				foreach ( $sectors as $sector ) :
				?>
				<div class="col-lg-4"><a class="sector-card" href="<?php echo esc_url( home_url( $sector[3] ) ); ?>"><span class="sector-media"><?php echo patrai_bs_asset_image( $sector[2], 'patrai-card', array( 'alt' => $sector[0], 'loading' => 'lazy', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><span class="sector-overlay"></span><span class="sector-copy"><small>Explore sector</small><strong><?php echo esc_html( $sector[0] ); ?></strong><em><?php echo esc_html( $sector[1] ); ?></em><b>Discover <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></span></a></div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section-space journey-preview soft-section">
		<div class="container">
			<div class="row align-items-center g-5">
				<div class="col-lg-5"><span class="eyebrow text-primary">Our journey</span><h2 class="section-title">Experience that keeps moving forward</h2><p class="section-lead">Since 1988, each product family has added another layer of material understanding and application knowledge.</p><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/our-journey/' ) ); ?>">Explore Our Journey <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></div>
				<div class="col-lg-7">
					<div class="journey-steps">
						<?php $journey = new WP_Query( array( 'post_type' => 'patrai_milestone', 'posts_per_page' => 3, 'orderby' => 'menu_order', 'order' => 'ASC', 'no_found_rows' => true ) ); ?>
						<?php while ( $journey->have_posts() ) : $journey->the_post(); ?><div class="journey-step"><span><?php echo esc_html( get_post_meta( get_the_ID(), '_patrai_milestone_year', true ) ); ?></span><div><h3><?php the_title(); ?></h3><p><?php echo esc_html( wp_trim_words( wp_strip_all_tags( get_the_content() ), 18 ) ); ?></p></div></div><?php endwhile; wp_reset_postdata(); ?>
					</div>
				</div>
			</div>
		</div>
	</section>

	<section class="section-space case-preview">
		<div class="container">
			<div class="section-heading text-center mx-auto"><span class="eyebrow text-primary">Application highlights</span><h2 class="section-title">See the thinking behind selection</h2><p>Representative approaches that show what we consider before recommending a product direction.</p></div>
			<div class="row g-4 justify-content-center">
				<?php $cases = new WP_Query( array( 'post_type' => 'patrai_case', 'posts_per_page' => 3, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) ); ?>
				<?php while ( $cases->have_posts() ) : $cases->the_post(); ?><div class="col-md-6 col-xl-4"><?php get_template_part( 'template-parts/case', 'card' ); ?></div><?php endwhile; wp_reset_postdata(); ?>
			</div>
		</div>
	</section>

	<section class="cta-band">
		<div class="container"><div class="cta-band-inner"><div><span class="eyebrow">Start with your requirement</span><h2>Have an application to discuss?</h2><p>Share the operating details, dimensions or drawings. We’ll help frame the next step.</p></div><div class="d-flex flex-wrap gap-3"><a class="btn btn-light btn-lg" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Request a Discussion</a><a class="btn btn-outline-light btn-lg" href="<?php echo esc_url( patrai_bs_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp Us</a></div></div></div>
	</section>
</main>
<?php get_footer(); ?>
