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
        <h4 class="page-title">{{ __('User Language') }}</h4>
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
                <a href="#">{{ __('User Language') }}</a>
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
                    <div class="card-title d-inline-block">{{ __('Language Settings') }}</div>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxForm" class="" action="{{ route('admin.userlanguage.update') }}"
                                method="post">
                                @csrf
                                <input type="hidden" name="language_id" value="{{ $language->id }}">
                                <div class="form-group">
                                    <label for="">{{ __('Name') }} **</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ __('Enter name') }}" value="{{ $language->name }}">
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Code') }} **</label>
                                    <input type="text" class="form-control" name="code"
                                        placeholder="{{ __('Enter code') }}" value="{{ $language->code }}">
                                    <p id="errcode" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ __('Direction') }} **</label>
                                    <select name="direction" class="form-control">
                                        <option value="" selected disabled>{{ __('Select a Direction') }}</option>
                                        <option value="0" {{ $language->rtl == 0 ? 'selected' : '' }}>
                                            {{ __('LTR') }} ({{ __('Left to Right') }})</option>
                                        <option value="1" {{ $language->rtl == 1 ? 'selected' : '' }}>
                                            {{ __('RTL') }} ({{ __('Right to Left') }})</option>
                                    </select>
                                    <p id="errdirection" class="mb-0 text-danger em"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit"  data-form="ajaxForm" id="" class="submitBtn btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
