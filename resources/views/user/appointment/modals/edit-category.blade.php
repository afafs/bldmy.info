<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryModal">
                    {{ $keywords['Edit_Category'] ?? __('Edit Category') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="editForm" action="{{ route('user.category.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="table_id" id="table_id" placeholder="Enter Category Name"
                        class="form-control ">
                    <div class="form-group">
                        <div class="col-12 mb-2 p-0">
                            <label for="image"><strong>{{ $keywords['Image'] ?? __('Image') }}
                                    **</strong></label>
                        </div>
                        <div class="col-md-12 showImage mb-3">
                            <img id="imageSrc" src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                class="img-thumbnail" width="75">
                        </div>
                        <input type="file" name="image" id="image" class="form-control">
                        <p class="text-warning mb-0 mt-2">
                            {{ $keywords['img_validation_msg'] ?? __('** Only JPG, PNG, JPEG, SVG Images are allowed') }}
                        </p>
                        <p id="errimage" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Category_name'] ?? __('Category name') }} *</label>
                        <input required type="text" name="name" id="name" placeholder="{{ $keywords['enter_category_name'] ?? __('Enter category name') }}"
                            class="form-control ">
                    </div>
                    <div class="form-group">
                        <label for="">{{ $keywords['Fee'] ?? __('Fee') }} * ({{ $userBs->base_currency_symbol }})</label>
                        <input required type="number" name="price" id="price"
                            placeholder="{{ $keywords['enter_fee'] ?? __('Enter fee') }}" class="form-control ">
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    {{ $keywords['Close'] ?? __('Close') }}
                </button>
                <button form="editForm" type="submit" class="btn btn-primary">
                    {{ $keywords['save'] ?? __('Save') }}
                </button>
            </div>
        </div>
    </div>
</div>
