<div class="modal fade right" id="modalAddMembership" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p class="heading">@lang('club.title.membership.add')</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form id="addClubMembership" class="form-horizontal" action="" method="POST">
                        @method('POST')
                        @csrf
                        <div class="card-body">
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

                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">{{ __('Submit') }}</button>
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
            $(".js-role-single").select2({
                placeholder: "@lang('role.action.select')...",
                theme: 'bootstrap4',
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
