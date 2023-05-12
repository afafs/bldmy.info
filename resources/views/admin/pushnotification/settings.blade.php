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
        <h4 class="page-title">{{ __('Push Notification Settings') }}</h4>
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
                <a href="#">{{ __('Push Notification') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Settings') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header">
                    <div class="card-title">{{ __('Push Notification Settings') }}</div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="pushSettingsForm" action="{{ route('admin.pushnotification.updateSettings') }}"
                                method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <div class="col-12 mb-2">
                                                <label for="image"><strong>{{ __('Icon Image') }} **</strong></label>
                                            </div>
                                            <div class="col-md-12 showImage mb-3">
                                                <img src="{{ asset('assets/front/img/' . 'pushnotification_icon.png') . '?' . time() }}"
                                                    alt="..." class="img-thumbnail" width="170">
                                            </div>
                                            <input type="file" name="file" id="image" class="form-control">
                                            @if ($errors->has('file'))
                                                <p class="mb-0 text-danger em">{{ $errors->first('file') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="form-group from-show-notify row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-success"
                                form="pushSettingsForm">{{ __('Update') }}</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
