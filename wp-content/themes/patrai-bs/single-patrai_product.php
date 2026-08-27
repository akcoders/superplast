<?php
/**
 * Dynamic single-product presentation.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) :
	the_post();
	$product_id   = get_the_ID();
	$terms        = get_the_terms( $product_id, 'patrai_product_category' );
	$term         = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;
	$tagline      = get_post_meta( $product_id, '_patrai_product_tagline', true );
	$features     = patrai_bs_meta_lines( '_patrai_product_features', $product_id );
	$applications = patrai_bs_meta_lines( '_patrai_product_applications', $product_id );
	$technical    = get_post_meta( $product_id, '_patrai_product_specs_html', true );
	$gallery      = get_post_meta( $product_id, '_patrai_product_gallery_ids', true );
	$gallery      = is_array( $gallery ) ? array_values( array_filter( array_map( 'absint', $gallery ) ) ) : array();
	?>
	<main id="main-content">
		<section class="single-product-hero">
			<div class="product-hero-shape" aria-hidden="true"></div>
			<div class="container position-relative"><div class="row align-items-center g-5 g-xl-6">
				<div class="col-lg-6">
					<div class="single-product-copy">
						<nav class="product-breadcrumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a><span>/</span><a href="<?php echo esc_url( home_url( '/our-products/' ) ); ?>">Products</a></nav>
						<span class="eyebrow"><?php echo esc_html( $term ? $term->name : 'Super Plast Product' ); ?></span>
						<h1><?php the_title(); ?></h1>
						<p><?php echo esc_html( $tagline ?: patrai_bs_excerpt( 36 ) ); ?></p>
						<div class="d-flex flex-wrap gap-3"><a class="btn btn-primary btn-lg" href="<?php echo esc_url( home_url( '/contact-us/?product=' . rawurlencode( get_the_title() ) ) ); ?>">Discuss This Product <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a><a class="btn btn-outline-primary btn-lg" href="<?php echo esc_url( patrai_bs_whatsapp_url() ); ?>" target="_blank" rel="noopener">WhatsApp</a></div>
					</div>
				</div>
				<div class="col-lg-6">
					<div class="single-product-visual">
						<div class="single-product-image"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'patrai-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => get_the_title() ) ); } ?></div>
					</div>
				</div>
			</div></div>
		</section>

		<nav class="product-section-nav" aria-label="Product page sections"><div class="container"><div class="product-section-links"><a href="#overview">Overview</a><?php if ( $features ) : ?><a href="#features">Features</a><?php endif; ?><?php if ( $applications ) : ?><a href="#applications">Applications</a><?php endif; ?><?php if ( $technical ) : ?><a href="#specifications">Specifications</a><?php endif; ?><?php if ( $gallery ) : ?><a href="#gallery">Gallery</a><?php endif; ?><a class="ms-lg-auto" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Enquire now <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></div></div></nav>

		<section id="overview" class="section-space product-overview-section"><div class="container"><div class="row g-5 g-xl-6">
			<div class="col-lg-8"><span class="eyebrow text-primary">Complete product overview</span><article class="entry-content product-content"><?php the_content(); ?></article></div>
			<aside class="col-lg-4"><div class="product-sidebar"><span class="eyebrow">Selection support</span><h2>Start with the operating conditions</h2><p>Share the application, system type, dimensions, temperature, flow, water quality and quantity wherever relevant.</p><ul><li>Application review</li><li>Product and model discussion</li><li>Dimensional requirement check</li><li>Commercial enquiry support</li></ul><a class="btn btn-light w-100" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Send Requirement</a><a class="sidebar-whatsapp" href="<?php echo esc_url( patrai_bs_whatsapp_url() ); ?>" target="_blank" rel="noopener"><?php echo patrai_bs_icon( 'whatsapp' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> Ask on WhatsApp</a></div></aside>
		</div></div></section>

		<?php if ( $features ) : ?>
		<section id="features" class="section-space soft-section product-features-section"><div class="container"><div class="section-heading d-flex flex-column flex-lg-row justify-content-between align-items-lg-end gap-3"><div><span class="eyebrow text-primary">Key product details</span><h2 class="section-title mb-0">Features & advantages</h2></div><p>Highlights extracted from the complete product information and editable from the Product admin screen.</p></div><div class="product-feature-grid">
			<?php foreach ( $features as $index => $feature ) : ?><article class="product-feature-item"><span><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><p><?php echo esc_html( $feature ); ?></p></article><?php endforeach; ?>
		</div></div></section>
		<?php endif; ?>

		<?php if ( $applications ) : ?>
		<section id="applications" class="section-space product-applications-section"><div class="container"><div class="application-panel"><div><span class="eyebrow">Where it fits</span><h2>Typical applications</h2><p>Final selection depends on the actual operating and dimensional requirements.</p></div><div class="application-list"><?php foreach ( $applications as $application ) : ?><span><?php echo patrai_bs_icon( 'check' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> <?php echo esc_html( $application ); ?></span><?php endforeach; ?></div></div></div></section>
		<?php endif; ?>

		<?php if ( $technical ) : ?>
		<section id="specifications" class="section-space soft-section product-technical-section"><div class="container"><div class="row g-5"><div class="col-lg-4"><div class="section-sticky-heading"><span class="eyebrow text-primary">Technical library</span><h2 class="section-title">Models & specifications</h2><p>Expand a model to review its source technical information. Every table remains editable in WordPress.</p></div></div><div class="col-lg-8"><div class="technical-accordion"><?php echo wp_kses_post( $technical ); ?></div></div></div></div></section>
		<?php endif; ?>

		<?php if ( $gallery ) : ?>
		<section id="gallery" class="section-space product-gallery-section"><div class="container"><div class="section-heading"><span class="eyebrow text-primary">Product gallery</span><h2 class="section-title mb-0">Product images</h2></div><div class="dynamic-product-gallery">
			<?php foreach ( $gallery as $index => $image_id ) : $full = wp_get_attachment_image_url( $image_id, 'full' ); ?><a class="gallery-item gallery-item-<?php echo esc_attr( ( $index % 7 ) + 1 ); ?>" href="<?php echo esc_url( $full ); ?>" target="_blank" rel="noopener"><span><?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ?: get_the_title() ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span><b>View full image <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></b></a><?php endforeach; ?>
		</div></div></section>
		<?php endif; ?>

		<?php if ( $term ) : ?>
		<section class="section-space soft-section"><div class="container"><div class="section-heading d-flex align-items-end justify-content-between gap-3"><div><span class="eyebrow text-primary">Related solutions</span><h2 class="section-title mb-0">More in <?php echo esc_html( $term->name ); ?></h2></div><a class="text-link" href="<?php echo esc_url( home_url( '/our-products/#' . $term->slug ) ); ?>">View family <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a></div><div class="row g-4">
		<?php $related = new WP_Query( array( 'post_type' => 'patrai_product', 'posts_per_page' => 3, 'post__not_in' => array( $product_id ), 'tax_query' => array( array( 'taxonomy' => 'patrai_product_category', 'field' => 'term_id', 'terms' => $term->term_id ) ), 'orderby' => 'menu_order', 'order' => 'ASC', 'no_found_rows' => true ) ); while ( $related->have_posts() ) : $related->the_post(); ?><div class="col-md-6 col-xl-4"><?php get_template_part( 'template-parts/product', 'card' ); ?></div><?php endwhile; wp_reset_postdata(); ?>
		</div></div></section>
		<?php endif; ?>
	</main>
	<?php
endwhile;
get_footer();
