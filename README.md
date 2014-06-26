tagebuchwebapp
==============

Repository für die Web-App des digitalen Klassentagebuchs

==============

Bildschirmgröße ermitteln

- Anhand der Browser-Signatur: nicht sehr zuverlässig, kann manipuliert werden. Geräte wie iPad und iPad Mini sind nicht unterscheidbar
- Anhand der Bildschirm-Auflösung: sehr problematisch auf mobilen Geräten, außerdem haben selbst Smartphones schon FullHD-Auflösungen

Wechsel zwischen Tages- und Wochenansicht durch Drehen des Geräts (nur bei Smartphones und Tablets):

- Hochkant: Tagesansicht
- Querkant: Wochenansicht

Response Mapping

Die Daten, die wir von der API erhalten sind in Deutsch, 
wir programmieren aber mit englischen Bezeichnungen und wandeln deshalb die Bezeichnungen durch das Response Mapping um.