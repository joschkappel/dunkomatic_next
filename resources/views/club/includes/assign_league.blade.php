<x-modal modalId="modalAssignLeague" modalTitle="{{ __('team.title.assign.league') }}" modalMethod="PUT" >
    <input type="hidden" name="team_id" id="team_id" value=""  />
    <input type="hidden" name="club_id" id="club_id" value=""  />
    <div class="form-group row">
        <label for="selLeague" class="col-sm-4 col-form-label">{{ trans_choice('league.league',1)}}</label>
        <div class="col-sm-6">
        <div class="input-group mb-3">
            <select class='js-league-single js-states form-control select2' id='selLeague' name='league_id'></select>
        </div>
        </div>
    </div>
</x-modal>

@push('js')
        <script>
            $(function() {
                $("#selLeague").select2({
                    placeholder: "@lang('league.action.select')...",
                    width: '100%',
                    allowClear: false,
                    minimumResultsForSearch: -1,
                    ajax: {
                            url: "{{ route('club.sb.league',['club' => $club])}}",
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


