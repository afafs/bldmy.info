@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Sections'] ?? __('Sections') }}</h4>
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
                <a href="{{ route('user.cv') }}">{{ $keywords['CV_Management'] ?? __('CVs Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $section->user_cv->cv_name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('user.cv.section.index', $section->user_cv_id) }}">{{ $keywords['Sections'] ?? __('Sections') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $section->name }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit'] ?? __('Edit') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Sections'] ?? __('Sections') }}</div>
                        </div>
                        <div class="col-lg-4">
                        </div>
                        <div class="col-lg-4 mt-2 mt-lg-0">
                            <a class="btn btn-primary float-right text-white btn-sm"
                                href="{{ route('user.cv.section.index', $section->user_cv_id) . '?language=' . request('language') }}"><i
                                    class="fas fa-backward"></i> {{ $keywords['Back'] ?? __('Back') }}</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if ($section->user_cv->direction == 2)
                            <div class="col-10 offset-1">
                                <div class="alert alert-info text-dark">
                                    If you want to enter <strong>LTR word / text</strong> in <strong>Section Name</strong>
                                    field, then wrap that <strong>word / text</strong> with
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
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                                action="{{ route('user.cv.section.update') }}" method="POST">
                                @csrf
                                <input type="hidden" name="section_id" value="{{ $section->id }}">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Icon'] ?? __('Icon') }} **</label>
                                            <div class="btn-group d-block">
                                                <button type="button" class="btn btn-primary iconpicker-component"><i
                                                        class="{{ $section->icon }}"></i></button>
                                                <button type="button" class="icp icp-dd btn btn-primary dropdown-toggle"
                                                    data-selected="fa-car" data-toggle="dropdown">
                                                </button>
                                                <div class="dropdown-menu"></div>
                                            </div>
                                            <input id="inputIcon" type="hidden" name="icon"
                                                value="{{ $section->icon }}">
                                            <p class="em text-danger mb-0" id="erricon"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label for="">{{ $keywords['Section_Name'] ?? __('Section Name') }}
                                                **</label>
                                            <input type="text"
                                                class="form-control {{ $section->user_cv->direction == 2 ? 'rtl' : '' }}"
                                                name="name" value="{{ $section->name }}">
                                            <p class="em text-danger mb-0" id="errname"></p>
                                        </div>
                                    </div>
                                </div>
                                @if ($section->user_cv->template == 1)
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="">{{ $keywords['Which_Column'] ?? __('Which Column') }}
                                                    **</label>
                                                <select name="column" id="" class="form-control">
                                                    <option value="" selected disabled>
                                                        {{ $keywords['Select_a_Column'] ?? __('Select a Column') }}
                                                    </option>
                                                    <option value="1" {{ $section->column == 1 ? 'selected' : '' }}>
                                                        {{ $keywords['Left_Column_in_CV'] ?? __('Left Column in CV') }}
                                                    </option>
                                                    <option value="2" {{ $section->column == 2 ? 'selected' : '' }}>
                                                        {{ $keywords['Right_Column_in_CV'] ?? __('Right Column in CV') }}
                                                    </option>
                                                </select>
                                                <p id="errcolumn" class="em text-danger mb-0"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer text-center">
                    <button id="" data-form="ajaxForm"
                        class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                </div>

            </div>
        </div>
    </div>
@endsection
