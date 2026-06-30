# ASSESI Design System — Elementor Kit

Importierbares Elementor-**Kit** mit den globalen **Farben** und **Schriften** der CI.
Quelle dieser Werte ist `assets/css/tokens.css`; dieses Kit spiegelt sie in Elementors
Site Settings, damit der Editor von sich aus die Markenwerte anbietet.

> Hinweis: Das Child-Theme setzt dieselben Globals ohnehin automatisch
> (`inc/elementor-globals.php`). Dieses Kit ist die **manuell importierbare** Alternative —
> z. B. für eine Installation ohne das Theme.

## Inhalt

- `manifest.json` — Kit-Metadaten und Liste der enthaltenen Site-Settings-Tabs
  (`global-colors`, `global-typography`).
- `site-settings.json` — die Werte: System-Farben (Navy/Blau/Navy/Gold) + Marken-Swatches
  (Gold/Navy/Blau/Lime) sowie System-Schriften (Display = Hanken Grotesk, Fließtext = Inter).

## Import

1. Die beiden Dateien in **eine ZIP** packen (Dateien im ZIP-**Wurzelverzeichnis**, nicht in
   einem Unterordner) — oder die fertige `assesi-design-system.zip` verwenden.
2. WordPress → Elementor → **Tools → Import / Export Kit → Kit importieren** → ZIP hochladen.
3. Beim Import **Site Settings** auswählen und importieren.
4. Kontrolle: Editor → **Site Settings → Global Colors / Global Fonts**.

ZIP neu bauen:

```
cd theme/assesi-child/elementor/kit && zip ../../../../assesi-design-system.zip manifest.json site-settings.json
```

## Schriften

Die Globals referenzieren „Hanken Grotesk" und „Inter". Diese werden vom Theme **self-hosted**
ausgeliefert; Elementors Google-Fonts-Ausgabe ist abgeschaltet. Ohne das ASSESI-Theme müssten
die Schriften anderweitig (z. B. als Custom Fonts) bereitgestellt werden.

## Format

Aufbau aus dem Elementor-Quellcode abgeleitet (Import-Runner liest `site_settings.settings`
mit `system_colors`/`custom_colors`/`system_typography`/`custom_typography`). Kit-Importe sind
versionsabhängig — bei einer Fehlermeldung die Elementor-Version notieren und anpassen.
