<?php

return [
    'row' => 'Row',
    'column' => 'Column',
    'error' => 'Issue',
    'league_id.required' => 'This league does not exist',
    'club_id.required' => 'The club for this home team foes not exist',
    'gym_id.required' => 'The Gym No for this home teams club does not exist',
    'game_id.required' => 'This game no does not exist for this league and home team',
    'alert.header' => 'Hints for succesfull uploads',
    'club.uploadhint.1' => 'Support format is .xlsx, .csv (commas as delimiter, ot quoted)',
    'club.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "Nr, Game date, Start, League, Home, Guest, Gym, OVerlaps".',
    'club.uploadhint.3' => 'You can only modify Game date and time as well as the gym.',
    'alert.footer' => 'In case of errors: No row wil be saved.',
    'referee.uploadhint.1' => 'Support format is .xlsx, .csv (commas as delimiter, ot quoted). You MUST NOT modify content in the ID column!',
    'referee.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "ID, Datum, Spieltag, Halle, Beginn, Runde, Nr, Heim, Gast, Schiri 1, Schiri 2".',
    'referee.uploadhint.3' => 'You can only modify referee data.',
];
