<div class="modal fade right" id="modalDeleteMessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="false">
    <div class="modal-dialog modal-side modal-bottom-right modal-notify modal-info" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header bg-danger">
                <p class="heading" id="dheader">@lang('message.title.delete')
                </p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">&times;</span>
                </button>
            </div>

            <!--Body-->
            <div class="modal-body">
                <div class="card card-info">

                    <form id="confirmDeleteMessage" class="form-horizontal" action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="msg_id" id="msg_id" value="">
                        <div class="card-body">
                            <p class="text-left">@lang('message.confirm.delete')</p>
                            <h4 class="text-left text-danger">
                              <span id="msg_title"></span></p>
                            </h4>
                        </div>
                        <div class="card-footer">
                            <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                                <button type="submit" class="btn btn-danger">{{ __('Submit')}}</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--Modal: modalRelatedContent-->
</div>
