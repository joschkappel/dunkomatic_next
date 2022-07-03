<div>
    <div class="modal fade" id="{{ $modalId }}" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" >
        <div class="modal-dialog modal-md" role="document">
            <!--Content-->
            <div class="modal-content ">
                <!--Header-->
                <div class="col-12 modal-header text-center">
                    <h3 class="modal-title">{{ $modalTitle }}</h3>
                    <button type="button" class="close" id="frmClose" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger"
                        data-dismiss="modal">{{ __('Close') }}</button>
                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
</div>
