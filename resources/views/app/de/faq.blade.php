@extends('layouts.page')

@section('content')
<x-card-list cardTitle="FAQs" >
<div>
    <div class="col-12">
        <div class="card card-outline card-dark collapsed-card">
          <x-card-header title="Als Bezirksleiter, ..." count=4 />
          <div class="card-body">
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich einen neuen Benutzer zulassen:" />
                <div class="card-body">
                    <p class="card-text">
                        Beschreibung kommt demnächst...
                    </p>
                    <a href="{{ route('admin.user.index.new',['region'=>$region, 'language'=>$language]) }}" class="btn btn-primary">@lang('auth.title.approve')</a>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich meinen Bezirk konfigurieren:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                        <a href="{{ route('region.edit',['region'=>$region, 'language'=>$language]) }}" class="btn btn-primary">@lang('region.title.edit',['region'=>$region->name])</a>
                    </p>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich eine Nachricht an Benutzer oder Funktionsträger schicken:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                        <a href="{{ route('message.create',['region'=>$region, 'language'=>$language, 'user'=>$user]) }}" class="btn btn-primary">@lang('message.title.new',['region'=>$region->name])</a>
                    </p>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich Rahmenterminpläne anlegen oder ändern:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                        <a href="{{ route('schedule.index',['region'=>$region, 'language'=>$language]) }}" class="btn btn-primary">@lang('schedule.title.list',['region'=>$region->name])</a>
                    </p>
                </div>
            </div>
          </div>
        </div>
        <div class="card card-outline card-dark collapsed-card">
            <x-card-header title="Als Abteilungsleiter, ..." count=5 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card">
                  <x-card-header title="möchte ich eine Mannschaft melden:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
              <div class="card card-outline card-info collapsed-card">
                  <x-card-header title="möchte ich Runden-Ziffern für meine Mannschaften wählen:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
              <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich Hallenzeiten für Heimspiele ändern:" />
                <div class="card-body">
                    <p class="card-text"></p>
                </div>
            </div>
              <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich meine Spielpläne downloaden:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                    </p>
                </div>
              </div>
              <div class="card card-outline card-info collapsed-card">
                  <x-card-header title="möchte ich einen Funktionsträger aufnehmen oder ändern:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
            </div>
        </div>
        <div class="card card-outline card-dark collapsed-card">
            <x-card-header title="Als Staffelleiter, ..." count=2 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich meine Spielpläne downloaden:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                    </p>
                </div>
              </div>
              <div class="card card-outline card-info collapsed-card">
                  <x-card-header title="möchte ich einen Funktionsträger aufnehmen oder ändern:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
            </div>
        </div>
        <div class="card card-outline card-dark collapsed-card">
            <x-card-header title="Als Schiedsrichterwart, ..." count=1 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card">
                <x-card-header title="möchte ich Schiedsrichter zuordnen:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                    </p>
                </div>
              </div>
            </div>
        </div>

    </div>
</div>
</x-card-list>

@stop

