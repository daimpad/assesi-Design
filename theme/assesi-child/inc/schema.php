<?php
/**
 * ASSESI — Schema.org (JSON-LD)
 * Domain-abhängig: assesi.eu (Verband) / assesi-label.eu (Siegel).
 *
 * Global:        Organization + WebSite (auf jeder Seite).
 * Unterseiten:   BreadcrumbList (aus der WP-Seitenhierarchie).
 * Optional:      assesi_faq_jsonld() — aus Templates aufrufbar.
 *
 * Echte Werte unten im $profiles-Array pflegen (TODO-Platzhalter ersetzen).
 * Ziel: Suchmaschinen- und KI-Lesbarkeit (Perplexity, SearchGPT).
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------
 * Domain-Profil
 * ---------------------------------------------------------- */
function assesi_schema_profile() {
	$host     = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( $_SERVER['HTTP_HOST'] ) : '';
	$is_label = ( false !== strpos( $host, 'assesi-label' ) );

	$profiles = array(
		'assesi' => array(
			'name'          => 'ASSESI — Association of European Sustainable Insurers',
			'alternateName' => 'ASSESI',
			'url'           => 'https://assesi.eu/',
			'description'   => 'Normbasierter Verband für nachhaltige Versicherer in Europa (DIN 77237).',
			'logo'          => 'https://assesi.eu/wp-content/uploads/assesi-logo.png', // TODO: echtes Logo
			'sameAs'        => array(), // TODO: Social-/Verzeichnis-Profile, z. B. LinkedIn
		),
		'label'  => array(
			'name'          => 'ASSESI Label — Siegel für nachhaltige Versicherungsprodukte',
			'alternateName' => 'ASSESI Label',
			'url'           => 'https://assesi-label.eu/',
			'description'   => 'Zertifizierungssiegel für nachhaltige Versicherungsprodukte, EmpCo-konform.',
			'logo'          => 'https://assesi-label.eu/wp-content/uploads/assesi-label-logo.png', // TODO
			'sameAs'        => array(),
		),
	);

	return $is_label ? $profiles['label'] : $profiles['assesi'];
}

/* ------------------------------------------------------------
 * Ausgabe-Helfer
 * ---------------------------------------------------------- */
function assesi_jsonld_echo( $data ) {
	$data = array_filter( $data, function ( $v ) {
		return ! ( is_array( $v ) && empty( $v ) ) && '' !== $v && null !== $v;
	} );
	echo "\n" . '<script type="application/ld+json">'
		. wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		. '</script>' . "\n";
}

/* ------------------------------------------------------------
 * Organization + WebSite + (Unterseiten) BreadcrumbList
 * ---------------------------------------------------------- */
add_action( 'wp_head', function () {

	$p       = assesi_schema_profile();
	$org_id  = $p['url'] . '#organization';
	$site_id = $p['url'] . '#website';

	// Organization
	assesi_jsonld_echo( array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Organization',
		'@id'           => $org_id,
		'name'          => $p['name'],
		'alternateName' => $p['alternateName'],
		'url'           => $p['url'],
		'logo'          => $p['logo'],
		'description'   => $p['description'],
		'areaServed'    => 'Europe',
		'sameAs'        => $p['sameAs'],
	) );

	// WebSite (publisher referenziert die Organization per @id)
	assesi_jsonld_echo( array(
		'@context'   => 'https://schema.org',
		'@type'      => 'WebSite',
		'@id'        => $site_id,
		'url'        => $p['url'],
		'name'       => $p['alternateName'],
		'inLanguage' => array( 'de', 'en' ),
		'publisher'  => array( '@id' => $org_id ),
	) );

	// BreadcrumbList — nur auf Unterseiten
	if ( ! is_front_page() && ( is_page() || is_single() ) ) {
		$items = array();
		$pos   = 1;

		$items[] = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => 'Start', 'item' => home_url( '/' ) );

		if ( is_page() ) {
			$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
			foreach ( $ancestors as $aid ) {
				$items[] = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title( $aid ), 'item' => get_permalink( $aid ) );
			}
		}

		$items[] = array( '@type' => 'ListItem', 'position' => $pos++, 'name' => get_the_title(), 'item' => get_permalink() );

		assesi_jsonld_echo( array(
			'@context'        => 'https://schema.org',
			'@type'           => 'BreadcrumbList',
			'itemListElement' => $items,
		) );
	}
}, 5 );

/* ------------------------------------------------------------
 * Optionaler Helfer: FAQPage
 * Aus einem Template/Shortcode aufrufen, sobald FAQ-Inhalte vorliegen:
 *
 *   assesi_faq_jsonld( array(
 *       array( 'q' => 'Was ist ASSESI?', 'a' => 'Ein normbasierter Verband …' ),
 *       array( 'q' => 'Was kostet die Mitgliedschaft?', 'a' => 'Der Beitrag …' ),
 *   ) );
 *
 * Muster für weitere Typen (DefinedTerm, HowTo, Event, Product) folgen
 * demselben Schema und werden mit den jeweiligen Seiten ergänzt.
 * ---------------------------------------------------------- */
function assesi_faq_jsonld( array $qa ) {
	if ( empty( $qa ) ) { return; }

	$entities = array();
	foreach ( $qa as $pair ) {
		if ( empty( $pair['q'] ) || empty( $pair['a'] ) ) { continue; }
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( $pair['q'] ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $pair['a'] ),
			),
		);
	}
	if ( empty( $entities ) ) { return; }

	assesi_jsonld_echo( array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	) );
}
