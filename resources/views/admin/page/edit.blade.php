@extends('admin.layout')
@if (Session::has('admin_lang'))
    @php
        $admin_lang = Session::get('admin_lang');
        $cd = str_replace('admin_', '', $admin_lang);
        $default = \App\Models\Language::where('code', $cd)->first();
    @endphp
@else
    @php
        $default = \App\Models\Language::where('is_default', 1)->first();
    @endphp
@endif

@if (!empty($page->language) && $page->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }
            form .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Pages') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') . '?language=' . $default->code }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Pages') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Page') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('admin.page.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-4">
                    <div class="row">
                        <div class="col-lg-10 offset-lg-1">
                            <form id="ajaxForm" action="{{ route('admin.page.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="pageid" value="{{ $page->id }}">
                                <div class="row">
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Name') }} **</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="{{ __('Enter Name') }}" value="{{ $page->name }}">
                                            <p id="errname" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Title') }} **</label>
                                            <input type="text" class="form-control" name="title"
                                                placeholder="{{ __('Enter Title') }}" value="{{ $page->title }}">
                                            <p id="errtitle" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label for="">{{ __('Status') }} **</label>
                                            <select class="form-control ltr" name="status">
                                                <option value="1" {{ $page->status == 1 ? 'selected' : '' }}>
                                                    {{ __('Active') }}</option>
                                                <option value="0" {{ $page->status == 0 ? 'selected' : '' }}>
                                                    {{ __('Deactive') }}</option>
                                            </select>
                                            <p id="errstatus" class="em text-danger mb-0"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Body') }} **</label>
                                    <textarea id="body" class="form-control summernote" name="body" data-height="500">{{ replaceBaseUrl($page->body) }}</textarea>
                                    <p id="errbody" class="em text-danger mb-0"></p>
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Meta Keywords') }}</label>
                                    <input class="form-control" name="meta_keywords" value="{{ $page->meta_keywords }}"
                                        placeholder="{{ __('Enter meta keywords') }}" data-role="tagsinput">
                                </div>
                                <div class="form-group">
                                    <label>{{ __('Meta Description') }}</label>
                                    <textarea class="form-control" name="meta_description" rows="5" placeholder="{{ __('Enter meta description') }}">{{ $page->meta_description }}</textarea>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" id="" data-form="ajaxForm" class="submitBtn btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
