<x-modal modalId="modalAssignClub" modalTitle="{{ __('club.action.assign') }}">
        <input type="hidden" name="modalAssignClub_region_id" id="modalAssignClub_region_id" value=""  />
        <div class="form-group row">
            <label for="selClub" class="col-sm-4 col-form-label">{{ trans_choice('club.club',1)}}</label>
            <div class="col-sm-6">
                <select class='js-club-single js-states form-control select2' id='selClub' name='club_id'></select>
            </div>
        </div>
</x-modal>

@push('js')

<script>
    $(document).on("show.bs.modal", "#modalAssignClub", function (e) {
        var url = "{{ route('club.sb.region', ['region'=>':region:'])}}";
      url = url.replace(':region:', $('#modalAssignClub_region_id').val() )

      $(".js-club-single").select2({
          placeholder: "{{ __('club.action.select')}}...",
          width: '100%',
          allowClear: false,
          minimumResultsForSearch: 5,
          ajax: {
                  url: url,
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
