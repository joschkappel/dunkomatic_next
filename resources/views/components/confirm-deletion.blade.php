<div>
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-md" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <h3 class="col-12 modal-title text-center">{{ $modalTitle }}</h3>
                    <button type="button" class="close" id="frmClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>

                <!--Body-->
                <form class="form-horizontal" id="{{ $modalId }}_Form" action="" method="POST">
                    @csrf
                    @method( $modalMethod )

                    <div class="modal-body">
                        <h4 class="text-left text-dark">
                            <p>{{ $deleteType }}
                                <span id="{{ $modalId }}_Instance"></span>
                            </p>
                        </h4>
                        </h5 class="text-left text-info">
                        <p>
                            <span class="text-info" id="{{ $modalId }}_Info"></span>
                        </p>
                        </h5>
                        <div class="alert alert-danger" role="alert">
                            {{ $modalConfirm }}
                        </div>

                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-primary"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>

            </div>
            <!--/.Content-->
        </div>
    </div>
</div>
