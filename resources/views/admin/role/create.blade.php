<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Role') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" class="" action="{{ route('admin.role.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">{{ __('Role Name') }} **</label>
                        <input class="form-control" name="name" placeholder="{{ __('Enter name') }}">
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button id="" data-form="ajaxForm" type="button" class="submitBtn btn btn-primary">{{ __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
