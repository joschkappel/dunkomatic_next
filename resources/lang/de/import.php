<?php

return [
    'row' => 'Zeile',
    'column' => 'Spalte',
    'error' => 'Problem',
    'league_id.required' => 'Die Spielrunde existiert nicht',
    'club_id.required' => 'Der Verein der Heimmannschaft existiert nicht',
    'gym_id.required' => 'Die Hallennr existiert nicht für diesen Heimverein',
    'game_id.required' => 'Die Spielnr existiert nicht in dieser Runde für diesen Heimverein',
    'alert.header' => 'Hinweise zum Hochladen',
    'club.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner)',
    'club.uploadhint.2' => 'Die erste Zeile muß folgende Spaltenüberschriften enthalten: "Nr, Datum Spieltag, Beginn, Runde, Heim, Gast, Halle, Überschneidungen".',
    'club.uploadhint.3' => 'Es können nur das Spieldatum, der Spielbeginn oder die Halle geändert werden.',
    'alert.footer' => 'Bei Fehlern: Es werden alle oder keine Zeile übernommen.',
    'referee.uploadhint.1' => 'Die Datei muss in einem dieser Formate vorliegen: .xlsx oder .csv (Komma als Trenner).
    Die Spalte "ID" darf NICHT verändert werden!',
    'referee.uploadhint.2' => 'Die erste Zeile MUSS folgende Spalten-(überschriften) enthalten: "ID, Datum, Spieltag, Halle, Beginn, Runde, Nr, Heim, Gast, Schiri 1, Schiri 2".',
    'referee.uploadhint.3' => 'Es können nur die Schiedsrichter geändert werden.',

];
