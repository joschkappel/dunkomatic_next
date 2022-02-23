@extends('layouts.page')

@section('content')
<x-card-list cardTitle="Impressum" >
<div>
    <div class="card-columns">
        <div class="card">
          <img class="card-img-top" src="{{ asset('img/pexels-court.jpg') }}" alt="Card image cap">
          <div class="card-body">
            <h5 class="card-title">About...</h5>
            <p class="card-text">Dunkomatic is an application which supports adminstaration of basketball leagues.</p>
          </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-developing.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Designed, Developed and Operated by...</h5>
              <p class="card-text">Jochen Kappel</p>
              <p class="card-text">Friedrichstraße 46</p>
              <p class="card-text">64521 Groß-Gerau</p>
              <p class="card-text"><small class="text-muted">jkappel@onlinehome.de - issues bugs suggestions on github...</small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-contactus.jpg') }}" alt="Card image cap">
            <div class="card-body">
              <h5 class="card-title">Contacts...</h5>
              <p class="card-text">For questions on the content pls contact: HBV Bezirisleiter</p>
              <p class="card-text"><small class="text-muted"></small></p>
            </div>
        </div>
        <div class="card">
            <img class="card-img-top" src="{{ asset('img/pexels-datacenter.jpg') }}" alt="Hosted By">
            <div class="card-body">
              <h5 class="card-title">Hosted by...</h5>
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

