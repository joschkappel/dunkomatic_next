<div>
    <div class="modal fade right" id="modalShowMessage" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true" data-backdrop="false">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="modalShowMessageTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div id="msgGreeting"></div>
                    <div id="msgBody"></div>
                    <div id="msgSalutation"></div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="btnMarkUnread" data-dismiss="modal">{{__('message.delete')}}</button>
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
