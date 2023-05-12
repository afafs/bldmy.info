@extends('user.layout')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Languages'] ?? __('Languages') }}</h4>
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
                <a href="#">{{ $keywords['Language_Management'] ?? __('Language Management') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Languages'] ?? __('Languages') }}</div>
                    <a href="#" class="btn btn-primary float-right" data-toggle="modal" data-target="#createModal">
                        <i class="fas fa-plus"></i>
                        {{ $keywords['Add_Language'] ?? __('Add Language') }}
                    </a>
                    {{-- <a href="#" class="btn btn-secondary float-right mr-1 " data-toggle="modal"
                        data-target="#addModal">
                        <span class="btn-label">
                            <i class="fas fa-plus"></i>
                        </span>
                        {{ $keywords['Add_New_Keyword'] ?? __('Add New Keyword') }}
                    </a> --}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (count($languages) == 0)
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __('NO LANGUAGE FOUND') }}
                                </h3>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-striped mt-3">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">{{ $keywords['Name'] ?? __('Name') }}</th>
                                                <th scope="col">{{ $keywords['Code'] ?? __('Code') }}</th>
                                                <th scope="col">
                                                    {{ $keywords['Appearance_in_Website'] ?? __('Appearance in Website') }}
                                                </th>
                                                <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
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
                                                                class="badge badge-success">{{ $keywords['Default'] ?? __('Default') }}</strong>
                                                        @else
                                                            <form class="d-inline-block"
                                                                action="{{ route('user.language.default', $language->id) }}"
                                                                method="post">
                                                                @csrf
                                                                <button class="btn btn-primary btn-sm" type="submit"
                                                                    name="button">{{ $keywords['Make_Default'] ?? __('Make Default') }}</button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.language.editKeyword', $language->id) . '?language=' . request('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit_Keyword'] ?? __('Edit Keyword') }}
                                                        </a>
                                                        <a class="btn btn-secondary btn-sm"
                                                            href="{{ route('user.language.edit', $language->id) . '?language=' . request('language') }}">
                                                            <span class="btn-label">
                                                                <i class="fas fa-edit"></i>
                                                            </span>
                                                            {{ $keywords['Edit'] ?? __('Edit') }}
                                                        </a>
                                                        <form class="deleteform d-inline-block"
                                                            action="{{ route('user.language.delete', $language->id) }}"
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
                    {{-- <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Add New Keyword') }}</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="ajaxForm" action="{{ route('user.language_management.add_keyword') }}"
                                    method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="">{{ __('Keyword') }}*</label>
                                        <input type="text" class="form-control" name="keyword"
                                            placeholder="{{ __('Enter Keyword') }}">
                                        <p id="errkeyword" class="mb-0 text-danger em"></p>
                                    </div>
                                    <div class="form-group">
                                        <label for="">{{ __('Value') }}*</label>
                                        <input type="text" class="form-control" name="value"
                                            placeholder="{{ __('Enter Value') }}">
                                        <p id="errvalue" class="mb-0 text-danger em"></p>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                                    {{ __('Close') }}
                                </button>
                                <button id="" data-form="ajaxForm" type="button"
                                    class="submitBtn btn btn-primary btn-sm">
                                    {{ __('Submit') }}
                                </button>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        {{-- modal start --}}
        <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
            aria-hidden="true">

        </div>
    </div>
    {{-- modal start end --}}
    <!-- Create Language Modal -->
    @includeif('user.language.create')
@endsection
