<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_CV'] ?? __('Add CV') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="ajaxForm" class="" action="{{ route('user.cv.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label
                                    class="form-label">{{ $keywords['Choose_a_Template'] ?? __('Choose a Template') }}</label>
                                <div class="row">
                                    <div class="col-4">
                                        <label class="imagecheck mb-4">
                                            <input name="template" type="radio" value="1"
                                                class="imagecheck-input ltr" checked>
                                            <figure class="imagecheck-figure">
                                                <img src="{{ asset('assets/front/img/user/cv-templates/1.jpg') }}"
                                                    alt="title" class="imagecheck-image">
                                            </figure>
                                        </label>
                                    </div>
                                    <div class="col-4">
                                        <label class="imagecheck mb-4">
                                            <input name="template" type="radio" value="2"
                                                class="imagecheck-input ltr">
                                            <figure class="imagecheck-figure">
                                                <img src="{{ asset('assets/front/img/user/cv-templates/2.jpg') }}"
                                                    alt="title" class="imagecheck-image">
                                            </figure>
                                        </label>
                                    </div>
                                </div>
                                <p class="em text-danger em-0" id="errtemplate"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="col-12 mb-2">
                                    <label
                                        for="image"><strong>{{ $keywords['Your_Image'] ?? __('Your Image') }}*</strong></label>
                                </div>
                                <div class="col-md-12 showImage mb-3">
                                    <img src="{{ asset('assets/admin/img/noimage.jpg') }}" alt="..."
                                        class="img-thumbnail" width="170">
                                </div>
                                <input type="file" name="image" id="image" class="form-control ltr">
                                <p id="errimage" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['CV_Name'] ?? __('CV Name') }} **</label>
                                <input type="text" class="form-control" name="cv_name"
                                    placeholder="{{ $keywords['Enter_CV_name'] ?? __('Enter CV name') }}"
                                    value="">
                                <p id="errcv_name" class="mb-0 text-danger em"></p>
                                <p class="text-warning mb-0">
                                    {{ $keywords['CV_Name_msg'] ?? __('This will be used to identify this specific CV from CVs list') }}
                                    .</p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Direction'] ?? __('Direction') }} **</label>
                                <select name="direction" class="form-control ltr">
                                    <option value="" selected disabled>
                                        {{ $keywords['Select_a_Direction'] ?? __('Select a Direction') }}</option>
                                    <option value="1">{{ $keywords['LTR'] ?? __('LTR') }}
                                        ({{ $keywords['Left_to_Right'] ?? __('Left to Right') }})</option>
                                    <option value="2">{{ $keywords['RTL'] ?? __('RTL') }}
                                        ({{ $keywords['Right_to_Left'] ?? __('Right to Left') }})</option>
                                </select>
                                <p id="errdirection" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="alert alert-info text-dark" id="ltrAlert" style="display: none;">
                        If you want to enter <strong>LTR word / text</strong> in <strong>Your Name, Your
                            Occupation</strong> field, then wrap that <strong>word / text</strong> with
                        <strong><code>{{ '<span dir="ltr"></span>' }}</code></strong>
                        <br>
                        For example,
                        <div class="row">
                            <div class="col-6">
                                <ul class="pl-3">
                                    <li class="mb-2">
                                        <input dir="rtl" class="form-control"
                                            value="{{ '<span dir="ltr">This is LTR Text</span>' }} هناك حقيقة">
                                    </li>
                                    <li class="mb-2">
                                        <input dir="rtl" class="form-control"
                                            value="هناك حقيقة {{ '<span dir="ltr">1234567</span>' }}">
                                    </li>
                                    <li>
                                        <input dir="rtl" class="form-control"
                                            value="{{ '<span dir="ltr">This is LTR Text</span>' }}">
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Your_Name'] ?? __('Your Name') }} **</label>
                                <input type="text" class="form-control" name="name"
                                    placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}" value="">
                                <p id="errname" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="">{{ $keywords['Your_Occupation'] ?? __('Your Occupation') }}
                                    **</label>
                                <input type="text" class="form-control" name="occupation"
                                    placeholder="{{ $keywords['Enter_occupation'] ?? __('Enter occupation') }}"
                                    value="">
                                <p id="erroccupation" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">{{ $keywords['Base_Color_Code'] ?? __('Base Color Code') }}
                                    **</label>
                                <input type="text" class="form-control jscolor ltr" name="base_color"
                                    placeholder="{{ $keywords['Enter_base_color'] ?? __('Enter base color') }}"
                                    value="F4B0AE">
                                <p id="errbase_color" class="mb-0 text-danger em"></p>
                            </div>
                        </div>
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
