<?php
/**
 * About page.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/page', 'hero', array( 'title' => 'About Us', 'text' => 'Manufacturing experience, material knowledge and an application-first outlook.', 'image' => 'img/background/cooling_tower.jpg' ) );
	?>
	<main id="main-content">
		<section class="section-space">
			<div class="container"><div class="row align-items-center g-5">
				<div class="col-lg-6"><div class="about-image-wrap simple"><?php echo patrai_bs_asset_image( 'img/home/cooling_tower.jpg', 'patrai-wide', array( 'class' => 'about-main-image', 'alt' => 'Cooling tower polymer components', 'loading' => 'eager', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><div class="experience-card"><strong>35+</strong><span>Years of manufacturing experience</span></div></div></div>
				<div class="col-lg-6"><span class="eyebrow text-primary">Who we are</span><h2 class="section-title">A manufacturing company built around useful outcomes</h2><div class="entry-content"><?php the_content(); ?></div></div>
			</div></div>
		</section>
		<section class="section-space soft-section">
			<div class="container"><div class="section-heading text-center mx-auto"><span class="eyebrow text-primary">How we work</span><h2 class="section-title">Clear priorities. Practical engineering.</h2></div><div class="row g-4">
				<div class="col-md-4"><div class="principle-card"><span>01</span><h3>Understand the application</h3><p>Operating conditions, environment, dimensions and desired outcome come first.</p></div></div>
				<div class="col-md-4"><div class="principle-card"><span>02</span><h3>Align material & geometry</h3><p>Polymer choice and product form are considered as one connected decision.</p></div></div>
				<div class="col-md-4"><div class="principle-card"><span>03</span><h3>Support implementation</h3><p>We keep communication direct as selection moves toward manufacturing and supply.</p></div></div>
			</div></div>
		</section>
		<section class="section-space"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-5"><span class="eyebrow text-primary">Our focus</span><h2 class="section-title">Three sectors, one polymer foundation</h2><p class="section-lead">The product families are different; the focus on durability, finish, function and service remains consistent.</p></div><div class="col-lg-7"><div class="focus-grid"><a href="<?php echo esc_url( home_url( '/product/cooling-tower-components/' ) ); ?>"><strong>Cooling towers</strong><span>Thermal-transfer and water-management components</span></a><a href="<?php echo esc_url( home_url( '/product/biological-media/' ) ); ?>"><strong>Water technology</strong><span>Media for biological and separation processes</span></a><a href="<?php echo esc_url( home_url( '/product/building-products/' ) ); ?>"><strong>Building & profiles</strong><span>Durable products and custom extrusion capability</span></a></div></div></div></div></section>
		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
