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
                <a href="#">{{ __('Edit Keyword') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Edit Language Keyword') }}</div>
                    <a href="#" class="btn btn-secondary float-right btn-sm mr-1 editBtn" data-toggle="modal"
                        data-target="#addModal">
                        <span class="btn-label">
                            <i class="fas fa-plus"></i>
                        </span>
                        {{ __('Add New Keyword') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5" id="app">
                    <div class="row">
                        <div class="col-lg-12">
                            <form method="post" action="{{ route('admin.userlanguage.updateKeyword') }}" id="langForm">
                                {{ csrf_field() }}
                                <div class="row">
                                    @foreach ($json as $key => $langu)
                                        <div class="col-md-4 mt-2">
                                            <div class="form-group">
                                                <label class="control-label">{{ str_replace('_', ' ', $key) }}</label>
                                                <div class="input-group">
                                                    <input type="text" value="{{ $langu }}"
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
                                <button id="langBtn" type="button" class="btn btn-success">{{ __('Update') }}</button>
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
                    <form id="ajaxForm" action="{{ route('admin.userlanguage.add_keyword') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ __('Keyword') }}*</label>
                            <input type="text" class="form-control" name="keyword"
                                placeholder="{{ __('Enter Keyword') }}">
                            <p id="errkeyword" class="mb-0 text-danger em"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                        {{ __('Close') }}
                    </button>
                    <button id="" data-form="ajaxForm" type="button" class="submitBtn btn btn-primary btn-sm">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal start end --}}
@endsection
{{--

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
@endsection --}}
