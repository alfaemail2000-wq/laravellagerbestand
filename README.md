# Initiale Installation

1. Environment Setup
   - Stellen Sie sicher, dass PHP, Composer und ein Webserver (wie Apache oder Nginx) auf Ihrem Rechner installiert sind.
   - Installieren Sie einen Datenbankserver (wie MySQL oder PostgreSQL).
   - Stellen Sie sicher, dass Sie Zugriff auf phpMyAdmin oder ein ähnliches Tool zur Verwaltung Ihrer Datenbank haben.
   - Passen sie die .env Datei an ihre Umgebung an.

2. Composer und NPM Abhängigkeiten installieren
   - Navigieren Sie in Ihrem Terminal zum Projektverzeichnis.
   - Führen Sie den folgenden Befehl aus, um die PHP-Abhängigkeiten zu installieren:
     ```
     composer install
     ```
   - Führen Sie den folgenden Befehl aus, um die JavaScript-Abhängigkeiten zu installieren:
     ```
     npm install
     ```
   - Bauen Sie die Assets mit dem folgenden Befehl:
     ```
     composer run dev
     ```
     
3. Datenbank einrichten
   - Migrieren und Seeden Sie die Datenbank mit dem folgenden Befehl:
     ```
     php artisan migrate:fresh --seed
     ```
   - Dies erstellt die notwendigen Tabellen und füllt sie mit Beispiel-Daten.
