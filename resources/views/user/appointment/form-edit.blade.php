@extends('user.layout')


@if (!empty($input->language) && $input->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            .nicEdit-main {
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
                <a href="{{ route('user.forminput') . '?language=' . request('language') }}">
                    {{ $keywords['Form_Builder'] ?? __('Form Builder') }}</a>
            </li>
            <li class="separator">
                <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $keywords['Edit'] ?? __('Edit') }}</a>
            </li>
        </ul>
    </div>


    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <div class="row">
                    <div class="col-lg-6">
                        {{ $keywords['Edit_Input'] ?? __('Edit Input') }}
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn btn-primary"
                            href="{{ route('user.forminput', ['id' => $input->category_id]) . '?language=' . request()->input('language') }}">{{ $keywords['Back'] ?? __('Back') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="row" id="app">

                <div class="col-lg-6 offset-lg-3">
                    <form id="ajaxForm" action="{{ route('user.form.inputUpdate') }}" method="post"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <input type="hidden" name="input_id" value="{{ $input->id }}">
                        <input type="hidden" name="type" value="{{ $input->type }}">

                        <div class="form-group">
                            <label>{{ $keywords['Required'] ?? __('Required') }}</label>
                            <div class="selectgroup w-100">
                                <label class="selectgroup-item">
                                    <input type="radio" name="required" value="1" class="selectgroup-input"
                                        {{ $input->required == 1 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['Yes'] ?? __('Yes') }}</span>
                                </label>
                                <label class="selectgroup-item">
                                    <input type="radio" name="required" value="0" class="selectgroup-input"
                                        {{ $input->required == 0 ? 'checked' : '' }}>
                                    <span class="selectgroup-button">{{ $keywords['No'] ?? __('No') }}</span>
                                </label>
                            </div>
                            <p id="errrequired" class="mb-0 text-danger em"></p>
                        </div>
                        <div class="form-group">
                            <label
                                for=""><strong>{{ $keywords['Label_Name'] ?? __('Label Name') }}</strong></label>
                            <div class="">
                                <input type="text" class="form-control" name="label" value="{{ $input->label }}">
                            </div>
                            <p id="errlabel" class="mb-0 text-danger em"></p>
                        </div>
                        @if ($input->type != 3 && $input->type != 5)
                            <div class="form-group">
                                <label
                                    for=""><strong>{{ $keywords['Placeholder'] ?? __('Placeholder') }}</strong></label>
                                <div class="">
                                    <input type="text" class="form-control" name="placeholder"
                                        value="{{ $input->placeholder }}">
                                </div>
                                <p id="errplaceholder" class="mb-0 text-danger em"></p>
                            </div>
                        @endif
                        @if ($input->type == 5)
                            <div class="form-group">
                                <label
                                    for=""><strong>{{ $keywords['file_extensions'] ?? __('File Extensions') }}</strong></label>
                                <input type="text" class="form-control" name="file_extensions" data-role="tagsinput"
                                    value="{{ $input->file_extensions }}"
                                    placeholder="{{ __('use comma to separate extensions.') }}">
                                <p id="errextensions" class="mb-0 text-danger em"></p>
                            </div>
                        @endif
                        @if ($input->type == 2 || $input->type == 3)
                            <div class="form-group">
                                <label for=""><strong>{{ $keywords['Options'] ?? __('Options') }}</strong></label>
                                <div class="row mb-2 counterrow" v-for="n in counter" :id="'counterrow' + n">
                                    <div class="col-md-11">
                                        <input class="form-control optionin" type="text" name="options[]"
                                            :value="names[n - 1]"
                                            placeholder="{{ $keywords['Option_label'] ?? __('Option label') }}">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-danger btn-sm  text-white"
                                            @click="removeOption(n)"><i class="fa fa-times"></i></button>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-sm text-white" @click="addOption()"><i
                                        class="fa fa-plus"></i>
                                    {{ $keywords['Add_Option'] ?? __('Add Option') }}</button>
                                <p id="erroptions" class="mb-2 text-danger em"></p>
                                <p id="erroptions.3" class="mb-2 text-danger em"></p>
                            </div>
                        @endif
                        <div class="text-center form-group">
                            <button id="" data-form="ajaxForm" type="submit"
                                class="submitBtn btn btn-primary">{{ $keywords['UPDATE_FIELD'] ?? __('UPDATE FIELD') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('vuescripts')
    <script>
        "use strict";
        var app = new Vue({
            el: '#app',
            data: {
                counter: parseInt('{{ $counter }}'),
                names: []
            },
            created() {
                $.get("{{ route('user.form.options', $input->id) }}", (data) => {
                    for (var i = 0; i < data.length; i++) {
                        this.names.push(data[i].name);
                    }
                });
            },
            methods: {
                addOption() {
                    $("#optionarea").addClass('d-block');
                    this.counter++;
                },
                removeOption(n) {
                    $("#counterrow" + n).remove();
                    if ($(".counterrow").length == 0) {
                        this.counter = 0;
                    }
                }
            }
        })
    </script>
@endsection
