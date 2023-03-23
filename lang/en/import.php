<?php

return [
    'row' => 'Row',
    'column' => 'Column',
    'error' => 'Issue',
    'league_id.required' => 'League :league does not exist',
    'club_id.required' => 'The club :home for this :who team does not exist',
    'team_id.required' => 'The :who team :team does not exists for the club',
    'gym_id.required' => 'The Gym No :gym for home club :home does not exist',
    'game_id.required' => 'The game no :game does not exist for league :league and home club :club',
    'alert.header' => 'Hints for succesfull uploads',
    'club.uploadhint.1' => 'Supported formats are .xlsx, .csv (commas as delimiter, ot quoted)',
    'club.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "Nr, Game date, Start, League, Home, Guest, Gym, OVerlaps".',
    'club.uploadhint.3' => 'You can only modify Game date and time as well as the gym.',
    'alert.footer' => 'In case of errors: No row wil be saved.',
    'referee.uploadhint.1' => 'Supported formats are .xlsx, .csv (commas as delimiter). You MUST NOT modify content in the ID column!',
    'referee.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "ID, Datum, Spieltag, Halle, Beginn, Runde, Nr, Heim, Gast, Schiri 1, Schiri 2".',
    'referee.uploadhint.3' => 'You can only modify referee data.',
    'league.uploadhint.1' => 'Supported formats are .xlsx, .csv (commas as delimiter).',
    'league.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "Nr","Datum Spieltag","Beginn","Heim","Gast","Halle","Schiri 1","Schiri 2".',
    'league.uploadhint.3' => 'You can modify or create all data for a game (except referee data).',
    'customgames.uploadhint.1' => 'Supported formats are .xlsx, .csv (commas as delimiter).',
    'customgames.uploadhint.2' => 'The first row is the header row and must contain these columnheaders: "Runde, Nr, Datum Spieltag, Beginn, Heim, Gast, Halle, Schiedsrichter".',
    'customgames.uploadhint.3' => 'Only games for leagues with custom schedules will be imported !',

];
