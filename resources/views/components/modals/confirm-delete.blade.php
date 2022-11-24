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
                {{ $slot }}
            </div>
            <!--/.Content-->
        </div>
    </div>
</div>
