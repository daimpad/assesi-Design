<?php
/**
 * ASSESI — Elementor-Integration
 * Spiegelt die CI in Elementors Globale Einstellungen, damit natives Bauen im
 * Editor automatisch markenkonform ist (keine manuelle UI-Pflege):
 *   1) Global Colors + Global Fonts einmalig aus der CI in den aktiven Kit setzen.
 *   2) Schriften als self-hosted registrieren und Elementors Google-Fonts-Ausgabe
 *      abschalten — sonst lädt Elementor Hanken/Inter doch wieder von Google.
 *
 * Single Source of Truth bleibt tokens.css; dies spiegelt nur dieselben Werte in
 * den Elementor-Kit, damit der Editor dieselben Swatches/Schriften anbietet.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Bei CI-Änderung erhöhen — dann wird der Kit erneut aus den CI-Werten gesetzt. */
if ( ! defined( 'ASSESI_KIT_VERSION' ) ) { define( 'ASSESI_KIT_VERSION', '1' ); }

/**
 * CI-Kernfarben aus tokens.css lesen (Single Source of Truth).
 * Fällt auf die verbindlichen Werte zurück, falls die Datei fehlt/unlesbar ist.
 */
function assesi_ci_colors() {
	$defaults = array(
		'gold' => '#F5C503', 'navy' => '#020044', 'blue' => '#3055A0',
		'lime' => '#B4CD00', 'white' => '#FFFFFF',
	);
	$file = get_stylesheet_directory() . '/assets/css/tokens.css';
	if ( ! is_readable( $file ) ) { return $defaults; }

	$css = file_get_contents( $file );
	$map = array(
		'gold' => '--ci-gold', 'navy' => '--ci-navy', 'blue' => '--ci-blue',
		'lime' => '--ci-lime', 'white' => '--ci-white',
	);
	$out = $defaults;
	foreach ( $map as $key => $var ) {
		if ( preg_match( '/' . preg_quote( $var, '/' ) . '\s*:\s*(#[0-9A-Fa-f]{3,8})/', $css, $m ) ) {
			$out[ $key ] = strtoupper( $m[1] );
		}
	}
	return $out;
}

/* ------------------------------------------------------------
 * 1) Schriften als self-hosted bei Elementor registrieren
 *    -> erscheinen im Font-Picker unter eigener Gruppe; Elementor versucht NICHT,
 *    sie von Google zu laden (sie kommen aus assets/fonts/fonts.css).
 * ---------------------------------------------------------- */
add_filter( 'elementor/fonts/groups', function ( $groups ) {
	$groups['assesi'] = 'ASSESI (self-hosted)';
	return $groups;
} );

add_filter( 'elementor/fonts/additional_fonts', function ( $fonts ) {
	$fonts['Hanken Grotesk'] = 'assesi';
	$fonts['Inter']          = 'assesi';
	return $fonts;
} );

/* Belt-and-suspenders: Elementor soll grundsätzlich keine Google-Fonts-Links
 * ausgeben (wir hosten selbst — DSGVO). */
add_filter( 'elementor/frontend/print_google_fonts', '__return_false' );

/* ------------------------------------------------------------
 * 2) Global Colors + Global Fonts einmalig in den aktiven Kit schreiben
 *    Idempotent (Option-Guard), defensiv (kann den Admin nie lahmlegen),
 *    nur im Admin — überschreibt bewusst die Editor-Swatches mit den CI-Werten.
 * ---------------------------------------------------------- */
add_action( 'admin_init', function () {

	if ( get_option( 'assesi_kit_synced' ) === ASSESI_KIT_VERSION ) { return; }
	if ( ! class_exists( '\Elementor\Plugin' ) ) { return; }

	try {
		$kits = \Elementor\Plugin::$instance->kits_manager;
		if ( ! $kits || ! method_exists( $kits, 'get_active_id' ) ) { return; }

		$kit_id = $kits->get_active_id();
		if ( ! $kit_id ) { return; }

		$settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
		if ( ! is_array( $settings ) ) { $settings = array(); }

		$c = assesi_ci_colors(); // aus tokens.css

		// Die vier System-Farbrollen des Editors
		$settings['system_colors'] = array(
			array( '_id' => 'primary',   'title' => 'Navy',      'color' => $c['navy'] ),
			array( '_id' => 'secondary', 'title' => 'Blau',      'color' => $c['blue'] ),
			array( '_id' => 'text',      'title' => 'Navy Text', 'color' => $c['navy'] ),
			array( '_id' => 'accent',    'title' => 'Gold',      'color' => $c['gold'] ),
		);

		// Zusätzliche Marken-Swatches (alle vier CI-Farben benannt verfügbar)
		$settings['custom_colors'] = array(
			array( '_id' => 'assesigold', 'title' => 'CI Gold', 'color' => $c['gold'] ),
			array( '_id' => 'assesinavy', 'title' => 'CI Navy', 'color' => $c['navy'] ),
			array( '_id' => 'assesiblue', 'title' => 'CI Blau', 'color' => $c['blue'] ),
			array( '_id' => 'assesilime', 'title' => 'CI Lime', 'color' => $c['lime'] ),
		);

		// System-Schriften: Display = Hanken Grotesk, Fließtext = Inter
		$hanken = array( 'typography_typography' => 'custom', 'typography_font_family' => 'Hanken Grotesk' );
		$inter  = array( 'typography_typography' => 'custom', 'typography_font_family' => 'Inter' );
		$settings['system_typography'] = array(
			array_merge( array( '_id' => 'primary',   'title' => 'Display (Hanken)',  'typography_font_weight' => '800' ), $hanken ),
			array_merge( array( '_id' => 'secondary', 'title' => 'Display Medium',     'typography_font_weight' => '700' ), $hanken ),
			array_merge( array( '_id' => 'text',      'title' => 'Fließtext (Inter)',  'typography_font_weight' => '400' ), $inter ),
			array_merge( array( '_id' => 'accent',    'title' => 'Akzent (Inter)',     'typography_font_weight' => '600' ), $inter ),
		);

		update_post_meta( $kit_id, '_elementor_page_settings', $settings );

		// Generiertes CSS neu aufbauen, damit die Globals sofort greifen
		if ( isset( \Elementor\Plugin::$instance->files_manager )
			&& method_exists( \Elementor\Plugin::$instance->files_manager, 'clear_cache' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
		}

		update_option( 'assesi_kit_synced', ASSESI_KIT_VERSION );

	} catch ( \Throwable $e ) {
		// Niemals den Admin blockieren — beim nächsten Laden erneut versuchen.
		return;
	}
} );
