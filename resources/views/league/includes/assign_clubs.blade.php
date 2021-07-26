<div class="modal fade right" id="modalAssignClubs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">????</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form class="form-horizontal" id="frmAssignClubs" action="{{ route('league.assign-clubs', ['league'=>$league->id]) }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <input type="hidden" name="item_id" id="itemid" value=""  />
                            <div class="form-group row">
                                <label for="selClub" class="col-sm-4 col-form-label">{{ trans_choice('club.club',1)}}</label>
                                <div class="col-sm-6">
                                <div class="input-group mb-3">
                                  <select class='js-club-single js-states form-control select2' id='selClub' name='club_id'></select>
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

      $(".js-club-single").select2({
          placeholder: "{{ __('club.action.select')}}...",
          theme: 'bootstrap4',
          allowClear: false,
          minimumResultsForSearch: 5,
          ajax: {
                  url: "{{ route('club.sb.region', ['region'=>session('cur_region')->id])}}",
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
