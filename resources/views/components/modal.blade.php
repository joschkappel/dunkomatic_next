<div>
    <div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-md" role="document">
            <!--Content-->
            <div class="modal-content ">
                <!--Header-->
                <div class="modal-header bg-secondary">
                    <h5 class="modal-title">{{ $modalTitle }}</h5>
                    <button type="button" class="close" id="frmClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!--Body-->
                <form class="form-horizontal" id="{{ $modalId }}_Form" action="" method="POST">
                    @csrf
                    @method( $modalMethod )
                    <div class="modal-body">
                        {{ $slot }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-dismiss="modal">{{ __('Close') }}</button>
                        {{ $addbuttons ?? "" }}
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
            <!--/.Content-->
        </div>
    </div>
</div>