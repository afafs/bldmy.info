<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Add_Holiday'] ?? __('Add Holiday') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.holiday.store') }}" id="ajaxForm" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="">{{ $keywords['Date'] ?? __('Date') }} *</label>
                        <input type="text" name="date" class="form-control datepicker" autocomplete="off">
                        <p id="errdate" class="mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-danger"
                    data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                <button data-form="ajaxForm" id="" type="button"
                    class="submitBtn btn btn-primary btn-success">{{ $keywords['Add'] ?? __('Add') }}</button>
            </div>
        </div>
    </div>
</div>
