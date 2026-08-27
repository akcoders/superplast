<?php
/**
 * Case-study card.
 *
 * @package Patrai_BS
 */
?>
<article <?php post_class( 'case-card h-100' ); ?>>
	<a class="case-card-image" href="<?php the_permalink(); ?>" tabindex="-1" aria-hidden="true">
		<?php if ( has_post_thumbnail() ) : ?><?php the_post_thumbnail( 'patrai-card', array( 'loading' => 'lazy', 'decoding' => 'async', 'alt' => get_the_title() ) ); ?><?php endif; ?>
	</a>
	<div class="case-card-body">
		<?php $terms = get_the_terms( get_the_ID(), 'patrai_case_industry' ); ?>
		<?php if ( $terms && ! is_wp_error( $terms ) ) : ?><span class="card-kicker"><?php echo esc_html( $terms[0]->name ); ?></span><?php endif; ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<p><?php echo esc_html( patrai_bs_excerpt( 24 ) ); ?></p>
		<a class="text-link" href="<?php the_permalink(); ?>">Read application <?php echo patrai_bs_icon( 'arrow' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
	</div>
</article>
