<p class="card-text" >
    Du hast 3 Optionen wiue Du Deine Heimspiel-Ansetzung ändern kannst:
    <ol>
        <li><a id="start" href="#option1">Spiele je Spieltag sehen und bearbeiten</a></li>
        <li><a id="start" href="#option2">Alle Spiele in einer Liste sehen und bearbeiten</a></li>
        <li><a id="start" href="#option3">Spiele Exportieren und Importieren</a></li>
    </ol>
</p>
<h5><span class="text-info">So funktioniert das</span></h5>
<p class="card-text">
    Sobald die Runden für die Erfassung der Heimspieltermine freigegeben sind, siehst Du auf deiner Vereinsseite in der Karte "Heimspiele" den Knopf "Heimpspieltermine Erfassen".
    Generell gelten für Option 1 und 2 diese Farbcodes:
    <ul class="list-group list-group-flush">
        <li class="list-group-item list-group-item-danger">Spielüberschneidung, d.h. der Slot für ein Spiel ist weniger als {{session('cur_region')->game_slot}} Minuten</li>
        <li class="list-group-item list-group-item-warning">Spielbeginn fehlt</li>
        <li class="list-group-item list-group-item-success">Alle anderen Heimspiele</li>
        <li class="list-group-item ">Auswärtsspiele</li>
    </ul>
</p>
<h4><span class="text-info"><a id="option1" href="#start">Spiele je Spieltag sehen und bearbeiten</a></span></h4>
<p class="card-text">
    Klicke auf deiner Vereinsseite in der Karte "Heimspiele" den Knopf "Heimpsieltermine Erfassen".
    Mit Klick auf den Button werden Dir in einem Graphen alle Spiele deines Vereins je Spieltag angezeigt:</br>
    <img class="rounded x-auto d-block border border-dark" src="{{ asset('img/club_homegame_chart.png') }}" alt="Heimspiele je Spieltag" width="75%"></br>
    Mit Klick auf die Legende kannst Du den Graphen filtern, also die Auswärtsspiele aus-/einblenden (mit Klick auf "Auswärtsspiele") .
</p>
<p class="card-text">
    Wenn Du nun auf die Balken einen Spieltag klickst, werden die Spiele dieses Tages für jede Halle in der Tabelle darunter angezeigt:</br>
    <img class="rounded x-auto d-block border border-dark" src="{{ asset('img/club_homegame_gday.png') }}" alt="Heimspiele für einen Spieltag" width="75%"></br>
    </br>

    Du kannst jetzt mit Klick auf die roten/gelben/grünen Heimspielknöpfe einen Dialog aufrufen und dort Termin, Uhrzeit und Halle ändern.</br>
    <img class="rounded x-auto d-block border border-dark" src="{{ asset('img/club_homegame_edit.png') }}" alt="Heimspieldaten ändern" width="75%"></br>
    </br>

<h4><span class="text-info"><a id="option2" href="#start">Alle Spiele in einer Liste sehen und bearbeiten</a></span></h4>
<p class="card-text">
    Wenn Du in der Spiele je Spieltag Seite (oben <a href="option1" >Option1</a>) auf "Zur Listenansicht wechseln" klickst, werden dir alle Spiele in einer Listenansicht angezeigt.</br>
    <img class="rounded x-auto d-block border border-dark" src="{{ asset('img/club_homegame_list.png') }}" alt="Heimspiele in Listenansicht" width="75%"></br>
    </br>


    Farblich unterlegt sind die Heimspiele.
    Hier kannst Du (wie in den meisten Tabellen)
    <ul>
       <li>filtern (über Suche)</li>
        <li>sortieren (mit Klick und Shift-Klick auf den Spaltenkopf)</li>
        <li>exportieren und drucken</li>
    </ul>
    Wenn Du auf die Spielnummer mit Pfeil klickst siehst Du denselben Dialog wie in <a href="#option1">Option 1</a> um die Ansetzung zu ändern.
</p>
<h4><span class="text-info"><a id="option3" href="#start">Spiele mit Excel bearbeiten</a></span></h4>
<p class="card-text">
    Klicke auf deiner Vereinsseite in der Karte "Heimspiele" den Knopf "Heimpsieltermine Erfassen".
    Du kannst ausgehend von der Listenansicht in <a href="option2">Option 2</a> die Spiele als Excel Datei exportieren, ändern und dann wieder Importieren.

</p>

