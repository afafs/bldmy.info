<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_Category'] ?? __('Add Category') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form" class="modal-form" action="{{ route('user.category.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ request('language') }}" name="language">
                    <div class="form-group">
                        <label>{{ $keywords['Language'] ?? __('Language') }} *</label>
                        <select name="user_language_id" class="form-control">
                            <option selected disabled>{{ $keywords['Select_a_language'] ?? __('Select a language') }}
                            </option>
                            @foreach ($languages as $language)
                                <option value="{{ $language->id }}">{{ $language->name }}</option>
                            @endforeach
                        </select>
                        <p id="erruser_language_id" class="mt-2 mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group " id="image-input">
                        <div class="row">
                            <div class="col-lg-12 p-0">
                                <div class="form-group">
                                    <div class="col-12 mb-2 p-0">
                                        <label for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}
                                                *</strong></label>
                                    </div>
                                    <div class="showImage mb-3 p-0">
                                        <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                            class="img-thumbnail" width="75">
                                    </div>
                                    <input type="file" name="image" id="image" class="form-control">
                                    <p class="text-warning mb-0 mt-2">
                                        {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                                    </p>
                                    <p id="errimage" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group ">
                        <label for="">{{ $keywords['Category_name'] ?? __('Category name') }}*</label>
                        <input type="text" name="name" id="name"
                            placeholder="{{ $keywords['enter_category_name'] ?? __('Enter category name') }}"
                            class="form-control ">
                    </div>
                    <div class="form-group ">
                        <label for="">{{ $keywords['Fee'] ?? __('Fee') }}*
                            ({{ $userBs->base_currency_symbol }})</label>
                        <input type="number" name="price" id="price"
                            placeholder="{{ $keywords['enter_fee'] ?? __('Enter fee') }}" class="form-control">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ $keywords['Close'] ?? __('Close') }}
                </button>
                <button form="form" type="submit" class="btn btn-primary">
                    {{ $keywords['save'] ?? __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
