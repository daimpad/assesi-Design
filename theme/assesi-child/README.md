# ASSESI Child Theme

Child-Theme für **Hello Elementor**. Trägt das ASSESI-Design-System für beide
Domains aus **einem** Codebestand. Die Charakter-Unterscheidung läuft über eine
Theme-Klasse am `<body>`, die `functions.php` domain-abhängig setzt:

- `assesi.eu` → `.theme-assesi` (institutionell, Navy-Navigation, EU-Blau-Akzent)
- `assesi-label.eu` → `.theme-label` (wärmer, weiße Navigation, Lime-Akzent)

## Struktur

```
assesi-child/
├─ style.css              Theme-Header + Platz für Overrides
├─ functions.php          Enqueue (Fonts, Tokens, Components, JS) + Body-Klasse
├─ inc/
│  ├─ schema.php          Schema.org JSON-LD (Organization, WebSite, Breadcrumbs)
│  ├─ elementor-globals.php  Global Colors/Fonts der CI in den Elementor-Kit (Code)
│  └─ handbook-route.php  Serviert handbook.html unter /handbuch (beide Domains)
└─ assets/
   ├─ css/
   │  ├─ tokens.css       Design-Tokens — Single Source of Truth
   │  └─ components.css    Validierte Komponenten (token-getrieben)
   └─ js/
      └─ ui.js            Accordion (Timeline) + Mobile-Menü
```

## SEO / Schema.org (KI-Lesbarkeit)

`inc/schema.php` gibt domain-abhängig JSON-LD aus:

- **Organization** + **WebSite** auf jeder Seite (Profil je Domain im `$profiles`-Array).
- **BreadcrumbList** auf Unterseiten, aus der WP-Seitenhierarchie.
- **FAQPage**-Helfer `assesi_faq_jsonld()` für FAQ-Seiten (aus Template aufrufbar).

Vor dem Livegang die `TODO`-Platzhalter im `$profiles`-Array pflegen (Logo-URL,
ggf. `sameAs`-Profile). Weitere Typen (DefinedTerm, HowTo, Event, Product) folgen
demselben Muster mit den jeweiligen Seiten.

## Installation

1. Ordner als `assesi-child` nach `wp-content/themes/` legen (auf beiden Installationen).
2. **Hello Elementor** als Parent-Theme installieren.
3. Child-Theme aktivieren. Die Body-Klasse wird automatisch nach Domain gesetzt.

## Verhältnis zu Elementor

- **Tokens** sind die Quelle für Farben, Schrift, Abstände, Radien. `tokens.css` wird
  vom Child-Theme als versionierte Basis geladen und greift global, sobald das Theme
  aktiv ist — auch in Elementor-Widgets, sofern diese die CSS-Variablen nutzen oder die
  Komponenten-Klassen tragen. Die zusätzliche, UI-editierbare Token-Quelle in
  **Customizer → Zusätzliches CSS** wird später ausgegeben und überschreibt die Basis.
- **Global Colors & Global Fonts** werden automatisch gesetzt: `inc/elementor-globals.php`
  schreibt die CI-Farben (System: Navy/Blau/Navy/Gold; zusätzlich CI Gold/Navy/Blau/Lime)
  und die Schriften (Display = Hanken Grotesk, Fließtext = Inter) einmalig in den aktiven
  Elementor-Kit. So bietet der Editor von sich aus die Markenwerte an — kein manuelles
  Pflegen. Bei CI-Änderung `ASSESI_KIT_VERSION` erhöhen, dann wird neu gesetzt.
- Die Schriften sind als **self-hosted** registriert; Elementors Google-Fonts-Ausgabe ist
  abgeschaltet (`elementor/frontend/print_google_fonts`), damit keine Schriften doch von
  Google geladen werden.
- **Produktionsbasis ist die native Route** der Startseiten-Vorlagen; HTML-Route und
  One-Pager sind pixelgenaue Referenzen zur Abnahme.
- Komponenten lassen sich als HTML-Widget oder Elementor-Template mit den
  dokumentierten Klassen (`.hero`, `.v-card`, `.acc`, `.cta-band`, `.footer` …) einsetzen.

## Charakter-Tokens (der Kontrast)

| Aspekt        | `.theme-assesi`     | `.theme-label`        |
|---------------|---------------------|-----------------------|
| Akzent        | EU-Blau             | Lime                  |
| Navigation    | Navy / weiß         | weiß / Navy           |
| Radius        | 10 px (sachlich)    | 22 px / Pill (warm)   |
| Tiefe         | feine Linien        | weiche Schatten       |

## Hinweise

- Farben verbindlich aus `Farben_Fonts.docx`. Lime/Gold sind Flächen-/Akzentfarben,
  **nicht** Textfarbe auf Weiß (Kontrast < WCAG AA).
- Logo ist Platzhalter, bis die Wortbildmarke final ist; die Token-Struktur bleibt
  davon unberührt.
- Version 0.16.0 — CI-Handbuch unter sauberer URL `/handbuch` (beide Domains,
  `inc/handbook-route.php`). Driftfrei: Handbuch liest Token-Werte zur Laufzeit,
  `elementor-globals.php` parst die CI-Hex aus `tokens.css`. Konsistenz-Werkzeug
  `tools/tokens.py` (Check + Spiegel-Generator); README auf die Kern­zusammenhänge
  eingedampft.
- Version 0.15.0 — Elementor-Integration: Global Colors + Global Fonts werden per
  Code aus der CI in den Elementor-Kit gesetzt (`inc/elementor-globals.php`),
  Schriften als self-hosted registriert und Elementors Google-Fonts abgeschaltet.
  Native Route als Produktionsbasis dokumentiert.
- Version 0.14.0 — Schriften selbst gehostet (Hanken Grotesk + Inter unter
  `assets/fonts/`, kein Google-CDN → keine IP-Übermittlung; Datenschutz-Vorlagen
  entsprechend angepasst). CI-Handbuch `handbook.html` überarbeitet und über
  `elementor/assesi-handbook.json` in beide Präsenzen einbettbar.
- Version 0.13.0 — Pflichtseiten Impressum + Datenschutzerklärung als eigenständige
  Vorlagen je Domain (Gerüst mit `[TODO]`-Platzhaltern, nativ editierbar; inkl.
  Google-Fonts/DSGVO-Hinweis). Siehe `elementor/README.md`.
- Version 0.12.0 — assesi-label.eu nativ als Elementor-Vorlagen (Header, Footer,
  Startseite HTML-Route + native Route) im warmen Muster, parallel zu assesi.eu;
  siehe `elementor/README.md`.
- Version 0.11.0 — `tokens.css` wird als Basis enqueued; gemeinsamer
  `assesi_is_label()`-Helfer für Theme-Klasse und Schema; a11y-/Resize-Feinschliff
  in `ui.js`. Beide One-Pager validiert; enthält Label-Module (Siegel-Showcase,
  Zielgruppen-Split, horizontaler Stepper, warmes CTA-Band) und die AA-Korrektur
  der Eyebrow-/Titel-Akzente im Label-Theme.
```
