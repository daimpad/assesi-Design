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
│  └─ schema.php          Schema.org JSON-LD (Organization, WebSite, Breadcrumbs)
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
- **Global Colors** in Elementor zusätzlich auf Navy/Blau/Gold/Lime mappen, damit
  der Editor dieselben Werte anbietet.
- **Global Fonts**: Display = Hanken Grotesk, Body = Inter. Greift `functions.php`,
  kann die Basis-Bindung in `components.css` (Body/Headings) entfallen — dort
  entsprechend auskommentieren.
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
