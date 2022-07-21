@component('mail::message')

Hallo,<br>
Dein Verein hat noch Heimspiele ohne Spielbeginn!
@component('mail::panel')
Es sind nur noch <strong>{{ $days_to_go }} Tage</strong> in denen Du Termin, Spielbeginn und Halle Deiner Heimspiele anpassen kannst.<br>

Du kannst deine Heimspiele auf deiner Vereinsseite sehen und bearbeiten.<br>
Klappe dazu die Karte "Heimspiele" auf und drücke auf "Heimspiele erfassen".
@endcomponent

Mehr dazu hier:
@component('mail::button', ['url' => $url , 'color' => 'success'])
Hilfe
@endcomponent

Danke,<br>
{{ config('dunkomatic.title') }}

@endcomponent
