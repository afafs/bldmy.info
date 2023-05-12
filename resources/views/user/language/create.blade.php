<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_Language'] ?? __('Add Language') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form id="ajaxForm" class="" action="{{ route('user.language.store') }}" method="POST">
                    @csrf
                    <input type="hidden" id="image" name="" value="">
                    <div class="form-group">
                        <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                        <input type="text" class="form-control" name="name"
                            placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}" value="">
                        <p id="errname" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Code'] ?? __('Code') }} **</label>
                        <input type="text" class="form-control" name="code"
                            placeholder="{{ $keywords['Enter_code'] ?? __('Enter code') }}" value="">
                        <p id="errcode" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Direction'] ?? __('Direction') }} **</label>
                        <select name="direction" class="form-control">
                            <option value="" selected disabled>
                                {{ $keywords['Select_a_Direction'] ?? __('Select a Direction') }}</option>
                            <option value="0">{{ $keywords['LTR'] ?? __('LTR') }}
                                ({{ $keywords['Left_to_Right'] ?? __('Left to Right') }})</option>
                            <option value="1">{{ $keywords['RTL'] ?? __('RTL') }}
                                ({{ $keywords['Right_to_Left'] ?? __('Right to Left') }})</option>
                        </select>
                        <p id="errdirection" class="mb-0 text-danger em"></p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                <button id="" data-form="ajaxForm" type="button"
                    class="submitBtn btn btn-primary">{{ $keywords['Submit'] ?? __('Submit') }}</button>
            </div>
        </div>
    </div>
</div>
