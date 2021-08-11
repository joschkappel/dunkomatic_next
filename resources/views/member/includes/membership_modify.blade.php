<x-modal modalId="modalMembershipMod" modalTitle="{{ __('club.title.membership.mod') }}" modalMethod="PUT">

    <x-slot name="addbuttons">
        <button id="btnDelMembership" class="btn btn-danger">{{ __('Delete') }}</a>
    </x-slot>
    
    <input type="hidden" id="hidDelUrl" name="hidDelUrl" value="">
    <div class="form-group row">
        <div class="col-sm-8">
            <input type="text" class="form-control @error('function') is-invalid @enderror"
                id="modmemfunction" name="function" placeholder="@lang('role.function')"
                value="">
            @error('function')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="form-group row">
        <div class="col-sm-8">
            <input type="text"
                class="form-control @error('email', 'err_member') is-invalid @enderror"
                id="modmememail" name="email" placeholder="@lang('role.email1')"
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
            $("#btnDelMembership").click(function() {
                var url = $("#hidDelUrl").val();
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    dataType: "json",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        $('#modalMembershipMod').modal('hide');
                        location.reload();
                    },
                });
            });
        })
    </script>
@endpush
