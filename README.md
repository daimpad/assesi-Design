# ASSESI — Web-Design-System

Design-System und WordPress-Child-Theme für die beiden Domains des
ASSESI-Nachhaltigkeitsstandards:

- **assesi.eu** — Verbandsseite (B2B), institutionell. Theme-Klasse `theme-assesi`.
- **assesi-label.eu** — Siegelseite (B2B+B2C), wärmer. Theme-Klasse `theme-label`.

Ein gemeinsamer Token-Kern stiftet die Verwandtschaft; zwei Charakter-Themes
schalten Akzent, Radius, Tiefe und Navigation um. Farbquelle ist
`Farben_Fonts.docx` (Gold `#F5C503`, Navy `#020044`, Blau `#3055A0`,
Lime `#B4CD00`). Schrift: Hanken Grotesk (Display) + Inter (Fließtext).

## Struktur

```
Assesi/
├─ theme/assesi-child/        WordPress-Child-Theme (Hello Elementor)
│  ├─ assets/css/
│  │  ├─ tokens.css           Design-Tokens — Quelle (Repo)
│  │  └─ components.css       Komponenten, token-getrieben
│  ├─ assets/js/ui.js         Accordion + Mobile-Menü
│  ├─ inc/schema.php          Schema.org JSON-LD (domain-abhängig)
│  ├─ elementor/*.json        Importierbare Elementor-/Theme-Builder-Vorlagen
│  ├─ functions.php           Enqueue + domain-abhängige Body-Klasse
│  └─ style.css               Theme-Header (Version)
└─ design/                    Referenzen & Snippets (nicht deploybar)
   ├─ styleguide.html         Lebende Komponenten-Referenz (beide Themes)
   ├─ assesi-eu-onepager.html      Validierte HTML-Referenz assesi.eu
   ├─ assesi-label-onepager.html   Validierte HTML-Referenz assesi-label.eu
   └─ tokens-for-elementor.css     Tokens zum Einfügen in Customizer/Elementor
```

## Wie es zusammenspielt

- **Tokens** sind die einzige Quelle für Farben/Schrift/Abstände/Radien. Im Betrieb
  liegen sie in **WordPress → Customizer → Zusätzliches CSS** (UI-editierbar);
  `theme/assesi-child/assets/css/tokens.css` ist die versionierte Kopie.
- **components.css** wird vom Child-Theme geladen und stylt sowohl die HTML-Bausteine
  als auch native Elementor-Widgets (Icon-Box, Akkordeon) markenkonform.
- **functions.php** setzt die Theme-Klasse domain-abhängig (`assesi-label.eu` →
  `theme-label`, sonst `theme-assesi`) und bindet `inc/schema.php` ein.
- **elementor/*.json** sind import-fähige Vorlagen je Domain: Startseite (HTML-Route +
  native Route), Header und Footer (Theme-Builder-Teile). Details: `elementor/README.md`.

## Stand

assesi.eu und assesi-label.eu sind nativ aufgebaut (Header + Startseite + Footer als
Elementor-Vorlagen, je HTML- und native Route). Details und Versionshinweise:
`theme/assesi-child/README.md`.
