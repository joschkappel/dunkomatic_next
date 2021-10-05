@extends('layouts.page')


@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm">
                <!-- small card CLUB -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <div class="row">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $club->shortname }}</h3><a class="text-white" href="{{ $club->url }}" target="_blank"><i class="fas fa-external-link-alt fa-sm"></i></a></h3>
                                <h5>{{ $club->name }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-basketball-ball"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <!-- card MEMBERS -->
                <div class="card card-outline card-dark h-50">
                    <div class="card-header align-content-between">
                        <h4 class="card-title pt-2"><i class="fas fa-user-tie fa-lg"></i> @lang('role.member') <span
                                class="badge badge-pill badge-info">{{ count($memberships) }}</span></h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $memberships as $ms )
                            @if ( App\Enums\Role::coerce($ms->role_id)->is(App\Enums\Role::ClubLead) )
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary text-md p-2">{{ __('role.'.$ms->role_id.'.short') }}</span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $ms->member->name }}</span>
                                        <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                        <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @foreach ( $memberships as $ms )
                            @if ( App\Enums\Role::coerce($ms->role_id)->in([ App\Enums\Role::RegionLead, App\Enums\Role::RegionTeam ]) )
                                <div class="info-box">
                                    <span class="info-box-icon bg-indigo text-md">{{ __('role.'.$ms->role_id.'.short') }}</span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $ms->member->name }}</span>
                                        <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                        <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                        @foreach ( $memberships as $ms )
                        @if ( App\Enums\Role::coerce($ms->role_id)->in([ App\Enums\Role::RefereeLead, App\Enums\Role::GirlsLead, App\Enums\Role::LeagueLead, App\Enums\Role::JuniorsLead ]) )
                            <div class="info-box">
                                <span class="info-box-icon bg-purple text-md p-2">{{ __('role.'.$ms->role_id.'.short') }}</span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $ms->member->name }}</span>
                                    <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                    <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
                                </div>
                            </div>
                        @endif
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <div class="col-sm-4">
                <!-- card TEAMS -->
                <div class="card card-outline card-dark h-50">
                    <div class="card-header">
                        <h4 class="card-title mt-2"><i class="fas fa-users fa-lg"></i> {{ trans_choice('team.team', 2) }} <span
                                class="badge badge-pill badge-info">{{ count($teams) }}</span></h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $teams->sortBy('league.shortname') as $t )
                        <div class="info-box">
                            <span class="info-box-icon @if ( isset($t->league->region) ? $t->league->region->is_top_level : false ) bg-indigo @else bg-gray @endif text-md p-2"><i class="">{{ isset($t->league) ? $t->league->shortname : 'not registered'}}</i></span>
                            <div class="info-box-content">
                                <span class="info-box-number">{{ $t->coach_name }}</span>
                                <span class="info-box-text"><i class="fas fa-phone"></i><a href="tel:{{ $t->coach_phone1 }}" target="_blank"> {{ $t->coach_phone1}}</a></span>
                                <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $t->coach_email }}" target="_blank"> {{ $t->coach_email }}</a></span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <div class="col-sm-4">
                <!-- card GYMS -->
                <div class="card card-outline card-dark h-50">
                    <div class="card-header">
                        <h4 class="card-title mt-2"><i class="fas fa-building fa-lg"></i> {{ trans_choice('gym.gym', 2) }} <span
                                class="badge badge-pill badge-info">{{ count($gyms) }}</span></h4>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $gyms->sortBy('gym_no') as $g )
                        <div class="info-box">
                            <span class="info-box-icon bg-gray text-md p-2"><i class="">{{ $g->gym_no }}</i></span>
                            <div class="info-box-content">
                                <span class="info-box-number">{{ $g->name }}</span>
                                <span class="info-box-text">
                                    <i class="fas fa-map-marked-alt"></i><a href="{{ config('dunkomatic.maps_uri') }}{{ urlencode($g->address) }}" target="_blank"> {{ $g->address }}</a>
                                </span>

                            </div>
                        </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
@stop

@section('js')

@stop
