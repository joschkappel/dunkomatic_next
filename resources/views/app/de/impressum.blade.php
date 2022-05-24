@extends('layouts.page')

@section('content')
<x-card-list cardTitle="Impressum" >
<div>
    <div class="card-columns">
        <div class="card">
          <img class="card-img-top" src="{{ asset('img/pexels-court.jpg') }}" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">Über...</h5>
            <p class="card-text"> <nobr>{{config('dunkomatic.title')}}</nobr>  ist eine Anwendung zur Verwaltung von Basketball-Ligen.</p>
          </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-developing.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Kontaktdaten...</h5>
              <p class="card-text">Jochen Kappel</p>
              <p class="card-text">Friedrichstraße 46</p>
              <p class="card-text">64521 Groß-Gerau</p>
              <p class="card-text"><small class="text-muted"><a href="mailto:jkappel@onlinehome.de">eMail <i class="fas fa-at"></i> jochen</i></a></small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-contactus.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Bei Fragen...</h5>
              <p class="card-text">Zu Fragen zum fachlichen Inhalt wende Dich an deine:n zuständige:n HBV Bezirksleiter:in</p>
              <p class="card-text">Bei Problemen und für Verbesserungsvorschlägen zu <nobr>{{config('dunkomatic.title')}} sende bitte eine eMail an <a href="mailto:dmatic.master@gmail.com"> den webmaster</a></p>
              <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-datacenter.jpg') }}" alt="Hosted By">
            <div class="card-body">
              <h5 class="card-title">Hosting durch...</h5>
              <p class="card-text"><a href="https://www.netcup.de" target="_blank" rel="noreferrer">Netcup</a></p>
              <p class="card-text"><small class="text-muted">im Rechenzentrum Nürnberg</small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-software.jpg') }}" alt="OSS">
            <div class="card-body">
              <h5 class="card-title">Open Source Software durch und durch...</h5>
              <p class="card-text">Den  <nobr>{{config('dunkomatic.title')}}</nobr>  Quellcode findest du auf <a href="https://github.com/joschkappel/dunkomatic_next" target="_blank" rel="noreferrer">github</a></p>
              <p class="card-text">Folgende Komponenten werden verwendet:</p>
              <ul class="list-group">
                <li class="list-group-item"><a href="https://laravel.com/" target="_blank" rel="noreferrer">Laravel framework</a></li>
                <li class="list-group-item"><a href="https://laradock.io/" target="_blank" rel="noreferrer">laradock</a></li>
                <li class="list-group-item"><a href="https://www.php.net/" target="_blank" rel="noreferrer">php 8.x</a></li>
                <li class="list-group-item"><a href="https://www.docker.com/" target="_blank" rel="noreferrer">docker</a></li>
                <li class="list-group-item"><a href="https://mariadb.org/" target="_blank" rel="noreferrer">mariadb</a></li>
                <li class="list-group-item"><a href="https://redis.io/" target="_blank" rel="noreferrer">redis</a></li>
                <li class="list-group-item"><a href="https://www.nginx.com/" target="_blank" rel="noreferrer">nginx</a></li>
              </ul>
            </div>
        </div>
    </div>
</div>
</x-card-list>

@stop

