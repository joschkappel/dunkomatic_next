@extends('layouts.page')

@section('content')
<x-card-list cardTitle="Release Information" >
<div>
    <div class="col-12">
        <div class="card card-outline card-info mb-3">
            <x-card-header title="v1.2.0" />
            <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-primary">Nachrichten mit Anhang
                        <ul>
                            <li>Mit jeder Nachricht kann eine Datei als Anhang mit verschickt werden.</li>
                            <li>Es sind nur PDF Dateien mit einer maximalen Größe von 1 MB erlaubt.</li>
                            <li>Der ANhang wird bei erstellen der Nachricht hochgeladen.</li>
                        </ul>
                    </li>
                  </ul>
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">Rundeneinteilung
                        <ul>
                            <li>Die Übersicht aller Runden eines Bezirks mit den jeweils gemeldeten Mannschaften kann jetzt exportiert oder ausgedruckt werden.</li>
                            <li>Die Übersicht ist nach Senioren und Jugend Mannschaften getrennt.</li>
                            <li>Aufruf über die Rundenverwaltung und dann "Drucken/Export".</li>
                        </ul>
                    </li>
                  </ul>
            </div>
        </div>
        <div class="card card-outline card-info collapsed-card mb-3">
            <x-card-header title="v1.1.1" />
            <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-primary">Eine Mannschaft von der laufenden Runde zurückziehen
                        <ul>
                            <li>Die Bezirksleiung kann über die Rundenverwaltung jetzt Mannschaften zurückziehen.</li>
                            <li>Dazu einfach (Runden->Verwalten) die entsprechende Mannschaft anklicken und die Aktion bestätigen.</li>
                            <li>Das Datum des Rückzugs wird bei der Mannschaft vermerkt und angezeigt.</li>
                            <li>Ab der nächsten Spielsaison wird dort auch das Datum der Meldung, der Ziffernwahl, etc angezeigt werden.</li>
                            <li>Auf der Startseite wird ständig die Liste aller zurückgezogenen Teams für alle Benutzer angezeigt.</li>
                        </ul>
                    </li>
                  </ul>
            </div>
        </div>
        <div class="card card-outline card-info collapsed-card mb-3">
            <x-card-header title="v1.1.0" />
            <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-primary">Automatische Listenerstellung
                        <ul>
                            <li>Die Listen werden täglich erstellt bzw aktualisiert</li>
                            <li>Es wird nur dann eien neue Version einer Liste erstellt, wenn sich die zugrundeliegenden Daten geändert haben.</li>
                            <li>Die Einstellungen auf Bezirksebene dazu entfallen</li>
                            <li>Alle Dateien sind mit Datumsangabe versioniert</li>
                            <li>Bei Bedarf kann die Listenerzeugung auf Bezirksebene jederzeit angestossen werden.</li>
                            <li>Bei Bedarf können erzeugte Listen auf Bezirksebene gelöscht werden.</li>
                        </ul>
                    </li>
                    <li class="list-group-item">Listen Downloads
                        <ul>
                            <li>Es gibt auf Bezirks-, Runden- und Vereinsebene eine "Download Zone"</li>
                            <li>Dort können die relevante Listen in den jeweiligen Formaten heruntergeladen werden</li>
                            <li>Alle Listen werden immer al HTML und XLSX Datei angeboten</li>
                            <li>Weitere Formate (PDF, CSV, ...) können auf Bezirksebene angefordert werden</li>
                            <li>Nach erstmaligem Download einer Liste, wird der Benutzer im Homescreen darauf hingewiesen, dass eiene neue Version zur Verfügung steht.</li>
                        </ul>
                    </li>
                    <li class="list-group-item list-group-item-primary">Spiele ohne Gegner
                        <ul>
                            <li>Es werden nur noch Spiele erzeugt, wenn Heimteam und Gegner zugeordnet sind</li>
                            <li>Es gibt keine "Spiele ohne Gegner" mehr, d.h. die "Löschfunktion" dazu entfällt</li>
                        </ul>
                    </li>
                  </ul>
            </div>
        </div>
        <div class="card card-outline card-info collapsed-card mb-3">
            <x-card-header title="v1.0.7" />
            <div class="card-body">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item list-group-item-primary">Mannschaftsverantwortlichen
                        <ul>
                            <li>Wurden in die Mitarbeitende Ansicht aufgenommen</li>
                            <li>Eine Suche nach "Rundenkürzel", also zB. "OLD" zeigt aller Mannschaftsverantwortlichen, alle Abteilungslieter und den Staffelleiter der Runde OLD an</li>
                            <li>Eine Suche nach "Rundenkürzel MV", also zB. "OLD MV" zeigt aller Mannschaftsverantwortlichen der Runde OLD an</li>
                        </ul>
                    </li>
                  </ul>
            </div>
        </div>
    </div>
</div>
</x-card-list>

@endsection
