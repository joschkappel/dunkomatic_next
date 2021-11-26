<x-modal modalId="modalInjectTeam" modalTitle="{{ __('league.action.inject', ['league'=>$league->shortname]) }}">
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

</x-modal>
<!--Modal: modalRelatedContent-->
@push('js')

<script>
    $(function() {

      $(".js-freechar-single").select2({
          placeholder: "@lang('league.sb_freechar')...",
          width: '100%',
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
          width: '100%',
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
