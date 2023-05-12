@extends('user.layout')

@if (!empty($la) && $la->rtl == 1)
    @section('styles')
        <style>
            form input {
                direction: rtl;
            }
        </style>
    @endsection
@endif

@if (empty($la) && $be->default_language_direction == 'rtl')
    @section('styles')
        <style>
            form input {
                direction: rtl;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Keyword'] ?? __('Edit Keyword') }}</h4>
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="flaticon-home"></i>
                </a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a
                    href="{{ route('user.language.index') . '?language=' . request('language') }}">{{ $keywords['Language_Management'] ?? __('Language Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit_Keyword'] ?? __('Edit Keyword') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">
                        {{ $keywords['Edit_Language_Keyword'] ?? __('Edit Language Keyword') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.language.index') . '?language=' . request('language') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="{{ route('user.language.updateKeyword', $la->id) }}"
                                id="ajaxEditForm">
                                {{ csrf_field() }}
                                <div class="row">
                                    @foreach ($u_keywords as $key => $val)
                                        <div class="col-md-4 mt-2">
                                            <div class="form-group">
                                                <label class="control-label">{{ str_replace('_', ' ', $key) }}</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ $val }}"
                                                        name="{{ $key }}" class="form-control form-control-lg">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button id="updateBtn" type="button"
                                    class="btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
