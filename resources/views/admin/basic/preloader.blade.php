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
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ __('Preloader') }}</h4>
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
                <a href="#">{{ __('Basic Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Preloader') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">{{ __('Update') }} {{ __('Preloader') }}</div>
                </div>
                <div class="card-body pt-5 pb-4">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form enctype="multipart/form-data" action="{{ route('admin.preloader.update') }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label>{{__('Status')}}</label>
                                            <div class="selectgroup w-100">
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="preloader_status" value="1"
                                                        class="selectgroup-input"
                                                        {{ $abs->preloader_status == 1 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Active') }}</span>
                                                </label>
                                                <label class="selectgroup-item">
                                                    <input type="radio" name="preloader_status" value="0"
                                                        class="selectgroup-input"
                                                        {{ $abs->preloader_status == 0 ? 'checked' : '' }}>
                                                    <span class="selectgroup-button">{{ __('Deactive') }}</span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong> {{ __('Preloader') }} **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ $abs->preloader ? asset('assets/front/img/' . $abs->preloader) : asset('assets/admin/img/noimage.jpg') }}"
                                                    alt="..." class="img-thumbnail" width="100">
                                            </div>
                                            <input type="file" name="file" id="image" class="form-control">
                                            @if ($errors->has('file'))
                                                <p class="text-danger mb-0">{{ $errors->first('file') }}</p>
                                            @endif
                                            <p class="text-warning mb-0">
                                                {{ __('Only GIF, JPG, JPEG, PNG file formats are allowed') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer">
                                    <div class="form">
                                        <div class="form-group from-show-notify row">
                                            <div class="col-12 text-center">
                                                <button type="submit" class="btn btn-success">{{ __('Update') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
