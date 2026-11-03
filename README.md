# Cordis Group – Webseite

Webseite der Schülerfirma **Cordis Group** an der Gemeinschaftsschule Campus Cordis Dresden
(Campus Café und Lieferservice Schülerrando).

- `site/` – alle Dateien der Webseite (PHP, kein Datenbank-Server nötig)
- `site/manifest.json` – Liste aller Dateien mit Prüfsumme; der Server holt Updates über `deploy.php` (nicht im Repo, enthält einen geheimen Schlüssel)

Nicht im Repo und auf dem Server geschützt: Blogbeiträge (`data/posts.json`), Redaktions-Passwort (`data/config.php`), hochgeladene Fotos (`uploads/`).
