# 📦 Lagerverwaltung – Laravel Livewire Projekt

Ich habe in diesem Projekt folgende Aufgaben umgesetzt:

## 1. 🧩 Datenmodelle
Datenmodelle für **Items** und **Movements** mit Beziehungen und Constraints nach der vorgegebenen Modelllogik implementiert.

---

## 2. 🗂️ Artikelverwaltung (Items)
- Produktions- & Versandlogik (Materialverbrauch + Fertigprodukt) umgesetzt.
- CRUD-Funktionen: **Erstellen, Anzeigen, Bearbeiten, Löschen**.
- Suchfunktion über **Livewire**.
- Status-Anzeige mit Badges:
    - 🟥 *Low Stock*
    - 🟩 *Ziel erreicht*
    - ⬜ *OK*

---

## 3. 🚚 Warenbewegungen (Movements)
- Logik für **Eingänge**, **Transfers** und **Ausgänge** erstellt.
- Übersichtliche **Routen und Tabs** für jeden Bereich hinzugefügt.
- **Produktionsprozess** umgesetzt (Materialverbrauch + Fertigprodukt-Buchung).
- Automatische Prüfung, ob genügend Bestand vorhanden ist.

**Beispielrouten:**
- `/movements/inbound` – Wareneingang
- `/movements/outbound` – Warenausgang
- `/movements/transfer` – Umlagerung
- `/movements/production` – Produktion

---

## 4. 📧 E-Mail-Benachrichtigungen
- Automatische Benachrichtigung bei zu geringem oder erreichtem Bestand.
- Aktuell als **Log-Ausgabe** unter:

---

## 5. 🎨 UI / UX
- Erfolgsmeldungen und Fehlermeldungen integriert.
- Übersichtliches, klares Design mit konsistenter Struktur.

---

## 6. 🏆 Bonusaufgabe: CSV-Export
- Download-Funktion für **Bestände pro Artikel** (Gesamt & je Lager) implementiert.
- Export als CSV-Datei direkt aus der Übersicht.

---

## 🧭 Navigation
Alle wichtigen Bereiche sind über die Navigation oder direkt per Route erreichbar:
- `/items` – Artikelübersicht
- `/movements/inbound` – Wareneingang
- `/movements/outbound` – Warenausgang
- `/movements/transfer` – Umlagerung
- `/movements/production` – Produktion


Das Projekt ist versioniert und auf GitHub als Arbeitsnachweiss bzw. Portfolio unter folgendem Link verfügbar:
https://github.com/alfaemail2000-wq/laravellagerbestand.git“

