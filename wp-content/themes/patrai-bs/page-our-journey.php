<?php
/**
 * Journey timeline.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/page', 'hero', array( 'title' => 'Our Journey', 'text' => 'Manufacturing roots. Expanding application knowledge. A continuous focus on useful products.', 'image' => 'img/slider/slider03.jpg' ) );
	?>
	<main id="main-content">
		<section class="section-space journey-intro"><div class="container"><div class="row g-5 align-items-center"><div class="col-lg-5"><span class="eyebrow text-primary">Progress with purpose</span><h2 class="section-title">Built step by step, requirement by requirement</h2></div><div class="col-lg-7"><div class="entry-content section-lead"><?php the_content(); ?></div></div></div></div></section>
		<section class="journey-timeline-section section-space soft-section"><div class="container"><div class="journey-timeline">
			<?php $timeline = new WP_Query( array( 'post_type' => 'patrai_milestone', 'posts_per_page' => -1, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'ASC' ), 'no_found_rows' => true ) ); ?>
			<?php $i = 0; while ( $timeline->have_posts() ) : $timeline->the_post(); ?>
				<article class="timeline-item <?php echo 0 === $i % 2 ? 'timeline-left' : 'timeline-right'; ?>"><div class="timeline-dot" aria-hidden="true"></div><div class="timeline-year"><?php echo esc_html( get_post_meta( get_the_ID(), '_patrai_milestone_year', true ) ); ?></div><div class="timeline-card"><span>Milestone <?php echo esc_html( str_pad( (string) ( $i + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span><h2><?php the_title(); ?></h2><div><?php the_content(); ?></div></div></article>
			<?php $i++; endwhile; wp_reset_postdata(); ?>
		</div></div></section>
		<section class="section-space"><div class="container"><div class="future-panel"><div><span class="eyebrow text-primary">What stays constant</span><h2>Durability, finish, function and relationships</h2></div><p>The technology and applications keep evolving. Our foundation remains manufacturing discipline, material understanding and a customer perspective.</p></div></div></section>
		<?php get_template_part( 'template-parts/cta' ); ?>
	</main>
	<?php
endwhile;
get_footer();
