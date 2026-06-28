# Elementor-Vorlagen

Import-fähige Vorlagen für beide Domains. Beide Sites folgen demselben Muster:
**Header** + **Footer** als Theme-Builder-Teile (native Widgets) und die **Startseite**
in zwei Routen — HTML (pixelgenau) und nativ (selektiv native Widgets).

```
assesi.eu                              assesi-label.eu
  assesi-eu-header.json      (header)    assesi-label-header.json      (header)
  assesi-eu-footer.json      (footer)    assesi-label-footer.json      (footer)
  assesi-eu-startseite.json  (page,HTML) assesi-label-startseite.json  (page,HTML)
  assesi-eu-startseite-nativ.json        assesi-label-startseite-nativ.json
                             (page,nativ)                              (page,nativ)
```

Voraussetzung in beiden Fällen: das **ASSESI-Child-Theme ist aktiv** (lädt Tokens,
`components.css` und `ui.js`). Ohne das Theme fehlt das Styling und Accordion/Mobile-Menü.
Die Theme-Klasse (`.theme-assesi` / `.theme-label`) setzt `functions.php` domain-abhängig —
die Label-Vorlagen färben sich also automatisch warm, sobald sie unter `assesi-label.eu`
laufen.

## Import

1. WordPress → **Vorlagen → Gespeicherte Vorlagen → Import-Vorlagen**, Datei hochladen.
2. Header/Footer (`type: header` / `footer`) erscheinen unter **Theme-Builder** und werden
   dort als Kopf-/Fußzeile für die ganze Domain veröffentlicht (Anzeigebedingung: ganze Seite).
3. Startseite (`type: page`) auf eine Seite anwenden. Beide Page-Vorlagen nutzen
   `elementor_canvas` (Navigation + Footer sind im Inhalt enthalten) — für die HTML-Route
   ist kein Theme-Builder-Header/Footer nötig. Wer die Theme-Builder-Teile nutzt, sollte die
   nativ-Route ohne den enthaltenen Nav/Footer-Block verwenden bzw. einen davon entfernen,
   damit Kopf/Fuß nicht doppelt erscheinen.

## HTML-Route vs. native Route

| Route   | Wann                                   | Editierbarkeit                          |
|---------|----------------------------------------|-----------------------------------------|
| HTML    | Pixelgenaue Übernahme der One-Pager    | Texte im HTML-Widget je Abschnitt       |
| nativ   | Redaktion editiert Fließtext selbst    | Titel/Eyebrow/Lede + Buttons nativ      |

In der **nativen Route** sind Abschnitts-Eyebrows, -Titel und -Ledes Elementor-
**Überschrift**-/**Text-Editor**-Widgets und damit ohne HTML editierbar; die markentypischen
Visuals bleiben pixelgenau im HTML-Widget:

- **assesi.eu** — Vorteile-Raster als native **Icon-Box**, Verlauf als native **Akkordeon**
  (beide mappen sauber auf Stock-Widgets). Hero-Karte, Preis-Karte, Nav, Footer als HTML.
- **assesi-label.eu** — die Signatur-Module (Siegel-Showcase, Zielgruppen-Split mit Listen,
  nummerierter Prozess-Stepper, Such-Mock) sind bewusst HTML, weil sie nicht verlustfrei auf
  Stock-Widgets abbilden. Nativ editierbar bleiben alle Abschnittsköpfe, der Hero-Text und die
  Buttons. Der Eyebrow-✓-Marker (CSS `.eyebrow::before`) entfällt in der nativen Route, weil
  der native Eyebrow ein gestyltes Überschrift-Widget ohne die `.eyebrow`-Klasse ist — in der
  HTML-Route ist er vorhanden.

Die Label-Titel nutzen in der HTML-Route `<em>` für die Lime-Unterstreichung
(`.theme-label .sec-title em`); Lime/Gold bleiben Flächen-/Akzentfarben, nie Textfarbe.

## Hinweise

- Cross-Domain-Links zeigen auf die jeweils andere Domain (`https://assesi.eu/` ↔
  `https://assesi-label.eu/`). Interne Sprünge sind Anker (`#warum`, `#prozess`, `#produkte`).
- Impressum/Datenschutz sind im Footer als Platzhalter (`#`) verlinkt — die eigenständigen
  Pflichtseiten je Domain folgen separat.
- Elementor-Template-JSON ist versionsabhängig. Falls beim Import ein Abschnitt hakt, ist er
  isoliert (ein Container je Abschnitt) und einzeln ersetzbar.
