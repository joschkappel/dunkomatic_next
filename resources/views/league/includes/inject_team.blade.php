<div class="modal fade right" id="modalInjectTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">@lang('league.action.inject', ['league'=>$league->shortname])</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('league.team.inject', ['league'=>$league->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                          <div class="form-group row">
                              <div class="col-sm-8">
                              <div class="input-group mb-3">
                                <select class='js-freechar-single js-states form-control select2' id='selChar' name='league_no'></select>
                              </div>
                              </div>
                          </div>
                          <div class="form-group row">
                              <div class="col-sm-8">
                              <div class="input-group mb-3">
                                <select class='js-team-single js-states form-control select2' id='selFreeTeam' name='team_id'></select>
                              </div>
                              </div>
                          </div>

                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">{{__('Submit')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--/.Content-->
    </div>
</div>
<!--Modal: modalRelatedContent-->
@push('js')

<script>
    $(function() {

      $(".js-freechar-single").select2({
          placeholder: "@lang('league.sb_freechar')...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('league.sb_freechar', $league->id)}}",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: false
                }
      });
      $(".js-team-single").select2({
          placeholder: "{{ __('club.action.select')}}...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: 5,
          ajax: {
                  url: "{{ route('team.free.sb', ['league' => $league->id])}}",
                  type: "get",
                  delay: 250,
                  processResults: function (response) {
                    return {
                      results: response
                    };
                  },
                  cache: true
                }
      });

    });
</script>
@endpush
