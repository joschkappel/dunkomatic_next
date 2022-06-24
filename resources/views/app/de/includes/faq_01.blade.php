<h5><span class="text-info">So funktioniert das</span></h5>
<p class="card-text">
    {{ config('dunkomatic.title')}} unterscheidet streng zwischen Benutzer:innen und Mitarbeiter:innen. D.h. Benutzer:innen <span class="text-uppercase font-weight-bold">können</span> Mitarbeitende sein und Mitarbeitende <span class="text-uppercase font-weight-bold">können</span> Benutzer:innen sein.
    Das erlaubt einem Verein z.B. die Abteilungsleitung zu trennen von demjenigen, der die Daten in der Anwednung betreut.
</p>
<p class="card-text">
    Jeder mit Zugriff auf die URL kann sich als Benutzer:in registrieren. Um den Zugang zu kontrollieren ist eine Freigabe der zuständigen Bezirksleitung notwendig.
    Der Ablauf eine Neu-Registrierung ist wie folgt:
    <ul>
        <li>der Benutzer registriert sich (mit Angabe des gewünschten Bezirks und eins Grunds)</li>
        <li>der Benutzer erhält eine eMail zur Verifikation der eMail Adresse</li>
        <li>die zuständige Bezirksleitung erhält eine eMail mit der Bitte um Prüfung. Zusätzlich erscheint eine neue Aufgabe in der Liste der "Aufgaben für diese Woche" des Bezirksvorstands</li>
        <li>die Bezirksleitung kann den Benutzer zulassen oder ablehnen. Im Falle einer Freigabe kann er Zugriffsrechte auf Vereine und Ligen setzen</li>
        <li>der Benutzer erhält eine eMail über Freigabe oder Ablehnung</li>
        <li>erst, wenn der Benutzer seine eMail Adresse erfolgreich verifiziert hat <span class="text-uppercase font-weight-bold">und</span> von der Bezirksleitung freigeben wurde, kann der die Anwendung benutzen</li>
    </ul>
</p>
<h5><span class="text-info">Zugangsanfrage freigeben</span></h5>
<p class="card-text">
    Um die Anfrage zuzulassen und das Konto freizugeben musst du ein Häckchen im Feld
    <img class="rounded x-auto d-block border border-dark" src="{{ asset('img/approved.png') }}" alt="Zugang Freigeben"> setzen.
</p>
<h5><span class="text-info">Zugangsanfrage ablehnen</span></h5>
<p class="card-text">
    Um die Anfrage abzulehnen und das Konto zu sperren darfst du kein Häckchen im Feld  <img class="rounded x-auto d-block border border-dark img-fluid" src="{{ asset('img/rejected.png') }}" alt="Zugang Ablehnen"> setzen. Gib eine Begründung für die Ablehnung ein.
</p>
<h5><span class="text-info">Ein Konto nachträglich sperren</span></h5>
<p class="card-text">
    Du kannst Zugangskonten jederzeit sperren, indem Du die "Liste Zugangskonten" öffnest und das gewünschte Konto sperrst (in der der Spalte "Aktion").
    Gesperrte Konten, die älter als 1 Monat sind, werden automatisch gelöscht (wie auch Konten, deren eMail nicht innerhalb einer Woche verifiziert wurdem).
</p>
<h5><span class="text-info">Zugriffsrechte einstellen</span></h5>
<p class="card-text">
  Siehe <a href="#faq_02">Zugriffsrechte einrichten</a>
</p>
