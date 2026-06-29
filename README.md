# ASSESI — Web-Design-System

Design-System und WordPress-Child-Theme für zwei Domains:

- **assesi.eu** — Verbandsseite, institutionell (Theme-Klasse `theme-assesi`)
- **assesi-label.eu** — Siegelseite, wärmer (Theme-Klasse `theme-label`)

Ein gemeinsamer Token-Kern, zwei Charaktere. Welche Klasse gilt, setzt `functions.php`
domain-abhängig am `<body>`.

## CI auf einen Blick

| | Wert |
|---|---|
| Gold | `#F5C503` |
| Navy | `#020044` |
| Blau | `#3055A0` |
| Lime | `#B4CD00` |
| Schrift Display | Hanken Grotesk |
| Schrift Fließtext | Inter |

Navy/Weiß/Blau sind Textfarben; Lime und Gold sind Flächen-/Akzentfarben (nie Text auf Weiß).
Schriften sind selbst gehostet (kein Google-CDN). Die vollständige, klickbare Referenz ist
das **Handbuch** (siehe unten).

## Das Handbuch ansehen

Nach Aktivierung des Themes erreichbar unter **`/handbuch`** auf beiden Domains
(z. B. `https://assesi.eu/handbuch`). Es zeigt alle Farben (per Klick kopierbar),
Schriften, Formen, Tiefe, Abstände, Bewegung und Komponenten beider Präsenzen.

## Single Source of Truth — der Änderungsprozess

**Farben, Schrift, Abstände, Radien ändert man an genau einer Stelle:**
`theme/assesi-child/assets/css/tokens.css`.

Diese Stellen leiten sich **automatisch** daraus ab — kein manuelles Nachziehen:

- `components.css` nutzt die Token-Variablen (`var(--…)`).
- `handbook.html` liest die Werte zur **Laufzeit** (verlinkt `tokens.css`) — kein Rebuild.
- `inc/elementor-globals.php` parst die CI-Farben aus `tokens.css` für den Elementor-Kit.

Eine kopierte Datei muss mitgezogen werden — dafür gibt es ein Werkzeug:

```
python3 tools/tokens.py          # prüft Konsistenz (meldet Drift)
python3 tools/tokens.py --fix    # erzeugt design/tokens-for-elementor.css neu
```

Der Check listet zusätzlich, wo CI-Hex hartkodiert ist (v. a. die fertigen
Elementor-Vorlagen) — relevant nur, falls sich die CI selbst je ändert.

## Struktur

```
Assesi/
├─ theme/assesi-child/        WordPress-Child-Theme (Hello Elementor)
│  ├─ assets/css/tokens.css   Design-Tokens — die einzige Quelle
│  ├─ assets/css/components.css  Komponenten, token-getrieben
│  ├─ assets/fonts/           Selbst gehostete Schriften + fonts.css
│  ├─ assets/js/ui.js         Accordion + Mobile-Menü
│  ├─ inc/                    schema.php · elementor-globals.php · handbook-route.php
│  ├─ elementor/*.json        Importierbare Elementor-/Theme-Builder-Vorlagen
│  ├─ handbook.html           CI-Handbuch (unter /handbuch ausgespielt)
│  ├─ functions.php           Enqueue, Body-Klasse, Module
│  └─ style.css               Theme-Header (Version)
├─ design/                    Referenzen (nicht deploybar): styleguide, One-Pager,
│                             tokens-for-elementor.css (generierter Customizer-Spiegel)
└─ tools/tokens.py            Konsistenz-Check + Spiegel-Generator
```

## Mehr Details

- **Installation & Einbindung (Schritt für Schritt): `docs/INSTALL.md`**
- Theme, Elementor-Verhältnis, Schema, Versionshinweise: `theme/assesi-child/README.md`
- Import der Vorlagen, HTML- vs. native Route: `theme/assesi-child/elementor/README.md`

**Produktionsbasis ist die native Route** der Startseiten; HTML-Route und One-Pager
sind pixelgenaue Referenzen zur Abnahme.
