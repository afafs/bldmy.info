@extends('user.layout')
@php
    $userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
    $userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Achievements'] ?? __('Achievements') }}</h4>
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
                <a href="#">{{ $keywords['Achievement_Page'] ?? __('Achievement Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Achievements'] ?? __('Achievements') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Achievements'] ?? __('Achievements') }}
                            </div>
                        </div>
                        <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
                            @if (!is_null($userDefaultLang))
                                <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                    data-target="#createModal"><i class="fas fa-plus"></i>
                                    {{ $keywords['Add_Achievement'] ?? __('Add Achievement') }}</a>
                                <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                    data-href="{{ route('user.achievement.bulk.delete') }}"><i
                                        class="flaticon-interface-5"></i>
                                    {{ $keywords['Delete'] ?? __('Delete') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-12">
                            @if (is_null($userDefaultLang))
                                <h3 class="text-center">{{ $keywords['NO_LANGUAGE_FOUND'] ?? __('NO LANGUAGE FOUND') }}
                                </h3>
                            @else
                                @if (count($achievements) == 0)
                                    <h3 class="text-center">
                                        {{ $keywords['NO_ACHIEVEMENT_FOUND'] ?? __('NO ACHIEVEMENT FOUND') }}</h3>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mt-3" id="basic-datatables">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <input type="checkbox" class="bulk-check" data-val="all">
                                                    </th>
                                                    <th scope="col">{{ $keywords['Title'] ?? __('Title') }}</th>
                                                    <th scope="col">{{ $keywords['Count'] ?? __('Count') }}</th>
                                                    <th scope="col">{{ $keywords['Actions'] ?? __('Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($achievements as $key => $achievement)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="bulk-check"
                                                                data-val="{{ $achievement->id }}">
                                                        </td>
                                                        <td>{{ strlen($achievement->title) > 30 ? mb_substr($achievement->title, 0, 30, 'UTF-8') . '...' : $achievement->title }}
                                                        </td>
                                                        <td>{{ $achievement->count }}</td>
                                                        <td>
                                                            <a class="btn btn-secondary btn-sm"
                                                                href="{{ route('user.achievement.edit', $achievement->id) . '?language=' . $achievement->language->code }}">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-edit"></i>
                                                                </span>
                                                                {{ $keywords['Edit'] ?? __('Edit') }}
                                                            </a>
                                                            <form class="deleteform d-inline-block"
                                                                action="{{ route('user.achievement.delete') }}"
                                                                method="post">
                                                                @csrf
                                                                <input type="hidden" name="achievement_id"
                                                                    value="{{ $achievement->id }}">
                                                                <button type="submit"
                                                                    class="btn btn-danger btn-sm deletebtn">
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
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Create Skill Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        {{ $keywords['Add_Achievement'] ?? __('Add Achievements') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('user.achievement.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ $keywords['Language'] ?? __('Language') }} **</label>
                            <select id="language" name="user_language_id" class="form-control">
                                <option value="" selected disabled>
                                    {{ $keywords['Select_a_language'] ?? __('Select a language') }}</option>
                                @foreach ($userLanguages as $lang)
                                    <option value="{{ $lang->id }}">{{ $lang->name }}</option>
                                @endforeach
                            </select>
                            <p id="erruser_language_id" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Title'] ?? __('Title') }} **</label>
                                    <input type="text" class="form-control" name="title"
                                        placeholder="{{ $keywords['Enter_title'] ?? __('Enter title') }}" value="">
                                    <p id="errtitle" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="count">{{ $keywords['Count'] ?? __('Count') }}**</label>
                                    <input id="count" type="number" class="form-control ltr" name="count"
                                        value=""
                                        placeholder="{{ $keywords['Enter_achievement_count'] ?? __('Enter achievement count') }}"
                                        min="1">
                                    <p id="errcount" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number" value=""
                                        placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0">
                                        <small>{{ $keywords['Achievement_Serial_Number_msg'] ??
                                            __('The higher the serial number is, the later the
                                                                                Skill will be shown') }}.</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-dismiss="modal">{{ $keywords['Close'] ?? __('Close') }}</button>
                    <button id="" data-form="ajaxForm" type="button"
                        class="submitBtn btn btn-primary">{{ $keywords['Submit'] ?? __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
