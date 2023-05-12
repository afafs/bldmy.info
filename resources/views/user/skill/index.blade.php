@extends('user.layout')
@php
$userDefaultLang = \App\Models\User\Language::where([['user_id', \Illuminate\Support\Facades\Auth::id()], ['is_default', 1]])->first();
$userLanguages = \App\Models\User\Language::where('user_id', \Illuminate\Support\Facades\Auth::id())->get();
@endphp

@includeIf('user.partials.rtl-style')

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Skills'] ?? __('Skills') }}</h4>
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
                <a href="#">{{ $keywords['Skill_Page'] ?? __('Skill Page') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Skills'] ?? __('Skills') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card-title d-inline-block">{{ $keywords['Skills'] ?? __('Skills') }}</div>
                        </div>
                        <div class="col-lg-4 offset-lg-4 mt-2 mt-lg-0">
                            @if (!is_null($userDefaultLang))
                                <a href="#" class="btn btn-primary float-right btn-sm" data-toggle="modal"
                                    data-target="#createModal"><i class="fas fa-plus"></i>
                                    {{ $keywords['Add_Skill'] ?? __(' Add Skill') }}</a>
                                <button class="btn btn-danger float-right btn-sm mr-2 d-none bulk-delete"
                                    data-href="{{ route('user.skill.bulk.delete') }}"><i
                                        class="flaticon-interface-5"></i>{{ $keywords['Delete'] ?? __('Delete') }}
                                </button>
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
                                @if (count($skills) == 0)
                                    <h3 class="text-center">{{ $keywords['NO_SKILL_FOUND'] ?? __('NO SKILL FOUND') }}</h3>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-striped mt-3" id="basic-datatables">
                                            <thead>
                                                <tr>
                                                    <th scope="col">
                                                        <input type="checkbox" class="bulk-check" data-val="all">
                                                    </th>
                                                    <th scope="col">{{ $keywords['Title'] ?? __(' Title') }}</th>
                                                    <th scope="col">{{ $keywords['Language'] ?? __(' Language') }}
                                                    </th>
                                                    <th scope="col">{{ $keywords['Percentage'] ?? __(' Percentage') }}
                                                    </th>
                                                    <th scope="col">{{ $keywords['Actions'] ?? __(' Actions') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($skills as $key => $skill)
                                                    <tr>
                                                        <td>
                                                            <input type="checkbox" class="bulk-check"
                                                                data-val="{{ $skill->id }}">
                                                        </td>
                                                        <td>{{ strlen($skill->title) > 30 ? mb_substr($skill->title, 0, 30, 'UTF-8') . '...' : $skill->title }}
                                                        </td>
                                                        <td>{{ $skill->language->name }}</td>
                                                        <td>{{ $skill->percentage }}</td>
                                                        <td>
                                                            <a class="btn btn-secondary btn-sm"
                                                                href="{{ route('user.skill.edit', $skill->id) . '?language=' . $skill->language->code }}">
                                                                <span class="btn-label">
                                                                    <i class="fas fa-edit"></i>
                                                                </span>
                                                                {{ $keywords['Edit'] ?? __('Edit') }}
                                                            </a>
                                                            <form class="deleteform d-inline-block"
                                                                action="{{ route('user.skill.delete') }}" method="post">
                                                                @csrf
                                                                <input type="hidden" name="skill_id"
                                                                    value="{{ $skill->id }}">
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
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ $keywords['Add_Skill'] ?? __(' Add Skill') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="ajaxForm" enctype="multipart/form-data" class="modal-form"
                        action="{{ route('user.skill.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ $keywords['Language'] ?? __('Language') }} **</label>
                            <select id="language" name="user_language_id" class="form-control">
                                <option value="" selected disabled>{{ $keywords['Select_a_language'] ?? __('Select a language') }}</option>
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
                                    <input type="text" class="form-control" name="title" placeholder="{{ $keywords['Enter_title'] ?? __('Enter title') }}"
                                        value="">
                                    <p id="errtitle" class="mb-0 text-danger em"></p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="percentage">{{ $keywords['Percentage'] ?? __('Percentage') }}**</label>
                                    <input id="percentage" type="number" class="form-control ltr" name="percentage"
                                        value="" placeholder="{{ $keywords['Enter_skill_percentage'] ?? __('Enter skill percentage') }}" min="1"
                                        max="100"
                                        onkeyup="if(parseInt(this.value)>100 || parseInt(this.value)<=0 ){this.value =100; return false;}">
                                    <p id="errpercentage" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0"><small>{{ $keywords['skills_percentage_msg'] ?? __('The percentage should between 1 to 100') }}.</small></p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Color'] ?? __('Color') }} **</label>
                                    <input type="text" name="color" value="#F78058"
                                        class="form-control jscolor ltr" placeholder="{{$keywords['Enter_Color'] ?? __('Enter Color')}}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }} **</label>
                                    <input type="number" class="form-control ltr" name="serial_number" value=""
                                        placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0"><small>{{ $keywords['skill_serial_number_msg'] ?? __('The higher the serial number is, the later the
                                        Skill will be shown') }} .</small></p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{$keywords["Close"] ??  __("Close") }}</button>
                    <button id="" data-form="ajaxForm" type="button" class="submitBtn btn btn-primary">{{$keywords["Submit"] ??  __("Submit") }}</button>
                </div>
            </div>
        </div>
    </div>
@endsection
