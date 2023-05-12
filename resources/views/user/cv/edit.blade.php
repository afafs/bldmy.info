@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_CV'] ?? __('Edit CV') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('user-dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="{{ route('user.cv') }}">{{ $keywords['CV_Management'] ?? __('CV Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $cv->cv_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_CV'] ?? __('Edit CV') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_CV'] ?? __('Edit CV') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.cv') . '?language=' . request('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        @if ($cv->direction == 2)
                            <div class="col-12">
                                <div class="alert alert-info text-dark">
                                    If you want to enter <strong>LTR word / text</strong> in <strong>Name,
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
                            </div>
                        @endif
                        <div class="col-lg-8 offset-lg-2">

                            <form id="ajaxForm" class="" action="{{ route('user.cv.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="cv_id" value="{{ $cv->id }}">

                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label
                                                class="form-label">{{ $keywords['Choose_a_Template'] ?? __('Choose a Template') }}</label>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="1"
                                                            class="imagecheck-input ltr"
                                                            {{ $cv->template == 1 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/front/img/user/cv-templates/1.jpg') }}"
                                                                alt="title" class="imagecheck-image">
                                                        </figure>
                                                    </label>
                                                </div>
                                                <div class="col-4">
                                                    <label class="imagecheck mb-4">
                                                        <input name="template" type="radio" value="2"
                                                            class="imagecheck-input ltr"
                                                            {{ $cv->template == 2 ? 'checked' : '' }}>
                                                        <figure class="imagecheck-figure">
                                                            <img src="{{ asset('assets/front/img/user/cv-templates/2.jpg') }}"
                                                                alt="title" class="imagecheck-image" 1>
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
                                                <img src="{{ !empty($cv->image) ? asset('assets/front/img/user/cv/' . $cv->image) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="170">
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
                                            <input type="text"
                                                class="form-control {{ $cv->direction == 2 ? 'rtl' : '' }}"
                                                name="cv_name"
                                                placeholder="{{ $keywords['Enter_CV_name'] ?? __('Enter CV name') }}"
                                                value="{{ $cv->cv_name }}">
                                            <p id="errcv_name" class="mb-0 text-danger em"></p>
                                            <p class="text-warning mb-0">
                                                {{ $keywords['CV_Name_msg'] ?? __('This will be used to identify this specific CV from CVs list') }}.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Direction'] ?? __('Direction') }}
                                                **</label>
                                            <select name="direction" class="form-control ltr">
                                                <option value="" selected disabled>
                                                    {{ $keywords['Select_a_Direction'] ?? __('Select a Direction') }}
                                                </option>
                                                <option value="1" {{ $cv->direction == 1 ? 'selected' : '' }}>
                                                    {{ $keywords['LTR'] ?? __('LTR') }}
                                                    ({{ $keywords['Left_to_Right'] ?? __('Left to Right') }})</option>
                                                <option value="2" {{ $cv->direction == 2 ? 'selected' : '' }}>
                                                    {{ $keywords['RTL'] ?? __('RTL') }}
                                                    ({{ $keywords['Right_to_Left'] ?? __('Right to Left') }})</option>
                                            </select>
                                            <p id="errdirection" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Your_Name'] ?? __('Your Name') }}
                                                **</label>
                                            <input type="text"
                                                class="form-control {{ $cv->direction == 2 ? 'rtl' : '' }}"
                                                name="name"
                                                placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}"
                                                value="{{ $cv->name }}">
                                            <p id="errname" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Your_Occupation'] ?? __('Your Occupation') }}
                                                **</label>
                                            <input type="text"
                                                class="form-control {{ $cv->direction == 2 ? 'rtl' : '' }}"
                                                name="occupation"
                                                placeholder="{{ $keywords['Enter_Occupation'] ?? __('Enter Occupation') }}"
                                                value="{{ $cv->occupation }}">
                                            <p id="erroccupation" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label
                                                for="">{{ $keywords['Base_Color_Code'] ?? __('Base Color Code') }}
                                                **</label>
                                            <input type="text" class="form-control jscolor ltr" name="base_color"
                                                placeholder="{{ $keywords['Enter_base_color'] ?? __('Enter base color') }}"
                                                value="{{ $cv->base_color }}">
                                            <p id="errbase_color" class="mb-0 text-danger em"></p>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" data-form="ajaxForm" id=""
                                    class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $("select[name='direction']").on('change', function() {
                val = $(this).val();
                let $formControls = $(".form-control:not(.ltr)");

                // if RTL is selected
                if (val == 2) {
                    $formControls.each(function() {
                        $(this).addClass('rtl');
                    });
                } else {
                    $formControls.each(function() {
                        $(this).removeClass('rtl');
                    });
                }
            });
        });
    </script>
@endsection
