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
                                <h5>{{ $club->club_no }} - {{ $club->name }}</h5>
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
            <div class="card-group">
                <!-- card MEMBERS -->
                <div class="col-md">
                    <div class="card card-outline card-dark p-2">
                        <x-card-header title="{{ trans_choice('role.member', count($memberships )) }}" icon="fas fa-user-tie"  :count="count($memberships)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $memberships as $ms )
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
                            @empty
                                <div class="info-box">
                                    <span class="info-box-icon bg-warning text-md p-2">🙁</span>
                                    <div class="info-box-content">
                                        <span class="info-box-text"><a href="#"> {{__('no entries found')}}</a></span>
                                    </div>
                                </div>
                            @endforelse
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
            </div>
            <!-- card TEAMS -->
            <div class="col-md">
                <div class="card card-outline card-dark p-2">
                    <x-card-header title="{{trans_choice('team.team', 2)}}" icon="fas fa-users"  :count="count($teams)" :showtools="false"/>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @forelse ( $teams->sortBy('league.shortname') as $t )
                            <div class="info-box">
                                <span class="info-box-icon @if ( isset($t->league->region) ? $t->league->region->is_top_level : false ) bg-indigo @else bg-gray @endif text-xs p-1"><i class="">{{ $t->namedesc}}</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $t->coach_name }}</span>
                                    <span class="info-box-text"><i class="fas fa-phone"></i><a href="tel:{{ $t->coach_phone1 }}" target="_blank"> {{ $t->coach_phone1}}</a></span>
                                    <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $t->coach_email }}" target="_blank"> {{ $t->coach_email }}</a></span>
                                </div>
                            </div>
                        @empty
                            <div class="info-box">
                                <span class="info-box-icon bg-warning text-md p-2">🙁</span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><a href="#"> {{__('no entries found')}}</a></span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
            <!-- card GYMS -->
            <div class="col-md">
                <div class="card card-outline card-dark p-2">
                    <x-card-header title="{{trans_choice('gym.gym', 2)}}" icon="fas fa-building"  :count="count($gyms)" :showtools="false"/>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @forelse ( $gyms->sortBy('gym_no') as $g )
                            <div class="info-box">
                                <span class="info-box-icon bg-gray text-md p-2"><i class="">{{ $g->gym_no }}</i></span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $g->name }}</span>
                                    <span class="info-box-text">
                                        <i class="fas fa-map-marked-alt"></i><a href="{{ config('dunkomatic.maps_uri') }}{{ urlencode($g->address) }}" target="_blank"> {{ $g->address }}</a>
                                    </span>

                                </div>
                            </div>
                        @empty
                            <div class="info-box">
                                <span class="info-box-icon bg-warning text-md p-2">🙁</span>
                                <div class="info-box-content">
                                    <span class="info-box-text"><a href="#"> {{__('no entries found')}}</a></span>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')

@stop
