<x-modal modalId="modalWithdrawTeam" modalTitle="{{ __('league.action.withdraw', ['league'=>$league->shortname]) }}" modalMethod="DELETE">
    <div class="form-group row">
        <div class="col-sm-8">
        <div class="input-group mb-3">
            <select class='js-example-placeholder-single js-states form-control select2' id='selTeam' name='team_id'></select>
        </div>
        </div>
    </div>
</x-modal>

@push('js')

<script>
    $(function() {

      $(".js-example-placeholder-single").select2({
          placeholder: "{{ __('team.action.select')}}...",
          width: '100%',
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
