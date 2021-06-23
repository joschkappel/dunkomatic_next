<div class="modal fade right" id="modalMembershipMod" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-info">
                <p id="modmemrole" class="heading">@lang('club.title.membership.mod')</p>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form id="frmModMembership" class="form-horizontal" action="" method="POST">
                        @method('PUT')
                        @csrf
                        <input type="hidden" id="hidDelUrl" name="hidDelUrl" value="">
                        <div class="card-body">
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

                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar"
                                aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-info">{{ __('Submit') }}</button>
                                <button id="btnDelMembership" class="btn btn-danger">{{ __('Delete') }}</a>
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
