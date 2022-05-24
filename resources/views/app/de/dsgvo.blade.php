@extends('layouts.page')

@section('content')
    <div class="container-fluid ">
        <div class="row">
            <div class="col-sm-12 pd-2">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title">Datenschutzerklärung</h3>
                        <p class="card-text">
                            Wir nehmen den Schutz Deiner Privat- und Persönlichkeitssphäre ernst und beachten die datenschutzrechtlichen Bestimmungen. Dein Vertrauen ist uns wichtig!
                        </p>
                        <p class="card-text">
                            Mit diesen Hinweisen kommen wir auch unseren Informationspflichten nach Artikel 13 der Datenschutz-Grundverordnung (DS-GVO) bei der Erhebung personenbezogener Daten nach. Personenbezogene Daten erheben wir als Kernbestandtiel der Anwendung.
                        Wir haben technische und organisatorische Maßnahmen getroffen, die sicherstellen, dass die Vorschriften über den Datenschutz von uns beachtet werden.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Personenbezogene Daten</h5>
                        <p class="card-text">
                            Personenbezogene Daten sind Informationen zu Deiner Person, wie zum Beispiel Dein Name, Adresse, Postanschrift oder auch Dein Nutzerverhalten, wenn dies nicht anonymisiert wird. Informationen, die nicht mit Deiner Identität in Verbindung gebracht werden, fallen nicht darunter.
                            Deine Daten werden zum einen dadurch erhoben, dass Du uns diese mitteilst. Dabei beruht die Verarbeitung auf Deiner Einwilligung. Hierbei kann es sich zum Beispiel um Daten handeln, die Du in ein Formular eingibst.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Zugriff auf die Anwendung</h5>
                        <p class="card-text">
                            Zugriff auf das Internet-Angebot
                            Andere Daten werden automatisch beim Besuch der Website durch das IT-System erfasst. Das sind vor allem technische Daten z. B. IP-Adresse, Internetbrowser, Betriebssystem oder Uhrzeit des Seitenaufrufs. Die Erfassung dieser Daten erfolgt automatisch, sobald Du unsere Website aufrufst.
                            Bei jedem Zugriff eines Nutzers auf die Website von {{ config('dunkomatic.title')}} werden Daten über diesen Vorgang anonymisiert gespeichert und verarbeitet.
                            Im Einzelnen werden über einen Zugriff/Abruf folgende Daten gespeichert:
                            <ul>
                            <li>Datum und Uhrzeit des Zugriffs</li>
                            <li>genutzte Gerätetechnik</li>
                            <li>IP-Adresse des Endgeräts</li>
                            </ul>
                            Diese Daten werden zum Nachhalten von Änderungen an den Daten für 1 Woche gespeichert und dann automatisch gelöscht. Es werden keine Nutzerprofile erstellt.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Cookies</h5>
                        <p class="card-text">
                            Im Rahmen der Anwendung setzen wir Cookies ein. Cookies sind kleine Textdateien, die Dein Browser automatisch erstellt und auf Deinem Endgerät (Laptop, Tablet, Smartphone, PC, o.ä.) speichert, wenn Du unsere Seite besuchst. Cookies richten auf Ihrem Endgerät keinen Schaden an, enthalten keine Viren oder sonstige Schadsoftware. In einem Cookie werden Informationen abgelegt, die sich jeweils im Zusammenhang mit dem spezifisch eingesetzten Endgerät ergeben. Dies bedeutet jedoch nicht, dass wir dadurch unmittelbar Kenntnis von Ihrer Identität erhalten.
                        </p>
                        <p class="card-text">
                            Cookies dienen dazu, die Nutzung der Anwendung zu ermöglichen und unser Angebot nutzerfreundlicher, effektiver und sicherer zu gestalten.
                        </p>
                        <p class="card-text">
                            Die meisten Browser akzeptieren Cookies automatisch. Möchtst Du dies nicht, kannst Du Deinen Browser jedoch so konfigurieren, dass keine Cookies auf Deinem Endgerät gespeichert werden oder stets ein Hinweis erscheint, bevor ein neues Cookie angelegt wird. Falls Du Cookies deaktivierst, kannst Du eventuell nicht alle Funktionen der Anwendung nutzen.
                            Bei den einzelnen Cookies ist jeweils der Name des Cookies, der Zweck, den das Cookie erfüllen soll, ein eventueller Zugriff Dritter auf das Cookie sowie die Funktionsdauer angegeben bzw. nach welchem Zeitraum ein Cookie gelöscht wird.
                        </p>
                        <p class="card-text">
                            Die Cookies, die wir in der Anwendung einsetzen, sind unbedingt erforderlich. Das bedeutet, dass Du ohne diese Cookies auf Deinem Endgerät unser Angebot nicht nutzen kannst. Durch solche Cookies werden bestimmte Funktionalitäten bereitgestellt, die wir benötigen, um unser Angebot zu betreiben, insbesondere, um einen von Ihnen als Nutzer unseres Angebots ausdrücklich gewünschten Dienst zur Verfügung stellen zu können.
                        </p>
                        <table  width="100%" class="table table-bordered table-condensed table-sm">
                            <thead class="thead-dark">
                                <tr>
                                <th scope="col">Name des Cookies</th>
                                <th scope="col">Zweck</th>
                                <th scope="col">Funktionsdauer</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>XSRF-TOKEN</td>
                                    <td>Um Betrug (konkret: Cross-Site-Request-Forgery/CSRF) zu verhindern.</td>
                                    <td>2 Stunden nach Beginn der Sitzung</td>
                                </tr>
                                <tr>
                                    <td>io</td>
                                    <td>Für Broadcasts vom Server.</td>
                                    <td>2 Stunden nach Beginn der Sitzung</td>
                                </tr>
                                <tr>
                                    <td>dunkomatic_next_session</td>
                                    <td>Dieses Cookie bewahrt den Status der Sitzung über Seitenanfragen hinweg.</td>
                                    <td>1 Sitzung</td>
                                </tr>
                                <tr>
                                    <td>dunkomatic_next_cookie_consent</td>
                                    <td>Um Betrug (konkret: Cross-Site-Request-Forgery/CSRF) zu verhindern.</td>
                                    <td>1 Jahr</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-body">
                        <p class="card-text">
                            Diese durch Cookies verarbeiteten Daten sind für die genannten Zwecke zur Wahrung unserer sich daraus ergebenden berechtigten Interessen sowie der Dritter nach Art. 6 Abs. 1 S. 1 lit. f DSGVO erforderlich.
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Links zu Webseiten anderer Anbieter</h5>
                        <p class="card-text">
                            {{config('dunkomatic.title')}} enthält Links zu Webseiten anderer Anbieter (HBV, Vereinsseiten, etc.). Wir haben keinen Einfluss darauf, dass diese Anbieter die Datenschutzbestimmungen einhalten
                        </p>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Kontaktdaten des Verantwortlichen</h5>
                        <p class="card-text">
                            Jochen Kappel,
                            Friedrichstraße 46,
                            64521 Groß-Gerau
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>


@stop
