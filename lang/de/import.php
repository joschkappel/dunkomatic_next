<?php

return [
    'row' => 'Zeile',
    'column' => 'Spalte',
    'error' => 'Problem',
    'league_id.required' => 'Die Spielrunde :league existiert nicht',
    'club_id.required' => 'Der Verein der :who-Mannschaft :club existiert nicht',
    'team_id.required' => 'Die :who-Mannschaft :team existiert nicht im Verein',
    'gym_id.required' => 'Die Hallennr :gym existiert nicht für den Heimverein :home',
    'game_id.required' => 'Die Spielnr ":game" existiert nicht in der Runde :league für den Heimverein :home',
    'alert.header' => 'Hinweise zum Hochladen',
    'club.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner)',
    'club.uploadhint.2' => 'Die erste Zeile muß folgende Spaltenüberschriften enthalten: "Nr, Datum Spieltag, Beginn, Runde, Heim, Gast, Halle, Überschneidungen".',
    'club.uploadhint.3' => 'Es können nur das Spieldatum, der Spielbeginn oder die Halle geändert werden.',
    'alert.footer' => 'Bei Fehlern: Es werden alle oder keine Zeile übernommen.',
    'referee.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner).
    Die Spalte "ID" darf NICHT verändert werden!',
    'referee.uploadhint.2' => 'Die erste Zeile MUSS folgende Spalten-(überschriften) enthalten: "ID, Datum, Spieltag, Halle, Beginn, Runde, Nr, Heim, Gast, Schiri 1, Schiri 2".',
    'referee.uploadhint.3' => 'Es können nur die Schiedsrichter geändert werden.',
    'league.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner)',
    'league.uploadhint.2' => 'Die erste Zeile muß folgende Spaltenüberschriften enthalten: "Nr","Datum Spieltag","Beginn","Heim","Gast","Halle","Schiri 1","Schiri 2".',
    'league.uploadhint.3' => 'Es können alle Daten (nicht die Schiedsrichter) geändert oder neu angefügt werden.',
    'customgames.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner)',
    'customgames.uploadhint.2' => 'Die erste Zeile MUSS folgende Spaltenüberschriften enthalten: "Runde, Nr, Datum Spieltag, Beginn, Heim, Gast, Halle, Schiedsrichter".',
    'customgames.uploadhint.3' => 'Es werden nur Spiele für Runden ohne Rahmenterminplan übernommen !',
];
