#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""ASSESI — Token-Konsistenz.

Single Source of Truth ist  theme/assesi-child/assets/css/tokens.css.
Abgeleitete Stellen MÜSSEN damit übereinstimmen. Dieses Werkzeug prüft das und
kann den einen kopierten Spiegel (Customizer-Snippet) neu erzeugen.

  python3 tools/tokens.py          # prüfen (exit 1 bei Drift)
  python3 tools/tokens.py --fix    # design/tokens-for-elementor.css neu erzeugen

Was sich AUTOMATISCH aus tokens.css ableitet (kein Eingriff nötig):
  - components.css           nutzt var(--…)
  - theme/.../handbook.html  liest die Werte zur Laufzeit (getComputedStyle)
  - inc/elementor-globals.php parst die CI-Hex aus tokens.css
Was dieses Werkzeug spiegelt/prüft:
  - design/tokens-for-elementor.css  (Customizer-Paste-Snippet, reine Kopie)
"""
import os, re, sys

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
TOKENS = os.path.join(ROOT, "theme/assesi-child/assets/css/tokens.css")
MIRROR = os.path.join(ROOT, "design/tokens-for-elementor.css")

HEADER = (
    "/* ASSESI — TOKENS für Elementor (Site-Einstellungen → Benutzerdefiniertes CSS).\n"
    "   GENERIERT aus theme/assesi-child/assets/css/tokens.css — NICHT direkt bearbeiten.\n"
    "   Aktualisieren: python3 tools/tokens.py --fix   ·   Prüfen: python3 tools/tokens.py */\n\n"
)

# CI-Markenfarben (ohne Weiß — zu allgegenwärtig, um aussagekräftig zu sein)
BRAND_HEX = {
    "#F5C503": "--ci-gold",
    "#020044": "--ci-navy",
    "#3055A0": "--ci-blue",
    "#B4CD00": "--ci-lime",
}


def read(p):
    with open(p, encoding="utf-8") as f:
        return f.read()


def build_mirror():
    return HEADER + read(TOKENS)


def cmd_fix():
    with open(MIRROR, "w", encoding="utf-8") as f:
        f.write(build_mirror())
    print("aktualisiert:", os.path.relpath(MIRROR, ROOT))


def scan_brand_hex():
    """Listet Markenfarben-Hex außerhalb von tokens.css/mirror — zur Sichtbarkeit."""
    hits = []
    skip_dirs = {".git", "node_modules"}
    allow = {os.path.normpath(TOKENS), os.path.normpath(MIRROR)}
    pat = re.compile("|".join(re.escape(h) for h in BRAND_HEX), re.I)
    for base, dirs, files in os.walk(ROOT):
        dirs[:] = [d for d in dirs if d not in skip_dirs]
        for fn in files:
            if not fn.endswith((".php", ".css", ".html", ".json")):
                continue
            p = os.path.join(base, fn)
            if os.path.normpath(p) in allow:
                continue
            try:
                n = len(pat.findall(read(p)))
            except Exception:
                continue
            if n:
                hits.append((os.path.relpath(p, ROOT), n))
    return sorted(hits)


def cmd_check():
    ok = True
    if not os.path.exists(MIRROR) or read(MIRROR) != build_mirror():
        ok = False
        print("DRIFT: design/tokens-for-elementor.css weicht von tokens.css ab.")
        print("       Beheben mit:  python3 tools/tokens.py --fix")
    else:
        print("OK: Customizer-Spiegel stimmt mit tokens.css überein.")

    hits = scan_brand_hex()
    if hits:
        print("\nHinweis — CI-Hex hartkodiert (bei CI-Änderung hier mitziehen):")
        for rel, n in hits:
            print(f"  {rel}: {n}×")
        print(
            "\n  Erwartet/akzeptiert: Elementor-*.json (Widget-Farben werden technisch\n"
            "  als Hex gespeichert), design/*-onepager.html + styleguide.html (eingefrorene\n"
            "  Referenzen) und inc/elementor-globals.php (nur Fallback-Defaults).\n"
            "  Die Live-Stellen (components.css, handbook.html, Kit) leiten automatisch ab."
        )
    return 0 if ok else 1


if __name__ == "__main__":
    if "--fix" in sys.argv[1:]:
        cmd_fix()
    else:
        sys.exit(cmd_check())
