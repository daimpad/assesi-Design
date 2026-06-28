# Elementor-Template — assesi.eu Startseite

`assesi-eu-startseite.json` — die validierte Startseite als import-fähiges
Elementor-Page-Template (Route C: selektiv nativ).

## Import

1. WordPress → **Vorlagen → Gespeicherte Vorlagen → Import-Vorlagen**.
2. `assesi-eu-startseite.json` hochladen.
3. Vorlage auf eine neue Seite anwenden (oder „Bearbeiten mit Elementor" → Vorlage einfügen).
4. Sicherstellen, dass das **ASSESI-Child-Theme aktiv** ist (Tokens, Komponenten-CSS
   und `ui.js` werden von dort geladen — sonst fehlt das Styling und das Accordion/Menü).

Das Template nutzt die Elementor-Canvas-Vorlage (`elementor_canvas`), da Navigation
und Footer im Inhalt enthalten sind — kein Theme-Builder-Header/Footer nötig.

## Was ist nativ editierbar vs. HTML

| Abschnitt   | Titel / Eyebrow / Fließtext        | Visuals / Buttons        |
|-------------|------------------------------------|--------------------------|
| Hero        | HTML (Zweispalter, pixelgenau)     | HTML                     |
| Vorteile    | **Titel + Lede nativ** editierbar  | Karten (HTML)            |
| Verlauf     | **Titel + Lede nativ** editierbar  | Accordion (HTML)         |
| Mitmachen   | HTML (Zweispalter, pixelgenau)     | HTML                     |
| Nav/Footer  | HTML                               | HTML                     |

Native Titel sind Elementor-**Überschrift**-Widgets (Klasse `sec-title`, inkl.
`<em>`-Akzent), Ledes sind **Text-Editor**-Widgets (Klasse `sec-lede`). Sie tragen
unsere Token-Stile, ohne Elementors Defaults zu kollidieren. HTML-Blöcke bleiben
pixelgenau und werden im jeweiligen HTML-Widget bearbeitet.

## Hinweis

Elementor-Template-JSON ist versionsabhängig. Falls beim Import ein Abschnitt
hakt, ist er isoliert (ein Container je Abschnitt) und einzeln ersetzbar. Bitte
nach dem ersten Import kurz Rückmeldung geben — dann repliziere ich dasselbe
Muster für assesi-label.eu.
