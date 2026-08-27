<?php
/**
 * Default page template.
 *
 * @package Patrai_BS
 */
get_header();
while ( have_posts() ) : the_post();
	get_template_part( 'template-parts/page', 'hero', array( 'title' => get_the_title(), 'text' => get_the_excerpt(), 'image' => 'img/background/contact-us-banner.jpg' ) );
	?>
	<main id="main-content" class="section-space"><div class="container"><article class="entry-content content-panel mx-auto"><?php the_content(); ?></article></div></main>
	<?php
endwhile;
get_footer();
