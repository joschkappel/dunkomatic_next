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
                    <x-card-header title="{{__('role.member')}}" icon="fas fa-user-tie"  :count="count($memberships)" :showtools="false"/>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $memberships as $ms )
                            <div class="info-box">
                                <span class="info-box-icon bg-primary text-md p-2">{{ __('role.'.$ms->role_id.'.short') }}</span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $ms->member->name }}</span>
                                    <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $ms->member->mobile }}" target="_blank"> {{ $ms->member->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $ms->member->phone }}" target="_blank"> {{ $ms->member->phone}}</a></span>
                                    <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $ms->member->email1 }}" target="_blank"> {{ $ms->member->email1 }}</a></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a class="btn btn-primary" href="{{ route('address.index_byrole', ['language'=>app()->getLocale(), 'region'=>$region, 'role'=> \App\Enums\Role::RegionLead ]) }}"><i class="fas fa-copy"></i> {{__('role.address.download')}}</a>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- card CLUBS -->
                <div class="card card-outline card-dark h-50">
                    <x-card-header title="{{ trans_choice('club.club', count($clubs))}}" icon="fas fa-basketball-ball"  :count="count($clubs)" :showtools="false"/>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $clubs as $c )
                            <div class="info-box">
                                <span class="info-box-icon bg-primary text-md p-2">{{ $c->clubs->first()->shortname }}</span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $c->name }}</span>
                                    <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $c->mobile }}" target="_blank"> {{ $c->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $c->phone }}" target="_blank"> {{ $c->phone}}</a></span>
                                    <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $c->email1 }}" target="_blank"> {{ $c->email1 }}</a></span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <a class="btn btn-primary" href="{{ route('address.index_byrole', ['language'=>app()->getLocale(), 'region'=>$region, 'role'=> \App\Enums\Role::ClubLead ]) }}"><i class="fas fa-copy"></i> {{__('role.address.download')}}</a>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <div class="col-sm-4">
                <!-- card LEAGUES -->
                <div class="card card-outline card-dark h-50">
                    <x-card-header title="{{ trans_choice('league.league', count($leagues))}}" icon="fas fa-trophy"  :count="count($leagues)" :showtools="false"/>
                    <!-- /.card-header -->
                    <div class="card-body overflow-auto">
                        @foreach ( $leagues as $l )
                            <div class="info-box">
                                <span class="info-box-icon bg-primary text-md p-2">{{ $l->leagues->first()->shortname }}</span>
                                <div class="info-box-content">
                                    <span class="info-box-number">{{ $l->name }}</span>
                                    <span class="info-box-text"><i class="fas fa-mobile"></i><a href="tel:{{ $l->mobile }}" target="_blank"> {{ $l->mobile}}</a> <i class="fas fa-phone"></i> <a href="tel:{{ $l->phone }}" target="_blank"> {{ $l->phone}}</a></span>
                                    <span class="info-box-text"><i class="fas fa-at"></i><a href="mailto:{{ $l->email1 }}" target="_blank"> {{ $l->email1 }}</a></span>
                                </div>
                            </div>
                        @endforeach
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
@stop

@section('js')

@stop
