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
        <h4 class="page-title">{{ $keywords['Appointment_Settings'] ?? __('Appointment Settings') }}</h4>
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
                <a
                    href="{{ route('user.forminput') . '?language=' . request('language') }}">{{ $keywords['Form_Builder'] ?? __('Form Builder') }}</a>
            </li>
        </ul>
    </div>

    <div class="row" id="app">
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card-title">{{ $keywords['Input_Fields'] ?? __('Input Fields') }}</div>
                        </div>
                        <div class="col-lg-4">

                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <p class="text-warning">** {{ $keywords['Do_not_create'] ?? __(' Do not create') }} <strong
                            class="text-danger">{{ $keywords['Name'] ?? __('Name') }} &amp;
                            {{ $keywords['Email'] ?? __('Email') }}</strong>
                        {{ $keywords['input_field_name_email_text'] ?? __('input field, it will be in the Appointment form By default') }}
                        .</p>
                    @if (count($inputs) > 0)
                        <div id="sortable">
                            @foreach ($inputs as $key => $input)
                                {{-- input type text --}}
                                @if ($input->type == 1)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input class="form-control" type="text" name="" value=""
                                                        placeholder="{{ $input->placeholder }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger btn-sm" type="submit">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($input->type == 2)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <select class="form-control" name="">
                                                        <option value="" selected disabled>
                                                            {{ $input->placeholder }}
                                                        </option>
                                                        @foreach ($input->form_input_options as $key => $option)
                                                            <option value="">{{ $option->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button class="btn btn-danger btn-sm" type="submit">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                @elseif ($input->type == 3)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    @foreach ($input->form_input_options as $key => $option)
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" id="customRadio{{ $option->id }}"
                                                                name="customRadio" class="custom-control-input">
                                                            <label class="custom-control-label"
                                                                for="customRadio{{ $option->id }}">{{ $option->name }}</label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($input->type == 4)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <textarea class="form-control" name="" rows="5" cols="80"
                                                        placeholder="{{ $input->placeholder }}"></textarea>
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($input->type == 6)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control datepicker"
                                                        autocomplete="off" placeholder="{{ $input->placeholder }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($input->type == 7)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">
                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="text" class="form-control timepicker"
                                                        autocomplete="off" placeholder="{{ $input->placeholder }}">
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @elseif ($input->type == 5)
                                    <form class="ui-state-default" action="{{ route('user.form.inputDelete') }}"
                                        method="post" data-id="{{ $input->id }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                                        <div class="form-group">

                                            <label for="">{{ $input->label }} @if ($input->required == 1)
                                                    <span>**</span>
                                                @elseif($input->required == 0)
                                                    ({{ $keywords['Optional'] ?? __('Optional') }})
                                                @endif
                                                @php
                                                    // $exts = explode(',', $input->file_extensions);
                                                @endphp
                                                ( {{ $keywords['Allowed_extensions'] ?? __('Allowed extensions') }} :
                                                {{ $input->file_extensions }})
                                            </label>
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <input type="file">
                                                </div>
                                                <div class="col-md-1">
                                                    <a class="btn btn-warning btn-sm"
                                                        href="{{ route('user.form.inputEdit', $input->id) . '?language=' . request()->input('language') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">
                        {{ $keywords['Create_Input'] ?? __('Create Input') }}
                        @if (!empty($back_url))
                            <a href="{{ $back_url }}"
                                class="btn btn-primary btn-sm float-right text-white">{{ $keywords['Back'] ?? __('Back') }}</a>
                        @endif
                    </div>
                </div>
                <form id="ajaxForm" action="{{ route('user.form.store') }}" method="post"
                    enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <input type="hidden" name="category_id" value="{{ $type_details ? $type_details->id : '' }}">
                    <input type="hidden" name="language_id" value="{{ $lang_id }}">
                    <div class="form-group">
                        <label for=""><strong>{{ $keywords['Field_Type'] ?? __('Field Type') }}</strong></label>
                        <div class="">
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio1"
                                    value="1" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio1">{{ $keywords['Text_Field'] ?? __('Text Field') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio2"
                                    value="2" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio2">{{ $keywords['Select'] ?? __('Select') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio3"
                                    value="3" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio3">{{ $keywords['Checkbox'] ?? __('Checkbox') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio4"
                                    value="4" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio4">{{ $keywords['Textarea'] ?? __('Textarea') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio6"
                                    value="6" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio6">{{ $keywords['Datepicker'] ?? __('Datepicker') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio7"
                                    value="7" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio7">{{ $keywords['Timepicker'] ?? __('Timepicker') }}</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input name="type" class="form-check-input" type="radio" id="inlineRadio5"
                                    value="5" v-model="type" @change="typeChange()">
                                <label class="form-check-label mb-0"
                                    for="inlineRadio5">{{ $keywords['File'] ?? __('File') }}</label>
                            </div>
                        </div>
                        <p id="errtype" class="mb-0 text-danger em"></p>
                    </div>

                    <div class="form-group">
                        <label>{{ $keywords['Required'] ?? __('Required') }}</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="required" value="1" class="selectgroup-input" checked>
                                <span class="selectgroup-button">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="required" value="0" class="selectgroup-input">
                                <span class="selectgroup-button">{{ $keywords['No'] ?? __('No') }}</span>
                            </label>
                        </div>
                        <p id="errrequired" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group">
                        <label for=""><strong>{{ $keywords['Label_Name'] ?? __('Label Name') }}</strong></label>
                        <div class="">
                            <input type="text" class="form-control" name="label" value=""
                                placeholder="{{ $keywords['Enter_Label_Name'] ?? __('Enter Label Name') }}">
                        </div>
                        <p id="errlabel" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group" v-if="placeholdershow">
                        <label for=""><strong>{{ $keywords['Placeholder'] ?? __('Placeholder') }}</strong></label>
                        <div class="">
                            <input type="text" class="form-control" name="placeholder" value=""
                                placeholder="{{ $keywords['Enter_Placeholder'] ?? __('Enter Placeholder') }}">
                        </div>
                        <p id="errplaceholder" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group" v-show="fileExtensions">
                        <label
                            for=""><strong>{{ $keywords['file_extensions'] ?? __('File Extensions') }}</strong></label>
                        <input type="text" class="form-control" name="file_extensions" data-role="tagsinput"
                            placeholder="{{ __('use comma to separate extensions.') }}">
                        <p id="errfile_extensions" class="mb-0 text-danger em"></p>
                    </div>
                    <div class="form-group" v-if="counter > 0" id="optionarea">
                        <label for=""><strong>{{ $keywords['Options'] ?? __('Options') }}</strong></label>
                        <div class="row mb-2 counterrow" v-for="n in counter" :id="'counterrow' + n">
                            <div class="col-md-10">
                                <input type="text" class="form-control" name="options[]" value=""
                                    placeholder="{{ $keywords['Option_label'] ?? __('Option label') }}">
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-md text-white btn-sm"
                                    @click="removeOption(n)"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <p id="erroptions.0" class="mb-2 text-danger em"></p>
                        <button type="button" class="btn btn-success btn-sm text-white" @click="addOption()"><i
                                class="fa fa-plus"></i> {{ $keywords['Add_Option'] ?? __('Add Option') }}</button>
                    </div>
                    <div class="form-group text-center">
                        <button id="" data-form="ajaxForm" type="submit"
                            class="submitBtn btn btn-primary btn-sm">{{ $keywords['ADD_FIELD'] ?? __('ADD FIELD') }}</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        "use strict";
        var orderUpdateUrl = "{{ route('user.form.orderUpdate') }}";
    </script>
    <script src="{{ asset('assets/user/js/quote.js') }}"></script>
@endsection
