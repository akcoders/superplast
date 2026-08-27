<?php
/**
 * Inner-page hero.
 *
 * @package Patrai_BS
 */

$patrai_title = isset( $args['title'] ) ? $args['title'] : get_the_title();
$patrai_text  = isset( $args['text'] ) ? $args['text'] : '';
$patrai_image = isset( $args['image'] ) ? $args['image'] : 'img/background/contact-us-banner.jpg';
?>
<section class="page-hero">
	<div class="page-hero-media" aria-hidden="true">
		<?php echo patrai_bs_asset_image( $patrai_image, 'patrai-wide', array( 'alt' => '', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ), 'inner-hero.jpg' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
	</div>
	<div class="container position-relative">
		<div class="page-hero-copy">
			<span class="eyebrow"><?php echo esc_html( patrai_bs_option( 'company_name' ) ); ?></span>
			<h1><?php echo esc_html( $patrai_title ); ?></h1>
			<?php if ( $patrai_text ) : ?><p><?php echo esc_html( $patrai_text ); ?></p><?php endif; ?>
			<nav aria-label="breadcrumb"><ol class="breadcrumb"><li class="breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a></li><li class="breadcrumb-item active" aria-current="page"><?php echo esc_html( $patrai_title ); ?></li></ol></nav>
		</div>
	</div>
</section>
