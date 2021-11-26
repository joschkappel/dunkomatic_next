<x-modal modalId="modalAddMembership" modalTitle="{{ __('club.title.membership.add') }}">
        <div class="form-group row">
            <div class="col-sm-8">
            <div class="input-group mb-3">
                <select class='js-role-single js-states form-control select2 @error('selRole') is-invalid @enderror' id='selRole'
                    name='selRole'></select>
                @error('selRole')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                </div>
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-8">
                <input type="text" class="form-control @error('function') is-invalid @enderror"
                    id="function" name="function" placeholder="@lang('role.function')"
                    value="{{ old('function') }}">
                @error('function')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="form-group row">
            <div class="col-sm-8">
                <input type="text"
                    class="form-control @error('email') is-invalid @enderror"
                    id="email" name="email" placeholder="@lang('role.email1')"
                    value="{{ old('email') }}"></input>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
</x-modal>
<!--Modal: modalRelatedContent-->
@push('js')

    <script>
        $(function() {
            $(".js-role-single").select2({
                placeholder: "@lang('role.action.select')...",
                width: '100%',
                multiple: false,
                allowClear: false,
                ajax: {
                    url: "{{ route('role.index') }}",
                    type: "POST",
                    delay: 250,
                    dataType: "json",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "scope": $('#entitytype').val()
                    },
                    processResults: function(response) {
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
