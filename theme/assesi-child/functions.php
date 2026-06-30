<?php
/**
 * ASSESI Child Theme — functions.php
 * Lädt Schriften, Design-Tokens, Komponenten und UI-Skript.
 * Setzt die Theme-Klasse domain-abhängig am <body>.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

/* ------------------------------------------------------------
 * Domain-Erkennung (eine Quelle für Theme-Klasse + Schema)
 * assesi-label.eu → true, sonst false.
 * ---------------------------------------------------------- */
if ( ! function_exists( 'assesi_is_label' ) ) {
	function assesi_is_label() {
		$host = isset( $_SERVER['HTTP_HOST'] ) ? strtolower( $_SERVER['HTTP_HOST'] ) : '';
		return ( false !== strpos( $host, 'assesi-label' ) );
	}
}

/* Module */
require_once get_stylesheet_directory() . '/inc/schema.php';
require_once get_stylesheet_directory() . '/inc/elementor-globals.php';
require_once get_stylesheet_directory() . '/inc/handbook-route.php';

/* ------------------------------------------------------------
 * Assets laden (Reihenfolge: Parent → Fonts → Tokens → Components → JS)
 * ---------------------------------------------------------- */
add_action( 'wp_enqueue_scripts', function () {

	$uri = get_stylesheet_directory_uri();
	$ver = wp_get_theme()->get( 'Version' );

	// Parent-Theme (Hello Elementor)
	wp_enqueue_style( 'hello-elementor', get_template_directory_uri() . '/style.css', array(), null );

	// Schriften: selbst gehostet (Noto Sans + Instrument Sans, OFL). Kein Google-CDN —
	// keine Übermittlung der Besucher-IP an Google (DSGVO). Variable Fonts unter
	// assets/fonts/, eingebunden über fonts.css.
	wp_enqueue_style( 'assesi-fonts', $uri . '/assets/fonts/fonts.css', array(), $ver );

	// Design-Tokens: versionierte Basis aus dem Repo. Greift sofort nach
	// Aktivierung, ohne manuellen Schritt. Die zusätzliche, UI-editierbare
	// Token-Quelle in WordPress → Customizer → Zusätzliches CSS wird in
	// wp_head später ausgegeben und überschreibt diese Basis bei gleichem
	// Selektor — Tokens bleiben also UI-editierbar.
	wp_enqueue_style( 'assesi-tokens', $uri . '/assets/css/tokens.css', array( 'hello-elementor' ), $ver );

	// Komponenten (setzen die Tokens voraus)
	wp_enqueue_style( 'assesi-components', $uri . '/assets/css/components.css', array( 'assesi-tokens' ), $ver );

	// Child-style.css (eigene Overrides, zuletzt)
	wp_enqueue_style( 'assesi-child', $uri . '/style.css', array( 'assesi-components' ), $ver );

	// UI: Accordion + Mobile-Menü
	wp_enqueue_script( 'assesi-ui', $uri . '/assets/js/ui.js', array(), $ver, true );

}, 20 );

/* ------------------------------------------------------------
 * Preload der primären (latin) Schriftdateien — schnellerer Erst-Render,
 * ersetzt den früheren Google-Preconnect (Schriften sind jetzt lokal).
 * ---------------------------------------------------------- */
add_action( 'wp_head', function () {
	$uri = get_stylesheet_directory_uri();
	foreach ( array( 'noto-sans-latin', 'instrument-sans-latin' ) as $f ) {
		echo '<link rel="preload" as="font" type="font/woff2" crossorigin href="'
			. esc_url( $uri . '/assets/fonts/' . $f . '.woff2' ) . '">' . "\n";
	}
}, 1 );

/* ------------------------------------------------------------
 * Theme-Klasse domain-abhängig setzen
 * assesi-label.eu → .theme-label, sonst → .theme-assesi
 * (Ein Codebestand, zwei Domains.)
 * ---------------------------------------------------------- */
add_filter( 'body_class', function ( $classes ) {
	$classes[] = assesi_is_label() ? 'theme-label' : 'theme-assesi';
	return $classes;
} );
