<div class="modal fade" id="modal-confirmation">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peringatan!</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            @{{confirmContent}}
          </div>
        </div>
        <div class="modal-footer">
          <button id="submitConfirm" type="button" class="btn btn-primary" @click="submitConfirm">Ya</button>
          <button id="notSubmitConfirm" type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>