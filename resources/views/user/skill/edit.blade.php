@extends('user.layout')
@if (!empty($skill->language) && $skill->language->rtl == 1)
    @section('styles')
        <style>
            form input,
            form textarea,
            form select {
                direction: rtl;
            }

            form .note-editor.note-frame .note-editing-area .note-editable {
                direction: rtl;
                text-align: right;
            }
        </style>
    @endsection
@endif

@section('content')
    <div class="page-header">
        <h4 class="page-title">{{ $keywords['Edit_Skill'] ?? __('Edit Skill') }}</h4>
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
                <a href="#">{{ $keywords['Edit_Skill'] ?? __('Edit Skill') }}</a>
            </li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-title d-inline-block">{{ $keywords['Edit_Skill'] ?? __('Edit Skill') }}</div>
                    <a class="btn btn-info btn-sm float-right d-inline-block"
                        href="{{ route('user.skill.index') . '?language=' . $skill->language->code }}">
                        <span class="btn-label">
                            <i class="fas fa-backward"></i>
                        </span>
                        {{ $keywords['Back'] ?? __('Back') }}
                    </a>
                </div>
                <div class="card-body pt-5 pb-5">
                    <div class="row">
                        <div class="col-lg-6 offset-lg-3">
                            <form id="ajaxForm" class="" action="{{ route('user.skill.update') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="skill_id" value="{{ $skill->id }}">

                                <div class="form-group">
                                    <label for="">{{ $keywords['Title'] ?? __('Title') }} **</label>
                                    <input type="text" class="form-control" name="title" value="{{ $skill->title }}"
                                        placeholder="{{ $keywords['Enter_title'] ?? __('Enter title') }}">
                                    <p id="errtitle" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="percentage">{{ $keywords['Percentage'] ?? __('Percentage') }}**</label>
                                    <input id="percentage" type="number" class="form-control ltr" name="percentage"
                                        value="{{ $skill->percentage }}"
                                        placeholder="{{ $keywords['Enter_skill_percentage'] ?? __('Enter skill percentage') }}"
                                        min="1" max="100"
                                        onkeyup="if(parseInt(this.value)>100 || parseInt(this.value)<=0 ){this.value =100; return false;}">
                                    <p id="errpercentage" class="mb-0 text-danger em"></p>
                                    <p class="text-warning mb-0">
                                        <small>{{ $keywords['skills_percentage_msg'] ?? __('The percentage should between 1 to 100') }}.</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Color'] ?? __('Color') }} **</label>
                                    <input type="text" class="form-control ltr jscolor" name="color"
                                        value="#{{ $skill->color }}"
                                        placeholder="{{ $keywords['Enter_Color'] ?? __('Enter Color') }}">
                                    <p id="errcolor" class="mb-0 text-danger em"></p>
                                </div>
                                <div class="form-group">
                                    <label for="">{{ $keywords['Serial_Number'] ?? __('Serial Number') }}
                                        **</label>
                                    <input type="number" class="form-control ltr" name="serial_number"
                                        value="{{ $skill->serial_number }}"
                                        placeholder="{{ $keywords['Enter_Serial_Number'] ?? __('Enter Serial Number') }}">
                                    <p id="errserial_number" class="mb-0 text-danger em"></p>
                                    <p class="text-warning">
                                        <small>{{ $keywords['skill_serial_number_msg'] ?? __('The higher the serial number is, the later the Skill will be shown') }}.</small>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="form">
                        <div class="form-group from-show-notify row">
                            <div class="col-12 text-center">
                                <button type="submit" data-form="ajaxForm" id=""
                                    class="submitBtn btn btn-success">{{ $keywords['Update'] ?? __('Update') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
