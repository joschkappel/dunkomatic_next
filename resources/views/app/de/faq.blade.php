@extends('layouts.page')

@section('content')
<x-card-list cardTitle="FAQs" >
<div>
    <div class="col-12">
        <div class="card card-outline card-dark collapsed-card">
          <x-card-header title="Als Bezirksleiter:in, ..." count=4 />
          <div class="card-body">
            <div class="card card-outline card-info collapsed-card" id="faq_01">
                <x-card-header title="möchte ich eine:n neue:n Benutzer:in zulassen oder ablehnen:" />
                <div class="card-body">
                    @include('app.de.includes.faq_01')
                    <a href="{{ route('admin.user.index.new',['region'=>$region, 'language'=>$language]) }}" class="btn btn-primary">@lang('auth.title.approve')</a>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card" id="faq_02">
                <x-card-header title="möchte ich Zugriffsrechte vergeben:" />
                <div class="card-body">
                    @include('app.de.includes.faq_02')
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card" id="faq_03">
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
            <div class="card card-outline card-info collapsed-card" id="faq_04">
                <x-card-header title="möchte ich eine Nachricht an Benutzer oder Funktionsträger schicken:" />
                <div class="card-body">
                    @include('app.de.includes.faq_04')
                    <a href="{{ route('message.create',['region'=>$region, 'language'=>$language, 'user'=>$user]) }}" class="btn btn-primary">@lang('message.title.new',['region'=>$region->name])</a>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card" id="faq_05">
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
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-info collapsed-card" id="faq_06">
                        <x-card-header title="möchte ich Spielrunden verwalten:" />
                        <div class="card-body">
                            @include('app.de.includes.faq_06')
                            <a href="{{ route('league.index_mgmt',['region'=>$region, 'language'=>$language]) }}" class="btn btn-primary">@lang('league.title.list',['region'=>$region->name])</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-outline card-info collapsed-card" id="faq_07">
                <x-card-header title="möchte ich eine Runde mit manuellem Rahmenplan verwalten:" />
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
            <x-card-header title="Als Abteilungsleiter:in, ..." count=5 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card" id="faq_20">
                  <x-card-header title="möchte ich eine Mannschaft melden:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
              <div class="card card-outline card-info collapsed-card" id="faq_21">
                  <x-card-header title="möchte ich Runden-Ziffern für meine Mannschaften wählen:" />
                  <div class="card-body">
                      <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                      </p>
                  </div>
              </div>
              <div class="card card-outline card-info collapsed-card" id="faq_22">
                <x-card-header title="möchte ich Hallenzeiten für Heimspiele ändern:" />
                <div class="card-body">
                    <p class="card-text"></p>
                </div>
            </div>
              <div class="card card-outline card-info collapsed-card" id="faq_23">
                <x-card-header title="möchte ich meine Spielpläne downloaden:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                    </p>
                </div>
              </div>
              <div class="card card-outline card-info collapsed-card" id="faq_24">
                  <x-card-header title="möchte ich eine:n Funktionsträger:in aufnehmen oder ändern:" />
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
            <x-card-header title="Als Staffelleiter:in, ..." count=2 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card" id="faq_40">
                <x-card-header title="möchte ich meine Spielpläne downloaden:" />
                <div class="card-body">
                    <p class="card-text">
                        <p class="card-text">
                            Beschreibung kommt demnächst...
                        </p>
                    </p>
                </div>
              </div>
              <div class="card card-outline card-info collapsed-card" id="faq_41">
                  <x-card-header title="möchte ich eine:n Funktionsträger:in aufnehmen oder ändern:" />
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
            <x-card-header title="Als Schiedsrichterwart:in, ..." count=1 />
            <div class="card-body">
              <div class="card card-outline card-info collapsed-card" id="faq_60">
                <x-card-header title="möchte ich Schiedsrichter:innen zuordnen:" />
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

@endsection

