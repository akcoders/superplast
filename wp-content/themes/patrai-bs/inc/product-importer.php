<?php
/**
 * Converts the archived Super Plast product catalog into editable product records.
 *
 * The source snapshot is bundled with the theme so a fresh installation receives
 * the same complete catalog without depending on the old website at runtime.
 *
 * @package Patrai_BS
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function patrai_bs_catalog_map() {
	return array(
		'cooling'             => array( 'slug' => 'cooling-tower-components', 'category' => 'Cooling Tower Components', 'image' => 'img/background/cooling_tower.jpg' ),
		'film-fills'          => array( 'slug' => 'film-fills', 'category' => 'Cooling Tower Components', 'image' => 'img/products/CoolingTowerComponents/cooling-tower-page/All-Film-Fills.jpg' ),
		'splash-fills'        => array( 'slug' => 'splash-fills', 'category' => 'Cooling Tower Components', 'image' => 'img/products/CoolingTowerComponents/cooling-tower-page/Splash-fills.jpg' ),
		'drift-eliminators'   => array( 'slug' => 'drift-eliminators', 'category' => 'Cooling Tower Components', 'image' => 'img/products/CoolingTowerComponents/cooling-tower-page/All-drift-eliminators.jpg' ),
		'nozzles'             => array( 'slug' => 'nozzles', 'category' => 'Cooling Tower Components', 'image' => 'img/products/CoolingTowerComponents/cooling-tower-page/Nozzles.jpg' ),
		'other-accessories'   => array( 'slug' => 'other-accessories', 'category' => 'Cooling Tower Components', 'image' => 'img/products/CoolingTowerComponents/cooling-tower-page/Cooling-Tower-Accessories.jpg' ),
		'water-technology'    => array( 'slug' => 'water-technology-industry', 'category' => 'Water Treatment Technology', 'image' => 'img/background/Water_Wastewater_Technology.jpg' ),
		'biological-media'    => array( 'slug' => 'biological-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/BiologicalMedia/biomedia.jpg' ),
		'floating-media'      => array( 'slug' => 'floating-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/BiologicalMedia/productRange/floating-media.jpg' ),
		'settling-media'      => array( 'slug' => 'settling-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/SettlingMedia/Settling-Tank01.jpg' ),
		'trickling-filter'    => array( 'slug' => 'trickling-filter-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/trickling-filter.jpg' ),
		'saff'                => array( 'slug' => 'saff-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/saff.jpg' ),
		'anaerobic-digestors' => array( 'slug' => 'anaerobic-digester-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/Anaerobic-Digester.jpg' ),
		'fab-reactor'         => array( 'slug' => 'fab-reactor-media', 'category' => 'Water Treatment Technology', 'image' => 'img/products/WaterTechnology/fab-reactor.jpg' ),
		'building'            => array( 'slug' => 'building-products', 'category' => 'Building Products', 'image' => 'img/background/buildingTechnology.jpg' ),
		'louvers-fins'        => array( 'slug' => 'louvers-fins', 'category' => 'Building Products', 'image' => 'img/products/building/louvers.jpg' ),
		'frp-gratings'        => array( 'slug' => 'frp-gratings', 'category' => 'Building Products', 'image' => 'img/products/building/frp-grating-vertical.jpg' ),
		'pvc-profiles'        => array( 'slug' => 'pvc-profiles', 'category' => 'PVC Sleeving & Profiles', 'image' => 'img/home/pvc_sleeving_and_soft_rigid.jpg' ),
	);
}

function patrai_bs_catalog_document( $html ) {
	$document = new DOMDocument( '1.0', 'UTF-8' );
	$previous = libxml_use_internal_errors( true );
	$document->loadHTML( '<?xml encoding="utf-8" ?>' . $html, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
	libxml_clear_errors();
	libxml_use_internal_errors( $previous );
	return $document;
}

function patrai_bs_catalog_text( $node ) {
	return trim( preg_replace( '/\s+/u', ' ', html_entity_decode( $node->textContent, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) ) );
}

function patrai_bs_catalog_has_ancestor( $node, $tag_name ) {
	$parent = $node->parentNode;
	while ( $parent && XML_ELEMENT_NODE === $parent->nodeType ) {
		if ( strtolower( $parent->nodeName ) === strtolower( $tag_name ) ) {
			return true;
		}
		$parent = $parent->parentNode;
	}
	return false;
}

function patrai_bs_catalog_has_class_ancestor( $node, $class_name ) {
	$parent = $node->parentNode;
	while ( $parent && XML_ELEMENT_NODE === $parent->nodeType ) {
		$classes = ' ' . preg_replace( '/\s+/', ' ', (string) $parent->getAttribute( 'class' ) ) . ' ';
		if ( false !== strpos( $classes, ' ' . $class_name . ' ' ) ) {
			return true;
		}
		$parent = $parent->parentNode;
	}
	return false;
}

function patrai_bs_catalog_remove_noise( DOMDocument $document, $remove_technical = true ) {
	$xpath = new DOMXPath( $document );
	$classes = array(
		'project-area-3', 'suscribe-area', 'single-awesome-project', 'awesome-project',
		'add-actions', 'project-action-btn', 'page-img', 'single-well-left',
		'full-width-video-wrap', 'embed-responsive', 'item-indicator',
	);
	if ( $remove_technical ) {
		$classes[] = 'sp-tech-section';
		$classes[] = 'modal';
	}
	$queries = array( '//script', '//style', '//*[contains(translate(@style," ",""),"display:none")]' );
	foreach ( $classes as $class ) {
		$queries[] = '//*[contains(concat(" ",normalize-space(@class)," ")," ' . $class . ' ")]';
	}
	foreach ( $queries as $query ) {
		$nodes = $xpath->query( $query );
		$remove = array();
		foreach ( $nodes as $node ) {
			$remove[] = $node;
		}
		foreach ( array_reverse( $remove ) as $node ) {
			if ( $node->parentNode ) {
				$node->parentNode->removeChild( $node );
			}
		}
	}
}

function patrai_bs_catalog_table_html( DOMElement $table ) {
	$rows = $table->getElementsByTagName( 'tr' );
	if ( ! $rows->length ) {
		return '';
	}
	$html = '<div class="table-responsive"><table class="table product-spec-table"><tbody>';
	foreach ( $rows as $row ) {
		$cells = array();
		foreach ( $row->childNodes as $cell ) {
			if ( XML_ELEMENT_NODE === $cell->nodeType && in_array( strtolower( $cell->nodeName ), array( 'td', 'th' ), true ) ) {
				$cells[] = $cell;
			}
		}
		if ( ! $cells ) {
			continue;
		}
		$html .= '<tr>';
		foreach ( $cells as $cell ) {
			$tag   = 'th' === strtolower( $cell->nodeName ) ? 'th' : 'td';
			$scope = 'th' === $tag ? ' scope="col"' : '';
			$html .= '<' . $tag . $scope . '>' . esc_html( patrai_bs_catalog_text( $cell ) ) . '</' . $tag . '>';
		}
		$html .= '</tr>';
	}
	$html .= '</tbody></table></div>';
	return $html;
}

function patrai_bs_catalog_semantic_html( DOMDocument $document, $product_title = '', $include_tables = false, $root = null ) {
	$xpath = new DOMXPath( $document );
	$query = './/h2|.//h3|.//h4|.//h5|.//p|.//ul|.//ol';
	if ( $include_tables ) {
		$query .= '|.//table';
	}
	$nodes = $root ? $xpath->query( $query, $root ) : $xpath->query( '//' . substr( $query, 3 ) );
	$seen  = array();
	$html  = '';
	$skip_titles = array( 'our product range', 'our-latest products', 'our latest products', 'welcome to our super plast company', 'our products' );
	foreach ( $nodes as $node ) {
		$name = strtolower( $node->nodeName );
		if ( in_array( $name, array( 'p', 'ul', 'ol' ), true ) && ( patrai_bs_catalog_has_ancestor( $node, 'li' ) || patrai_bs_catalog_has_ancestor( $node, 'table' ) ) ) {
			continue;
		}
		if ( 'table' === $name && patrai_bs_catalog_has_ancestor( $node, 'table' ) ) {
			continue;
		}
		$text = patrai_bs_catalog_text( $node );
		$key  = strtolower( $text );
		if ( ! $text || isset( $seen[ $key ] ) ) {
			continue;
		}
		if ( in_array( $name, array( 'h2', 'h3', 'h4', 'h5' ), true ) ) {
			if ( in_array( $key, $skip_titles, true ) || strtolower( $product_title ) === $key ) {
				continue;
			}
			$seen[ $key ] = true;
			$html .= in_array( $name, array( 'h2', 'h3' ), true ) ? '<h2>' . esc_html( $text ) . '</h2>' : '<h3>' . esc_html( $text ) . '</h3>';
			continue;
		}
		if ( 'p' === $name ) {
			if ( strlen( $text ) < 18 || preg_match( '/^(read more|get your quote|components|products)$/i', $text ) ) {
				continue;
			}
			$seen[ $key ] = true;
			$html .= '<p>' . esc_html( $text ) . '</p>';
			continue;
		}
		if ( in_array( $name, array( 'ul', 'ol' ), true ) ) {
			$items = array();
			foreach ( $node->getElementsByTagName( 'li' ) as $item ) {
				$item_text = patrai_bs_catalog_text( $item );
				if ( strlen( $item_text ) > 3 ) {
					$items[ strtolower( $item_text ) ] = $item_text;
				}
			}
			if ( $items ) {
				$html .= '<ul class="patrai-check-list">';
				foreach ( $items as $item_text ) {
					$html .= '<li>' . esc_html( $item_text ) . '</li>';
				}
				$html .= '</ul>';
			}
			continue;
		}
		if ( 'table' === $name && $node instanceof DOMElement ) {
			$html .= patrai_bs_catalog_table_html( $node );
		}
	}
	return $html;
}

function patrai_bs_catalog_overview( $html, $title ) {
	$document = patrai_bs_catalog_document( $html );
	patrai_bs_catalog_remove_noise( $document, true );
	$output = patrai_bs_catalog_semantic_html( $document, $title, false );
	return $output ?: '<p>' . esc_html__( 'Contact our team for product and application details.', 'patrai-bs' ) . '</p>';
}

function patrai_bs_catalog_technical( $html ) {
	$document = patrai_bs_catalog_document( $html );
	$xpath    = new DOMXPath( $document );
	$models   = $xpath->query( '//*[contains(concat(" ",normalize-space(@class)," ")," sp-tech-section ")]' );
	$output   = '';
	$count    = 0;
	foreach ( $models as $model ) {
		$title_node = $xpath->query( './/*[contains(concat(" ",normalize-space(@class)," ")," modal-title ")]', $model )->item( 0 );
		if ( ! $title_node ) {
			$title_node = $xpath->query( './/h3|.//h4', $model )->item( 0 );
		}
		$title = $title_node ? patrai_bs_catalog_text( $title_node ) : sprintf( __( 'Technical Model %d', 'patrai-bs' ), $count + 1 );
		$body  = $xpath->query( './/*[contains(concat(" ",normalize-space(@class)," ")," modal-body ")]', $model )->item( 0 );
		if ( ! $body ) {
			$body = $model;
		}
		$body_html = patrai_bs_catalog_semantic_html( $document, $title, true, $body );
		if ( ! trim( $body_html ) ) {
			continue;
		}
		$count++;
		$output .= '<details class="tech-model"' . ( 1 === $count ? ' open' : '' ) . '><summary><span>' . esc_html__( 'Product model', 'patrai-bs' ) . '</span><strong>' . esc_html( $title ) . '</strong></summary><div class="tech-model-body">' . $body_html . '</div></details>';
	}

	// Preserve any product tables that were not placed inside legacy modal sections.
	foreach ( $xpath->query( '//table' ) as $table ) {
		if ( patrai_bs_catalog_has_class_ancestor( $table, 'sp-tech-section' ) ) {
			continue;
		}
		$count++;
		$output .= '<details class="tech-model"' . ( 1 === $count ? ' open' : '' ) . '><summary><span>' . esc_html__( 'Technical specifications', 'patrai-bs' ) . '</span><strong>' . sprintf( esc_html__( 'Specification set %d', 'patrai-bs' ), $count ) . '</strong></summary><div class="tech-model-body">' . patrai_bs_catalog_table_html( $table ) . '</div></details>';
	}
	return array( 'html' => $output, 'count' => $count );
}

function patrai_bs_catalog_line_items( $html, $mode = 'features' ) {
	$document = patrai_bs_catalog_document( $html );
	$xpath    = new DOMXPath( $document );
	$items    = array();
	if ( 'applications' === $mode ) {
		foreach ( $xpath->query( '//h2|//h3|//h4|//h5' ) as $heading ) {
			if ( false === stripos( patrai_bs_catalog_text( $heading ), 'application' ) ) {
				continue;
			}
			$container = $heading->parentNode;
			for ( $level = 0; $level < 2 && $container && $container->parentNode; $level++ ) {
				$container = $container->parentNode;
			}
			foreach ( $xpath->query( './/li', $container ) as $item ) {
				$text = patrai_bs_catalog_text( $item );
				if ( strlen( $text ) > 4 && strlen( $text ) < 180 ) {
					$items[ strtolower( $text ) ] = $text;
				}
			}
		}
	} else {
		foreach ( $xpath->query( '//li' ) as $item ) {
			$text = patrai_bs_catalog_text( $item );
			if ( strlen( $text ) > 10 && strlen( $text ) < 220 && ! preg_match( '/^(read more|view|click)/i', $text ) ) {
				$items[ strtolower( $text ) ] = $text;
			}
		}
		if ( count( $items ) < 6 ) {
			foreach ( $xpath->query( '//h3|//h4|//h5' ) as $heading ) {
				$text = patrai_bs_catalog_text( $heading );
				if ( strlen( $text ) > 12 && strlen( $text ) < 110 && ! preg_match( '/(product range|our.latest|welcome|technical specification|our products)/i', $text ) ) {
					$items[ strtolower( $text ) ] = $text;
				}
			}
		}
	}
	return array_slice( array_values( $items ), 0, 'applications' === $mode ? 10 : 12 );
}

function patrai_bs_catalog_application_defaults( $source_key ) {
	$defaults = array(
		'cooling'             => array( 'Power-sector cooling towers', 'Industrial cooling towers', 'HVAC cooling towers', 'Crossflow configurations', 'Counterflow configurations' ),
		'film-fills'          => array( 'Crossflow cooling towers', 'Counterflow cooling towers', 'HVAC installations', 'Power plants', 'Process industries', 'Higher-suspended-solids water applications' ),
		'splash-fills'        => array( 'Cooling towers with challenging water quality', 'Applications with suspended solids', 'Industrial cooling systems' ),
		'drift-eliminators'   => array( 'Crossflow cooling towers', 'Counterflow cooling towers', 'Industrial and HVAC tower systems' ),
		'nozzles'             => array( 'Cooling tower water distribution', 'Industrial spray distribution applications' ),
		'other-accessories'   => array( 'Cooling tower fill installation', 'V-bar support assemblies', 'Nylon cable support arrangements' ),
		'water-technology'    => array( 'Municipal sewage treatment plants', 'Industrial sewage treatment plants', 'Effluent treatment plants', 'Drinking water treatment plants', 'Pretreatment, primary and secondary treatment' ),
		'biological-media'    => array( 'Trickling filters and biotowers', 'Submerged aeration fixed film systems', 'Anaerobic hybrid digesters', 'FAB and moving-bed biofilm reactors' ),
		'floating-media'      => array( 'Fluidized aerobic bioreactors', 'Moving-bed biofilm reactors', 'Wastewater biological treatment' ),
		'settling-media'      => array( 'Primary clarification', 'Secondary clarification', 'Wastewater treatment plants', 'Drinking water treatment plants' ),
		'trickling-filter'    => array( 'Plastic-media trickling filters', 'Biotowers', 'Municipal and industrial wastewater treatment' ),
		'saff'                => array( 'Submerged activated fixed-film reactors', 'Space-constrained sewage treatment plants', 'Upgrading conventional activated-sludge systems' ),
		'anaerobic-digestors' => array( 'Anaerobic hybrid reactors', 'High-strength wastewater treatment', 'Biogas-producing digestion processes' ),
		'fab-reactor'         => array( 'Fluidized aerobic bioreactors', 'Moving-bed biofilm reactors', 'Compact biological wastewater treatment' ),
		'building'            => array( 'Doors and windows', 'Fascia and cladding', 'Flooring and pipes', 'Low-maintenance construction applications' ),
		'louvers-fins'        => array( 'Ventilation and screening', 'Building facades', 'Industrial and architectural profile applications' ),
		'frp-gratings'        => array( 'Walkways and bridges', 'Pipe and equipment racks', 'Tank loading platforms', 'Maintenance platforms', 'Cooling tower sections', 'Swimming pools and drainage areas' ),
		'pvc-profiles'        => array( 'Electrical insulation and sleeving', 'Flexible protection profiles', 'Rigid custom-extruded sections', 'Industrial and building profile applications' ),
	);
	return isset( $defaults[ $source_key ] ) ? $defaults[ $source_key ] : array();
}

function patrai_bs_catalog_gallery_ids( $html, $featured_path = '' ) {
	$document = patrai_bs_catalog_document( $html );
	$xpath    = new DOMXPath( $document );
	$ids      = array();
	if ( $featured_path ) {
		$id = patrai_bs_asset_attachment_id( $featured_path );
		if ( $id ) {
			$ids[ $id ] = $id;
		}
	}
	foreach ( $xpath->query( '//img[@src]' ) as $image ) {
		$src = $image->getAttribute( 'src' );
		if ( false !== strpos( $src, '{{assets}}/' ) ) {
			$path = substr( $src, strpos( $src, '{{assets}}/' ) + 11 );
		} elseif ( preg_match( '#/superplast-company/(.+)$#', $src, $matches ) ) {
			$path = $matches[1];
		} else {
			continue;
		}
		if ( preg_match( '#(loader|logo|venubox|lib/img|service/work4|recources/)#i', $path ) ) {
			continue;
		}
		$id = patrai_bs_asset_attachment_id( $path );
		if ( $id ) {
			$ids[ $id ] = $id;
		}
	}
	return array_values( $ids );
}

function patrai_bs_catalog_excerpt_from_html( $html ) {
	$text = wp_strip_all_tags( $html );
	return wp_trim_words( $text, 34, '' );
}

function patrai_bs_import_legacy_product_catalog( $force = false ) {
	$version = '2.0.0';
	if ( ! $force && $version === get_option( 'patrai_bs_catalog_version' ) ) {
		return array( 'updated' => 0, 'skipped' => true );
	}
	if ( ! class_exists( 'DOMDocument' ) ) {
		return new WP_Error( 'catalog_dom_missing', __( 'The PHP DOM extension is required to prepare the starter catalog.', 'patrai-bs' ) );
	}
	$file = PATRAI_BS_DIR . '/data/legacy-catalog.json';
	if ( ! is_readable( $file ) ) {
		return new WP_Error( 'catalog_missing', __( 'Legacy product catalog data is missing.', 'patrai-bs' ) );
	}
	$data = json_decode( file_get_contents( $file ), true ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	if ( ! is_array( $data ) || empty( $data['pages'] ) ) {
		return new WP_Error( 'catalog_invalid', __( 'Legacy product catalog data is invalid.', 'patrai-bs' ) );
	}
	$updated = 0;
	foreach ( patrai_bs_catalog_map() as $source_key => $config ) {
		if ( empty( $data['pages'][ $source_key ] ) ) {
			continue;
		}
		$source   = $data['pages'][ $source_key ];
		$overview = patrai_bs_catalog_overview( $source['content'], $source['title'] );
		$technical = patrai_bs_catalog_technical( $source['content'] );
		$existing = get_page_by_path( $config['slug'], OBJECT, 'patrai_product' );
		$post_data = array(
			'post_type'    => 'patrai_product',
			'post_status'  => 'publish',
			'post_title'   => $source['title'],
			'post_name'    => $config['slug'],
			'post_content' => $overview,
		);
		if ( $existing ) {
			$post_data['ID'] = $existing->ID;
			if ( ! $force && get_post_meta( $existing->ID, '_patrai_catalog_imported', true ) ) {
				continue;
			}
			$id = wp_update_post( wp_slash( $post_data ) );
		} else {
			$post_data['post_excerpt'] = patrai_bs_catalog_excerpt_from_html( $overview );
			$id = wp_insert_post( wp_slash( $post_data ) );
		}
		if ( ! $id || is_wp_error( $id ) ) {
			continue;
		}
		$excerpt = get_post_field( 'post_excerpt', $id );
		if ( ! $excerpt ) {
			$excerpt = patrai_bs_catalog_excerpt_from_html( $overview );
			wp_update_post( array( 'ID' => $id, 'post_excerpt' => $excerpt ) );
		}
		$features = patrai_bs_catalog_line_items( $source['content'], 'features' );
		if ( $features ) {
			update_post_meta( $id, '_patrai_product_features', implode( "\n", $features ) );
		}
		$applications = patrai_bs_catalog_line_items( $source['content'], 'applications' );
		$applications = array_values( array_unique( array_merge( $applications, patrai_bs_catalog_application_defaults( $source_key ) ) ) );
		update_post_meta( $id, '_patrai_product_applications', implode( "\n", array_slice( $applications, 0, 12 ) ) );
		update_post_meta( $id, '_patrai_product_tagline', $excerpt );
		update_post_meta( $id, '_patrai_product_specs_html', $technical['html'] );
		update_post_meta( $id, '_patrai_product_model_count', absint( $technical['count'] ) );
		update_post_meta( $id, '_patrai_product_gallery_ids', patrai_bs_catalog_gallery_ids( $source['content'], $config['image'] ) );
		update_post_meta( $id, '_patrai_catalog_source_key', $source_key );
		update_post_meta( $id, '_patrai_catalog_imported', $version );
		$image_id = patrai_bs_asset_attachment_id( $config['image'] );
		if ( $image_id ) {
			set_post_thumbnail( $id, $image_id );
		}
		wp_set_object_terms( $id, $config['category'], 'patrai_product_category' );
		$updated++;
	}
	update_option( 'patrai_bs_catalog_version', $version, false );
	return array( 'updated' => $updated, 'skipped' => false );
}
