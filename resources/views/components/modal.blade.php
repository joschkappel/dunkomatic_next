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
                <form class="form-horizontal" id="{{ $modalId }}_Form" action="" method="POST" >
                    @csrf
                    @method( $modalMethod )
                    <div class="modal-body">
                        {{ $slot }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary"
                            data-dismiss="modal">{{ __('Cancel') }}</button>
                        {{ $addbuttons ?? "" }}
                        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                    </div>
                </form>
            </div>
            <!--/.Content-->
        </div>
    </div>
</div>
