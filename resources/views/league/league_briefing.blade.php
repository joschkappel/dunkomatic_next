@extends('layouts.page')


@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm">
                <!-- small card LEAGUE -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <div class="row">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $league->shortname }}</h3></h3>
                                <h5>{{ $league->name }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-trophy"></i>
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
                        <x-card-header title="{{__('role.member')}}" icon="fas fa-user-tie"  :count="count($memberships)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $memberships as $ms )
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary text-md p-2">{{ __('role.'.$ms->role_id.'.short') }}</span>
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $ms->member->name }}</span>
                                        <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                        <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
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
                        <x-card-header title="{{trans_choice('club.club', 2)}}" icon="fas fa-building"  :count="count($clubs)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $clubs->sortBy('shortname') as $c )
                                <div class="info-box">
                                    <span class="info-box-icon bg-gray text-md p-2"><i class="">{{ $c->shortname }}</i></span>
                                    <div class="info-box-content">
                                        @foreach ( $c->memberships as $ms)
                                            @if ( $ms->role_id == 1)
                                                <span class="info-box-number">{{ $ms->member->name }}</span>
                                                <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                                <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
                                            @endif
                                        @endforeach
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
                <!-- card TEAMS -->
                <div class="col-md">
                    <div class="card card-outline card-dark p-2">
                        <x-card-header title="{{trans_choice('team.team', 2)}}" icon="fas fa-users"  :count="count($teams)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $teams->sortBy('club.shortname') as $t )
                            <div class="info-box">
                                <span class="info-box-icon @if ( isset($t->league->region) ? $t->league->region->is_top_level : false ) bg-indigo @else bg-gray @endif text-md p-2"><i class="">{{ isset($t->club) ? $t->club->shortname : 'not registered'}}</i></span>
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
            </div>
        </div>
    </div>
@stop

@section('js')

@stop
