<x-modal modalId="modalRegisterTeam" modalTitle="{{ __('league.action.register') }}"  modalMethod="PUT">
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

        $('#modalRegisterTeam').on('show.bs.modal', function (e) {

            $(".js-team-single").select2({
                placeholder: "{{ __('team.action.select')}}...",
                width: '100%',
                allowClear: false,
                minimumResultsForSearch: 5,
                ajax: {
                        url:  $('#modalRegisterTeam').data("urlsb2"),
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

    });
</script>
@endpush
