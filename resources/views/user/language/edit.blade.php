@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Language'] ?? __('Edit Language') }}</h4>
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
                <a href="{{ route('user.language.index') . '?language=' . request()->input('language') }}">{{ $keywords['Language_Management'] ?? __('Language Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Language'] ?? __('Edit Language') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Language'] ?? __('Edit Language') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.language.index') . '?language=' . request()->input('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">

                            <form id="ajaxForm" class="" action="{{ route('user.language.update') }}" method="post">
                                @csrf
                                <input type="hidden" name="language_id" value="{{ $language->id }}">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Name'] ?? __('Name') }} **</label>
                                    <input type="text" class="form-control" name="name"
                                        placeholder="{{ $keywords['Enter_Name'] ?? __('Enter name') }}"
                                        value="{{ $language->name }}">
                                    <p id="errname" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Code'] ?? __('Code') }} **</label>
                                    <input readonly type="text" class="form-control" name="code"
                                        placeholder="{{ $keywords['Enter_code'] ?? __('Enter code') }}"
                                        value="{{ $language->code }}">
                                    <p id="errcode" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Direction'] ?? __('Direction') }} **</label>
                                    <select name="direction" class="form-control">
                                        <option value="" selected disabled>
                                            {{ $keywords['Select_a_Direction'] ?? __('Select a Direction') }}</option>
                                        <option value="0" {{ $language->rtl == 0 ? 'selected' : '' }}>
                                            {{ $keywords['LTR'] ?? __('LTR') }}
                                            ({{ $keywords['Left_to_Right'] ?? __('Left to Right') }})</option>
                                        <option value="1" {{ $language->rtl == 1 ? 'selected' : '' }}>
                                            {{ $keywords['RTL'] ?? __('RTL') }}
                                            ({{ $keywords['Right_to_Left'] ?? __('Right to Left') }})</option>
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
