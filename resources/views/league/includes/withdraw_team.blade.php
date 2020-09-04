<div class="modal fade right" id="modalWithdrawTeam" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">@lang('league.action.withdraw', ['league'=>$league->shortname])</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" action="{{ route('league.team.withdraw', ['league'=>$league->id]) }}" method="POST">
                        @csrf
                        @method('delete')
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="selTeam" class="col-sm-4 col-form-label">{{ trans_choice('team.team',1)}}</label>
                                <div class="col-sm-6">
                                  <select class='js-example-placeholder-single js-states form-control select2' id='selTeam' name='team_id'></select>
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

      $(".js-example-placeholder-single").select2({
          placeholder: "{{ __('team.action.select')}}...",
          allowClear: false,
          minimumResultsForSearch: -1,
          ajax: {
                  url: "{{ route('league.team.sb', $league)}}",
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
