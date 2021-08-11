@extends('layouts.page')


@section('content_header')
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <!-- small card CLUB -->
              <div class="small-box bg-gray">
                  <div class="inner">
                      <div class="row">
                      <input type="hidden" id="entitytype" value="App\Models\Region">
                        <div class="col-sm-8 pd-2">
                            <h3>{{ $region->code }}</h3>
                            <h5>{{ $region->name }}</h5>
                        </div>
                        <div class="col-sm-4 pd-2">
                            <ul class="list-group">
                              <li class="list-group-item list-group-item-info py-0"> {{ $region->clubs_count }} CLubs </li>
                              <li class="list-group-item list-group-item-info py-0"> {{ $region->teams_count }} Teams </li>
                              <li class="list-group-item list-group-item-info py-0"> {{ $region->gyms_count }} Hallen </li>
                              <li class="list-group-item list-group-item-info py-0"> {{ $region->leagues_count }} Runden </li>
                            </ul>
                        </div>                        
                      </div>
                  </div>
                  <div class="icon">
                      <i class="fas fa-globe-europe"></i>
                  </div>
                  <a href="{{ route('region.edit',['language'=> app()->getLocale(),'region' => $region ]) }}" class="small-box-footer" dusk="btn-edit">
                      @lang('region.action.edit') <i class="fas fa-arrow-circle-right"></i>
                  </a>
                  @if ( ($region->clubs_count==0) and ($region->child_regions_count == 0) and ($region->leagues_count==0) )
                  <a id="deleteRegion" href="#" data-toggle="modal" data-target="#modalDeleteRegion" class="small-box-footer" dusk="btn-delete">
                      @lang('region.action.delete') <i class="fa fa-trash"></i>
                  </a>
                  @endif
              </div>
            </div>
    </div>
</div><!-- /.container-fluid -->
@stop

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 pd-2">

        <!-- card MEMBERS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-user-tie"></i> @lang('role.member')  <span class="badge badge-pill badge-info">{{ count($members) }}</span></h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            @foreach ($members as $member )
            <p><button type="button" id="deleteMember" class="btn btn-outline-danger btn-sm" data-member-id="{{ $member->id }}"
              data-member-name="{{ $member->name }}"
              data-region-sname="{{ $region->code }}" data-toggle="modal" data-target="#modalDeleteMember"><i class="fa fa-trash"></i></button>
            <a href="{{ route('member.edit',[ 'language'=>app()->getLocale(),'member' => $member ]) }}" class=" px-2">{{ $member->name }} <i class="fas fa-arrow-circle-right"></i></a>
            @if (! $member->is_user)
            <a href="{{ route('member.invite',[ 'member' => $member]) }}" type="button" class="btn btn-outline-primary btn-sm"><i class="far fa-paper-plane"></i></a>
            @endif
            <button type="button" id="addMembership" class="btn btn-outline-primary btn-sm" data-member-id="{{ $member->id }}"
              data-region-id="{{ $region->id }}" data-toggle="modal" data-target="#modalClubMembershipAdd"><i class="fas fa-user-tag"></i></button>
              @foreach ($member['memberships'] as $membership)
                @if (($membership->membership_type == 'App\Models\Region' ) and ($membership->membership_id == $region->id))
                <button type="button" id="modMembership" class="btn btn-outline-primary btn-sm" data-membership-id="{{ $membership->id }}" 
                data-function="{{ $membership->function }}" data-email="{{ $membership->email }}" data-role="{{ App\Enums\Role::getDescription($membership->role_id) }}" 
                data-toggle="modal" data-target="#modalRegionbMembershipMod">{{ App\Enums\Role::getDescription($membership->role_id) }}</button>
                @else
                <span class="badge badge-secondary">{{ App\Enums\Role::getDescription($membership->role_id) }}</span>
                @endif
              @endforeach
          </p>
            @endforeach

          </div>
          <!-- /.card-body -->
          <div class="card-footer">
            <a href="{{ route('membership.region.create',[ 'language'=>app()->getLocale(), 'region' => $region ]) }}" class="btn btn-primary" >
            <i class="fas fa-plus-circle"></i>  @lang('club.member.action.create')
            </a>
          </div>
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->

    <!-- all modals here -->
    <x-confirm-deletion modalId="modalDeleteRegion" modalTitle="{{ __('region.title.delete')}}" modalConfirm="{{ __('region.confirm.delete') }}" deleteType="{{ trans_choice('region.region',1) }}" />
    @include('member/includes/membership_add')
    @include('member/includes/membership_modify')
    <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete')}}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ __('role.member') }}" />                
    <!-- all modals above -->
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-sm-4">
            <div class="card border-secondary bg-secondary text-white">
                <img src="{{asset('img/'.config('dunkomatic.grafics.region', 'oops.jpg'))}}" class="card-img" alt="...">
                <div class="card-img-overlay">
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
  $(function() {
    $("#deleteRegion").click( function(){
       $('#modalDeleteRegion_Instance').html('{{ $region->name }}');
       var url = "{{ route('region.destroy', ['region'=>$region])}}";
       $('#modalDeleteRegion_Form').attr('action', url);
       $('#modalDeleteRegion').modal('show');
    });
    $("button#addMembership").click( function(){
       var url = "{{ route('membership.region.add', ['region'=>':regionid:', 'member'=>':memberid:'])}}";
       url = url.replace(':memberid:', $(this).data('member-id'));
       url = url.replace(':regionid:', $(this).data('region-id'));
       $('#modalAddMembership_Form').attr('action', url);
       $('#modalAddMembership').modal('show');
    });
    $("button#modMembership").click( function(){
       var url = "{{ route('membership.update', ['membership'=>':membershipid:'])}}";
       url = url.replace(':membershipid:', $(this).data('membership-id'));
       var url2 = "{{ route('membership.destroy', ['membership'=>':membershipid:'])}}";
       url2= url2.replace(':membershipid:', $(this).data('membership-id'));
       $('#hidDelUrl').val( url2);
       $('#modmemfunction').val($(this).data('function'));
       $('#modmememail').val($(this).data('email'));
       $('#modmemrole').val($(this).data('role'));
       $('#modalMembershipMod_Form').attr('action', url);
       $('#modalMembershipMod').modal('show');
    }); 
    $("button#deleteMember").click( function(){
       $('#modalDeleteMember_Instance').html($(this).data('member-name'));
       var url = "{{ route('membership.region.destroy', ['region'=>$region, 'member'=>':member:']) }}";
       url = url.replace(':member:', $(this).data('member-id'));
       $('#modalDeleteMember_Form').attr('action', url);
       $('#modalDeleteMember').modal('show');
    });    
  });

</script>
@stop
