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
        <h4 class="page-title">{{ __('Edit Keyword') }}</h4>
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
                <a href="#">{{ __('Language Management') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ __('Edit Keyword') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Language Keyword') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block" href="{{ route('admin.language.index') }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ __('Back') }}
                    </a>

                    <a href="#" class="btn btn-secondary float-right btn-sm mr-1 editBtn" data-toggle="modal"
                        data-target="#addModal">
                        <span class="btn-label">
                            <i class="fas fa-plus"></i>
                        </span>
                        @if ($isAdmin == 1)
                            {{ __('Add Admin Keyword') }}
                        @else
                            {{ __('Add Frontend Keyword') }}
                        @endif
                    </a>
                </div>
                <div class="card-body pt-5 pb-5" id="app">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post"
                                action="{{ !empty($la) ? route('admin.language.updateKeyword', $la->id) : route('admin.language.updateKeyword', 0) }}"
                                id="ajaxEditForm">
                                {{ csrf_field() }}
                                <input type="hidden" value="{{ $isAdmin }}" name="isAdmin" id="">
                                <div class="row">
                                    <div class="col-md-4 mt-2" v-for="(value, key) in datas" :key="key">
                                        <div class="form-group">
                                            <label class="control-label">@{{ key }}</label>
                                            <div class="input-group">
                                                <input type="text" :value="value" :name="'keys[' + key + ']'"
                                                    class="form-control form-control-lg">
                                            </div>
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
                                <button id="updateBtn" type="button" class="btn btn-success">{{ __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- modal start --}}
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add New Keyword') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm"
                        @if ($isAdmin == 1) action="{{ route('admin.language_management.add_keyword') }}" @else  action="{{ route('admin.language_management.add_front_keyword') }}" @endif
                        method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ __('Keyword') }}*</label>
                            <input type="text" class="form-control" name="front_keyword"
                                placeholder="{{ __('Enter Keyword') }}">
                            <p id="errkeyword" class="mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button id="" data-form="ajaxForm" type="submit" class="submitBtn btn btn-primary btn-sm">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal start end --}}
@endsection

@section('vuescripts')
    <script>
        "use strict";
        window.app = new Vue({
            el: '#app',
            data: {
                datas: @php echo json_encode($json) @endphp,
            }
        })
    </script>
@endsection
