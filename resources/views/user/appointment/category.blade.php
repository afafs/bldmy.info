@extends('user.layout')
@php
    $selLang = \App\Models\User\Language::where([['code', request()->input('language')], ['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id]])->first();
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id], ['is_default', 1]])->first();
    
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::guard('web')->user()->id)->get();
@endphp
@if (!empty($selLang) && $selLang->rtl == 1)
    @section('styles')
        <style>
            form:not(.modal-form) input,
            form:not(.modal-form) textarea,
            form:not(.modal-form) select,
            select[name='userLanguage'] {
                direction: rtl;
            }

            form:not(.modal-form) .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif
@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Categories'] ?? __('Categories') }}</h4>
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
                <a href="#">{{ $keywords['Appointment_Settings'] ?? __('Appointment Settings') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Categories'] ?? __('Categories') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Categories'] ?? __('Categories') }}</div>
                        </div>
                        <div class="col-lg-3">
                            
                        </div>
                        <div class="col-lg-4 offset-lg-1 mt-2 mt-lg-0">
                            <a href="#" data-toggle="modal" data-target="#createModal"
                                class="btn btn-primary btn-sm float-lg-right float-left"><i class="fas fa-plus"></i>
                                {{ $keywords['Add'] ?? __('Add') }}</a>
                            <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                data-href="{{ route('user.bulk-delete-category') }}">
                                <i class="flaticon-interface-5"></i> {{ $keywords['Delete'] ?? __('Delete') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-lg-12">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if (count($categories) == 0)
                                <h3 class="text-center">{{ $keywords['NO_CATEGORY_FOUND'] ?? __('NO CATEGORY FOUND') }} !
                                </h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3" id="basic-datatables">
                                        <thead>
                                            <tr>
                                                <th scope="col">
                                                    <input type="checkbox" class="bulk-check" data-val="all">
                                                </th>
                                                <th scope="col">{{ $keywords['Category_Icon'] ?? __('Category Icon') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Category_Name'] ?? __('Category Name') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Fee'] ?? __('Fee') }}
                                                    ({{ $userBs->base_currency_symbol }})</th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($categories as $category)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="bulk-check"
                                                            data-val="{{ $category->id }}">
                                                    </td>
                                                    <td>
                                                        <img width="100" src="{{ asset('assets/user/img/category') . '/' . $category->image }}"
                                                            alt="Icon">
                                                    </td>
                                                    <td>{{ $category->name }}</td>
                                                    <td>{{ $category->appointment_price }}</td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm mr-1 "
                                                            href="{{ route('user.forminput', ['id' => $category->id]) . '?language=' . $userDefaultLang->code }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-file"></i>
                                                            </span>
                                                            {{ $keywords['Form'] ?? __('Form') }}
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm mr-1 editbtnAd" href="#"
                                                            data-toggle="modal" data-target="#editCategoryModal"
                                                            data-id="{{ $category->id }}" data-modal="editCategoryModal"
                                                            data-name="{{ $category->name }}"
                                                            data-price="{{ $category->appointment_price }}"
                                                            data-image="{{ $category->image }}" data-edit="editCategory">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.category.delete', ['category' => $category->id]) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ $keywords['Delete'] ?? __('Delete') }}
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    {{-- create modal --}}
    @include('user.appointment.modals.create-category')

    {{-- edit modal --}}
    @include('user.appointment.modals.edit-category')
@endsection

@section('scripts')
    <script src="{{ asset('assets/user/dashboard/js/advertisement.js') }}"></script>
@endsection
