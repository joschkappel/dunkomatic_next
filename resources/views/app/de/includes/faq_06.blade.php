<h5><span class="text-info">So funktioniert das</span></h5>
<p class="card-text">
    Die Spielrunden werden in Phasen eingeteilt, um damit Aktionen für Benutzergruppen steuern zu können.
    Die folgende Tabelle zeigt alle Phasen einer Spielrunde und die möglichen Aktionen von Beirksverwaltern und Vereinsverwaltern.
</p>
<ul class="list-group list-group-flush">
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_setup.png') }}">
            die Setup Phase</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>definiert die Runde mit Namen, Kürzel, Anzahl der Teams und Rahmenterminplan.</br>
            <div class="text-info"><b>die Vereine: </b></div>können diese Runde noch nicht sehen.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_registration.png') }}">
            die Mannschaftsmeldung</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>kann Vereine in die Runde aufnehmen und entfernen, Mannschaften eines Vereins an-/abmelden, den Mannschaften Ziffern zuordnen.
            <div class="text-info"><b>die Vereine: </b></div>können Mannschaften zur Runde anmelden.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_selection.png') }}">
            die Ziffernwahl</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>kann Vereine in die Runde aufnehmen und entfernen, Mannschaften eines Vereins an-/abmelden, den Mannschaften Ziffern zuordnen.
            <div class="text-info"><b>die Vereine: </b></div>können Ziffern für Ihr Team wählen.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_freeze.png') }}">
            Warten auf Freigabe / Spielerzeugung</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>kann letzte Änderungen an der Rundenzusammenstellung durchführen bevor die Spielpaarungen erzeugt werden.
            <div class="text-info"><b>die Vereine: </b></div>nichtsmehr an dieser Runde verändern.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_scheduling.png') }}">
            Heimspieltermine</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>kann letzte Änderungen an der Rundenzusammenstellung durchführen. Ein Wechsel zurück zur vorherigen Phase LÖSCHT alle Spielpaarungen wieder.
            <div class="text-info"><b>die Vereine: </b></div>können für Ihre Heimspiele Spieltag, Spielbeginn und Austragungsort nachtragen oder ändern.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_referees.png') }}">
            die Schiedsrichtereinteilung</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>kann mit ensprechenden Rechten die Schiedsrichterzuordnungen für alle Spiele des Bezirks ändern.
            <div class="text-info"><b>die Vereine: </b></div>nichts an dieser Runde ändern.
        </p>
    </li>
    <li class="list-group-item">
        <div class="text-left">
            <h5><img class="rounded" src="{{ asset('img/league_icon_live.png') }}">
            die Runde ist live</h5>
        </div>
        <p class="card-text">
            <div class="text-success"><b>die Bezirksleitung: </b></div>nichts an dieser Runde ändern.
            <div class="text-info"><b>die Vereine: </b></div>nichts an dieser Runde ändern.
        </p>
    </li>
  </ul>
  <h5><span class="text-info">Wie kann ich die Phasen ändern</span></h5>
  <p class="card-text">
    Dazu gibt es drei Wege:
    <ul class="list-group list-group-flush">
        <li class="list-group-item">
            <h5>Manuell für eine einzelne Runde</h5>
            <p class="card-text">
            In der Rundenlist kann jede Runden manuell in die nächste oder vorherige Phase überführt werden.
            Das geschieht durch klicken auf die Schalter in den Spalten "zur nächsten Phase" und "zur vorherigen Phase" in der Zeile der entsprechenden Runde.
            Aufpassen ! abhängig von der Bildschirmgröße und -auflösung können die Spalten zugeklappt sein:
            </p>
            <img class="rounded x-auto d-block border border-dark img-fluid" src="{{ asset('img/league_phase_change_one.png') }}" alt="Manueller Wechsel eine Runden">

        </li>
        <li class="list-group-item">
            <h5>Manuell für mehrere Runden</h5>
            <p class="card-text">
            In der Rundenlist können alle Runden einer Phase manuell in die nächste oder vorherige Phase überführt werden.
            Das geschieht durch klicken auf die Schalter "zur nächsten Phase" und "zur vorherigen Phase" im Listenkopf.
            Als Beispiel - klicken dieses Schalters:
            </p>
            <img class="rounded x-auto d-block border border-dark img-fluid" src="{{ asset('img/league_phase_change_all.png') }}" alt="Manueller Wechsel aller Runden">
            <p class="card-text">Führt alle Runden, welche sich in der Phase "Warten auf Freigabe/Spielerzeugung" befinden in die nächste Phase "Heimspieltermine" über. D.h. damit werden alle Spielpaarungen für die infrage kommenden Rundne erzeugt.</p>
        </li>
        <li class="list-group-item">
            <h5>Automatisiert</h5>
            <p class="card-text">
            Abhänging von Datumsangaben in den Bezirkseinstellungen werden alle Runden eines Bezirks, die sich inder korrekten Phase befinden in die nächste überführt.
            Folgende automatischen Übergänge sind vorgesehen:</p>
            <img class="rounded x-auto d-block border border-dark img-fluid" src="{{ asset('img/region_phase_settings.png') }}" alt="Bezirkseinstellungen">
            <p class="card-text">
                Diese Phasenübergängen werden ausgelöst:
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Starte Ziffernwahl - bringt alle Runden im Status "Mannschaftsmeldung" in die Phase "Ziffernwahl". Jetzt können Vereine Ziffern wählen</li>
                    <li class="list-group-item">Ende Zifernwahl - bringt alle Runden im Status "Ziffernwahl" in die Phase "Warten auf Freigabe/Spielerzeugung". Die Ziffernwahl ist nicht mehr möglich</li>
                    <li class="list-group-item">Starte Heimspieltermine - bringt alle Runden im Status "Warten auf Freigabe/Spielerzeugung" in die Phase "Heimspieltermine". Jetzt können Vereine Heimspiele bearbeiten.</li>
                    <li class="list-group-item">Ende Heimspieltermine - bringt alle Runden im Status "Heimspieltermine" in die Phase "Schiedsrichterzuordnung". DIe Vereine könne nichts mehr an den Heimspielen ändern.</li>
                    <li class="list-group-item">Starte Spielrunde - bringt alle Runden im Status "Schiedsrichterzuordnung" in die Phase "Live". Die Runden ist für jegliche Änderung gesperrt.</li>
                </ul>
                <p class="card-text text-danger">
                    Generell gilt, dass der automatische Start einer Phase am gewählten Tag morgens um 08:00 Uhr erfolgt. Die Phase wird am gewählten Datum um 20:00 beendet.
                </p>


        </li>
    </ul>


