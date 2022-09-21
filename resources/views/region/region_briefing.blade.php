@extends('layouts.page')


@section('content_header')
    <div class="container-fluid">
        <div class="row ">
            <div class="col-sm">
                <!-- small card REGION -->
                <div class="small-box bg-primary">
                    <div class="inner">
                        <div class="row">
                            <div class="col-sm-6 pd-2">
                                <h3>{{ $region->code }}</h3></h3>
                                <h5>{{ $region->name }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="icon">
                        <i class="fas fa-globe-europe"></i>
                    </div>
                    <a href="#" data-toggle="modal"  class="small-box-footer" data-target="#modalDownloadZone">Zur Download Zone
                        <i class="fas fa-arrow-circle-right"></i></a>
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
                        <x-card-header title="{{ trans_choice('role.member', count($memberships))}}" icon="fas fa-user-tie"  :count="count($memberships)" :showtools="false"/>
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
                        <div class="card-footer">
                            <a class="btn btn-primary" href="{{ route('address.index_byrole', ['language'=>app()->getLocale(), 'region'=>$region, 'role'=> \App\Enums\Role::RegionLead ]) }}"><i class="fas fa-copy"></i> {{__('role.address.download')}}</a>
                        </div>
                    </div>
                </div>
                <!-- card CLUBS -->
                <div class="col-md">
                    <div class="card card-outline card-dark p-2">
                        <x-card-header title="{{ trans_choice('club.club', count($clubs))}}" icon="fas fa-basketball-ball"  :count="count($clubs)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $clubs as $c )
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary text-md p-2">{{ $c->shortname }}</span>
                                    @foreach( $c->members->where('pivot.role_id', \App\Enums\Role::ClubLead ) as $m)
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $m->name }}</span>
                                        <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $m->mobile }}" target="_blank"> {{ $m->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $m->phone }}" target="_blank"> {{ $m->phone}}</a></span>
                                        <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $m->email1 }}" target="_blank"> {{ $m->email1 }}</a></span>
                                    </div>
                                    @endforeach
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
                        <div class="card-footer">
                            <a class="btn btn-primary" href="{{ route('address.index_byrole', ['language'=>app()->getLocale(), 'region'=>$region, 'role'=> \App\Enums\Role::ClubLead ]) }}"><i class="fas fa-copy"></i> {{__('role.address.download')}}</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- card LEAGUES -->
                <div class="col-md">
                    <div class="card card-outline card-dark p-2">
                        <x-card-header title="{{ trans_choice('league.league', count($leagues))}}" icon="fas fa-trophy"  :count="count($leagues)" :showtools="false"/>
                        <!-- /.card-header -->
                        <div class="card-body overflow-auto">
                            @forelse ( $leagues as $l )
                                <div class="info-box">
                                    <span class="info-box-icon bg-primary text-md p-2">{{ $l->shortname }}</span>
                                    <div class="info-box-content">
                                        @foreach( $l->members->where('pivot.role_id', \App\Enums\Role::LeagueLead ) as $m)
                                            <span class="info-box-number">{{ $m->name }}</span>
                                            <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $m->mobile }}" target="_blank"> {{ $m->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $m->phone }}" target="_blank"> {{ $m->phone}}</a></span>
                                            <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $m->email1 }}" target="_blank"> {{ $m->email1 }}</a></span>
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
                        <div class="card-footer">
                            <a class="btn btn-primary" href="{{ route('address.index_byrole', ['language'=>app()->getLocale(), 'region'=>$region, 'role'=> \App\Enums\Role::LeagueLead ]) }}"><i class="fas fa-copy"></i> {{__('role.address.download')}}</a>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </div>
    <!-- all modals here -->
    @include('reports/includes/download_zone')
    <!-- all modals above -->
@stop

@section('js')

@stop
