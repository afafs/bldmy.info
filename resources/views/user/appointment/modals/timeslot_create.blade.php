<!-- Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ $keywords['Add_Time_Frame'] ?? __('Add Time Frame') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('user.timeslot.store') }}" id="ajaxForm" method="POST">
                    @csrf
                    <input type="hidden" name="day_name" value="{{ request('day') }}">
                    <div class="form-group">
                        <label for="">{{ $keywords['Start_Time'] ?? __('Start Time') }} *</label>
                        <input type="text" name="start" class="form-control timepicker" autocomplete="off">
                        <p id="errstart" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['End_Time'] ?? __('End Time') }} *</label>
                        <input type="text" name="end" class="form-control timepicker" autocomplete="off">
                        <p id="errend" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Maximum_Booking'] ?? __('Maximum Booking') }} *</label>
                        <input type="number" name="max_booking" class="form-control" autocomplete="off" value="0">
                        <p class="mb-0 text-danger em" id="errmax_booking"></p>
                        <p class="text-warning mb-0">**
                            {{ $keywords['Enter_0_for_unlimited_booking'] ?? __('Enter 0 for unlimited booking') }}
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-danger"
                    data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                <button id="" data-form="ajaxForm" type="button"
                    class="submitBtn btn btn-primary btn-success">{{ $keywords['Add'] ?? __('Add') }}</button>
            </div>
        </div>
    </div>
</div>
