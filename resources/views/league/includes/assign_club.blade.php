<x-modal modalId="modalAssignClub" modalTitle="{{ __('club.action.assign', ['league'=>$league->shortname]) }}">                
        <input type="hidden" name="item_id" id="itemid" value=""  />
        <div class="form-group row">
            <label for="selClub" class="col-sm-4 col-form-label">{{ trans_choice('club.club',1)}}</label>
            <div class="col-sm-6">
                <select class='js-club-single js-states form-control select2' id='selClub' name='club_id'></select>
            </div>
        </div>
</x-modal>

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
