@extends('layouts.page')

@push('css')
    <style>
    .chart-wrapper {
    border: 2px solid black;
    width: 100%;
    border-radius: 10px;
    background: whitesmoke;
    padding: 10px;
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
                    @can('update-regions')
                    <a href="{{ route('region.edit',['language'=> app()->getLocale(),'region' => $region ]) }}" class="small-box-footer" dusk="btn-edit">
                        @lang('region.action.edit') <i class="fas fa-arrow-circle-right"></i>
                    </a>
                    @endcan
                    @can('create-regions')
                    @if ( ($region->clubs_count==0) and ($region->child_regions_count == 0) and ($region->leagues_count==0) )
                    <a id="deleteRegion" href="#" data-toggle="modal" data-target="#modalDeleteRegion" class="small-box-footer" dusk="btn-delete">
                        @lang('region.action.delete') <i class="fa fa-trash"></i>
                    </a>
                    @endif
                    @endcan
                </div>
                </div>
                <div class="col-sm ">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-basketball-ball"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">{{ trans_choice('club.club',$region->clubs_count) }}</span>
                            <span class="info-box-number text-md"><a href="{{ route('club.index', ['language' => app()->getLocale(),'region'=>$region]); }}">{{ $region->clubs_count }}</a></span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><a href="{{ route('member.index', ['language' => app()->getLocale(), 'region'=>$region]); }}"><i class="fas fa-user-tie"></i></a></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">{{ __('role.member') }}</span>
                            <span class="info-box-number text-md"><a href="{{ route('member.index', ['language' => app()->getLocale(), 'region'=>$region]); }}">{{ $member_count }}</a></span>
                        </div>
                    </div>
                </div>
                <div class="col-sm ">
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-trophy"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">{{ trans_choice('league.league',$region->leagues_count) }}</span>
                            <span class="info-box-number text-md"><a href="{{ route('league.index', ['language' => app()->getLocale(), 'region'=>$region]); }}">{{ $region->leagues_count }}</a></span>
                        </div>
                    </div>
                    <div class="info-box">
                        <span class="info-box-icon bg-info"><i class="fas fa-running"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-lg">{{ trans_choice('game.game', $games_count) }}</span>
                            <span class="info-box-number text-md"><a href="{{ route('game.index', ['language' => app()->getLocale(),'region'=>$region]) }}">{{ $games_count }}</a></span>
                        </div>

                    </div>
                </div>
        </div>
    </div><!-- /.container-fluid -->
@stop

@section('content')
    <div class="container-fluid">
    <div class="row">
        <div class="col-md-6 pd-2">
            <!-- card MEMBERS -->
            <x-member-card :members="$members" :entity="$region" entity-class="App\Models\Region" />
            <!-- /.card -->
        </div>
        <div class="col-sm-6 pd-2">
                <!-- card REFEREES -->
                <div class="card card-outline card-dark collapsed-card" id="refereeCard">
                    <x-card-header title="{{ __('game.menu.referees')}}" icon="fas fa-stopwatch"  :count="$games_noref_count" />
                    <!-- /.card-header -->
                    <div class="card-body">

                        <div class="row m-1">
                            <div class="chart-wrapper">
                                <canvas id="missingrefereeschart"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        @can('update-games')
                        <a href="{{ route('game.index', ['language' => app()->getLocale(),'region'=>$region]) }}"
                            class="btn btn-primary float-right mr-2">
                            <i class="far fa-edit"></i> @lang('game.action.assign-referees')</a>
                        @endcan
                    </div>
                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 pd-2">
            <!-- card CLUB ANALYSIS -->
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="{{ __('region.chart.clubstats')}}" icon="fas fa-chart-line"  count="2" />
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
    <div class="row">
        <div class="col-sm-12 pd-2">
            <!-- card LEAGUE ANALYSIS -->
            <div class="card card-outline card-info collapsed-card">
                <x-card-header title="{{ __('region.chart.leaguestats')}}" icon="fas fa-chart-line"  count="2" />
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

       var mrc = document.getElementById('missingrefereeschart').getContext('2d');
       var missingrefereeschart = new Chart(mrc, {
            type: 'bar',
            data: { },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne3' }, },
              responsive: true,

              title: {
                display: true,
                text: '{{ __('region.chart.title.referees') }}'
              },
            }
        });

       var lsc = document.getElementById('leaguestatechart').getContext('2d');
       var leaguestatechart = new Chart(lsc, {
            type: 'bar',
            data: { datasets: [{ data: [], }] },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne4' }, },
              responsive: true,
              title: {
                display: true,
                text: '{{ __('region.chart.title.leaguestate') }}'
              },
              scales: {
                y: { beginAtZero: true, stacked: true },
                x: { stacked: true }
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
              text: '{{ __('region.chart.title.leaguesocio') }}'
            },
          }
        });


       var ctc = document.getElementById('clubteamchart').getContext('2d');
       var clubteamchart = new Chart(ctc, {
            type: 'bar',
            data: { datasets: [{ data: [], }] },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.Spectral4' }, },
              responsive: true,

              title: {
                display: true,
                text: '{{ __('region.chart.title.clubteams') }}'
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
            data: { datasets: [{ data: [], }] },
            options: {
              plugins: { colorschemes: { scheme: 'brewer.SetOne7' }, },
              responsive: true,
              title: {
                display: true,
                text: '{{ __('region.chart.title.clubmembers') }}'
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

      load_chart( missingrefereeschart, '{{ route('region.game.noreferee.chart', ['region' => $region->id]) }}' );

/*       cmc_canvas.onclick = function(e) {
        var slice = clubmemberchart.getElementAtEvent(e);
        if (!slice.length) return;
        alert('you clicked on '+slice[0]._model.label);
      }; */


  });

</script>
@stop
