<?php
/**
 * Native theme options screen.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function patrai_bs_options_menu() {
	add_theme_page( __( 'PATRAI BS Options', 'patrai-bs' ), __( 'PATRAI BS Options', 'patrai-bs' ), 'manage_options', 'patrai-bs-options', 'patrai_bs_options_page' );
}
add_action( 'admin_menu', 'patrai_bs_options_menu' );

function patrai_bs_register_options() {
	register_setting( 'patrai_bs_group', 'patrai_bs_options', 'patrai_bs_sanitize_options' );
}
add_action( 'admin_init', 'patrai_bs_register_options' );

function patrai_bs_sanitize_options( $input ) {
	$defaults = patrai_bs_defaults();
	$output   = array();
	$text_fields = array( 'company_name', 'tagline', 'since', 'phone', 'phone_secondary', 'email', 'email_secondary', 'whatsapp', 'hero_kicker', 'hero_title', 'about_title', 'contact_recipient' );
	$url_fields  = array( 'facebook', 'twitter', 'linkedin', 'brochure_url' );
	$area_fields = array( 'address', 'hero_text', 'about_text' );
	$colors      = array( 'primary_color', 'primary_dark', 'soft_color' );
	foreach ( $text_fields as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_text_field( $input[ $key ] ) : $defaults[ $key ];
	}
	foreach ( $url_fields as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? esc_url_raw( $input[ $key ] ) : $defaults[ $key ];
	}
	foreach ( $area_fields as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) ? sanitize_textarea_field( $input[ $key ] ) : $defaults[ $key ];
	}
	foreach ( $colors as $key ) {
		$output[ $key ] = isset( $input[ $key ] ) && sanitize_hex_color( $input[ $key ] ) ? sanitize_hex_color( $input[ $key ] ) : $defaults[ $key ];
	}
	return $output;
}

function patrai_bs_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$options = wp_parse_args( (array) get_option( 'patrai_bs_options', array() ), patrai_bs_defaults() );
	$fields  = array(
		'company_name'      => array( 'Company name', 'text' ),
		'tagline'           => array( 'Tagline', 'text' ),
		'since'             => array( 'Since label', 'text' ),
		'phone'             => array( 'Primary phone', 'text' ),
		'phone_secondary'   => array( 'Secondary phone', 'text' ),
		'email'             => array( 'Primary email', 'email' ),
		'email_secondary'   => array( 'Secondary email', 'email' ),
		'whatsapp'          => array( 'WhatsApp number', 'text' ),
		'address'           => array( 'Address', 'textarea' ),
		'facebook'          => array( 'Facebook URL', 'url' ),
		'twitter'           => array( 'X / Twitter URL', 'url' ),
		'linkedin'          => array( 'LinkedIn URL', 'url' ),
		'brochure_url'      => array( 'Brochure PDF URL', 'url' ),
		'contact_recipient' => array( 'Contact form recipient', 'email' ),
	);
	$home_fields = array(
		'hero_kicker' => array( 'Fallback hero kicker', 'text' ),
		'hero_title'  => array( 'Fallback hero title', 'text' ),
		'hero_text'   => array( 'Fallback hero text', 'textarea' ),
		'about_title' => array( 'Home about heading', 'text' ),
		'about_text'  => array( 'Home about text', 'textarea' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'PATRAI BS Theme Options', 'patrai-bs' ); ?></h1>
		<p><?php esc_html_e( 'Global company details and homepage defaults live here. Manage page copy under Pages, slides under Home Slides, products under Products, journey entries under Journey, and case studies under Case Studies.', 'patrai-bs' ); ?></p>
		<form method="post" action="options.php">
			<?php settings_fields( 'patrai_bs_group' ); ?>
			<h2><?php esc_html_e( 'Company & contact', 'patrai-bs' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
			<?php foreach ( $fields as $key => $config ) : ?>
				<tr><th scope="row"><label for="patrai-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $config[0] ); ?></label></th><td>
				<?php if ( 'textarea' === $config[1] ) : ?>
					<textarea class="large-text" rows="3" id="patrai-<?php echo esc_attr( $key ); ?>" name="patrai_bs_options[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $options[ $key ] ); ?></textarea>
				<?php else : ?>
					<input class="regular-text" type="<?php echo esc_attr( $config[1] ); ?>" id="patrai-<?php echo esc_attr( $key ); ?>" name="patrai_bs_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $options[ $key ] ); ?>">
				<?php endif; ?>
				</td></tr>
			<?php endforeach; ?>
			</tbody></table>

			<h2><?php esc_html_e( 'Homepage copy', 'patrai-bs' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
			<?php foreach ( $home_fields as $key => $config ) : ?>
				<tr><th scope="row"><label for="patrai-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $config[0] ); ?></label></th><td>
				<?php if ( 'textarea' === $config[1] ) : ?>
					<textarea class="large-text" rows="3" id="patrai-<?php echo esc_attr( $key ); ?>" name="patrai_bs_options[<?php echo esc_attr( $key ); ?>]"><?php echo esc_textarea( $options[ $key ] ); ?></textarea>
				<?php else : ?>
					<input class="large-text" type="text" id="patrai-<?php echo esc_attr( $key ); ?>" name="patrai_bs_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $options[ $key ] ); ?>">
				<?php endif; ?>
				</td></tr>
			<?php endforeach; ?>
			</tbody></table>

			<h2><?php esc_html_e( 'Light blue palette', 'patrai-bs' ); ?></h2>
			<table class="form-table" role="presentation"><tbody>
			<?php foreach ( array( 'primary_color' => 'Primary blue', 'primary_dark' => 'Deep blue', 'soft_color' => 'Soft blue background' ) as $key => $label ) : ?>
				<tr><th scope="row"><label for="patrai-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $label ); ?></label></th><td><input type="color" id="patrai-<?php echo esc_attr( $key ); ?>" name="patrai_bs_options[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $options[ $key ] ); ?>"></td></tr>
			<?php endforeach; ?>
			</tbody></table>
			<?php submit_button(); ?>
		</form>
	</div>
	<?php
}
