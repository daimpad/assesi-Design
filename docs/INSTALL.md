# ASSESI — Installation & Einbindung

Alles ist fertig. Es muss nur installiert und zusammengefügt werden — kein Code
mehr zu schreiben. Diese Anleitung gilt **pro Domain** (einmal für assesi.eu,
einmal für assesi-label.eu). Das Theme erkennt die Domain selbst und schaltet
auf das richtige Erscheinungsbild um.

## Das Paket

- **`assesi-child.zip`** — das installierbare Child-Theme. Enthält Design-Tokens,
  Komponenten-CSS, selbst gehostete Schriften, das UI-Skript, Schema.org, die
  Elementor-Globals-Automatik, die `/handbuch`-Route und das Handbuch selbst.
- **Elementor-Vorlagen** (die `*.json` aus `theme/assesi-child/elementor/`) — zum
  Import in Elementor (Header, Footer, Startseite, Pflichtseiten, Handbuch).

„Globale CSS" musst du nicht separat einspielen: Tokens (`tokens.css`) und
Komponenten (`components.css`) lädt das Theme automatisch. Auch die Elementor
Global Colors/Fonts werden vom Theme per Code gesetzt.

## Voraussetzungen

1. Eine WordPress-Installation je Domain.
2. **Hello Elementor** (kostenloses Parent-Theme, aus dem WP-Theme-Verzeichnis).
3. **Elementor** (kostenlos). Für die globale Kopf-/Fußzeile (Theme-Builder) und
   die Pflichtseiten mit Theme-Rahmen zusätzlich **Elementor Pro**.

## Schritt für Schritt (je Domain)

1. **Parent-Theme:** Design → Themes → Hinzufügen → „Hello Elementor" suchen,
   installieren (nicht aktivieren nötig).
2. **Child-Theme:** Design → Themes → Hinzufügen → **Theme hochladen** →
   `assesi-child.zip` wählen → installieren → **aktivieren**.
   Die Body-Klasse (`theme-assesi` / `theme-label`) wird automatisch nach Domain
   gesetzt — assesi-label.eu wird warm, alles andere institutionell.
3. **Plugins:** Elementor (und Elementor Pro) installieren und aktivieren.
4. **Vorlagen importieren:** Templates → Saved Templates → **Import Templates** →
   die passenden `*.json` hochladen. **Pro Domain nur die jeweils passenden Dateien:**

   | Datei (assesi.eu / assesi-label.eu) | Typ | Verwendung |
   |---|---|---|
   | `*-header.json` | Header | Theme Builder → als Kopfzeile veröffentlichen (ganze Website) |
   | `*-footer.json` | Footer | Theme Builder → als Fußzeile veröffentlichen (ganze Website) |
   | `*-startseite-nativ.json` | Seite | Produktionsbasis: auf eine Seite anwenden, als Startseite setzen |
   | `*-startseite.json` | Seite | Alternative HTML-Route (pixelgenau), optional |
   | `*-impressum.json` | Seite | Pflichtseite, `[TODO]` mit echten Daten füllen |
   | `*-datenschutz.json` | Seite | Pflichtseite, `[TODO]` mit echten Daten füllen |
   | `assesi-handbook.json` | Seite | optional — bettet das Handbuch ein |

   Header/Footer im **Theme Builder** mit Anzeigebedingung „ganze Website"
   veröffentlichen. Wer die HTML-Route der Startseite nutzt (Nav/Footer sind dort
   enthalten), braucht keinen Theme-Builder-Header/-Footer auf dieser Seite.
5. **Startseite festlegen:** Einstellungen → Lesen → „Eine statische Seite" → die
   importierte Startseite wählen.
6. **Global Colors/Fonts:** nichts zu tun. Das Theme schreibt die CI-Farben und
   Schriften beim ersten Aufruf des WP-Adminbereichs in den Elementor-Kit. Kontrolle:
   Elementor-Editor → Site Settings → Global Colors / Global Fonts.
7. **Design-Handbuch:** unter **`/handbuch`** erreichbar, sobald das Theme aktiv ist
   (z. B. `https://assesi.eu/handbuch`). Ein Import ist dafür nicht nötig.

## Vor dem Livegang noch füllen

- **Impressum/Datenschutz:** alle `[TODO]` auf den beiden Seiten je Domain durch die
  echten Angaben ersetzen (verantwortliche Stelle, Anschrift, Hoster, Aufsichtsbehörde …).
  Die gelbe Hinweisbox oben auf jeder Seite danach entfernen.
- **Schema.org:** in `inc/schema.php` im `$profiles`-Array die `TODO`-Platzhalter
  (echte Logo-URL, ggf. `sameAs`-Profile) eintragen.

## Tokens später ändern (optional, UI-editierbar)

Farben/Schrift/Abstände/Radien liegen in `assets/css/tokens.css` (eine Quelle).
Wer sie ohne Code über die WordPress-Oberfläche anpassen will, fügt den Inhalt von
`design/tokens-for-elementor.css` in **Customizer → Zusätzliches CSS** ein und ändert
dort die Werte — Komponenten, native Elementor-Widgets und das Handbuch ziehen
automatisch nach.

## Updates

Bei einer neuen Theme-Version: `assesi-child.zip` neu erzeugen (Ordner
`theme/assesi-child/` zippen) und über Design → Themes → Hinzufügen → Theme
hochladen erneut einspielen (überschreibt das alte). Bei CI-Änderungen nur
`tokens.css` anfassen; `python3 tools/tokens.py --fix` aktualisiert den
Customizer-Spiegel, `python3 tools/tokens.py` prüft auf Drift.
