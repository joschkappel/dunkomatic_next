@extends('layouts.page')

@push('css')
<style>
.chart-wrapper {
  border: 1px solid gray;
  width: 75%;
}
</style>
@endpush

@section('content_header')
<div class="container-fluid">
    <div class="row ">
      <div class="col-sm">
              <!-- small card REGION -->
              <div class="small-box bg-primary">
                  <div class="inner">
                      <div class="row">
                        <input type="hidden" id="entitytype" value="App\Models\Region">
                          <div class="col-sm-6 pd-2">
                              <h3>{{ $region->code }}</h3>
                              <h5>{{ $region->name }}</h5>
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
            <div class="col-sm ">
                <div class="info-box"> 
                    <span class="info-box-icon bg-info"><i class="fas fa-basketball-ball"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">{{ __('region.clubs.count',['count' => $region->clubs_count  ]) }}</span>
                    </div>                
                </div>
                <div class="info-box"> 
                    <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">{{ __('region.gyms.count',['count' => $region->gyms_count  ]) }}</span>
                    </div>                  
                </div>
            </div> 
            <div class="col-sm ">
                <div class="info-box"> 
                    <span class="info-box-icon bg-info"><i class="fas fa-trophy"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">{{ __('region.leagues.count',['count' => $region->leagues_count  ]) }}</span>
                    </div>                
                </div>
                <div class="info-box"> 
                    <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text text-lg">{{ __('region.teams.count',['count' => $region->teams_count  ]) }}</span>
                    </div>                

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
    </div>
    <div class="col-sm-6 pd-2">
        <!-- card LEAGUE ANALYSIS -->
        <div class="card card-outline card-info collapsed-card">
          <div class="card-header">
            <h4 class="card-title"><i class="fas fa-trophy"></i> @lang('league statistics') </h4>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row m-5">
              <div class="chart-wrapper">
                <canvas id="leaguestatechart"></canvas>
              </div>
            </div>
            <div class="row m-5">
              <div class="chart-wrapper">
                <canvas id="leaguesociochart"></canvas>
              </div>
            </div>
          </div>
          <!-- /.card-body -->
          <!-- /.card-footer -->
        </div>
        <!-- /.card -->
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 pd-2">
          <!-- card CLUB ANALYSIS -->
          <div class="card card-outline card-info ">
            <div class="card-header">
              <h4 class="card-title"><i class="fas fa-basketball-ball"></i> @lang('club statistics')</h4>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
              </div>
              <!-- /.card-tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="row m-5">
                <div class="chart-wrapper">
                  <canvas id="clubteamchart"></canvas>
                </div>
              </div>
              <div class="row m-5">
                <div class="chart-wrapper">
                  <canvas id="clubmemberchart"></canvas>
              </div>  
              </div>
            </div>
            <!-- /.card-body -->
            <!-- /.card-footer -->
          </div>
          <!-- /.card -->
      </div>
    </div>
  </div>  
  <!-- all modals here -->
  <x-confirm-deletion modalId="modalDeleteRegion" modalTitle="{{ __('region.title.delete')}}" modalConfirm="{{ __('region.confirm.delete') }}" deleteType="{{ trans_choice('region.region',1) }}" />
  @include('member/includes/membership_add')
  @include('member/includes/membership_modify')
  <x-confirm-deletion modalId="modalDeleteMember" modalTitle="{{ __('role.title.delete')}}" modalConfirm="{{ __('role.confirm.delete') }}" deleteType="{{ __('role.member') }}" />                
  <!-- all modals above -->
</div>
@endsection

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

       var lsc = document.getElementById('leaguestatechart').getContext('2d');
       var leaguestatechart = new Chart(lsc, {
            type: 'pie',
            data: { datasets: [{ data: [], }] },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne9' }, },
              title: { 
                display: true,
                text: 'Leagues by Status'
              },
            }
        });

       var lsoc = document.getElementById('leaguesociochart').getContext('2d');   
       var leaguesociochart = new Chart(lsoc, {
          type: "pie",
          data: { },
          options: {
            legend: {
              labels: {
                generateLabels: function(context) {
                  // Get the default label list
                  var original = Chart.defaults.pie.legend.labels.generateLabels;
                  var labels = original.call(this, context);

                  // Build an array of colors used in the datasets of the chart
                  var datasetColors = context.chart.data.datasets.map(function(e) {
                    return e.backgroundColor;
                  });
                  datasetColors = datasetColors.flat();

                  // Modify the color and hide state of each label
                  labels.forEach(label => {
                    // There are twice as many labels as there are datasets. This converts the label index into the corresponding dataset index
                    label.datasetIndex = (label.index - label.index % 3) / 3;

                    // The hidden state must match the dataset's hidden state
                    label.hidden = !context.chart.isDatasetVisible(label.datasetIndex);

                    // Change the color to match the dataset
                    label.fillStyle = datasetColors[label.index];
                  });

                  return labels;
                }
              },
              onClick: function(mouseEvent, legendItem) {
                // toggle the visibility of the dataset from what it currently is
                this.chart.getDatasetMeta(
                  legendItem.datasetIndex
                ).hidden = this.chart.isDatasetVisible(legendItem.datasetIndex);
                this.chart.update();
              }
            },
            tooltips: {
              callbacks: {
                label: function(tooltipItem, data) {
                  var labelIndex = (tooltipItem.datasetIndex * 3) + tooltipItem.index;
                  return data.labels[labelIndex] + ": "+ data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                }
              }
            },
            title: { 
              display: true,
              text: 'Leagues by Age and Gender'
            },
          }
        });


       var ctc = document.getElementById('clubteamchart').getContext('2d');
       var clubteamchart = new Chart(ctc, {
            type: 'bar',
            data: { datasets: [{ data: [], }] },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne9' }, },
              responsive: true,
            
              title: { 
                display: true,
                text: 'Clubs by team'
              },
              scales: {
                y: { beginAtZero: true, stacked: true },
                x: { stacked: true }
              },
            }
        });

       var cmc = document.getElementById('clubmemberchart').getContext('2d');
       var clubmemberchart = new Chart(cmc, {
            type: 'bar',
            data: { },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne9' }, },
              responsive: true,

              title: { 
                display: true,
                text: 'Clubs by members'
              },
              scales: {
                y: { beginAtZero: true, stacked: true },
                x: { stacked: true }
              },
            }
        });        

       function load_chart(chart, route) {
            $.ajax({
                type: 'GET',
                url: route,
                success: function(response) {
                    chart.data.labels = response['labels'];
                    chart.data.datasets = response['datasets'];

                    chart.update();
                },
            });
        };

      load_chart( leaguestatechart, '{{ route('region.league.state.chart', ['region' => $region->id]) }}' );
      load_chart( leaguesociochart, '{{ route('region.league.socio.chart', ['region' => $region->id]) }}' );
      load_chart( clubteamchart, '{{ route('region.club.team.chart', ['region' => $region->id]) }}' );
      load_chart( clubmemberchart, '{{ route('region.club.member.chart', ['region' => $region->id]) }}' );


  });

</script>
@stop
