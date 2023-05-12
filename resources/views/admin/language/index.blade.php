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
        <h4 class="page-title">{{ __('Languages') }}</h4>
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
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ __('Languages') }}</div>
                    <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal"><i
                            class="fas fa-plus"></i> {{ __('Add Language') }}</a>
                    <a href="#" class="btn btn-secondary float-right mr-1 editBtn" data-toggle="modal"
                        data-target="#addModal">
                        <span class="btn-label">
                            <i class="fas fa-plus"></i>
                        </span>
                        {{ __('Add Admin Keyword') }}
                    </a>
                    <a href="#" class="btn btn-info float-right mr-1 editBtn" data-toggle="modal"
                        data-target="#addModalFrontend">
                        <span class="btn-label">
                            <i class="fas fa-plus"></i>
                        </span>
                        {{ __('Add Frontend Keyword') }}
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($languages) == 0)
                                <h3 class="text-center">{{ __('NO LANGUAGE FOUND') }}</h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ __('Name') }}</th>
                                                <th scope="col">{{ __('Code') }}</th>
                                                <th scope="col">{{ __('Appearance in Website') }}</th>
                                                <th scope="col">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($languages as $key => $language)
                                                <tr>
                                                    <td>{{ $loop->iteration + 1 }}</td>
                                                    <td>{{ $language->name }}</td>
                                                    <td>{{ $language->code }}</td>
                                                    <td>
                                                        @if ($language->is_default == 1)
                                                            <strong
                                                                class="badge badge-success">{{ __('Default') }}</strong>
                                                        @else
                                                            <form class="d-inline-block"
                                                                action="{{ route('admin.language.default', $language->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <button class="btn btn-primary btn-sm" type="submit"
                                                                    name="button">{{ __('Make Default') }}</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('admin.language.editKeyword', $language->id) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit Front Keyword') }}
                                                        </a>

                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('admin.language.editAdminKeyword', $language->id) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit Admin Keyword') }}
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('admin.language.edit', $language->id) }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('admin.language.delete', $language->id) }}"
                                                            method="post">
                                                            @csrf
                                                            <button type="submit" class="btn btn-danger btn-sm deletebtn">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-trash"></i>
                                                                </span>
                                                                {{ __('Delete') }}
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

            </div>
        </div>
    </div>


    {{-- modal start --}}
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Admin Keyword') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="admiAdKeyword" action="{{ route('admin.language_management.add_keyword') }}" method="POST">
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
                    <button data-form="admiAdKeyword" type="submit" class="submitBtn btn btn-primary btn-sm">
                        {{ __('Submit') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
    {{-- modal start end --}}

    {{-- modal start --}}
    <div class="modal fade" id="addModalFrontend" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <form id="ajaxFormAddFrontkeyword" action="{{ route('admin.language_management.add_front_keyword') }}"
            method="POST">
            @csrf
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add Frontend Keyword') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">{{ __('Keyword') }}*</label>
                            <input type="text" class="form-control" name="front_keyword"
                                placeholder="{{ __('Enter Keyword') }}">
                            <p id="errfront_keyword" class="mb-0 text-danger em"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                            {{ __('Close') }}
                        </button>
                        <button data-form="ajaxFormAddFrontkeyword" type="submit"
                            class="submitBtn btn btn-primary btn-sm">
                            {{ __('Submit') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    {{-- modal start end --}}
    <!-- Create Language Modal -->
    @includeif('admin.language.create')
@endsection
