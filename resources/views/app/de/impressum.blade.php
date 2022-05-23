@extends('layouts.page')

@section('content')
<x-card-list cardTitle="Impressum" >
<div>
    <div class="card-columns">
        <div class="card">
          <img class="card-img-top" src="{{ asset('img/pexels-court.jpg') }}" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Über...</h5>
            <p class="card-text">Dunkomatic ist eine Anwendung zur Verwaltung von Basketball-Ligen.</p>
          </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-developing.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Entworfen, entwickelt un betrieben von ...</h5>
              <p class="card-text">Jochen Kappel</p>
              <p class="card-text">Friedrichstraße 46</p>
              <p class="card-text">64521 Groß-Gerau</p>
              <p class="card-text"><small class="text-muted">jkappel@onlinehome.de - issues bugs suggestions on github...</small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-contactus.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Kontakt...</h5>
              <p class="card-text">Zu Fragen zum fachlichen Inhalt wende Dich an den zuständigen HBV Bezirksleiter</p>
              <p class="card-text">Zu Problemen und Verbesserungsvorschlägen zu DunkOMatic: webmaster</p>
              <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-datacenter.jpg') }}" alt="Hosted By">
            <div class="card-body">
              <h5 class="card-title">Hosting durch...</h5>
              <p class="card-text">Netcup</p>
              <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-software.jpg') }}" alt="OSS">
            <div class="card-body">
              <h5 class="card-title">Open Source Software Base...</h5>
              <p class="card-text">Driven by the laravel framework. Docker, laradock, php, laravel, mariadb, redis, nginx </p>
              <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
    </div>
</div>
</x-card-list>

@stop

