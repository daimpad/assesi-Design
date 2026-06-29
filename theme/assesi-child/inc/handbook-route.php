<?php
/**
 * ASSESI — CI-Handbuch unter sauberer URL
 * Serviert theme/assesi-child/handbook.html unter /handbuch (beide Domains).
 * Die relativen Asset-Pfade (./assets/...) werden auf das Theme-URI umgeschrieben,
 * damit das Handbuch unter jeder URL korrekt lädt (Anker-Links bleiben unberührt).
 *
 * Slug überschreibbar via Konstante ASSESI_HANDBOOK_SLUG (z. B. in wp-config.php).
 * Für „auf Root sichtbar": die Handbuch-Seite in WordPress als Startseite setzen
 * (Einstellungen → Lesen) oder einen anderen Slug wählen.
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! defined( 'ASSESI_HANDBOOK_SLUG' ) ) { define( 'ASSESI_HANDBOOK_SLUG', 'handbuch' ); }

/* Rewrite-Regel registrieren und einmalig flushen (self-healing, ohne Re-Aktivierung). */
add_action( 'init', function () {
	add_rewrite_rule( '^' . ASSESI_HANDBOOK_SLUG . '/?$', 'index.php?assesi_handbook=1', 'top' );
	if ( get_option( 'assesi_handbook_rewrites' ) !== ASSESI_HANDBOOK_SLUG ) {
		flush_rewrite_rules( false );
		update_option( 'assesi_handbook_rewrites', ASSESI_HANDBOOK_SLUG );
	}
}, 11 );

add_filter( 'query_vars', function ( $vars ) {
	$vars[] = 'assesi_handbook';
	return $vars;
} );

add_action( 'template_redirect', function () {
	if ( ! intval( get_query_var( 'assesi_handbook' ) ) ) { return; }

	$file = get_stylesheet_directory() . '/handbook.html';
	if ( ! is_readable( $file ) ) {
		status_header( 404 );
		echo 'Handbuch nicht gefunden.';
		exit;
	}

	$html = file_get_contents( $file );
	$uri  = trailingslashit( get_stylesheet_directory_uri() );
	// Relative Asset-Pfade -> absolute Theme-URLs (Anker-Links #… bleiben unberührt)
	$html = str_replace(
		array( 'href="./assets/', 'src="./assets/' ),
		array( 'href="' . $uri . 'assets/', 'src="' . $uri . 'assets/' ),
		$html
	);

	if ( ! headers_sent() ) {
		status_header( 200 );
		header( 'Content-Type: text/html; charset=utf-8' );
		header( 'X-Robots-Tag: noindex' ); // internes Abnahme-Dokument
	}
	echo $html;
	exit;
} );

/* Bei Theme-Wechsel Rewrites neu schreiben. */
add_action( 'after_switch_theme', function () {
	delete_option( 'assesi_handbook_rewrites' );
	add_rewrite_rule( '^' . ASSESI_HANDBOOK_SLUG . '/?$', 'index.php?assesi_handbook=1', 'top' );
	flush_rewrite_rules();
} );
