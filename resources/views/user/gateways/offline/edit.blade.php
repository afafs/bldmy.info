<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Edit_Gateway'] ?? __('Edit Gateway') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxEditForm" class="" action="{{ route('user.gateway.offline.update') }}"
                    method="POST">
                    @csrf
                    <input id="inogateway_id" type="hidden" name="ogateway_id" value="">
                    <div class="form-group">
                        <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                        <input id="inname" type="text" class="form-control" name="name" value=""
                            placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}">
                        <p id="eerrname" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Short_Description'] ?? __('Short Description') }}</label>
                        <textarea id="inshort_description" class="form-control" name="short_description" rows="3" cols="80"
                            placeholder="{{ $keywords['Enter_Short_Description'] ?? __('Enter short description') }}"></textarea>
                        <p id="eerrshort_description" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label for="">{{ $keywords['Instructions'] ?? __('Instructions') }}</label>
                        <textarea id="ininstructions" class="form-control summernote" name="instructions" rows="3" cols="80"
                            placeholder="{{ $keywords['Enter_instructions'] ?? __('Enter instructions') }}" data-height="150"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>{{ $keywords['Receipt_Image'] ?? __('Receipt Image') }} **</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="is_receipt" value="1"
                                            class="selectgroup-input">
                                        <span
                                            class="selectgroup-button">{{ $keywords['Active'] ?? __('Active') }}</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="is_receipt" value="0"
                                            class="selectgroup-input">
                                        <span
                                            class="selectgroup-button">{{ $keywords['Deactive'] ?? __('Deactive') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                    **</label>
                                <input id="inserial_number" type="number" class="form-control ltr" name="serial_number"
                                    value=""
                                    placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                <p id="eerrserial_number" class="mb-0 text-danger em"></p>
                                <p class="text-warning">
                                    <small>{{ $keywords['offline_gateway_serial_no_text'] ?? __('The higher the serial number is, the later the gateway will be shown everywhere') }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                <button id="updateBtn" type="button"
                    class="btn btn-primary">{{ $keywords['Update'] ?? __('Update') }}</button>
            </div>
        </div>
    </div>
</div>
