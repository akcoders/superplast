<?php
/**
 * Single case study.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) : the_post();
	$terms = get_the_terms( get_the_ID(), 'patrai_case_industry' );
	?>
	<main id="main-content">
		<section class="case-single-hero"><div class="container"><div class="case-single-grid"><div class="case-single-copy"><span class="eyebrow"><?php echo esc_html( $terms && ! is_wp_error( $terms ) ? $terms[0]->name : 'Application Highlight' ); ?></span><h1><?php the_title(); ?></h1><p><?php echo esc_html( patrai_bs_excerpt( 34 ) ); ?></p></div><div class="case-single-image"><?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'patrai-wide', array( 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async', 'alt' => get_the_title() ) ); } ?></div></div></div></section>
		<section class="section-space"><div class="container"><div class="row justify-content-center"><div class="col-lg-9"><article class="entry-content case-content"><?php the_content(); ?></article><div class="case-next"><span>Have a similar application?</span><h2>Let’s review the actual operating details.</h2><a class="btn btn-primary" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>">Discuss Your Requirement</a></div></div></div></div></section>
	</main>
	<?php
endwhile;
get_footer();
