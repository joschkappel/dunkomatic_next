<?php

return [
    'app.salutation'    => 'Viele Grüße und Spaß !',
    'app.greeting'    => 'Hallo !',
    'app.actionurl'     =>  'Wenn Du den Knopf ":actionText" nicht betätigen möchtest, kannst Du auch den folgenden Link in deinen Browser kopieren:',

    'regionadmin.greeting'  => 'Hallo BezirsleiterIn !',
    'user.greeting'  => 'Hallo :username!',

    'newuser.subject'   =>  'Neu Benutzer Registrierung',
    'newuser.line'      =>  'Ein neuer Benutzer hat sich mit der eMail :email registriert. Bitte bestätige die Registrierung.',
    'newuser.action'    =>  'Neuen Benutzer Validieren',

    'welcome.subject' => 'Willkommen bei '.config('app.name'),
    'welcome.line1' => 'Du bist als :userroles für den :region eingetragen. ',
    'welcome.line2' => 'Wichtige Mitteilungen siehst Du bei jedem Login auf der Home Seite. Die kannst Du auch durch klick auf das <strong>Logo</strong> oben links jederzeit erreichen. ',
    'welcome.line3' => 'Du kannst die Daten folgender Vereine und Spielrunden ändern: <ul><li> :clubs </li><li> :leagues </li></ul> Die Daten aller anderer Vereine und Ligen kannst Du in den jeweiligen Steckbriefen sehen.',

    'verifyemail.subject'   =>  config('app.name').' Registrierung - Bestätigung Deiner eMail Addresse',
    'verifyemail.action'    =>  'eMail Bestätigen',
    'verifyemail.line1' =>  'Bitte bestätige Deine eMail Adresse unten.',
    'verifyemail.line2' =>  'Falls Du kein Benutzerkonto registriert hast, ist keine weitere Aktion notwendig.',

    'resetpassword.subject' =>  'Passwort Zurücksetzen',
    'resetpassword.action' =>   'Password Zurücksetzen',
    'resetpassword.line1' =>    'Wir haben eine Anforderung erhalten dein Passwort zurückzusetzen.',
    'resetpassword.line2' =>    'Dieser Link verfällt in :count Minuten.',
    'resetpassword.line3' =>    'Falls Du diese Anfrage nicht gestellt hast, ist keine weitere Aktion notwendig.',

    'rejectuser.subject'    => 'Registrierungsanfrage Abgewiesen',
    'rejectuser.line1'  =>  'Die Bezirksleitung des Bezirks :REGION hat Deine Registrierung mit der folgenden Begründung abgewiesen: ":reason".',
    'rejectuser.line2'  =>  'Für Rückfragen wende Dich bitte an folgende eMail Adresse :email',

    'approveuser.subject'   => 'Registrierungsanfrage Akzeptiert',
    'approveuser.line1' =>  'Die Bezirksleitung des :REGION hat Deine Registrierung akzeptiert! ',
    'approveuser.line2' =>  'Stelle sicher, dass Du deine eMail verifiziert hast und Viel Spaß mit '.config('app.name'),

    'newseason.subject' =>  'Neue Spielsaison gestartet',
    'newseason.line1'   =>  'Die neue Spielsaison :season wurde in '.config('app.name').' gestartet.',
    'newseason.line2'   =>  'Etwas Arbeit und eine Menge Spass kommen auf Dich zu.',
    'newseason.line3'   =>  'Bleib dran und prüfe deine Nachrichten oder eMails regelmäßig !',

    'checkregionsetting.subject' => 'Neue Spielsaison gestartet',
    'checkregionsetting.line1' => 'Die neue Spielsaison :season ist gestartet.',
    'checkregionsetting.line2' => 'Die Datumsangaben in den Einstellungen deines Bezirks :REGION wurden automatisch geändert und um ein Jahr verschoben.',
    'checkregionsetting.line3' => 'Bitte überprüfe die Daten und passe sie gegebenenfalls an.',
    'checkregionsetting.action' => 'Bezirkseinstellungen',

    'missinglead.subject'   =>  'Fehlende Abteilungs- und Staffelleiter',
    'missinglead.line1' =>  'Vereine ohne Abteilnugsleiter:',
    'missinglead.line2' =>  'Spielrunden ohne Staffelleiter:',

    'leaguerptavail.subject'    =>  ':LEAGUE Spielpläne sind verfügbar !',
    'leaguerptavail.line'   =>  'Die Spielpläne für die Spielrunde :league wurden erzeugt und stehen zum Download bereit!.',
    'leaguerptavail.action'     =>  'Spielpläne Download',

    'leaguegamesgen.subject'    => 'Spielpaarungen für :LEAGUE erzeugt',
    'leaguegamesgen.line1'    => 'Die Spielpaarungen für die Runde :league wurden erzeugt und sind bereit für Dich ',
    'leaguegamesgen.line2'    => 'zur Überprüfung und Eingabe Deiner Heimspiel Termine.',
    'leaguegamesgen.action'    => 'Heimspiele Bearbeiten',
    'leaguegamesgen.action2'    => 'Liste Heimspiele',

    'clubrptavail.subject'  =>  ':CLUB Spielpläne sind verfügbar !',
    'clubrptavail.line'  =>  'Die Spielpläne für den Verein :club wurden erzeugt und stehen zum Download bereit!.',
    'clubrptavail.action'   =>  'Download Reports',

    'invalidemail.subject'  =>  'Fehlerhafte eMail Adressen :CLUBCODE',
    'invalidemail.line' =>  'Bei folgenden Vereinsmitarbeitern des Vereins :clubname wurden fehlerhafte eMail Adressen angegeben.',
    'invalidemail.action'   =>  'eMail Adressen Korrigieren',

    'registerteams.subject'  =>  'Mannschaftsmeldung für Spielrunde :LEAGUE',
    'registerteams.line1'    =>  'Dein Verein wurde zur Spielrunde :league zugelassen.',
    'registerteams.line2'    =>  'Du kannst jetzt eine Mannschaft dieser Runde zuordnen.',
    'registerteams.action'   =>  'Mannschaft Melden',
    'selectleagueno.subject'  =>  'Ziffernwahl für Spielrunde :LEAGUE',
    'selectleagueno.line1'    =>  'Alle Mannschaften für Spielrunde :league wurden gemeldet.',
    'selectleagueno.line2'    =>  'Du kannst jetzt die Spielziffer für deine Mannschaften wählen.',
    'selectleagueno.action'   =>  'Ziffernwahl',

    'league.salutation' =>  'Viele Grüße, Deine Staffelleitung :leaguelead',

    'clubdeassigned.subject'    =>  'Mannschaftslöschung :LEAGUE',
    'clubdeassigned.line1'  =>  'Deine Mannschaft :TEAM wurde von der Spielrunde :LEAGUE zurückgezogen.',
    'clubdeassigned.line2'  =>  'Für alle Fragen kontaktiere bitte die Staffelleitung.',

    'charpickenabled.subject'  =>  ':REGION Ziffernwahl Saison :season :mode',
    'charpickenabled.line1' =>  'Die Bezirksleitung :region hat die Ziffernwahl für die Saison :season :mode.',
    'charpickenabled.line2' =>  'Zur Ziffernwahl für Ihre gemeldeten Mannschaften bitte unten Klicken.',
    'charpickenabled.open'  =>  'geöffnet',
    'charpickenabled.closed'    =>  'beendet',
    'charpickenabled.action'    =>  'Ziffernwahl',

    'inviteuser.subject'    => 'Einladung für '.config('app.name'),
    'inviteuser.action' =>  'Als Benutzer Registrieren',
    'inviteuser.line1'  =>  ':sendername hat Dich als Benutzer für '.config('app.name').' eingeladen.',
    'inviteuser.line2'  =>  'Mit Betätigen des Knopfes kannst Du Dich als Benutzer registrieren.',

    'overlappinggames.subject'  => 'Überlappende Heimspielansetzungen',
    'overlappinggames.line1'  => 'Wir haben :games_count überlappende Heimspielansetzungen für deinen Verein :CLUB gefunden.',
    'overlappinggames.line2'  => 'Wenn Du auf die Spalte ":overlapcolumn" klickst, kannst Du die überlappenden Spiele sehen und ändern.',
    'overlappinggames.action'  => 'Spielbeginn Ändern',

    'unscheduledgames.subject'  => 'Heimspielansetzungen',
    'unscheduledgames.line1'  => 'Wir haben :games_count fehlende Heimspielansetzungen für deinen Verein :CLUB gefunden.',
    'unscheduledgames.line2'  => 'Wenn Du auf die Spalte ":gametimecolumn" klickst, kannst Du die Spiele mit fehlender Uhrzeit sehen und ändern.',
    'unscheduledgames.action'  => 'Spielbeginn Festlegen',
];
