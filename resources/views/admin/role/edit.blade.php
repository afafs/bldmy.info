<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Edit Roles') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" class="" action="{{ route('admin.role.update') }}" method="POST">
                    @csrf
                    <input id="inrole_id" type="hidden" name="role_id" value="">
                    <div class="form-group">
                        <label for="">{{ __('Name') }} **</label>
                        <input id="inname" class="form-control" name="name" placeholder="{{ __('Enter name') }}">
                        <p id="eerrname" class="mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button id="updateBtn" type="button" class="btn btn-primary">{{ __('Save Changes') }}</button>
            </div>
        </div>
    </div>
</div>
