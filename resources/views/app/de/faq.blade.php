@extends('layouts.page')

@section('content')
<x-card-list cardTitle="FAQs" >
<div>
    <div class="card-group">
        <div class="card card-outline card-dark">
          <x-card-header title="Als Bezirksleiter..." count=2 />
          <div class="card-body">
            <div class="card">
                <x-card-header title="möchte ich einen neuen Benutzer zulassen" />
                <div class="card-body">
                    <p class="card-text"></p>
                </div>
            </div>
            <div class="card">
                <x-card-header title="möchte ich meinen Bezirk konfigurieren" />
                <div class="card-body">
                    <p class="card-text"></p>
                </div>
            </div>
          </div>
        </div>

    </div>
</div>
</x-card-list>

@stop

