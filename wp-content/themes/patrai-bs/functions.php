<?php
/**
 * PATRAI BS theme bootstrap.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'PATRAI_BS_VERSION', '1.3.2' );
define( 'PATRAI_BS_DIR', get_template_directory() );
define( 'PATRAI_BS_URI', get_template_directory_uri() );

require_once PATRAI_BS_DIR . '/inc/setup.php';
require_once PATRAI_BS_DIR . '/inc/content-types.php';
require_once PATRAI_BS_DIR . '/inc/navigation.php';
require_once PATRAI_BS_DIR . '/inc/theme-options.php';
require_once PATRAI_BS_DIR . '/inc/product-importer.php';
require_once PATRAI_BS_DIR . '/inc/seed.php';
