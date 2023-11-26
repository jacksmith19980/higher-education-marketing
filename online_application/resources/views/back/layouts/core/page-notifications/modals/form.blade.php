<div class="form_modal modal bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none;" id="form-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body"></div>


            <div id="modalFooter" class="modal-footer">

                <button type="button" class="btn btn-danger" onclick="app.dismissModal(this)">{{__('Close')}}</button>

                <a class="text-white btn btn-success btn-ok">{{__('Save')}}</a>

            </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
