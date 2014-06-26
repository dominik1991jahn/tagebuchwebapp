# Repository für die Web-App des digitalen Klassentagebuchs

## "Tunnel"

Alle Anfragen an den API-Server werden durch einen Tunnel geleitet. Dabei handelt es sich um ein PHP-Script, welches die Befehle entgegennimmt (z.B. aus einem AJAX-Request) und leitet diese dann an den API-Server weiter. Anschließend werden die Ergebnisse in das JSON-Format umgewandelt und an das aufrufende Script zurückgegeben.

## Bildschirmgröße ermitteln

- Anhand der Browser-Signatur: nicht sehr zuverlässig, kann manipuliert werden. Geräte wie iPad und iPad Mini sind nicht unterscheidbar
- Anhand der Bildschirm-Auflösung: sehr problematisch auf mobilen Geräten, außerdem haben selbst Smartphones schon FullHD-Auflösungen

### Wechsel zwischen Tages- und Wochenansicht durch Drehen des Geräts (nur bei Smartphones und Tablets):

- Hochkant: Tagesansicht
- Querkant: Wochenansicht

## Response Mapping

Die Daten, die wir von der API erhalten sind in Deutsch, 
wir programmieren aber mit englischen Bezeichnungen und wandeln deshalb die Bezeichnungen durch das Response Mapping um.
